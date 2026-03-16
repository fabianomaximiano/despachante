<?php
/**
 * Tema: Despachante Digital Flow
 * Versão: 3.4.0 (Otimizada para Performance & Acessibilidade)
 */

if (!defined('ABSPATH')) {
    exit;
}

/*
|--------------------------------------------------------------------------
| Caminho base do tema e Requisitos
|--------------------------------------------------------------------------
*/
if (!defined('DESPACHANTE_THEME_DIR')) {
    define('DESPACHANTE_THEME_DIR', get_template_directory());
}

// Carregamento de módulos originais do tema
require_once DESPACHANTE_THEME_DIR . '/inc/helpers.php';
require_once DESPACHANTE_THEME_DIR . '/inc/setup.php';
require_once DESPACHANTE_THEME_DIR . '/inc/database.php';
require_once DESPACHANTE_THEME_DIR . '/inc/customizer.php';
require_once DESPACHANTE_THEME_DIR . '/inc/cpts.php';
require_once DESPACHANTE_THEME_DIR . '/inc/metaboxes.php';
require_once DESPACHANTE_THEME_DIR . '/inc/uploads.php';
require_once DESPACHANTE_THEME_DIR . '/inc/email.php';
require_once DESPACHANTE_THEME_DIR . '/inc/form-handler.php';
require_once DESPACHANTE_THEME_DIR . '/inc/admin-leads.php';
require_once DESPACHANTE_THEME_DIR . '/inc/dynamic-css.php';
require_once DESPACHANTE_THEME_DIR . '/inc/lgpd.php';
require_once DESPACHANTE_THEME_DIR . '/inc/dashboard.php';
require_once DESPACHANTE_THEME_DIR . '/inc/admin-ui.php';

/*
|--------------------------------------------------------------------------
| OTIMIZAÇÕES DE PERFORMANCE (PAGESPEED)
|--------------------------------------------------------------------------
*/

/**
 * Limpeza de scripts nativos desnecessários
 */
add_action('init', function() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_generator');
});

/**
 * Carregamento Assíncrono para FontAwesome (Não bloqueia a renderização)
 */
add_filter('style_loader_tag', function($tag, $handle) {
    if ($handle === 'font-awesome') {
        return str_replace("rel='stylesheet'", "rel='preload' as='style' onload=\"this.onload=null;this.rel='stylesheet'\"", $tag);
    }
    return $tag;
}, 10, 2);

/**
 * Adiciona DEFER aos scripts para melhorar o LCP e TBT
 */
add_filter('script_loader_tag', function($tag, $handle) {
    $scripts_to_defer = array('bootstrap-js', 'handle-pre-analise', 'despachante-lgpd');
    foreach ($scripts_to_defer as $defer_script) {
        if (strpos($handle, $defer_script) !== false) {
            return str_replace(' src', ' defer src', $tag);
        }
    }
    return $tag;
}, 10, 2);

/*
|--------------------------------------------------------------------------
| MELHORIAS DE ACESSIBILIDADE (A11Y)
|--------------------------------------------------------------------------
*/

/**
 * Garante que imagens do conteúdo tenham o atributo alt, mesmo que vazio
 */
add_filter('the_content', function($content) {
    return preg_replace_callback('/<img(?!.*?alt=(["\']丸).*?\/?>)/i', function($m) {
        return str_replace('<img', '<img alt="Imagem do serviço despachante"', $m[0]);
    }, $content);
});

/**
 * Adiciona preconnect para domínios externos no head
 */
add_action('wp_head', function() {
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
    echo '<link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>' . "\n";
}, 1);