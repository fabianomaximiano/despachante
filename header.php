<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php
    $seo_description = despachante_get_theme_mod_compat(
        'seo_meta_description',
        array(),
        'Sistema para Despachantes - Demonstração da plataforma de gestão e automação para regularização de veículos desenvolvida por Fabiano Maximiano.'
    );
    ?>

    <meta name="description" content="<?php echo esc_attr($seo_description); ?>">
    <link rel="canonical" href="<?php echo esc_url(home_url('/')); ?>">

    <meta property="og:title" content="<?php bloginfo('name'); ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo esc_url(home_url('/')); ?>">
    <meta property="og:description" content="<?php echo esc_attr($seo_description); ?>">

    <?php if (has_site_icon()) : ?>
        <meta property="og:image" content="<?php echo esc_url(get_site_icon_url(1200, 630)); ?>">
    <?php endif; ?>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <div id="root">
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3">
            <div class="container">
                <a class="navbar-brand font-weight-bold" href="<?php echo esc_url(home_url('/')); ?>">
                    <?php bloginfo('name'); ?>
                </a>

                <button
                    class="navbar-toggler"
                    type="button"
                    data-toggle="collapse"
                    data-target="#navbarNav"
                    aria-controls="navbarNav"
                    aria-expanded="false"
                    aria-label="Abrir menu de navegação"
                >
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'container'      => false,
                        'menu_class'     => 'navbar-nav ml-auto',
                        'fallback_cb'    => 'despachante_primary_menu_fallback',
                    ));
                    ?>
                </div>
            </div>
        </nav>