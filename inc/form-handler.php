<?php
if (!defined('ABSPATH')) {
    exit;
}

/* ======================================
FORM HANDLER - AJAX PRÉ-ANÁLISE
====================================== */

function despachante_handle_pre_analise_submission() {
    check_ajax_referer('despachante_pre_analise_nonce', 'nonce');

    $nome         = isset($_POST['nome']) ? sanitize_text_field(wp_unslash($_POST['nome'])) : '';
    $whatsapp     = isset($_POST['telefone']) ? sanitize_text_field(wp_unslash($_POST['telefone'])) : '';
    $email        = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
    $servico      = isset($_POST['servico']) ? absint($_POST['servico']) : 0;
    $objetivo     = isset($_POST['objetivo_atendimento']) ? sanitize_text_field(wp_unslash($_POST['objetivo_atendimento'])) : '';
    $mensagem     = isset($_POST['mensagem']) ? sanitize_textarea_field(wp_unslash($_POST['mensagem'])) : '';
    $lgpd         = isset($_POST['lgpd_aceito']) ? 1 : 0;
    $tipo_cliente = isset($_POST['tipo_cliente']) ? sanitize_text_field(wp_unslash($_POST['tipo_cliente'])) : 'pf';

    $lgpd_banner_enabled  = function_exists('despachante_lgpd_is_enabled') ? despachante_lgpd_is_enabled() : false;
    $lgpd_banner_accepted = isset($_POST['lgpd_banner_accepted']) ? absint($_POST['lgpd_banner_accepted']) : 0;

    $consent_ip = isset($_SERVER['REMOTE_ADDR'])
        ? sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR']))
        : '';

    $consent_at = current_time('mysql');

    $consent_version = isset($_POST['lgpd_consent_version'])
        ? sanitize_text_field(wp_unslash($_POST['lgpd_consent_version']))
        : '1.0';

    $consent_source = isset($_POST['lgpd_consent_source'])
        ? sanitize_text_field(wp_unslash($_POST['lgpd_consent_source']))
        : '';

    if (empty($nome)) {
        wp_send_json_error(array(
            'message' => 'Informe seu nome.'
        ), 400);
    }

    if (empty($whatsapp)) {
        wp_send_json_error(array(
            'message' => 'Informe seu WhatsApp.'
        ), 400);
    }

    if (!despachante_is_valid_whatsapp($whatsapp)) {
        wp_send_json_error(array(
            'message' => 'Informe um WhatsApp válido com DDD.'
        ), 400);
    }

    if (empty($email)) {
        wp_send_json_error(array(
            'message' => 'Informe seu e-mail.'
        ), 400);
    }

    if (!is_email($email)) {
        wp_send_json_error(array(
            'message' => 'Informe um e-mail válido.'
        ), 400);
    }

    if (empty($servico)) {
        wp_send_json_error(array(
            'message' => 'Selecione um serviço.'
        ), 400);
    }

    if (empty($objetivo)) {
        wp_send_json_error(array(
            'message' => 'Selecione o objetivo do atendimento.'
        ), 400);
    }

    if (!$lgpd) {
        wp_send_json_error(array(
            'message' => 'É necessário aceitar o consentimento de uso dos dados para continuar.'
        ), 400);
    }

    if ($lgpd_banner_enabled && !$lgpd_banner_accepted) {
        wp_send_json_error(array(
            'message' => 'Antes de enviar, aceite o banner de privacidade LGPD.'
        ), 400);
    }

    $must_upload_documents = ($objetivo === 'enviar_documentos');

    // if ($must_upload_documents) {
    //     $checklist_validation = despachante_validate_required_checklist_files(
    //         $servico,
    //         isset($_FILES['documentos_checklist']) ? $_FILES['documentos_checklist'] : array()
    //     );
    if ($must_upload_documents) {
        $checklist_validation = despachante_validate_required_checklist_files(
        $servico,
        isset($_FILES['documentos_checklist']) ? $_FILES['documentos_checklist'] : array(),
        $tipo_cliente
    );

        if (is_wp_error($checklist_validation)) {
            wp_send_json_error(array(
                'message' => $checklist_validation->get_error_message()
            ), 400);
        }
    }

    $servico_post = get_post($servico);
    $servico_nome = ($servico_post && $servico_post->post_type === 'servicos')
        ? $servico_post->post_title
        : '';

    if (empty($consent_source)) {
        if ($lgpd_banner_enabled && $lgpd_banner_accepted && $lgpd) {
            $consent_source = 'banner_formulario';
        } elseif ($lgpd) {
            $consent_source = 'formulario';
        } else {
            $consent_source = 'desconhecido';
        }
    }

    global $wpdb;
    $leads_table = $wpdb->prefix . 'despachante_leads';

    $inserted = $wpdb->insert(
        $leads_table,
        array(
            'nome'                 => $nome,
            'whatsapp'             => $whatsapp,
            'email'                => $email,
            'servico_id'           => $servico,
            'servico_nome'         => $servico_nome,
            'objetivo_atendimento' => $objetivo,
            'mensagem'             => $mensagem,
            'lgpd_aceito'          => $lgpd,
            'lgpd_consent_at'      => $consent_at,
            'lgpd_consent_ip'      => $consent_ip,
            'lgpd_consent_version' => $consent_version,
            'lgpd_consent_source'  => $consent_source,
            'ip_address'           => $consent_ip,
            'user_agent'           => isset($_SERVER['HTTP_USER_AGENT']) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_USER_AGENT'])) : '',
            'status'               => 'novo',
        ),
        array('%s', '%s', '%s', '%d', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%s', '%s')
    );

    if (!$inserted) {
        wp_send_json_error(array(
            'message' => 'Não foi possível salvar sua solicitação.'
        ), 500);
    }

    $lead_id        = (int) $wpdb->insert_id;
    $uploaded_files = array();

    if ($must_upload_documents && !empty($_FILES['documentos_checklist'])) {
        $normalized_checklist = despachante_extract_checklist_files($_FILES['documentos_checklist']);
        $document_labels      = isset($_POST['document_labels']) && is_array($_POST['document_labels'])
            ? wp_unslash($_POST['document_labels'])
            : array();

        foreach ($normalized_checklist as $slug => $single_file) {
            if (empty($single_file['name'])) {
                continue;
            }

            $document_label = isset($document_labels[$slug])
                ? sanitize_text_field($document_labels[$slug])
                : sanitize_text_field($slug);

            $upload_result = despachante_handle_single_upload(
                $single_file,
                $lead_id,
                $document_label,
                sanitize_text_field($slug),
                1
            );

            if (is_wp_error($upload_result)) {
                wp_send_json_error(array(
                    'message' => $upload_result->get_error_message()
                ), 400);
            }

            $uploaded_files[] = $upload_result;
        }
    }

    if ($must_upload_documents && !empty($_FILES['documentos_extras']) && !empty($_FILES['documentos_extras']['name']) && is_array($_FILES['documentos_extras']['name'])) {
        $file_count = count($_FILES['documentos_extras']['name']);

        for ($i = 0; $i < $file_count; $i++) {
            if (empty($_FILES['documentos_extras']['name'][$i])) {
                continue;
            }

            $single_file = array(
                'name'     => $_FILES['documentos_extras']['name'][$i],
                'type'     => $_FILES['documentos_extras']['type'][$i],
                'tmp_name' => $_FILES['documentos_extras']['tmp_name'][$i],
                'error'    => $_FILES['documentos_extras']['error'][$i],
                'size'     => $_FILES['documentos_extras']['size'][$i],
            );

            $upload_result = despachante_handle_single_upload(
                $single_file,
                $lead_id,
                'Documento extra',
                'documento_extra',
                0
            );

            if (is_wp_error($upload_result)) {
                wp_send_json_error(array(
                    'message' => $upload_result->get_error_message()
                ), 400);
            }

            $uploaded_files[] = $upload_result;
        }
    }

    $lead_data = array(
        'nome'                 => $nome,
        'whatsapp'             => $whatsapp,
        'email'                => $email,
        'servico_nome'         => $servico_nome,
        'objetivo_atendimento' => $objetivo,
        'mensagem'             => $mensagem,
        'lgpd_aceito'          => $lgpd,
        'lgpd_consent_at'      => $consent_at,
        'lgpd_consent_ip'      => $consent_ip,
        'lgpd_consent_version' => $consent_version,
        'lgpd_consent_source'  => $consent_source,
        'tipo_cliente'         => $tipo_cliente,
    );

    despachante_send_lead_email($lead_id, $lead_data, $uploaded_files);

    if (function_exists('despachante_send_client_confirmation')) {
        despachante_send_client_confirmation($lead_data);
    }

    wp_send_json_success(array(
        'message' => 'Solicitação enviada com sucesso.',
        'lead_id' => $lead_id,
    ));
}

add_action('wp_ajax_despachante_pre_analise_submit', 'despachante_handle_pre_analise_submission');
add_action('wp_ajax_nopriv_despachante_pre_analise_submit', 'despachante_handle_pre_analise_submission');