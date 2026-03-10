<?php
/**
 * Tema: Despachante Digital Flow
 * Versão: 3.0.3
 */

function despachante_digital_scripts() {

    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css'
    );

    wp_enqueue_style(
        'bootstrap-css',
        'https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css'
    );

    wp_enqueue_style(
        'main-style',
        get_template_directory_uri() . '/assets/css/estyle.css',
        array('bootstrap-css'),
        '3.0.3'
    );

}
add_action('wp_enqueue_scripts', 'despachante_digital_scripts');

add_theme_support('title-tag');
add_theme_support('post-thumbnails');



/* ======================================
CUSTOMIZER
====================================== */

function despachante_customize_register($wp_customize) {

    /* ======================================
    WHATSAPP
    ====================================== */

    $wp_customize->add_section('whatsapp_section', array(
        'title' => 'WhatsApp',
        'priority' => 30
    ));

    /* Botão desenvolvedor */

    $wp_customize->add_setting('dev_whatsapp_enabled', array(
        'default' => true
    ));

    $wp_customize->add_control('dev_whatsapp_enabled', array(
        'label' => 'Exibir botão "Falar com o Desenvolvedor"',
        'section' => 'whatsapp_section',
        'type' => 'checkbox'
    ));



    $wp_customize->add_setting('whatsapp_number');

    $wp_customize->add_control('whatsapp_number', array(
        'label' => 'WhatsApp Desenvolvedor',
        'section' => 'whatsapp_section',
        'type' => 'text'
    ));



    $wp_customize->add_setting('whatsapp_message');

    $wp_customize->add_control('whatsapp_message', array(
        'label' => 'Mensagem Desenvolvedor',
        'section' => 'whatsapp_section',
        'type' => 'text'
    ));



    /* WhatsApp escritório */

    $wp_customize->add_setting('office_whatsapp_enabled', array(
        'default' => true
    ));

    $wp_customize->add_control('office_whatsapp_enabled', array(
        'label' => 'Exibir WhatsApp flutuante do escritório',
        'section' => 'whatsapp_section',
        'type' => 'checkbox'
    ));



    $wp_customize->add_setting('office_whatsapp_number');

    $wp_customize->add_control('office_whatsapp_number', array(
        'label' => 'WhatsApp do Escritório',
        'section' => 'whatsapp_section',
        'type' => 'text'
    ));



    $wp_customize->add_setting('office_whatsapp_message');

    $wp_customize->add_control('office_whatsapp_message', array(
        'label' => 'Mensagem WhatsApp Escritório',
        'section' => 'whatsapp_section',
        'type' => 'text'
    ));



    $wp_customize->add_setting('office_whatsapp_text');

    $wp_customize->add_control('office_whatsapp_text', array(
        'label' => 'Texto botão WhatsApp',
        'section' => 'whatsapp_section',
        'type' => 'text'
    ));




    /* ======================================
    REDES SOCIAIS
    ====================================== */

    $wp_customize->add_section('social_section', array(
        'title' => 'Redes Sociais',
        'priority' => 40
    ));

    $wp_customize->add_setting('social_instagram');
    $wp_customize->add_control('social_instagram', array(
        'label' => 'Instagram',
        'section' => 'social_section',
        'type' => 'url'
    ));

    $wp_customize->add_setting('social_facebook');
    $wp_customize->add_control('social_facebook', array(
        'label' => 'Facebook',
        'section' => 'social_section',
        'type' => 'url'
    ));

    $wp_customize->add_setting('social_linkedin');
    $wp_customize->add_control('social_linkedin', array(
        'label' => 'LinkedIn',
        'section' => 'social_section',
        'type' => 'url'
    ));

    $wp_customize->add_setting('social_x');
    $wp_customize->add_control('social_x', array(
        'label' => 'X',
        'section' => 'social_section',
        'type' => 'url'
    ));



    /* estilo redes */

    $wp_customize->add_setting('social_style', array(
        'default' => 'icon'
    ));

    $wp_customize->add_control('social_style', array(
        'label' => 'Estilo dos ícones',
        'section' => 'social_section',
        'type' => 'select',
        'choices' => array(
            'icon' => 'Somente ícone',
            'circle' => 'Ícone dentro de círculo',
            'square' => 'Ícone dentro de quadrado arredondado'
        )
    ));

}

add_action('customize_register', 'despachante_customize_register');