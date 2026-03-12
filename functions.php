<?php
/**
 * Tema: Despachante Digital Flow
 * Versão: 3.3.7
 */

if (!defined('ABSPATH')) {
    exit;
}

/*
|--------------------------------------------------------------------------
| Caminho base do tema
|--------------------------------------------------------------------------
*/

define('DESPACHANTE_THEME_DIR', get_template_directory());

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