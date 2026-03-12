<?php
if (!defined('ABSPATH')) {
    exit;
}

/* ======================================
LGPD - CUSTOMIZER
====================================== */

function despachante_customize_register_lgpd($wp_customize) {

    $wp_customize->add_section('lgpd_section', array(
        'title'    => 'LGPD',
        'priority' => 43,
    ));

    $wp_customize->add_setting('lgpd_enabled', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));

    $wp_customize->add_control('lgpd_enabled', array(
        'label'   => 'Exibir banner LGPD',
        'section' => 'lgpd_section',
        'type'    => 'checkbox',
    ));

    $wp_customize->add_setting('lgpd_title', array(
        'default'           => 'Sua privacidade é importante',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('lgpd_title', array(
        'label'   => 'Título do banner',
        'section' => 'lgpd_section',
        'type'    => 'text',
    ));

    $wp_customize->add_setting('lgpd_text', array(
        'default'           => 'Usamos cookies e armazenamos seus dados apenas para atendimento e melhoria da experiência no site. Ao continuar, você concorda com nossa política de privacidade.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));

    $wp_customize->add_control('lgpd_text', array(
        'label'   => 'Texto do banner',
        'section' => 'lgpd_section',
        'type'    => 'textarea',
    ));

    $wp_customize->add_setting('lgpd_accept_text', array(
        'default'           => 'Aceitar',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('lgpd_accept_text', array(
        'label'   => 'Texto do botão aceitar',
        'section' => 'lgpd_section',
        'type'    => 'text',
    ));

    $wp_customize->add_setting('lgpd_policy_text', array(
        'default'           => 'Ver política',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('lgpd_policy_text', array(
        'label'   => 'Texto do link da política',
        'section' => 'lgpd_section',
        'type'    => 'text',
    ));

    $wp_customize->add_setting('lgpd_policy_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('lgpd_policy_url', array(
        'label'       => 'URL da política de privacidade',
        'section'     => 'lgpd_section',
        'type'        => 'url',
        'description' => 'Exemplo: /politica-de-privacidade ou URL completa.',
    ));

    $wp_customize->add_setting('lgpd_position', array(
        'default'           => 'bottom-right',
        'sanitize_callback' => 'despachante_sanitize_lgpd_position',
    ));

    $wp_customize->add_control('lgpd_position', array(
        'label'   => 'Posição do banner',
        'section' => 'lgpd_section',
        'type'    => 'select',
        'choices' => array(
            'bottom-left'   => 'Inferior esquerdo',
            'bottom-center' => 'Inferior central',
            'bottom-right'  => 'Inferior direito',
            'top-center'    => 'Superior central',
        ),
    ));

    $wp_customize->add_setting('lgpd_bg_color', array(
        'default'           => '#1f2937',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'lgpd_bg_color',
        array(
            'label'   => 'Cor de fundo do banner',
            'section' => 'lgpd_section',
        )
    ));

    $wp_customize->add_setting('lgpd_text_color', array(
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'lgpd_text_color',
        array(
            'label'   => 'Cor do texto',
            'section' => 'lgpd_section',
        )
    ));

    $wp_customize->add_setting('lgpd_button_color', array(
        'default'           => '#22c55e',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'lgpd_button_color',
        array(
            'label'   => 'Cor do botão aceitar',
            'section' => 'lgpd_section',
        )
    ));
}
add_action('customize_register', 'despachante_customize_register_lgpd');

/* ======================================
LGPD - SANITIZER
====================================== */

function despachante_sanitize_lgpd_position($value) {
    $valid = array(
        'bottom-left',
        'bottom-center',
        'bottom-right',
        'top-center',
    );

    return in_array($value, $valid, true) ? $value : 'bottom-right';
}

/* ======================================
LGPD - HELPERS
====================================== */

function despachante_lgpd_is_enabled() {
    return (bool) get_theme_mod('lgpd_enabled', true);
}

function despachante_get_lgpd_settings() {
    return array(
        'enabled'      => despachante_lgpd_is_enabled(),
        'title'        => get_theme_mod('lgpd_title', 'Sua privacidade é importante'),
        'text'         => get_theme_mod('lgpd_text', 'Usamos cookies e armazenamos seus dados apenas para atendimento e melhoria da experiência no site. Ao continuar, você concorda com nossa política de privacidade.'),
        'accept_text'  => get_theme_mod('lgpd_accept_text', 'Aceitar'),
        'policy_text'  => get_theme_mod('lgpd_policy_text', 'Ver política'),
        'policy_url'   => get_theme_mod('lgpd_policy_url', ''),
        'position'     => get_theme_mod('lgpd_position', 'bottom-right'),
        'bg_color'     => get_theme_mod('lgpd_bg_color', '#1f2937'),
        'text_color'   => get_theme_mod('lgpd_text_color', '#ffffff'),
        'button_color' => get_theme_mod('lgpd_button_color', '#22c55e'),
    );
}

/* ======================================
LGPD - RENDER FRONTEND
====================================== */

function despachante_render_lgpd_banner() {
    if (is_admin() || !despachante_lgpd_is_enabled()) {
        return;
    }

    $settings = despachante_get_lgpd_settings();
    $position_class = 'despachante-lgpd--' . sanitize_html_class($settings['position']);
    ?>
    <div
        id="despachanteLgpdBanner"
        class="despachante-lgpd <?php echo esc_attr($position_class); ?>"
        role="dialog"
        aria-live="polite"
        aria-label="Aviso de privacidade"
    >
        <div class="despachante-lgpd__content">
            <?php if (!empty($settings['title'])) : ?>
                <div class="despachante-lgpd__title">
                    <?php echo esc_html($settings['title']); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($settings['text'])) : ?>
                <div class="despachante-lgpd__text">
                    <?php echo nl2br(esc_html($settings['text'])); ?>
                </div>
            <?php endif; ?>

            <div class="despachante-lgpd__actions">
                <button
                    type="button"
                    id="despachanteLgpdAccept"
                    class="despachante-lgpd__button"
                >
                    <?php echo esc_html($settings['accept_text']); ?>
                </button>

                <?php if (!empty($settings['policy_url'])) : ?>
                    <a
                        href="<?php echo esc_url($settings['policy_url']); ?>"
                        class="despachante-lgpd__link"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        <?php echo esc_html($settings['policy_text']); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
}
add_action('wp_footer', 'despachante_render_lgpd_banner');

/* ======================================
LGPD - CSS DINÂMICO
====================================== */

function despachante_lgpd_dynamic_css() {
    if (!despachante_lgpd_is_enabled()) {
        return;
    }

    $settings = despachante_get_lgpd_settings();

    echo '<style id="despachante-lgpd-dynamic-css">
        .despachante-lgpd{
            position:fixed;
            z-index:99999;
            width:min(420px, calc(100% - 30px));
            background:' . esc_attr($settings['bg_color']) . ';
            color:' . esc_attr($settings['text_color']) . ';
            border-radius:14px;
            box-shadow:0 14px 35px rgba(0,0,0,0.22);
            padding:20px;
            font-family:Arial,Helvetica,sans-serif;
        }

        .despachante-lgpd--bottom-left{
            left:15px;
            bottom:15px;
        }

        .despachante-lgpd--bottom-center{
            left:50%;
            bottom:15px;
            transform:translateX(-50%);
        }

        .despachante-lgpd--bottom-right{
            right:15px;
            bottom:15px;
        }

        .despachante-lgpd--top-center{
            left:50%;
            top:15px;
            transform:translateX(-50%);
        }

        .despachante-lgpd__title{
            font-size:18px;
            font-weight:700;
            margin-bottom:10px;
            line-height:1.3;
        }

        .despachante-lgpd__text{
            font-size:14px;
            line-height:1.6;
            margin-bottom:16px;
            opacity:0.96;
        }

        .despachante-lgpd__actions{
            display:flex;
            flex-wrap:wrap;
            gap:12px;
            align-items:center;
        }

        .despachante-lgpd__button{
            appearance:none;
            border:none;
            background:' . esc_attr($settings['button_color']) . ';
            color:#ffffff;
            padding:10px 18px;
            border-radius:8px;
            font-size:14px;
            font-weight:700;
            cursor:pointer;
        }

        .despachante-lgpd__button:hover{
            opacity:0.92;
        }

        .despachante-lgpd__link{
            color:' . esc_attr($settings['text_color']) . ';
            text-decoration:underline;
            font-size:14px;
            font-weight:600;
        }

        @media (max-width: 576px){
            .despachante-lgpd{
                left:15px !important;
                right:15px !important;
                bottom:15px !important;
                top:auto !important;
                width:auto;
                transform:none !important;
            }
        }
    </style>';
}
add_action('wp_head', 'despachante_lgpd_dynamic_css');