<?php
if (!defined('ABSPATH')) {
    exit;
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

    $reviews_section_bg   = get_theme_mod('reviews_section_bg', '#f8fafc');
    $reviews_card_radius  = absint(get_theme_mod('reviews_card_radius', 16));
    $reviews_card_border  = get_theme_mod('reviews_card_border_color', '#e5e7eb');
    $reviews_card_shadow  = get_theme_mod('reviews_card_shadow_color', 'rgba(0,0,0,0.05)');
    $reviews_card_padding = absint(get_theme_mod('reviews_card_padding', 25));
    $reviews_card_bg      = get_theme_mod('reviews_card_bg', '#ffffff');

    echo '<style id="despachante-dynamic-css">';

    /* Rodapé */
    echo '.footer-cta{background-color:' . esc_attr($footer_bg) . ' !important;}';

    /* Variáveis CSS do tema */
    echo ':root{
        --services-icon-bg:' . esc_attr($services_icon_bg) . ';
        --services-icon-border:' . esc_attr($services_icon_border) . ';
        --services-icon-color:' . esc_attr($services_icon_color) . ';
        --services-card-border:' . esc_attr($services_card_border_color) . ';
    }';

    /* Avaliações Google */
    echo '.reviews-section{
        background:' . esc_attr($reviews_section_bg) . ';
    }';

    echo '.reviews-slider-wrapper .wp-google-review-slider,
          .reviews-slider-wrapper .wp-google-review{
        background:' . esc_attr($reviews_card_bg) . ';
        border:1px solid ' . esc_attr($reviews_card_border) . ';
        border-radius:' . $reviews_card_radius . 'px;
        padding:' . $reviews_card_padding . 'px;
        box-shadow:0 6px 18px ' . esc_attr($reviews_card_shadow) . ';
    }';

    echo '</style>';
}

add_action('wp_head', 'despachante_dynamic_css');