<?php
/**
 * Tema: Despachante Digital Flow
 * Versão: 3.4.0 (Otimizada para Performance e Acessibilidade)
 */

if (!defined('ABSPATH')) {
    exit;
}

/*
|--------------------------------------------------------------------------
| Caminho base do tema
|--------------------------------------------------------------------------
*/
if (!defined('DESPACHANTE_THEME_DIR')) {
    define('DESPACHANTE_THEME_DIR', get_template_directory());
}

/*
|--------------------------------------------------------------------------
| Helpers de compatibilidade para Theme Mods
|--------------------------------------------------------------------------
*/
if (!function_exists('despachante_get_theme_mod_compat')) {
    function despachante_get_theme_mod_compat($primary, $fallbacks = array(), $default = '') {
        $keys = array_merge(array($primary), (array) $fallbacks);
        foreach ($keys as $key) {
            $value = get_theme_mod($key, null);
            if ($value !== null && $value !== '') {
                return $value;
            }
        }
        return $default;
    }
}

if (!function_exists('despachante_get_theme_mod_bool_compat')) {
    function despachante_get_theme_mod_bool_compat($primary, $fallbacks = array(), $default = false) {
        $keys = array_merge(array($primary), (array) $fallbacks);
        foreach ($keys as $key) {
            $value = get_theme_mod($key, null);
            if ($value !== null && $value !== '') {
                return rest_sanitize_boolean($value);
            }
        }
        return (bool) $default;
    }
}

/*
|--------------------------------------------------------------------------
| Carregar módulos do tema
|--------------------------------------------------------------------------
*/
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
| OTIMIZAÇÕES DE PERFORMANCE E ACESSIBILIDADE
|--------------------------------------------------------------------------
*/

/**
 * Limpeza de scripts nativos desnecessários para reduzir requisições
 */
add_action('init', function() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_generator');
});

/**
 * Melhora a acessibilidade garantindo que imagens tenham o atributo alt
 */
add_filter('the_content', function($content) {
    return preg_replace_callback('/<img(?!.*?alt=(["\']丸).*?\/?>)/i', function($m) {
        return str_replace('<img', '<img alt="Serviço de Despachante"', $m[0]);
    }, $content);
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
 * Adiciona preconnect para domínios de ativos externos
 */
add_action('wp_head', function() {
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
    echo '<link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>' . "\n";
    echo '<link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>' . "\n";
}, 1);