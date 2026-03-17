<?php
if (!defined('ABSPATH')) {
    exit;
}

/* ======================================
SETUP DO TEMA
====================================== */

/**
 * Enfileira apenas o que ainda não é tratado no functions.php
 *
 * IMPORTANTE:
 * - Bootstrap, estyle.css, handlePreAnalise.js e lgpd.js
 *   já são carregados no functions.php
 * - aqui mantemos apenas Font Awesome
 * - e localizamos o script do formulário já enfileirado
 *   para evitar o envio duplicado via AJAX
 */
function despachante_digital_scripts() {

    /* Font Awesome */
    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css',
        array(),
        '5.15.4'
    );
}
add_action('wp_enqueue_scripts', 'despachante_digital_scripts', 5);

/**
 * Localiza os dados do AJAX no mesmo handle já registrado no functions.php
 * Isso evita carregar handlePreAnalise.js duas vezes.
 */
function despachante_localize_pre_analise_script() {

    $script_handle = 'pre-analise-script';

    if (!wp_script_is($script_handle, 'enqueued')) {
        return;
    }

    wp_localize_script(
        $script_handle,
        'wp_ajax_obj',
        array(
            'ajax_url'     => admin_url('admin-ajax.php'),
            'nonce'        => wp_create_nonce('despachante_pre_analise_nonce'),
            'max_upload'   => wp_max_upload_size(),
            'service_docs' => function_exists('despachante_get_service_documents_map')
                ? despachante_get_service_documents_map()
                : array(),
            'messages'     => array(
                'select_service' => 'Selecione um serviço para exibir os documentos necessários.',
                'no_docs'        => 'Este serviço não possui checklist configurado no momento.',
                'sending'        => 'Enviando sua solicitação...',
            ),
        )
    );
}
add_action('wp_enqueue_scripts', 'despachante_localize_pre_analise_script', 20);

/* ======================================
SUPORTE DO TEMA
====================================== */

function despachante_theme_support() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');

    register_nav_menus(array(
        'primary' => 'Menu principal',
    ));
}
add_action('after_setup_theme', 'despachante_theme_support');

/* ======================================
CLASSES DO MENU PARA BOOTSTRAP
====================================== */

if (!function_exists('despachante_nav_menu_add_li_classes')) {
    function despachante_nav_menu_add_li_classes($classes, $item, $args) {
        if (isset($args->theme_location) && $args->theme_location === 'primary') {
            $classes[] = 'nav-item';
        }

        return $classes;
    }
}
add_filter('nav_menu_css_class', 'despachante_nav_menu_add_li_classes', 10, 3);

if (!function_exists('despachante_nav_menu_add_link_classes')) {
    function despachante_nav_menu_add_link_classes($atts, $item, $args) {
        if (isset($args->theme_location) && $args->theme_location === 'primary') {
            $existing = isset($atts['class']) ? $atts['class'] . ' ' : '';
            $atts['class'] = trim($existing . 'nav-link');
        }

        return $atts;
    }
}
add_filter('nav_menu_link_attributes', 'despachante_nav_menu_add_link_classes', 10, 3);

/* ======================================
FALLBACK DO MENU PRINCIPAL
====================================== */

if (!function_exists('despachante_primary_menu_fallback')) {
    function despachante_primary_menu_fallback() {
        echo '<ul class="navbar-nav ml-auto">';
        echo '<li class="nav-item"><a class="nav-link" href="#inicio">Início</a></li>';
        echo '<li class="nav-item"><a class="nav-link" href="#fluxo">Como Funciona</a></li>';
        echo '<li class="nav-item"><a class="nav-link" href="#servicos">Serviços</a></li>';
        echo '<li class="nav-item"><a class="nav-link" href="#contato">Contato</a></li>';
        echo '</ul>';
    }
}