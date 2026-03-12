<?php
if (!defined('ABSPATH')) {
    exit;
}

/* ======================================
UPLOADS
====================================== */

function despachante_allowed_upload_mimes() {
    return array(
        'image/jpeg',
        'image/png',
        'application/pdf',
    );
}

function despachante_validate_upload($file) {
    $allowed_mimes = despachante_allowed_upload_mimes();
    $max_size      = 8 * 1024 * 1024;

    if (empty($file['name'])) {
        return true;
    }

    if (!empty($file['error']) && (int) $file['error'] !== UPLOAD_ERR_OK) {
        return new WP_Error(
            'upload_error',
            'Erro no envio do arquivo: ' . $file['name']
        );
    }

    if (!empty($file['size']) && (int) $file['size'] > $max_size) {
        return new WP_Error(
            'upload_size',
            'O arquivo ' . $file['name'] . ' excede o limite de 8MB.'
        );
    }

    $check = wp_check_filetype_and_ext($file['tmp_name'], $file['name']);
    $type  = isset($check['type']) ? $check['type'] : '';

    if (empty($type) || !in_array($type, $allowed_mimes, true)) {
        return new WP_Error(
            'upload_type',
            'Formato não permitido para o arquivo ' . $file['name'] . '. Use JPG, PNG ou PDF.'
        );
    }

    return true;
}

function despachante_handle_single_upload($file, $lead_id, $document_type = 'documento', $document_slug = '', $is_checklist = 0) {
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';

    $validation = despachante_validate_upload($file);

    if (is_wp_error($validation)) {
        return $validation;
    }

    $overrides = array(
        'test_form' => false,
        'mimes'     => array(
            'jpg|jpeg' => 'image/jpeg',
            'png'      => 'image/png',
            'pdf'      => 'application/pdf',
        ),
    );

    $uploaded = wp_handle_upload($file, $overrides);

    if (isset($uploaded['error'])) {
        return new WP_Error('upload_error', $uploaded['error']);
    }

    $filetype = wp_check_filetype(basename($uploaded['file']), null);

    $attachment = array(
        'post_mime_type' => $filetype['type'],
        'post_title'     => sanitize_file_name(pathinfo($file['name'], PATHINFO_FILENAME)),
        'post_content'   => '',
        'post_status'    => 'inherit',
    );

    $attachment_id = wp_insert_attachment($attachment, $uploaded['file']);

    if (!is_wp_error($attachment_id) && $attachment_id) {
        $attach_data = wp_generate_attachment_metadata($attachment_id, $uploaded['file']);
        wp_update_attachment_metadata($attachment_id, $attach_data);
    } else {
        $attachment_id = 0;
    }

    global $wpdb;
    $files_table = $wpdb->prefix . 'despachante_lead_files';

    $public_file_url = despachante_build_public_upload_url($uploaded['file']);

    $insert_data = array(
        'lead_id'        => (int) $lead_id,
        'tipo_documento' => $document_type,
        'file_name'      => sanitize_file_name($file['name']),
        'file_url'       => $public_file_url,
        'file_path'      => sanitize_text_field($uploaded['file']),
        'mime_type'      => sanitize_text_field($filetype['type']),
        'attachment_id'  => (int) $attachment_id,
    );

    $insert_format = array('%d', '%s', '%s', '%s', '%s', '%s', '%d');

    if (despachante_table_has_column($files_table, 'document_slug')) {
        $insert_data['document_slug'] = $document_slug;
        array_splice($insert_format, 2, 0, '%s');
    }

    if (despachante_table_has_column($files_table, 'is_checklist')) {
        $insert_data['is_checklist'] = (int) $is_checklist;
        array_splice(
            $insert_format,
            despachante_table_has_column($files_table, 'document_slug') ? 3 : 2,
            0,
            '%d'
        );
    }

    $inserted = $wpdb->insert($files_table, $insert_data, $insert_format);

    if ($inserted === false) {
        return new WP_Error(
            'db_insert_file_error',
            'O upload foi recebido, mas não foi possível registrar o arquivo no banco de dados.'
        );
    }

    return array(
        'url'           => $public_file_url,
        'path'          => $uploaded['file'],
        'attachment_id' => $attachment_id,
        'file_name'     => $file['name'],
        'document_type' => $document_type,
        'document_slug' => $document_slug,
        'is_checklist'  => (int) $is_checklist,
    );
}

function despachante_extract_checklist_files($files_array) {
    $normalized = array();

    if (
        empty($files_array) ||
        !isset($files_array['name']) ||
        !is_array($files_array['name'])
    ) {
        return $normalized;
    }

    foreach ($files_array['name'] as $slug => $name) {
        $normalized[$slug] = array(
            'name'     => isset($files_array['name'][$slug]) ? $files_array['name'][$slug] : '',
            'type'     => isset($files_array['type'][$slug]) ? $files_array['type'][$slug] : '',
            'tmp_name' => isset($files_array['tmp_name'][$slug]) ? $files_array['tmp_name'][$slug] : '',
            'error'    => isset($files_array['error'][$slug]) ? $files_array['error'][$slug] : 0,
            'size'     => isset($files_array['size'][$slug]) ? $files_array['size'][$slug] : 0,
        );
    }

    return $normalized;
}

function despachante_get_required_documents_by_customer_type($servico, $tipo_cliente = 'pf') {
    $docs = despachante_get_service_required_documents($servico);

    if ($tipo_cliente !== 'pj') {
        return $docs;
    }

    if (empty($docs)) {
        return array(
            'CNPJ',
            'Contrato social',
            'Documento do responsável',
            'CRLV',
            'Comprovante de pagamento',
            'Procuração',
        );
    }

    $mapped = array();

    foreach ($docs as $doc) {
        $normalized = strtolower(trim((string) $doc));

        if ($normalized === 'cpf') {
            $mapped[] = 'CNPJ';
        } elseif ($normalized === 'rg') {
            $mapped[] = 'Contrato social';
        } elseif ($normalized === 'cnh') {
            $mapped[] = 'Documento do responsável';
        } elseif ($normalized === 'comprovante de residência') {
            $mapped[] = 'Documento do responsável';
        } else {
            $mapped[] = $doc;
        }
    }

    if (!in_array('CNPJ', $mapped, true)) {
        array_unshift($mapped, 'CNPJ');
    }

    if (!in_array('Contrato social', $mapped, true)) {
        $mapped[] = 'Contrato social';
    }

    if (!in_array('Documento do responsável', $mapped, true)) {
        $mapped[] = 'Documento do responsável';
    }

    return array_values(array_unique($mapped));
}

function despachante_validate_required_checklist_files($servico, $files_array, $tipo_cliente = 'pf') {
    $required_docs = despachante_get_required_documents_by_customer_type($servico, $tipo_cliente);

    if (empty($required_docs)) {
        return true;
    }

    $normalized_files = despachante_extract_checklist_files($files_array);
    $missing_docs     = array();

    foreach ($required_docs as $doc_label) {
        $slug = despachante_slugify($doc_label);

        if (
            !isset($normalized_files[$slug]) ||
            empty($normalized_files[$slug]['name']) ||
            !isset($normalized_files[$slug]['error']) ||
            (int) $normalized_files[$slug]['error'] !== UPLOAD_ERR_OK
        ) {
            $missing_docs[] = $doc_label;
        }
    }

    if (!empty($missing_docs)) {
        return new WP_Error(
            'missing_required_documents',
            'Envie todos os documentos obrigatórios do checklist: ' . implode(', ', $missing_docs) . '.'
        );
    }

    return true;
}