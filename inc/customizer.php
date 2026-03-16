<?php
if (!defined('ABSPATH')) {
    exit;
}

/* ======================================
CUSTOMIZER
====================================== */

function despachante_customize_register($wp_customize) {

    /* ======================================
    NOVA SEÇÃO: SEO & CABEÇALHO
    ====================================== */
    $wp_customize->add_section('despachante_seo_section', array(
        'title'    => 'SEO e Identidade',
        'priority' => 20
    ));

    // Configuração para Meta Description
    $wp_customize->add_setting('seo_meta_description', array(
        'default'           => 'Sistema para Despachantes - Demonstração da plataforma de gestão e automação para regularização de veículos desenvolvida por Fabiano Maximiano.',
        'sanitize_callback' => 'sanitize_text_field'
    ));

    $wp_customize->add_control('seo_meta_description', array(
        'label'       => 'Descrição do Site (Google)',
        'description' => 'Aparece nos resultados de busca e melhora a acessibilidade.',
        'section'     => 'despachante_seo_section',
        'type'        => 'textarea'
    ));

    /* ======================================
    SEÇÃO: HERO (Existente)
    ====================================== */
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
        'default'           => 'Começar agora',
        'sanitize_callback' => 'sanitize_text_field'
    ));

    $wp_customize->add_control('hero_button_text', array(
        'label'   => 'Texto do Botão Hero',
        'section' => 'hero_section',
        'type'    => 'text'
    ));

    /* ======================================
    SEÇÃO: E-MAILS (Resumo do seu arquivo original)
    ====================================== */
    $wp_customize->add_section('email_section', array(
        'title'    => 'E-mails do Sistema',
        'priority' => 100
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
        'label'   => 'Rodapé dos e-mails',
        'section' => 'email_section',
        'type'    => 'textarea'
    ));
}

add_action('customize_register', 'despachante_customize_register');