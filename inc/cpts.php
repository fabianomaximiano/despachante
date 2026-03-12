<?php
if (!defined('ABSPATH')) {
    exit;
}

/* ======================================
CUSTOM POST TYPES
====================================== */

function registrar_cpts_despachante() {

    register_post_type('servicos', array(
        'labels' => array(
            'name'               => 'Serviços',
            'singular_name'      => 'Serviço',
            'add_new'            => 'Adicionar novo',
            'add_new_item'       => 'Adicionar novo serviço',
            'edit_item'          => 'Editar serviço',
            'new_item'           => 'Novo serviço',
            'view_item'          => 'Ver serviço',
            'search_items'       => 'Buscar serviços',
            'not_found'          => 'Nenhum serviço encontrado',
            'not_found_in_trash' => 'Nenhum serviço encontrado na lixeira',
            'menu_name'          => 'Serviços',
        ),
        'public'             => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_icon'          => 'dashicons-portfolio',
        'supports'           => array('title', 'editor', 'thumbnail'),
        'show_in_rest'       => true,
        'has_archive'        => false,
        'publicly_queryable' => true,
        'rewrite'            => array('slug' => 'servicos'),
    ));

    register_post_type('faq', array(
        'labels' => array(
            'name'               => 'FAQ',
            'singular_name'      => 'Pergunta',
            'add_new'            => 'Adicionar nova',
            'add_new_item'       => 'Adicionar nova pergunta',
            'edit_item'          => 'Editar pergunta',
            'new_item'           => 'Nova pergunta',
            'view_item'          => 'Ver pergunta',
            'search_items'       => 'Buscar perguntas',
            'not_found'          => 'Nenhuma pergunta encontrada',
            'not_found_in_trash' => 'Nenhuma pergunta encontrada na lixeira',
            'menu_name'          => 'FAQ',
        ),
        'public'             => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_icon'          => 'dashicons-editor-help',
        'supports'           => array('title', 'editor'),
        'show_in_rest'       => true,
        'has_archive'        => false,
        'publicly_queryable' => true,
        'rewrite'            => array('slug' => 'faq'),
    ));
}

add_action('init', 'registrar_cpts_despachante');