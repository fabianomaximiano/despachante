<?php
if (!defined('ABSPATH')) {
    exit;
}

/* ======================================
HELPERS DE SANITIZAÇÃO
====================================== */

if (!function_exists('despachante_sanitize_checkbox')) {
    function despachante_sanitize_checkbox($checked) {
        return (isset($checked) && true == $checked);
    }
}

if (!function_exists('despachante_sanitize_select')) {
    function despachante_sanitize_select($input, $setting) {
        $input = sanitize_key($input);
        $control = $setting->manager->get_control($setting->id);

        if (!$control || empty($control->choices)) {
            return $setting->default;
        }

        return array_key_exists($input, $control->choices) ? $input : $setting->default;
    }
}

if (!function_exists('despachante_sanitize_absint')) {
    function despachante_sanitize_absint($value) {
        return absint($value);
    }
}

if (!function_exists('despachante_sanitize_text_or_rgba')) {
    function despachante_sanitize_text_or_rgba($value) {
        return sanitize_text_field($value);
    }
}

/* ======================================
CUSTOMIZER
====================================== */

function despachante_customize_register($wp_customize) {

    /* ======================================
    SEO E IDENTIDADE
    ====================================== */
    $wp_customize->add_section('despachante_seo_section', array(
        'title'    => 'SEO e Identidade',
        'priority' => 20,
    ));

    $wp_customize->add_setting('seo_meta_description', array(
        'default'           => 'Sistema para Despachantes - Demonstração da plataforma de gestão e automação para regularização de veículos desenvolvida por Fabiano Maximiano.',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('seo_meta_description', array(
        'label'       => 'Descrição do Site (Google)',
        'description' => 'Aparece nos resultados de busca e melhora a acessibilidade.',
        'section'     => 'despachante_seo_section',
        'type'        => 'textarea',
    ));

    /* ======================================
    HERO
    ====================================== */
    $wp_customize->add_section('hero_section', array(
        'title'    => 'Hero',
        'priority' => 25,
    ));

    $wp_customize->add_setting('hero_title', array(
        'default'           => 'Despachante Digital Flow',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('hero_title', array(
        'label'   => 'Título do Hero',
        'section' => 'hero_section',
        'type'    => 'text',
    ));

    $wp_customize->add_setting('hero_subtitle', array(
        'default'           => 'Agilidade, segurança e atendimento profissional para regularização de veículos.',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('hero_subtitle', array(
        'label'   => 'Subtítulo do Hero',
        'section' => 'hero_section',
        'type'    => 'text',
    ));

    $wp_customize->add_setting('hero_button_text', array(
        'default'           => 'Começar agora',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('hero_button_text', array(
        'label'   => 'Texto do Botão Hero',
        'section' => 'hero_section',
        'type'    => 'text',
    ));

    $wp_customize->add_setting('hero_button_link', array(
        'default'           => '#pre-analise',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('hero_button_link', array(
        'label'       => 'Link do Botão Hero',
        'description' => 'Pode ser uma âncora interna como #pre-analise ou uma URL completa.',
        'section'     => 'hero_section',
        'type'        => 'url',
    ));

    $wp_customize->add_setting('hero_bg_image', array(
        'default'           => get_template_directory_uri() . '/assets/img/hero.webp',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control(
        $wp_customize,
        'hero_bg_image',
        array(
            'label'       => 'Imagem de fundo do Hero',
            'description' => 'Prefira imagens WebP otimizadas para melhorar o PageSpeed.',
            'section'     => 'hero_section',
        )
    ));

    /* ======================================
    SERVIÇOS - VISUAL
    ====================================== */
    $wp_customize->add_section('services_visual_section', array(
        'title'    => 'Serviços - Visual',
        'priority' => 40,
    ));

    $wp_customize->add_setting('services_icon_shape', array(
        'default'           => 'rounded-square',
        'sanitize_callback' => 'despachante_sanitize_select',
    ));

    $wp_customize->add_control('services_icon_shape', array(
        'label'   => 'Formato do ícone',
        'section' => 'services_visual_section',
        'type'    => 'select',
        'choices' => array(
            'rounded-square' => 'Quadrado arredondado',
            'circle'         => 'Círculo',
        ),
    ));

    $wp_customize->add_setting('services_hover_effect', array(
        'default'           => 'lift',
        'sanitize_callback' => 'despachante_sanitize_select',
    ));

    $wp_customize->add_control('services_hover_effect', array(
        'label'   => 'Efeito no hover',
        'section' => 'services_visual_section',
        'type'    => 'select',
        'choices' => array(
            'lift'      => 'Elevar card',
            'glow'      => 'Glow',
            'zoom-icon' => 'Zoom no ícone',
        ),
    ));

    $wp_customize->add_setting('services_icon_bg_color', array(
        'default'           => '#f4ede3',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'services_icon_bg_color',
        array(
            'label'   => 'Cor de fundo do ícone',
            'section' => 'services_visual_section',
        )
    ));

    $wp_customize->add_setting('services_icon_border_color', array(
        'default'           => '#d8be97',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'services_icon_border_color',
        array(
            'label'   => 'Cor da borda do ícone',
            'section' => 'services_visual_section',
        )
    ));

    $wp_customize->add_setting('services_icon_color', array(
        'default'           => '#9a6a3a',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'services_icon_color',
        array(
            'label'   => 'Cor do ícone',
            'section' => 'services_visual_section',
        )
    ));

    $wp_customize->add_setting('services_card_border_color', array(
        'default'           => '#ece3d7',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'services_card_border_color',
        array(
            'label'   => 'Cor da borda do card',
            'section' => 'services_visual_section',
        )
    ));

    /* ======================================
    AVALIAÇÕES - VISUAL
    ====================================== */
    $wp_customize->add_section('reviews_visual_section', array(
        'title'    => 'Avaliações - Visual',
        'priority' => 45,
    ));

    $wp_customize->add_setting('google_reviews_shortcode', array(
        'default'           => '[wp-review-slider]',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('google_reviews_shortcode', array(
        'label'       => 'Shortcode das avaliações',
        'description' => 'Ex.: [wp-review-slider]',
        'section'     => 'reviews_visual_section',
        'type'        => 'text',
    ));

    $wp_customize->add_setting('reviews_section_bg', array(
        'default'           => '#f8fafc',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'reviews_section_bg',
        array(
            'label'   => 'Fundo da seção de avaliações',
            'section' => 'reviews_visual_section',
        )
    ));

    $wp_customize->add_setting('reviews_card_bg', array(
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'reviews_card_bg',
        array(
            'label'   => 'Fundo dos cards',
            'section' => 'reviews_visual_section',
        )
    ));

    $wp_customize->add_setting('reviews_card_border_color', array(
        'default'           => '#e5e7eb',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'reviews_card_border_color',
        array(
            'label'   => 'Cor da borda dos cards',
            'section' => 'reviews_visual_section',
        )
    ));

    $wp_customize->add_setting('reviews_card_shadow_color', array(
        'default'           => 'rgba(0,0,0,0.05)',
        'sanitize_callback' => 'despachante_sanitize_text_or_rgba',
    ));

    $wp_customize->add_control('reviews_card_shadow_color', array(
        'label'       => 'Cor da sombra dos cards',
        'description' => 'Aceita HEX ou RGBA. Ex.: rgba(0,0,0,0.05)',
        'section'     => 'reviews_visual_section',
        'type'        => 'text',
    ));

    $wp_customize->add_setting('reviews_card_radius', array(
        'default'           => 16,
        'sanitize_callback' => 'despachante_sanitize_absint',
    ));

    $wp_customize->add_control('reviews_card_radius', array(
        'label'       => 'Borda arredondada dos cards (px)',
        'section'     => 'reviews_visual_section',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 60,
            'step' => 1,
        ),
    ));

    $wp_customize->add_setting('reviews_card_padding', array(
        'default'           => 25,
        'sanitize_callback' => 'despachante_sanitize_absint',
    ));

    $wp_customize->add_control('reviews_card_padding', array(
        'label'       => 'Padding dos cards (px)',
        'section'     => 'reviews_visual_section',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 80,
            'step' => 1,
        ),
    ));

    /* ======================================
    RODAPÉ - GERAL
    ====================================== */
    $wp_customize->add_section('footer_section', array(
        'title'    => 'Rodapé',
        'priority' => 110,
    ));

    $wp_customize->add_setting('footer_cta_title', array(
        'default'           => 'Interessado em ter um sistema assim para sua empresa?',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('footer_cta_title', array(
        'label'   => 'Título do CTA do rodapé',
        'section' => 'footer_section',
        'type'    => 'text',
    ));

    $wp_customize->add_setting('footer_cta_subtitle', array(
        'default'           => 'Esta é uma Landing Page de demonstração. Modernize seu atendimento local hoje mesmo.',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('footer_cta_subtitle', array(
        'label'   => 'Subtítulo do CTA do rodapé',
        'section' => 'footer_section',
        'type'    => 'text',
    ));

    $wp_customize->add_setting('footer_cta_text', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('footer_cta_text', array(
        'label'       => 'Texto legado do CTA',
        'description' => 'Compatibilidade com versões antigas do tema.',
        'section'     => 'footer_section',
        'type'        => 'text',
    ));

    $wp_customize->add_setting('footer_copy', array(
        'default'           => 'Todos os direitos reservados.',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('footer_copy', array(
        'label'   => 'Texto de copyright',
        'section' => 'footer_section',
        'type'    => 'text',
    ));

    $wp_customize->add_setting('footer_copyright', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('footer_copyright', array(
        'label'       => 'Copyright legado',
        'description' => 'Compatibilidade com versões antigas do tema.',
        'section'     => 'footer_section',
        'type'        => 'text',
    ));

    $wp_customize->add_setting('footer_bg_color', array(
        'default'           => '#1d2d3d',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'footer_bg_color',
        array(
            'label'   => 'Cor de fundo do rodapé',
            'section' => 'footer_section',
        )
    ));

    /* ======================================
    RODAPÉ - CONTATO
    ====================================== */
    $wp_customize->add_section('footer_contact_section', array(
        'title'    => 'Rodapé - Contato',
        'priority' => 111,
    ));

    $wp_customize->add_setting('footer_zipcode', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('footer_zipcode', array(
        'label'       => 'CEP',
        'description' => 'Informe só números ou no formato 00000-000. O restante será sugerido automaticamente.',
        'section'     => 'footer_contact_section',
        'type'        => 'text',
    ));

    $wp_customize->add_setting('footer_cep', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('footer_cep', array(
        'label'       => 'CEP legado',
        'description' => 'Compatibilidade com versões antigas do tema.',
        'section'     => 'footer_contact_section',
        'type'        => 'text',
    ));

    $wp_customize->add_setting('footer_address', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('footer_address', array(
        'label'       => 'Logradouro + número + bairro',
        'description' => 'Ex.: Rua Sara Newton, 103 - Jd. Boa Vista',
        'section'     => 'footer_contact_section',
        'type'        => 'text',
    ));

    $wp_customize->add_setting('footer_city_state', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('footer_city_state', array(
        'label'       => 'Cidade / Estado',
        'description' => 'Ex.: São Paulo - SP',
        'section'     => 'footer_contact_section',
        'type'        => 'text',
    ));

    $wp_customize->add_setting('footer_phone', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('footer_phone', array(
        'label'   => 'Telefone',
        'section' => 'footer_contact_section',
        'type'    => 'text',
    ));

    $wp_customize->add_setting('footer_whatsapp', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('footer_whatsapp', array(
        'label'   => 'WhatsApp',
        'section' => 'footer_contact_section',
        'type'    => 'text',
    ));

    $wp_customize->add_setting('footer_email', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_email',
    ));

    $wp_customize->add_control('footer_email', array(
        'label'   => 'E-mail',
        'section' => 'footer_contact_section',
        'type'    => 'email',
    ));

    $wp_customize->add_setting('footer_hours', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('footer_hours', array(
        'label'   => 'Horário de atendimento',
        'section' => 'footer_contact_section',
        'type'    => 'text',
    ));

    /* ======================================
    RODAPÉ - REDES SOCIAIS
    ====================================== */
    $wp_customize->add_section('footer_social_section', array(
        'title'    => 'Rodapé - Redes sociais',
        'priority' => 112,
    ));

    $wp_customize->add_setting('social_instagram', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('social_instagram', array(
        'label'   => 'Instagram',
        'section' => 'footer_social_section',
        'type'    => 'url',
    ));

    $wp_customize->add_setting('social_facebook', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('social_facebook', array(
        'label'   => 'Facebook',
        'section' => 'footer_social_section',
        'type'    => 'url',
    ));

    $wp_customize->add_setting('social_linkedin', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('social_linkedin', array(
        'label'   => 'LinkedIn',
        'section' => 'footer_social_section',
        'type'    => 'url',
    ));

    $wp_customize->add_setting('social_x', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('social_x', array(
        'label'   => 'X / Twitter',
        'section' => 'footer_social_section',
        'type'    => 'url',
    ));

    $wp_customize->add_setting('social_style', array(
        'default'           => 'circle',
        'sanitize_callback' => 'despachante_sanitize_select',
    ));

    $wp_customize->add_control('social_style', array(
        'label'   => 'Formato dos ícones sociais',
        'section' => 'footer_social_section',
        'type'    => 'select',
        'choices' => array(
            'icon'           => 'Somente ícone',
            'circle'         => 'Círculo',
            'rounded-square' => 'Quadrado com cantos arredondados',
            'square'         => 'Quadrado reto',
        ),
    ));

    $wp_customize->add_setting('social_icon_style', array(
        'default'           => 'circle',
        'sanitize_callback' => 'despachante_sanitize_select',
    ));

    $wp_customize->add_control('social_icon_style', array(
        'label'       => 'Formato legado dos ícones',
        'description' => 'Compatibilidade com versões antigas do tema.',
        'section'     => 'footer_social_section',
        'type'        => 'select',
        'choices'     => array(
            'icon'           => 'Somente ícone',
            'circle'         => 'Círculo',
            'rounded-square' => 'Quadrado com cantos arredondados',
            'square'         => 'Quadrado reto',
        ),
    ));

    $wp_customize->add_setting('social_icon_color', array(
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'social_icon_color',
        array(
            'label'   => 'Cor do ícone',
            'section' => 'footer_social_section',
        )
    ));

    $wp_customize->add_setting('social_icon_hover_color', array(
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'social_icon_hover_color',
        array(
            'label'   => 'Cor do ícone no hover',
            'section' => 'footer_social_section',
        )
    ));

    $wp_customize->add_setting('social_bg_color', array(
        'default'           => 'rgba(255,255,255,0.08)',
        'sanitize_callback' => 'despachante_sanitize_text_or_rgba',
    ));

    $wp_customize->add_control('social_bg_color', array(
        'label'       => 'Fundo do ícone',
        'description' => 'Aceita HEX ou RGBA. Ex.: rgba(255,255,255,0.08)',
        'section'     => 'footer_social_section',
        'type'        => 'text',
    ));

    $wp_customize->add_setting('social_bg_hover_color', array(
        'default'           => 'rgba(255,255,255,0.16)',
        'sanitize_callback' => 'despachante_sanitize_text_or_rgba',
    ));

    $wp_customize->add_control('social_bg_hover_color', array(
        'label'       => 'Fundo do ícone no hover',
        'description' => 'Aceita HEX ou RGBA.',
        'section'     => 'footer_social_section',
        'type'        => 'text',
    ));

    $wp_customize->add_setting('social_border_color', array(
        'default'           => 'rgba(255,255,255,0.10)',
        'sanitize_callback' => 'despachante_sanitize_text_or_rgba',
    ));

    $wp_customize->add_control('social_border_color', array(
        'label'       => 'Cor da borda',
        'description' => 'Aceita HEX ou RGBA.',
        'section'     => 'footer_social_section',
        'type'        => 'text',
    ));

    $wp_customize->add_setting('social_border_hover_color', array(
        'default'           => 'rgba(255,255,255,0.22)',
        'sanitize_callback' => 'despachante_sanitize_text_or_rgba',
    ));

    $wp_customize->add_control('social_border_hover_color', array(
        'label'       => 'Cor da borda no hover',
        'description' => 'Aceita HEX ou RGBA.',
        'section'     => 'footer_social_section',
        'type'        => 'text',
    ));

    $wp_customize->add_setting('social_icon_size', array(
        'default'           => 18,
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('social_icon_size', array(
        'label'       => 'Tamanho do ícone (px)',
        'section'     => 'footer_social_section',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 12,
            'max'  => 40,
            'step' => 1,
        ),
    ));

    $wp_customize->add_setting('social_box_size', array(
        'default'           => 46,
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('social_box_size', array(
        'label'       => 'Tamanho da caixa (px)',
        'section'     => 'footer_social_section',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 28,
            'max'  => 80,
            'step' => 1,
        ),
    ));

    $wp_customize->add_setting('social_gap', array(
        'default'           => 12,
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('social_gap', array(
        'label'       => 'Espaçamento entre ícones (px)',
        'section'     => 'footer_social_section',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 40,
            'step' => 1,
        ),
    ));

    $wp_customize->add_setting('social_border_radius', array(
        'default'           => 12,
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('social_border_radius', array(
        'label'       => 'Raio do quadrado arredondado (px)',
        'section'     => 'footer_social_section',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 40,
            'step' => 1,
        ),
    ));

    /* ======================================
    RODAPÉ - WHATSAPP CTA
    ====================================== */
    $wp_customize->add_section('footer_dev_whatsapp_section', array(
        'title'    => 'Rodapé - Botão do CTA',
        'priority' => 113,
    ));

    $wp_customize->add_setting('dev_whatsapp_enabled', array(
        'default'           => true,
        'sanitize_callback' => 'despachante_sanitize_checkbox',
    ));

    $wp_customize->add_control('dev_whatsapp_enabled', array(
        'label'   => 'Ativar botão do CTA',
        'section' => 'footer_dev_whatsapp_section',
        'type'    => 'checkbox',
    ));

    $wp_customize->add_setting('show_developer_whatsapp', array(
        'default'           => true,
        'sanitize_callback' => 'despachante_sanitize_checkbox',
    ));

    $wp_customize->add_control('show_developer_whatsapp', array(
        'label'       => 'Ativar botão do CTA legado',
        'description' => 'Compatibilidade com versões antigas do tema.',
        'section'     => 'footer_dev_whatsapp_section',
        'type'        => 'checkbox',
    ));

    $wp_customize->add_setting('whatsapp_number', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('whatsapp_number', array(
        'label'       => 'Número do WhatsApp do CTA',
        'description' => 'Use só números, ex.: 5511986240526',
        'section'     => 'footer_dev_whatsapp_section',
        'type'        => 'text',
    ));

    $wp_customize->add_setting('developer_whatsapp_number', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('developer_whatsapp_number', array(
        'label'       => 'Número legado do CTA',
        'description' => 'Compatibilidade com versões antigas do tema.',
        'section'     => 'footer_dev_whatsapp_section',
        'type'        => 'text',
    ));

    $wp_customize->add_setting('whatsapp_message', array(
        'default'           => 'Olá! Gostaria de falar com o desenvolvedor.',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('whatsapp_message', array(
        'label'   => 'Mensagem do CTA',
        'section' => 'footer_dev_whatsapp_section',
        'type'    => 'text',
    ));

    $wp_customize->add_setting('developer_whatsapp_message', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('developer_whatsapp_message', array(
        'label'       => 'Mensagem legado do CTA',
        'description' => 'Compatibilidade com versões antigas do tema.',
        'section'     => 'footer_dev_whatsapp_section',
        'type'        => 'text',
    ));

    $wp_customize->add_setting('developer_whatsapp_text', array(
        'default'           => 'Falar com o Desenvolvedor',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('developer_whatsapp_text', array(
        'label'   => 'Texto do botão do CTA',
        'section' => 'footer_dev_whatsapp_section',
        'type'    => 'text',
    ));

    /* ======================================
    WHATSAPP FLUTUANTE - ESCRITÓRIO
    ====================================== */
    $wp_customize->add_section('floating_whatsapp_section', array(
        'title'    => 'WhatsApp flutuante',
        'priority' => 114,
    ));

    $wp_customize->add_setting('office_whatsapp_enabled', array(
        'default'           => true,
        'sanitize_callback' => 'despachante_sanitize_checkbox',
    ));

    $wp_customize->add_control('office_whatsapp_enabled', array(
        'label'   => 'Ativar WhatsApp flutuante',
        'section' => 'floating_whatsapp_section',
        'type'    => 'checkbox',
    ));

    $wp_customize->add_setting('show_floating_whatsapp', array(
        'default'           => true,
        'sanitize_callback' => 'despachante_sanitize_checkbox',
    ));

    $wp_customize->add_control('show_floating_whatsapp', array(
        'label'       => 'Ativar WhatsApp flutuante legado',
        'description' => 'Compatibilidade com versões antigas do tema.',
        'section'     => 'floating_whatsapp_section',
        'type'        => 'checkbox',
    ));

    $wp_customize->add_setting('office_whatsapp_number', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('office_whatsapp_number', array(
        'label'       => 'Número do WhatsApp flutuante',
        'description' => 'Use só números, ex.: 5511986240526',
        'section'     => 'floating_whatsapp_section',
        'type'        => 'text',
    ));

    $wp_customize->add_setting('floating_whatsapp_number', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('floating_whatsapp_number', array(
        'label'       => 'Número legado do WhatsApp flutuante',
        'description' => 'Compatibilidade com versões antigas do tema.',
        'section'     => 'floating_whatsapp_section',
        'type'        => 'text',
    ));

    $wp_customize->add_setting('office_whatsapp_message', array(
        'default'           => 'Olá! Gostaria de falar com o escritório.',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('office_whatsapp_message', array(
        'label'   => 'Mensagem do WhatsApp flutuante',
        'section' => 'floating_whatsapp_section',
        'type'    => 'text',
    ));

    $wp_customize->add_setting('floating_whatsapp_message', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('floating_whatsapp_message', array(
        'label'       => 'Mensagem legado do WhatsApp flutuante',
        'description' => 'Compatibilidade com versões antigas do tema.',
        'section'     => 'floating_whatsapp_section',
        'type'        => 'text',
    ));

    $wp_customize->add_setting('office_whatsapp_text', array(
        'default'           => 'Fale com o escritório',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('office_whatsapp_text', array(
        'label'   => 'Texto do botão flutuante',
        'section' => 'floating_whatsapp_section',
        'type'    => 'text',
    ));

    /* ======================================
    FORMAS DE PAGAMENTO
    ====================================== */
    $wp_customize->add_section('payment_section', array(
        'title'    => 'Formas de pagamento',
        'priority' => 115,
    ));

    $wp_customize->add_setting('payment_show_section', array(
        'default'           => false,
        'sanitize_callback' => 'despachante_sanitize_checkbox',
    ));

    $wp_customize->add_control('payment_show_section', array(
        'label'   => 'Exibir seção de pagamento no rodapé',
        'section' => 'payment_section',
        'type'    => 'checkbox',
    ));

    $wp_customize->add_setting('payment_section_title', array(
        'default'           => 'Formas de pagamento',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('payment_section_title', array(
        'label'   => 'Título da seção',
        'section' => 'payment_section',
        'type'    => 'text',
    ));

    $wp_customize->add_setting('payment_support_text', array(
        'default'           => 'Aceitamos os meios de pagamento abaixo.',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('payment_support_text', array(
        'label'   => 'Texto de apoio',
        'section' => 'payment_section',
        'type'    => 'text',
    ));

    $payment_checks = array(
        'payment_show_pix'                 => 'Exibir PIX',
        'payment_show_credit_card'         => 'Exibir cartão de crédito',
        'payment_show_visa'                => 'Exibir Visa',
        'payment_show_mastercard'          => 'Exibir Mastercard',
        'payment_show_mercado_pago'        => 'Exibir Mercado Pago',
        'payment_use_generic_card_icon'    => 'Usar ícone genérico de cartão',
        'payment_enable_installments_text' => 'Exibir texto de parcelamento',
    );

    foreach ($payment_checks as $setting_id => $label) {
        $wp_customize->add_setting($setting_id, array(
            'default'           => false,
            'sanitize_callback' => 'despachante_sanitize_checkbox',
        ));

        $wp_customize->add_control($setting_id, array(
            'label'   => $label,
            'section' => 'payment_section',
            'type'    => 'checkbox',
        ));
    }

    $wp_customize->add_setting('payment_installments_text', array(
        'default'           => 'Consulte condições.',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('payment_installments_text', array(
        'label'   => 'Texto de parcelamento',
        'section' => 'payment_section',
        'type'    => 'text',
    ));

    /* ======================================
    E-MAILS DO SISTEMA
    ====================================== */
    $wp_customize->add_section('email_section', array(
        'title'    => 'E-mails do Sistema',
        'priority' => 120,
    ));

    $wp_customize->add_setting('email_logo', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control(
        $wp_customize,
        'email_logo',
        array(
            'label'       => 'Logo dos e-mails',
            'section'     => 'email_section',
            'description' => 'Essa imagem aparecerá no topo dos e-mails enviados pelo sistema.',
        )
    ));

    $wp_customize->add_setting('email_company_name', array(
        'default'           => get_bloginfo('name'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('email_company_name', array(
        'label'   => 'Nome da empresa nos e-mails',
        'section' => 'email_section',
        'type'    => 'text',
    ));

    $wp_customize->add_setting('email_primary_color', array(
        'default'           => '#1d2d3d',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'email_primary_color',
        array(
            'label'   => 'Cor principal dos e-mails',
            'section' => 'email_section',
        )
    ));

    $wp_customize->add_setting('email_footer_text', array(
        'default'           => 'Mensagem automática enviada pelo sistema de atendimento.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));

    $wp_customize->add_control('email_footer_text', array(
        'label'   => 'Rodapé dos e-mails',
        'section' => 'email_section',
        'type'    => 'textarea',
    ));
}

add_action('customize_register', 'despachante_customize_register');