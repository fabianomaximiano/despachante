<?php
/**
 * Tema: Despachante Digital Flow
 * Versão: 3.3.5
 */

function despachante_digital_scripts() {
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css');
    wp_enqueue_style('main-style', get_template_directory_uri() . '/assets/css/estyle.css', array('bootstrap-css'), '3.3.5');

    wp_enqueue_script('jquery');
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js', array('jquery'), '4.6.2', true);

    if (file_exists(get_template_directory() . '/assets/js/handlePreAnalise.js')) {
        wp_enqueue_script(
            'handle-pre-analise',
            get_template_directory_uri() . '/assets/js/handlePreAnalise.js',
            array('jquery'),
            '3.3.5',
            true
        );

        wp_localize_script('handle-pre-analise', 'wp_ajax_obj', array(
            'ajax_url'      => admin_url('admin-ajax.php'),
            'nonce'         => wp_create_nonce('despachante_pre_analise_nonce'),
            'max_upload'    => wp_max_upload_size(),
            'service_docs'  => despachante_get_service_documents_map(),
            'messages'      => array(
                'select_service' => 'Selecione um serviço para exibir os documentos necessários.',
                'no_docs'        => 'Este serviço não possui checklist configurado no momento.',
                'sending'        => 'Enviando sua solicitação...',
            ),
        ));
    }
}
add_action('wp_enqueue_scripts', 'despachante_digital_scripts');

add_theme_support('title-tag');
add_theme_support('post-thumbnails');

/* ======================================
SANITIZERS
====================================== */

function despachante_sanitize_icon_shape($value) {
    $valid = array('rounded-square', 'circle');
    return in_array($value, $valid, true) ? $value : 'rounded-square';
}

function despachante_sanitize_hover_effect($value) {
    $valid = array('lift', 'glow', 'zoom-icon');
    return in_array($value, $valid, true) ? $value : 'lift';
}

function despachante_sanitize_rgba_color($value) {
    $value = trim((string) $value);

    if ($value === '') {
        return 'rgba(0,0,0,0.05)';
    }

    if (preg_match('/^rgba?\(\s*(\d{1,3}\s*,\s*){2,3}(\d*\.?\d+)?\s*\)$/', $value)) {
        return $value;
    }

    return 'rgba(0,0,0,0.05)';
}

function despachante_sanitize_social_icon_style($value) {
    $valid = array('icon', 'circle', 'square');
    return in_array($value, $valid, true) ? $value : 'icon';
}

/* ======================================
UTILS
====================================== */

function despachante_slugify($text) {
    $text = remove_accents((string) $text);
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9]+/', '_', $text);
    $text = trim($text, '_');

    return !empty($text) ? $text : 'documento';
}

function despachante_normalize_document_label($label) {
    return trim(wp_strip_all_tags((string) $label));
}

function despachante_table_has_column($table_name, $column_name) {
    global $wpdb;

    $table_name  = preg_replace('/[^a-zA-Z0-9_]/', '', (string) $table_name);
    $column_name = preg_replace('/[^a-zA-Z0-9_]/', '', (string) $column_name);

    if (empty($table_name) || empty($column_name)) {
        return false;
    }

    $result = $wpdb->get_var("SHOW COLUMNS FROM `{$table_name}` LIKE '{$column_name}'");

    return !empty($result);
}

function despachante_get_runtime_base_url() {
    $scheme = is_ssl() ? 'https' : 'http';
    $host   = '';

    if (!empty($_SERVER['HTTP_HOST'])) {
        $host = sanitize_text_field(wp_unslash($_SERVER['HTTP_HOST']));
    }

    if (empty($host) || $host === '0.0.0.0' || $host === '127.0.0.1') {
        $site_url = site_url();
        $parts    = wp_parse_url($site_url);

        if (!empty($parts['scheme'])) {
            $scheme = $parts['scheme'];
        }

        if (!empty($parts['host']) && !in_array($parts['host'], array('0.0.0.0', '127.0.0.1'), true)) {
            $host = $parts['host'];

            if (!empty($parts['port'])) {
                $host .= ':' . $parts['port'];
            }
        }
    }

    if (empty($host) || $host === '0.0.0.0' || $host === '127.0.0.1') {
        $host = 'localhost:8085';
    }

    return $scheme . '://' . $host;
}

function despachante_build_public_upload_url($absolute_file_path) {
    $absolute_file_path = wp_normalize_path($absolute_file_path);
    $content_dir        = wp_normalize_path(WP_CONTENT_DIR);

    $relative_path = '';

    if (strpos($absolute_file_path, $content_dir) === 0) {
        $relative_path = 'wp-content/' . ltrim(str_replace($content_dir, '', $absolute_file_path), '/');
    } else {
        $base_dir = wp_normalize_path(ABSPATH);
        $relative_path = ltrim(str_replace($base_dir, '', $absolute_file_path), '/');
    }

    $relative_path = ltrim(str_replace('\\', '/', $relative_path), '/');

    return esc_url_raw(trailingslashit(despachante_get_runtime_base_url()) . $relative_path);
}

function despachante_normalize_public_url($url, $file_path = '') {
    $url = trim((string) $url);

    if (!empty($file_path)) {
        $normalized_from_path = despachante_build_public_upload_url($file_path);

        if (
            empty($url) ||
            strpos($url, '0.0.0.0') !== false ||
            strpos($url, '127.0.0.1') !== false
        ) {
            return $normalized_from_path;
        }
    }

    if (empty($url)) {
        return '';
    }

    $parts = wp_parse_url($url);

    if (empty($parts['host'])) {
        return $url;
    }

    if (in_array($parts['host'], array('0.0.0.0', '127.0.0.1'), true)) {
        $runtime = wp_parse_url(despachante_get_runtime_base_url());
        $scheme  = !empty($runtime['scheme']) ? $runtime['scheme'] : 'http';
        $host    = !empty($runtime['host']) ? $runtime['host'] : 'localhost';
        $port    = !empty($runtime['port']) ? ':' . $runtime['port'] : '';
        $path    = !empty($parts['path']) ? $parts['path'] : '';
        $query   = !empty($parts['query']) ? '?' . $parts['query'] : '';
        $frag    = !empty($parts['fragment']) ? '#' . $parts['fragment'] : '';

        return esc_url_raw($scheme . '://' . $host . $port . $path . $query . $frag);
    }

    return esc_url_raw($url);
}

/* ======================================
BANCO DE DADOS - LEADS E ARQUIVOS
====================================== */

function despachante_get_db_version() {
    return '1.2.1';
}

function despachante_create_database_tables() {
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();
    $leads_table     = $wpdb->prefix . 'despachante_leads';
    $files_table     = $wpdb->prefix . 'despachante_lead_files';

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    $sql_leads = "CREATE TABLE {$leads_table} (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        nome VARCHAR(255) NOT NULL,
        whatsapp VARCHAR(50) NOT NULL,
        email VARCHAR(255) DEFAULT '' NOT NULL,
        servico_id BIGINT(20) UNSIGNED DEFAULT 0 NOT NULL,
        servico_nome VARCHAR(255) DEFAULT '' NOT NULL,
        objetivo_atendimento VARCHAR(100) DEFAULT '' NOT NULL,
        mensagem LONGTEXT NULL,
        lgpd_aceito TINYINT(1) DEFAULT 0 NOT NULL,
        ip_address VARCHAR(100) DEFAULT '' NOT NULL,
        user_agent TEXT NULL,
        status VARCHAR(50) DEFAULT 'novo' NOT NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY  (id),
        KEY servico_id (servico_id),
        KEY status (status),
        KEY created_at (created_at)
    ) {$charset_collate};";

    $sql_files = "CREATE TABLE {$files_table} (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        lead_id BIGINT(20) UNSIGNED NOT NULL,
        tipo_documento VARCHAR(150) NOT NULL,
        document_slug VARCHAR(150) DEFAULT '' NOT NULL,
        is_checklist TINYINT(1) DEFAULT 0 NOT NULL,
        file_name VARCHAR(255) DEFAULT '' NOT NULL,
        file_url TEXT NULL,
        file_path TEXT NULL,
        mime_type VARCHAR(150) DEFAULT '' NOT NULL,
        attachment_id BIGINT(20) UNSIGNED DEFAULT 0 NOT NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY  (id),
        KEY lead_id (lead_id),
        KEY tipo_documento (tipo_documento),
        KEY document_slug (document_slug)
    ) {$charset_collate};";

    dbDelta($sql_leads);
    dbDelta($sql_files);

    update_option('despachante_db_version', despachante_get_db_version());
}

function despachante_maybe_create_database_tables() {
    $installed_version = get_option('despachante_db_version');

    if ($installed_version !== despachante_get_db_version()) {
        despachante_create_database_tables();
    }
}
add_action('after_switch_theme', 'despachante_create_database_tables');
add_action('admin_init', 'despachante_maybe_create_database_tables');

/* ======================================
DOCUMENTOS POR SERVIÇO
====================================== */

function despachante_get_default_required_documents() {
    return array(
        'RG',
        'CPF',
        'CNH',
        'Comprovante de residência',
        'CRLV',
        'Comprovante de pagamento',
        'Procuração',
    );
}

function despachante_get_service_required_documents($post_id) {
    $saved = get_post_meta($post_id, '_serv_documentos_requeridos', true);

    if (is_array($saved) && !empty($saved)) {
        return array_values(array_filter(array_map('despachante_normalize_document_label', $saved)));
    }

    return despachante_get_default_required_documents();
}

function despachante_get_service_documents_map() {
    $map = array();

    $services = get_posts(array(
        'post_type'      => 'servicos',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'fields'         => 'ids',
    ));

    if (!empty($services)) {
        foreach ($services as $service_id) {
            $map[$service_id] = despachante_get_service_required_documents($service_id);
        }
    }

    return $map;
}

/* ======================================
CUSTOMIZER
====================================== */

function despachante_customize_register($wp_customize) {

    /* Hero */
    $wp_customize->add_section('hero_section', array(
        'title'    => 'Hero',
        'priority' => 25
    ));

    $wp_customize->add_setting('hero_title', array(
        'default'           => 'Despachante Digital Flow',
        'sanitize_callback' => 'sanitize_text_field'
    ));

    $wp_customize->add_control('hero_title', array(
        'label'   => 'Título do Hero',
        'section' => 'hero_section',
        'type'    => 'text'
    ));

    $wp_customize->add_setting('hero_subtitle', array(
        'default'           => 'Agilidade, segurança e atendimento profissional para regularização de veículos.',
        'sanitize_callback' => 'sanitize_text_field'
    ));

    $wp_customize->add_control('hero_subtitle', array(
        'label'   => 'Subtítulo do Hero',
        'section' => 'hero_section',
        'type'    => 'text'
    ));

    $wp_customize->add_setting('hero_button_text', array(
        'default'           => 'Começar Agora',
        'sanitize_callback' => 'sanitize_text_field'
    ));

    $wp_customize->add_control('hero_button_text', array(
        'label'   => 'Texto do botão',
        'section' => 'hero_section',
        'type'    => 'text'
    ));

    $wp_customize->add_setting('hero_button_link', array(
        'default'           => '#pre-analise',
        'sanitize_callback' => 'sanitize_text_field'
    ));

    $wp_customize->add_control('hero_button_link', array(
        'label'       => 'Link do botão',
        'section'     => 'hero_section',
        'type'        => 'text',
        'description' => 'Exemplo: #pre-analise, /contato ou URL completa.'
    ));

    $wp_customize->add_setting('hero_bg_image', array(
        'default'           => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=1920&q=80',
        'sanitize_callback' => 'esc_url_raw'
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control(
        $wp_customize,
        'hero_bg_image',
        array(
            'label'   => 'Imagem de fundo do Hero',
            'section' => 'hero_section'
        )
    ));

    /* WhatsApp */
    $wp_customize->add_section('whatsapp_section', array(
        'title'    => 'Configurações de WhatsApp',
        'priority' => 30
    ));

    $wp_customize->add_setting('dev_whatsapp_enabled', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean'
    ));

    $wp_customize->add_control('dev_whatsapp_enabled', array(
        'label'   => 'Exibir botão "Falar com o Desenvolvedor"',
        'section' => 'whatsapp_section',
        'type'    => 'checkbox'
    ));

    $wp_customize->add_setting('whatsapp_number', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field'
    ));

    $wp_customize->add_control('whatsapp_number', array(
        'label'       => 'WhatsApp do Desenvolvedor (CTA do rodapé)',
        'section'     => 'whatsapp_section',
        'type'        => 'text',
        'description' => 'Mantém o botão do rodapé que já existe.'
    ));

    $wp_customize->add_setting('whatsapp_message', array(
        'default'           => 'Olá! Gostaria de fazer um orçamento.',
        'sanitize_callback' => 'sanitize_text_field'
    ));

    $wp_customize->add_control('whatsapp_message', array(
        'label'   => 'Mensagem do Desenvolvedor (CTA do rodapé)',
        'section' => 'whatsapp_section',
        'type'    => 'text'
    ));

    $wp_customize->add_setting('office_whatsapp_enabled', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean'
    ));

    $wp_customize->add_control('office_whatsapp_enabled', array(
        'label'   => 'Exibir WhatsApp flutuante do escritório',
        'section' => 'whatsapp_section',
        'type'    => 'checkbox'
    ));

    $wp_customize->add_setting('office_whatsapp_number', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field'
    ));

    $wp_customize->add_control('office_whatsapp_number', array(
        'label'       => 'WhatsApp do Escritório (botão flutuante)',
        'section'     => 'whatsapp_section',
        'type'        => 'text',
        'description' => 'Exemplo: 5511999999999'
    ));

    $wp_customize->add_setting('office_whatsapp_message', array(
        'default'           => 'Olá! Gostaria de solicitar mais informações sobre os serviços do despachante.',
        'sanitize_callback' => 'sanitize_text_field'
    ));

    $wp_customize->add_control('office_whatsapp_message', array(
        'label'   => 'Mensagem do Escritório (botão flutuante)',
        'section' => 'whatsapp_section',
        'type'    => 'text'
    ));

    $wp_customize->add_setting('office_whatsapp_text', array(
        'default'           => 'Fale com o escritório',
        'sanitize_callback' => 'sanitize_text_field'
    ));

    $wp_customize->add_control('office_whatsapp_text', array(
        'label'   => 'Texto do botão flutuante',
        'section' => 'whatsapp_section',
        'type'    => 'text'
    ));

    /* Rodapé */
    $wp_customize->add_section('footer_section', array(
        'title'    => 'Rodapé',
        'priority' => 40
    ));

    $wp_customize->add_setting('footer_cta_title', array(
        'default'           => 'Interessado em ter um sistema assim para sua empresa?',
        'sanitize_callback' => 'sanitize_text_field'
    ));

    $wp_customize->add_control('footer_cta_title', array(
        'label'   => 'Título CTA',
        'section' => 'footer_section',
        'type'    => 'text'
    ));

    $wp_customize->add_setting('footer_cta_subtitle', array(
        'default'           => 'Esta é uma Landing Page de demonstração. Modernize seu atendimento local hoje mesmo.',
        'sanitize_callback' => 'sanitize_text_field'
    ));

    $wp_customize->add_control('footer_cta_subtitle', array(
        'label'   => 'Subtítulo CTA',
        'section' => 'footer_section',
        'type'    => 'text'
    ));

    $wp_customize->add_setting('footer_copy', array(
        'default'           => '© 2026 — Todos os direitos reservados.',
        'sanitize_callback' => 'sanitize_text_field'
    ));

    $wp_customize->add_control('footer_copy', array(
        'label'   => 'Texto de copyright',
        'section' => 'footer_section',
        'type'    => 'text'
    ));

    $wp_customize->add_setting('footer_bg_color', array(
        'default'           => '#1d2d3d',
        'sanitize_callback' => 'sanitize_hex_color'
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'footer_bg_color',
        array(
            'label'   => 'Cor de Fundo Rodapé',
            'section' => 'footer_section'
        )
    ));

    $wp_customize->add_setting('footer_address', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field'
    ));

    $wp_customize->add_control('footer_address', array(
        'label'   => 'Endereço completo',
        'section' => 'footer_section',
        'type'    => 'text'
    ));

    $wp_customize->add_setting('footer_zipcode', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field'
    ));

    $wp_customize->add_control('footer_zipcode', array(
        'label'   => 'CEP',
        'section' => 'footer_section',
        'type'    => 'text'
    ));

    $wp_customize->add_setting('footer_city_state', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field'
    ));

    $wp_customize->add_control('footer_city_state', array(
        'label'   => 'Cidade / Estado',
        'section' => 'footer_section',
        'type'    => 'text'
    ));

    $wp_customize->add_setting('footer_phone', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field'
    ));

    $wp_customize->add_control('footer_phone', array(
        'label'   => 'Telefone',
        'section' => 'footer_section',
        'type'    => 'text'
    ));

    $wp_customize->add_setting('footer_whatsapp', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field'
    ));

    $wp_customize->add_control('footer_whatsapp', array(
        'label'   => 'WhatsApp do rodapé',
        'section' => 'footer_section',
        'type'    => 'text'
    ));

    $wp_customize->add_setting('footer_email', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_email'
    ));

    $wp_customize->add_control('footer_email', array(
        'label'   => 'E-mail',
        'section' => 'footer_section',
        'type'    => 'email'
    ));

    $wp_customize->add_setting('footer_hours', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field'
    ));

    $wp_customize->add_control('footer_hours', array(
        'label'   => 'Horário de atendimento',
        'section' => 'footer_section',
        'type'    => 'text'
    ));

    /* Redes Sociais */
    $wp_customize->add_section('social_section', array(
        'title'    => 'Redes Sociais',
        'priority' => 41
    ));

    $wp_customize->add_setting('social_instagram', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw'
    ));

    $wp_customize->add_control('social_instagram', array(
        'label'   => 'Instagram',
        'section' => 'social_section',
        'type'    => 'url'
    ));

    $wp_customize->add_setting('social_facebook', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw'
    ));

    $wp_customize->add_control('social_facebook', array(
        'label'   => 'Facebook',
        'section' => 'social_section',
        'type'    => 'url'
    ));

    $wp_customize->add_setting('social_linkedin', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw'
    ));

    $wp_customize->add_control('social_linkedin', array(
        'label'   => 'LinkedIn',
        'section' => 'social_section',
        'type'    => 'url'
    ));

    $wp_customize->add_setting('social_x', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw'
    ));

    $wp_customize->add_control('social_x', array(
        'label'   => 'X',
        'section' => 'social_section',
        'type'    => 'url'
    ));

    $wp_customize->add_setting('social_style', array(
        'default'           => 'icon',
        'sanitize_callback' => 'despachante_sanitize_social_icon_style'
    ));

    $wp_customize->add_control('social_style', array(
        'label'   => 'Estilo dos ícones',
        'section' => 'social_section',
        'type'    => 'select',
        'choices' => array(
            'icon'   => 'Somente ícone',
            'circle' => 'Ícone dentro de círculo',
            'square' => 'Ícone dentro de quadrado arredondado'
        )
    ));

    /* Serviços */
    $wp_customize->add_section('services_section', array(
        'title'    => 'Serviços',
        'priority' => 35
    ));

    $wp_customize->add_setting('services_icon_shape', array(
        'default'           => 'rounded-square',
        'sanitize_callback' => 'despachante_sanitize_icon_shape'
    ));

    $wp_customize->add_control('services_icon_shape', array(
        'label'   => 'Formato do ícone',
        'section' => 'services_section',
        'type'    => 'select',
        'choices' => array(
            'rounded-square' => 'Quadrado com cantos arredondados',
            'circle'         => 'Círculo',
        )
    ));

    $wp_customize->add_setting('services_hover_effect', array(
        'default'           => 'lift',
        'sanitize_callback' => 'despachante_sanitize_hover_effect'
    ));

    $wp_customize->add_control('services_hover_effect', array(
        'label'   => 'Efeito ao passar o mouse',
        'section' => 'services_section',
        'type'    => 'select',
        'choices' => array(
            'lift'      => 'Elevação suave',
            'glow'      => 'Brilho suave',
            'zoom-icon' => 'Zoom no ícone',
        )
    ));

    $wp_customize->add_setting('services_icon_bg_color', array(
        'default'           => '#f4ede3',
        'sanitize_callback' => 'sanitize_hex_color'
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'services_icon_bg_color',
        array(
            'label'   => 'Cor de fundo do ícone',
            'section' => 'services_section'
        )
    ));

    $wp_customize->add_setting('services_icon_border_color', array(
        'default'           => '#d8be97',
        'sanitize_callback' => 'sanitize_hex_color'
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'services_icon_border_color',
        array(
            'label'   => 'Cor da borda do ícone',
            'section' => 'services_section'
        )
    ));

    $wp_customize->add_setting('services_icon_color', array(
        'default'           => '#9a6a3a',
        'sanitize_callback' => 'sanitize_hex_color'
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'services_icon_color',
        array(
            'label'   => 'Cor do ícone',
            'section' => 'services_section'
        )
    ));

    $wp_customize->add_setting('services_card_border_color', array(
        'default'           => '#ece3d7',
        'sanitize_callback' => 'sanitize_hex_color'
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'services_card_border_color',
        array(
            'label'   => 'Cor da borda do card',
            'section' => 'services_section'
        )
    ));

    /* Avaliações Google */
    $wp_customize->add_section('google_reviews_section', array(
        'title'    => 'Avaliações Google',
        'priority' => 36
    ));

    $wp_customize->add_setting('reviews_section_bg', array(
        'default'           => '#f8fafc',
        'sanitize_callback' => 'sanitize_hex_color'
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'reviews_section_bg',
        array(
            'label'   => 'Fundo da seção',
            'section' => 'google_reviews_section'
        )
    ));

    $wp_customize->add_setting('reviews_card_radius', array(
        'default'           => '16',
        'sanitize_callback' => 'absint'
    ));

    $wp_customize->add_control('reviews_card_radius', array(
        'label'       => 'Arredondamento do card (px)',
        'section'     => 'google_reviews_section',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 60,
            'step' => 1
        )
    ));

    $wp_customize->add_setting('reviews_card_border_color', array(
        'default'           => '#e5e7eb',
        'sanitize_callback' => 'sanitize_hex_color'
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'reviews_card_border_color',
        array(
            'label'   => 'Cor da borda do card',
            'section' => 'google_reviews_section'
        )
    ));

    $wp_customize->add_setting('reviews_card_shadow_color', array(
        'default'           => 'rgba(0,0,0,0.05)',
        'sanitize_callback' => 'despachante_sanitize_rgba_color'
    ));

    $wp_customize->add_control('reviews_card_shadow_color', array(
        'label'       => 'Cor da sombra (RGBA)',
        'section'     => 'google_reviews_section',
        'type'        => 'text',
        'description' => 'Exemplo: rgba(0,0,0,0.05)'
    ));

    $wp_customize->add_setting('reviews_card_padding', array(
        'default'           => '25',
        'sanitize_callback' => 'absint'
    ));

    $wp_customize->add_control('reviews_card_padding', array(
        'label'       => 'Padding do card (px)',
        'section'     => 'google_reviews_section',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 80,
            'step' => 1
        )
    ));

    $wp_customize->add_setting('reviews_card_bg', array(
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color'
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'reviews_card_bg',
        array(
            'label'   => 'Fundo do card',
            'section' => 'google_reviews_section'
        )
    ));

    $wp_customize->add_setting('google_reviews_shortcode', array(
        'default'           => '[wp-review-slider]',
        'sanitize_callback' => 'sanitize_text_field'
    ));

    $wp_customize->add_control('google_reviews_shortcode', array(
        'label'       => 'Shortcode do plugin de avaliações',
        'section'     => 'google_reviews_section',
        'type'        => 'text',
        'description' => 'Cole aqui o shortcode gerado pelo plugin WP Google Review Slider.'
    ));
}
add_action('customize_register', 'despachante_customize_register');

/* ======================================
CPTS
====================================== */

function registrar_cpts_despachante() {
    register_post_type('servicos', array(
        'labels' => array(
            'name'          => 'Serviços',
            'singular_name' => 'Serviço'
        ),
        'public'       => true,
        'menu_icon'    => 'dashicons-portfolio',
        'supports'     => array('title', 'editor', 'thumbnail'),
        'show_in_rest' => true
    ));

    register_post_type('faq', array(
        'labels' => array(
            'name'          => 'FAQ',
            'singular_name' => 'Pergunta'
        ),
        'public'       => true,
        'menu_icon'    => 'dashicons-editor-help',
        'supports'     => array('title', 'editor'),
        'show_in_rest' => true
    ));
}
add_action('init', 'registrar_cpts_despachante');

/* ======================================
META BOXES DOS SERVIÇOS
====================================== */

function despachante_add_service_metaboxes() {
    add_meta_box(
        'mb_serv_icon',
        'Ícone do Card',
        'serv_icon_html',
        'servicos',
        'side'
    );

    add_meta_box(
        'mb_serv_docs',
        'Checklist de Documentos',
        'serv_docs_html',
        'servicos',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'despachante_add_service_metaboxes');

function serv_icon_html($post) {
    $icone = get_post_meta($post->ID, '_serv_icone', true);

    wp_nonce_field('save_servico_meta_boxes', 'servico_meta_nonce');

    $icon_options = array(
        'Transferência de propriedade de veículos'            => 'fas fa-exchange-alt',
        'Primeiro emplacamento (veículos zero km)'            => 'fas fa-car-side',
        'Licenciamento anual e regularização de IPVA/multas'  => 'fas fa-calendar-check',
        'Alteração de características (cor, motor, etc.)'     => 'fas fa-palette',
        'Comunicação de venda de veículos'                    => 'fas fa-handshake',
        'Baixa de gravame (alienação fiduciária)'             => 'fas fa-unlock',
        '2ª via de documentos (CRLV-e/CRV)'                   => 'fas fa-copy',
        'Regularização de motor e remarcação de chassi'       => 'fas fa-cogs',
    );
    ?>
    <p>
        <label for="serv_icone"><strong>Classe do Ícone (Font Awesome)</strong></label><br>
        <input
            type="text"
            id="serv_icone"
            name="serv_icone"
            value="<?php echo esc_attr($icone); ?>"
            placeholder="ex: fas fa-car-side"
            style="width:100%;"
        >
    </p>

    <p style="margin:10px 0 6px;">
        <strong>Opções sugeridas para serviços de despachante:</strong>
    </p>

    <div style="display:flex; flex-direction:column; gap:8px;">
        <?php foreach ($icon_options as $label => $class) : ?>
            <button
                type="button"
                class="button button-secondary despachante-icon-option"
                data-icon="<?php echo esc_attr($class); ?>"
                style="display:flex; align-items:center; justify-content:flex-start; gap:10px; text-align:left;"
            >
                <i class="<?php echo esc_attr($class); ?>" style="width:20px;"></i>
                <span><?php echo esc_html($label); ?> — <code><?php echo esc_html($class); ?></code></span>
            </button>
        <?php endforeach; ?>
    </div>

    <p style="margin-top:10px; color:#666; font-size:12px;">
        Clique em uma opção para preencher automaticamente o campo acima.
    </p>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var input = document.getElementById('serv_icone');
            var buttons = document.querySelectorAll('.despachante-icon-option');

            buttons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var iconClass = this.getAttribute('data-icon');
                    if (input) {
                        input.value = iconClass;
                        input.focus();
                    }
                });
            });
        });
    </script>
    <?php
}

function serv_docs_html($post) {
    $docs = despachante_get_service_required_documents($post->ID);
    ?>
    <p>Informe <strong>um documento por linha</strong>. Esse checklist aparecerá no formulário quando este serviço for selecionado.</p>
    <textarea name="serv_documentos_requeridos" rows="10" style="width:100%;"><?php echo esc_textarea(implode("\n", $docs)); ?></textarea>
    <p style="margin-top:8px;color:#666;font-size:12px;">
        Exemplo:<br>
        RG<br>
        CPF<br>
        CNH<br>
        Comprovante de residência
    </p>
    <?php
}

function despachante_save_servico_meta($post_id) {
    if (!isset($_POST['servico_meta_nonce'])) return;
    if (!wp_verify_nonce($_POST['servico_meta_nonce'], 'save_servico_meta_boxes')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!isset($_POST['post_type']) || $_POST['post_type'] !== 'servicos') return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['serv_icone'])) {
        update_post_meta($post_id, '_serv_icone', sanitize_text_field($_POST['serv_icone']));
    }

    if (isset($_POST['serv_documentos_requeridos'])) {
        $raw_lines = explode("\n", wp_unslash($_POST['serv_documentos_requeridos']));
        $docs = array();

        foreach ($raw_lines as $line) {
            $line = despachante_normalize_document_label($line);
            if (!empty($line)) {
                $docs[] = $line;
            }
        }

        update_post_meta($post_id, '_serv_documentos_requeridos', $docs);
    }
}
add_action('save_post', 'despachante_save_servico_meta');

/* ======================================
UPLOAD / EMAIL / FORMULÁRIO
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

    if (!empty($file['error']) && $file['error'] !== UPLOAD_ERR_OK) {
        return new WP_Error('upload_error', 'Erro no envio do arquivo: ' . $file['name']);
    }

    if (!empty($file['size']) && $file['size'] > $max_size) {
        return new WP_Error('upload_size', 'O arquivo ' . $file['name'] . ' excede o limite de 8MB.');
    }

    $check = wp_check_filetype_and_ext($file['tmp_name'], $file['name']);
    $type  = isset($check['type']) ? $check['type'] : '';

    if (empty($type) || !in_array($type, $allowed_mimes, true)) {
        return new WP_Error('upload_type', 'Formato não permitido para o arquivo ' . $file['name'] . '. Use JPG, PNG ou PDF.');
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
        'lead_id'        => $lead_id,
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
        array_splice($insert_format, despachante_table_has_column($files_table, 'document_slug') ? 3 : 2, 0, '%d');
    }

    $inserted = $wpdb->insert($files_table, $insert_data, $insert_format);

    if ($inserted === false) {
        return new WP_Error('db_insert_file_error', 'O upload foi recebido, mas não foi possível registrar o arquivo no banco de dados.');
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

function despachante_send_lead_email($lead_id, $lead_data, $uploaded_files = array()) {
    $recipient = get_theme_mod('footer_email', get_option('admin_email'));
    if (empty($recipient) || !is_email($recipient)) {
        $recipient = get_option('admin_email');
    }

    $subject = 'Nova pré-análise recebida #' . $lead_id;

    $message  = "Nova solicitação recebida.\n\n";
    $message .= "ID: {$lead_id}\n";
    $message .= "Nome: {$lead_data['nome']}\n";
    $message .= "WhatsApp: {$lead_data['whatsapp']}\n";
    $message .= "E-mail: {$lead_data['email']}\n";
    $message .= "Serviço: {$lead_data['servico_nome']}\n";
    $message .= "Objetivo: {$lead_data['objetivo_atendimento']}\n";
    $message .= "Mensagem: {$lead_data['mensagem']}\n";
    $message .= "LGPD aceito: " . ($lead_data['lgpd_aceito'] ? 'Sim' : 'Não') . "\n\n";

    if (!empty($uploaded_files)) {
        $message .= "Arquivos enviados:\n";
        foreach ($uploaded_files as $file_info) {
            $message .= '- ' . $file_info['document_type'] . ': ' . $file_info['file_name'] . ' - ' . $file_info['url'] . "\n";
        }
        $message .= "\n";
    }

    $message .= "Painel administrativo: " . admin_url('admin.php?page=despachante-leads&lead_id=' . $lead_id);

    $headers = array('Content-Type: text/plain; charset=UTF-8');

    wp_mail($recipient, $subject, $message, $headers);
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

function despachante_validate_required_checklist_files($servico, $files_array) {
    $required_docs = despachante_get_service_required_documents($servico);

    if (empty($required_docs)) {
        return true;
    }

    $normalized_files = despachante_extract_checklist_files($files_array);
    $missing_docs = array();

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

function despachante_handle_pre_analise_submission() {
    check_ajax_referer('despachante_pre_analise_nonce', 'nonce');

    $nome     = isset($_POST['nome']) ? sanitize_text_field(wp_unslash($_POST['nome'])) : '';
    $whatsapp = isset($_POST['telefone']) ? sanitize_text_field(wp_unslash($_POST['telefone'])) : '';
    $email    = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
    $servico  = isset($_POST['servico']) ? absint($_POST['servico']) : 0;
    $objetivo = isset($_POST['objetivo_atendimento']) ? sanitize_text_field(wp_unslash($_POST['objetivo_atendimento'])) : '';
    $mensagem = isset($_POST['mensagem']) ? sanitize_textarea_field(wp_unslash($_POST['mensagem'])) : '';
    $lgpd     = isset($_POST['lgpd_aceito']) ? 1 : 0;

    if (empty($nome)) {
        wp_send_json_error(array('message' => 'Informe seu nome.'), 400);
    }

    if (empty($whatsapp)) {
        wp_send_json_error(array('message' => 'Informe seu WhatsApp.'), 400);
    }

    if (empty($email)) {
        wp_send_json_error(array('message' => 'Informe seu e-mail.'), 400);
    }

    if (!is_email($email)) {
        wp_send_json_error(array('message' => 'Informe um e-mail válido.'), 400);
    }

    if (empty($servico)) {
        wp_send_json_error(array('message' => 'Selecione um serviço.'), 400);
    }

    $checklist_validation = despachante_validate_required_checklist_files(
        $servico,
        isset($_FILES['documentos_checklist']) ? $_FILES['documentos_checklist'] : array()
    );

    if (is_wp_error($checklist_validation)) {
        wp_send_json_error(array('message' => $checklist_validation->get_error_message()), 400);
    }

    $servico_post = get_post($servico);
    $servico_nome = ($servico_post && $servico_post->post_type === 'servicos') ? $servico_post->post_title : '';

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
            'ip_address'           => isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'])) : '',
            'user_agent'           => isset($_SERVER['HTTP_USER_AGENT']) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_USER_AGENT'])) : '',
            'status'               => 'novo',
        ),
        array('%s', '%s', '%s', '%d', '%s', '%s', '%s', '%d', '%s', '%s', '%s')
    );

    if (!$inserted) {
        wp_send_json_error(array('message' => 'Não foi possível salvar sua solicitação.'), 500);
    }

    $lead_id = (int) $wpdb->insert_id;
    $uploaded_files = array();

    if (!empty($_FILES['documentos_checklist'])) {
        $normalized_checklist = despachante_extract_checklist_files($_FILES['documentos_checklist']);
        $document_labels = isset($_POST['document_labels']) && is_array($_POST['document_labels']) ? wp_unslash($_POST['document_labels']) : array();

        foreach ($normalized_checklist as $slug => $single_file) {
            if (empty($single_file['name'])) {
                continue;
            }

            $document_label = isset($document_labels[$slug]) ? sanitize_text_field($document_labels[$slug]) : sanitize_text_field($slug);
            $upload_result  = despachante_handle_single_upload($single_file, $lead_id, $document_label, sanitize_text_field($slug), 1);

            if (is_wp_error($upload_result)) {
                wp_send_json_error(array('message' => $upload_result->get_error_message()), 400);
            }

            $uploaded_files[] = $upload_result;
        }
    }

    if (!empty($_FILES['documentos_extras']) && !empty($_FILES['documentos_extras']['name']) && is_array($_FILES['documentos_extras']['name'])) {
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

            $upload_result = despachante_handle_single_upload($single_file, $lead_id, 'Documento extra', 'documento_extra', 0);

            if (is_wp_error($upload_result)) {
                wp_send_json_error(array(
                    'message' => $upload_result->get_error_message(),
                ), 400);
            }

            $uploaded_files[] = $upload_result;
        }
    }

    despachante_send_lead_email($lead_id, array(
        'nome'                 => $nome,
        'whatsapp'             => $whatsapp,
        'email'                => $email,
        'servico_nome'         => $servico_nome,
        'objetivo_atendimento' => $objetivo,
        'mensagem'             => $mensagem,
        'lgpd_aceito'          => $lgpd,
    ), $uploaded_files);

    wp_send_json_success(array(
        'message' => 'Solicitação enviada com sucesso.',
        'lead_id' => $lead_id,
    ));
}
add_action('wp_ajax_despachante_pre_analise_submit', 'despachante_handle_pre_analise_submission');
add_action('wp_ajax_nopriv_despachante_pre_analise_submit', 'despachante_handle_pre_analise_submission');

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
        $uploaded_map = array();

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

        echo '<p><a href="' . esc_url(admin_url('admin.php?page=despachante-leads')) . '" class="button">Voltar para lista</a></p>';

        echo '<table class="widefat striped" style="max-width:900px;">';
        echo '<tbody>';
        echo '<tr><th style="width:220px;">ID</th><td>' . esc_html($lead->id) . '</td></tr>';
        echo '<tr><th>Nome</th><td>' . esc_html($lead->nome) . '</td></tr>';
        echo '<tr><th>WhatsApp</th><td>' . esc_html($lead->whatsapp) . '</td></tr>';
        echo '<tr><th>E-mail</th><td>' . esc_html($lead->email) . '</td></tr>';
        echo '<tr><th>Serviço</th><td>' . esc_html($lead->servico_nome) . '</td></tr>';
        echo '<tr><th>Objetivo</th><td>' . esc_html($lead->objetivo_atendimento) . '</td></tr>';
        echo '<tr><th>Mensagem</th><td>' . nl2br(esc_html($lead->mensagem)) . '</td></tr>';
        echo '<tr><th>LGPD aceito</th><td>' . ($lead->lgpd_aceito ? 'Sim' : 'Não') . '</td></tr>';
        echo '<tr><th>Status</th><td>' . esc_html($lead->status) . '</td></tr>';
        echo '<tr><th>Data</th><td>' . esc_html($lead->created_at) . '</td></tr>';
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
                <th>Status</th>
                <th>Data</th>
                <th>Ações</th>
            </tr>
          </thead><tbody>';

    foreach ($leads as $lead) {
        $view_url = admin_url('admin.php?page=despachante-leads&lead_id=' . absint($lead->id));

        echo '<tr>';
        echo '<td>' . esc_html($lead->id) . '</td>';
        echo '<td>' . esc_html($lead->nome) . '</td>';
        echo '<td>' . esc_html($lead->whatsapp) . '</td>';
        echo '<td>' . esc_html($lead->servico_nome) . '</td>';
        echo '<td>' . esc_html($lead->status) . '</td>';
        echo '<td>' . esc_html($lead->created_at) . '</td>';
        echo '<td><a class="button" href="' . esc_url($view_url) . '">Ver detalhes</a></td>';
        echo '</tr>';
    }

    echo '</tbody></table>';
    echo '</div>';
}

/* ======================================
CSS DINÂMICO
====================================== */

function despachante_dynamic_css() {
    $footer_bg                  = get_theme_mod('footer_bg_color', '#1d2d3d');
    $services_icon_bg           = get_theme_mod('services_icon_bg_color', '#f4ede3');
    $services_icon_border       = get_theme_mod('services_icon_border_color', '#d8be97');
    $services_icon_color        = get_theme_mod('services_icon_color', '#9a6a3a');
    $services_card_border_color = get_theme_mod('services_card_border_color', '#ece3d7');

    $reviews_section_bg        = get_theme_mod('reviews_section_bg', '#f8fafc');
    $reviews_card_radius       = get_theme_mod('reviews_card_radius', '16');
    $reviews_card_border_color = get_theme_mod('reviews_card_border_color', '#e5e7eb');
    $reviews_card_shadow_color = get_theme_mod('reviews_card_shadow_color', 'rgba(0,0,0,0.05)');
    $reviews_card_padding      = get_theme_mod('reviews_card_padding', '25');
    $reviews_card_bg           = get_theme_mod('reviews_card_bg', '#ffffff');

    echo '<style>
        .footer-cta {
            background-color: ' . esc_attr($footer_bg) . ' !important;
        }

        :root {
            --services-icon-bg: ' . esc_attr($services_icon_bg) . ';
            --services-icon-border: ' . esc_attr($services_icon_border) . ';
            --services-icon-color: ' . esc_attr($services_icon_color) . ';
            --services-card-border: ' . esc_attr($services_card_border_color) . ';
        }

        .reviews-section {
            background: ' . esc_attr($reviews_section_bg) . ';
        }

        .wprevpro_t1_outer {
            border-radius: ' . absint($reviews_card_radius) . 'px;
            border: 1px solid ' . esc_attr($reviews_card_border_color) . ';
            box-shadow: 0 10px 25px ' . esc_attr($reviews_card_shadow_color) . ';
            padding: ' . absint($reviews_card_padding) . 'px;
            background: ' . esc_attr($reviews_card_bg) . ';
        }
    </style>';
}
add_action('wp_head', 'despachante_dynamic_css');