<?php
if (!defined('ABSPATH')) {
    exit;
}

/* ======================================
PAINEL ADMINISTRATIVO DE LEADS
====================================== */

function despachante_register_admin_menu() {
    add_menu_page(
        'Leads Despachante',
        'Leads',
        'manage_options',
        'despachante-leads',
        'despachante_render_admin_leads_page',
        'dashicons-id',
        26
    );
}
add_action('admin_menu', 'despachante_register_admin_menu');

function despachante_render_admin_leads_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    global $wpdb;
    $leads_table = $wpdb->prefix . 'despachante_leads';
    $files_table = $wpdb->prefix . 'despachante_lead_files';

    $lead_id = isset($_GET['lead_id']) ? absint($_GET['lead_id']) : 0;

    echo '<div class="wrap">';
    echo '<h1>Leads do Despachante</h1>';

    if ($lead_id > 0) {
        $lead = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$leads_table} WHERE id = %d", $lead_id));
        $files = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$files_table} WHERE lead_id = %d ORDER BY created_at DESC", $lead_id));

        if (!$lead) {
            echo '<div class="notice notice-error"><p>Lead não encontrado.</p></div>';
            echo '<p><a href="' . esc_url(admin_url('admin.php?page=despachante-leads')) . '" class="button">Voltar</a></p>';
            echo '</div>';
            return;
        }

        $expected_docs = despachante_get_service_required_documents($lead->servico_id);
        $uploaded_map  = array();

        if (!empty($files)) {
            foreach ($files as $file) {
                $doc_slug_value = isset($file->document_slug) ? $file->document_slug : '';
                $key = !empty($doc_slug_value) ? $doc_slug_value : despachante_slugify($file->tipo_documento);

                $file->file_url = despachante_normalize_public_url(
                    isset($file->file_url) ? $file->file_url : '',
                    isset($file->file_path) ? $file->file_path : ''
                );

                $uploaded_map[$key] = $file;
            }
        }

        $objetivo_label = function_exists('despachante_get_objective_label')
            ? despachante_get_objective_label($lead->objetivo_atendimento)
            : $lead->objetivo_atendimento;

        echo '<p><a href="' . esc_url(admin_url('admin.php?page=despachante-leads')) . '" class="button">Voltar para lista</a></p>';

        echo '<table class="widefat striped" style="max-width:950px;">';
        echo '<tbody>';
        echo '<tr><th style="width:240px;">ID</th><td>' . esc_html($lead->id) . '</td></tr>';
        echo '<tr><th>Nome</th><td>' . esc_html($lead->nome) . '</td></tr>';
        echo '<tr><th>WhatsApp</th><td>' . esc_html($lead->whatsapp) . '</td></tr>';
        echo '<tr><th>E-mail</th><td>' . esc_html($lead->email) . '</td></tr>';
        echo '<tr><th>Serviço</th><td>' . esc_html($lead->servico_nome) . '</td></tr>';
        echo '<tr><th>Objetivo</th><td>' . esc_html($objetivo_label) . '</td></tr>';
        echo '<tr><th>Mensagem</th><td>' . nl2br(esc_html($lead->mensagem)) . '</td></tr>';
        echo '<tr><th>LGPD aceito no formulário</th><td>' . ((int) $lead->lgpd_aceito === 1 ? 'Sim' : 'Não') . '</td></tr>';

        if (isset($lead->lgpd_consent_at)) {
            echo '<tr><th>Data/hora do consentimento</th><td>' . esc_html($lead->lgpd_consent_at) . '</td></tr>';
        }

        if (isset($lead->lgpd_consent_ip)) {
            echo '<tr><th>IP do consentimento</th><td>' . esc_html($lead->lgpd_consent_ip) . '</td></tr>';
        }

        if (isset($lead->lgpd_consent_version)) {
            echo '<tr><th>Versão do consentimento</th><td>' . esc_html($lead->lgpd_consent_version) . '</td></tr>';
        }

        if (isset($lead->lgpd_consent_source)) {
            echo '<tr><th>Origem do consentimento</th><td>' . esc_html($lead->lgpd_consent_source) . '</td></tr>';
        }

        echo '<tr><th>IP do envio</th><td>' . esc_html($lead->ip_address) . '</td></tr>';
        echo '<tr><th>User Agent</th><td style="word-break:break-word;">' . esc_html($lead->user_agent) . '</td></tr>';
        echo '<tr><th>Status</th><td>' . esc_html($lead->status) . '</td></tr>';
        echo '<tr><th>Data do lead</th><td>' . esc_html($lead->created_at) . '</td></tr>';
        echo '</tbody>';
        echo '</table>';

        echo '<h2 style="margin-top:30px;">Checklist esperado</h2>';

        if (!empty($expected_docs)) {
            echo '<table class="widefat striped" style="max-width:1000px;">';
            echo '<thead><tr><th>Documento</th><th>Status</th><th>Arquivo</th><th>Download</th></tr></thead><tbody>';

            foreach ($expected_docs as $doc_label) {
                $doc_slug = despachante_slugify($doc_label);
                $file = isset($uploaded_map[$doc_slug]) ? $uploaded_map[$doc_slug] : null;

                echo '<tr>';
                echo '<td>' . esc_html($doc_label) . '</td>';

                if ($file) {
                    echo '<td><span style="color:#0a7f36;font-weight:600;">Enviado</span></td>';
                    echo '<td>' . esc_html($file->file_name) . '</td>';
                    echo '<td><a class="button button-primary" href="' . esc_url($file->file_url) . '" target="_blank" rel="noopener noreferrer">Abrir / Baixar</a></td>';
                } else {
                    echo '<td><span style="color:#a00;font-weight:600;">Não enviado</span></td>';
                    echo '<td>—</td>';
                    echo '<td>—</td>';
                }

                echo '</tr>';
            }

            echo '</tbody></table>';
        } else {
            echo '<p>Este serviço não possui checklist configurado.</p>';
        }

        echo '<h2 style="margin-top:30px;">Arquivos enviados</h2>';

        if (!empty($files)) {
            echo '<table class="widefat striped" style="max-width:1000px;">';
            echo '<thead><tr><th>Documento</th><th>Checklist</th><th>Arquivo</th><th>Tipo</th><th>Download</th></tr></thead><tbody>';

            foreach ($files as $file) {
                $is_checklist_value = isset($file->is_checklist) ? (int) $file->is_checklist : 0;

                $download_url = despachante_normalize_public_url(
                    isset($file->file_url) ? $file->file_url : '',
                    isset($file->file_path) ? $file->file_path : ''
                );

                echo '<tr>';
                echo '<td>' . esc_html($file->tipo_documento) . '</td>';
                echo '<td>' . ($is_checklist_value === 1 ? 'Sim' : 'Não') . '</td>';
                echo '<td>' . esc_html($file->file_name) . '</td>';
                echo '<td>' . esc_html($file->mime_type) . '</td>';
                echo '<td><a class="button button-primary" href="' . esc_url($download_url) . '" target="_blank" rel="noopener noreferrer">Abrir / Baixar</a></td>';
                echo '</tr>';
            }

            echo '</tbody></table>';
        } else {
            echo '<p>Nenhum arquivo enviado.</p>';
        }

        echo '</div>';
        return;
    }

    $leads = $wpdb->get_results("SELECT * FROM {$leads_table} ORDER BY created_at DESC LIMIT 200");

    if (empty($leads)) {
        echo '<p>Nenhum lead recebido ainda.</p>';
        echo '</div>';
        return;
    }

    echo '<table class="widefat striped">';
    echo '<thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>WhatsApp</th>
                <th>Serviço</th>
                <th>Objetivo</th>
                <th>LGPD</th>
                <th>Status</th>
                <th>Data</th>
                <th>Ações</th>
            </tr>
          </thead><tbody>';

    foreach ($leads as $lead) {
        $view_url = admin_url('admin.php?page=despachante-leads&lead_id=' . absint($lead->id));

        $objetivo_label = function_exists('despachante_get_objective_label')
            ? despachante_get_objective_label($lead->objetivo_atendimento)
            : $lead->objetivo_atendimento;

        $lgpd_label = ((int) $lead->lgpd_aceito === 1) ? 'Sim' : 'Não';

        echo '<tr>';
        echo '<td>' . esc_html($lead->id) . '</td>';
        echo '<td>' . esc_html($lead->nome) . '</td>';
        echo '<td>' . esc_html($lead->whatsapp) . '</td>';
        echo '<td>' . esc_html($lead->servico_nome) . '</td>';
        echo '<td>' . esc_html($objetivo_label) . '</td>';
        echo '<td>' . esc_html($lgpd_label) . '</td>';
        echo '<td>' . esc_html($lead->status) . '</td>';
        echo '<td>' . esc_html($lead->created_at) . '</td>';
        echo '<td><a class="button" href="' . esc_url($view_url) . '">Ver detalhes</a></td>';
        echo '</tr>';
    }

    echo '</tbody></table>';
    echo '</div>';
}