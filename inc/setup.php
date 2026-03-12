<?php
if (!defined('ABSPATH')) {
    exit;
}

/* ======================================
SETUP DO TEMA
====================================== */

function despachante_digital_scripts() {

    /* Font Awesome */
    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css',
        array(),
        '5.15.4'
    );

    /* Bootstrap */
    wp_enqueue_style(
        'bootstrap-css',
        'https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css',
        array(),
        '4.6.2'
    );

    /* Estilo principal */
    wp_enqueue_style(
        'main-style',
        get_template_directory_uri() . '/assets/css/estyle.css',
        array('bootstrap-css'),
        '3.3.8'
    );

    /* jQuery */
    wp_enqueue_script('jquery');

    /* Bootstrap JS */
    wp_enqueue_script(
        'bootstrap-js',
        'https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js',
        array('jquery'),
        '4.6.2',
        true
    );

    /* ======================================
    SCRIPT DO FORMULÁRIO
    ====================================== */

    if (file_exists(get_template_directory() . '/assets/js/handlePreAnalise.js')) {

        wp_enqueue_script(
            'handle-pre-analise',
            get_template_directory_uri() . '/assets/js/handlePreAnalise.js',
            array('jquery'),
            '3.3.8',
            true
        );

        wp_localize_script(
            'handle-pre-analise',
            'wp_ajax_obj',
            array(
                'ajax_url'      => admin_url('admin-ajax.php'),
                'nonce'         => wp_create_nonce('despachante_pre_analise_nonce'),
                'max_upload'    => wp_max_upload_size(),
                'service_docs'  => function_exists('despachante_get_service_documents_map')
                    ? despachante_get_service_documents_map()
                    : array(),
                'messages'      => array(
                    'select_service' => 'Selecione um serviço para exibir os documentos necessários.',
                    'no_docs'        => 'Este serviço não possui checklist configurado no momento.',
                    'sending'        => 'Enviando sua solicitação...',
                ),
            )
        );
    }

    /* ======================================
    SCRIPT LGPD
    ====================================== */

    if (file_exists(get_template_directory() . '/assets/js/lgpd.js')) {

        wp_enqueue_script(
            'despachante-lgpd',
            get_template_directory_uri() . '/assets/js/lgpd.js',
            array(),
            '3.3.8',
            true
        );

    }

}

add_action('wp_enqueue_scripts', 'despachante_digital_scripts');


/* ======================================
SUPORTE DO TEMA
====================================== */

function despachante_theme_support() {

    add_theme_support('title-tag');

    add_theme_support('post-thumbnails');

}

add_action('after_setup_theme', 'despachante_theme_support');