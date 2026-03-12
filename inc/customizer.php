<?php
if (!defined('ABSPATH')) {
    exit;
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

    /* E-mails */
    $wp_customize->add_section('email_section', array(
        'title'    => 'E-mails',
        'priority' => 42
    ));

    $wp_customize->add_setting('email_logo', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw'
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control(
        $wp_customize,
        'email_logo',
        array(
            'label'       => 'Logo dos e-mails',
            'section'     => 'email_section',
            'description' => 'Essa imagem aparecerá no topo dos e-mails enviados pelo sistema.'
        )
    ));

    $wp_customize->add_setting('email_company_name', array(
        'default'           => get_bloginfo('name'),
        'sanitize_callback' => 'sanitize_text_field'
    ));

    $wp_customize->add_control('email_company_name', array(
        'label'   => 'Nome da empresa nos e-mails',
        'section' => 'email_section',
        'type'    => 'text'
    ));

    $wp_customize->add_setting('email_primary_color', array(
        'default'           => '#1d2d3d',
        'sanitize_callback' => 'sanitize_hex_color'
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'email_primary_color',
        array(
            'label'   => 'Cor principal dos e-mails',
            'section' => 'email_section'
        )
    ));

    $wp_customize->add_setting('email_footer_text', array(
        'default'           => 'Mensagem automática enviada pelo sistema de atendimento.',
        'sanitize_callback' => 'sanitize_textarea_field'
    ));

    $wp_customize->add_control('email_footer_text', array(
        'label'   => 'Texto do rodapé dos e-mails',
        'section' => 'email_section',
        'type'    => 'textarea'
    ));
}

add_action('customize_register', 'despachante_customize_register');