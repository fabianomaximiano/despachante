<?php
if (!defined('ABSPATH')) {
    exit;
}

/* ======================================
EMAIL TEMPLATE
====================================== */

function despachante_email_template($subject, $content) {

    $logo          = get_theme_mod('email_logo');
    $company_name  = get_theme_mod('email_company_name', get_bloginfo('name'));
    $primary_color = get_theme_mod('email_primary_color', '#1d2d3d');
    $footer_text   = get_theme_mod(
        'email_footer_text',
        'Mensagem automática enviada pelo sistema.'
    );

    ob_start();
    ?>
    <html>
    <body style="margin:0;padding:0;background:#f4f6f8;font-family:Arial,Helvetica,sans-serif;">
        <table width="100%" cellpadding="0" cellspacing="0" style="padding:40px 0;">
            <tr>
                <td align="center">
                    <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 4px 12px rgba(0,0,0,0.08);">

                        <tr>
                            <td style="background:<?php echo esc_attr($primary_color); ?>;padding:20px;text-align:center;">
                                <?php if ($logo) : ?>
                                    <img src="<?php echo esc_url($logo); ?>" style="max-height:60px;">
                                <?php else : ?>
                                    <h2 style="color:#ffffff;margin:0;">
                                        <?php echo esc_html($company_name); ?>
                                    </h2>
                                <?php endif; ?>
                            </td>
                        </tr>

                        <tr>
                            <td style="padding:30px;color:#333333;font-size:15px;line-height:1.6;">
                                <h3 style="margin-top:0;"><?php echo esc_html($subject); ?></h3>

                                <?php echo wp_kses_post($content); ?>
                            </td>
                        </tr>

                        <tr>
                            <td style="background:#f4f6f8;padding:20px;font-size:12px;color:#666;text-align:center;">
                                <?php echo esc_html($footer_text); ?><br>
                                <?php echo esc_html($company_name); ?>
                            </td>
                        </tr>

                    </table>
                </td>
            </tr>
        </table>
    </body>
    </html>
    <?php

    return ob_get_clean();
}

/* ======================================
EMAIL PARA O ESCRITÓRIO
====================================== */

function despachante_send_lead_email($lead_id, $lead_data, $uploaded_files = array()) {

    $recipient = get_theme_mod('footer_email', get_option('admin_email'));

    if (empty($recipient) || !is_email($recipient)) {
        $recipient = get_option('admin_email');
    }

    $subject = 'Nova pré-análise recebida #' . $lead_id;

    $content  = '<p><strong>Nova solicitação recebida.</strong></p>';

    $content .= '<p>';
    $content .= '<strong>ID:</strong> ' . esc_html($lead_id) . '<br>';
    $content .= '<strong>Nome:</strong> ' . esc_html($lead_data['nome']) . '<br>';
    $content .= '<strong>WhatsApp:</strong> ' . esc_html($lead_data['whatsapp']) . '<br>';
    $content .= '<strong>E-mail:</strong> ' . esc_html($lead_data['email']) . '<br>';
    $content .= '<strong>Serviço:</strong> ' . esc_html($lead_data['servico_nome']) . '<br>';
    $content .= '<strong>Objetivo:</strong> ' . esc_html($lead_data['objetivo_atendimento']) . '<br>';
    $content .= '<strong>Mensagem:</strong><br>' . nl2br(esc_html($lead_data['mensagem']));
    $content .= '</p>';

    $content .= '<p><strong>LGPD aceito:</strong> ' .
        ($lead_data['lgpd_aceito'] ? 'Sim' : 'Não') .
        '</p>';

    if (!empty($uploaded_files)) {

        $content .= '<p><strong>Documentos enviados:</strong></p>';
        $content .= '<ul>';

        foreach ($uploaded_files as $file) {

            $url = despachante_normalize_public_url(
                $file['url'],
                isset($file['path']) ? $file['path'] : ''
            );

            $content .= '<li>';
            $content .= esc_html($file['document_type']) .
                ': <a href="' . esc_url($url) . '">' .
                esc_html($file['file_name']) .
                '</a>';
            $content .= '</li>';
        }

        $content .= '</ul>';
    }

    $content .= '<p>';
    $content .= '<a href="' .
        esc_url(admin_url('admin.php?page=despachante-leads&lead_id=' . $lead_id)) .
        '" style="display:inline-block;background:#1d2d3d;color:#fff;padding:10px 20px;text-decoration:none;border-radius:4px;">';
    $content .= 'Abrir no painel administrativo';
    $content .= '</a>';
    $content .= '</p>';

    $html = despachante_email_template($subject, $content);

    $headers = array(
        'Content-Type: text/html; charset=UTF-8'
    );

    wp_mail($recipient, $subject, $html, $headers);
}

/* ======================================
EMAIL PARA O CLIENTE (OPCIONAL)
====================================== */

function despachante_send_client_confirmation($lead_data) {

    if (empty($lead_data['email']) || !is_email($lead_data['email'])) {
        return;
    }

    $subject = 'Recebemos sua solicitação';

    $content  = '<p>Olá <strong>' . esc_html($lead_data['nome']) . '</strong>,</p>';

    $content .= '<p>';
    $content .= 'Recebemos sua solicitação referente ao serviço:<br>';
    $content .= '<strong>' . esc_html($lead_data['servico_nome']) . '</strong>';
    $content .= '</p>';

    $content .= '<p>';
    $content .= 'Nossa equipe irá analisar as informações e retornará pelo WhatsApp informado.';
    $content .= '</p>';

    $content .= '<p>';
    $content .= 'Obrigado pelo contato.';
    $content .= '</p>';

    $html = despachante_email_template($subject, $content);

    $headers = array(
        'Content-Type: text/html; charset=UTF-8'
    );

    wp_mail($lead_data['email'], $subject, $html, $headers);
}