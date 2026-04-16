<?php
/**
 * Tema: Despachante Digital Flow
 * Versão: 3.6.6
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
$modules = [
    'helpers.php',
    'setup.php',
    'database.php',
    'customizer.php',
    'cpts.php',
    'metaboxes.php',
    'uploads.php',
    'email.php',
    'form-handler.php',
    'admin-leads.php',
    'dynamic-css.php',
    'lgpd.php',
    'dashboard.php',
    'admin-ui.php',
];

foreach ($modules as $file) {
    $path = DESPACHANTE_THEME_DIR . '/inc/' . $file;

    if (file_exists($path)) {
        require_once $path;
    } else {
        error_log('Módulo do tema ausente: ' . $file);
    }
}

/*
|--------------------------------------------------------------------------
| Helpers de assets
|--------------------------------------------------------------------------
*/
if (!function_exists('despachante_get_asset_version')) {
    function despachante_get_asset_version($relative_path, $fallback = '1.0') {
        $full_path = get_template_directory() . $relative_path;

        if (file_exists($full_path)) {
            return (string) filemtime($full_path);
        }

        return (string) $fallback;
    }
}

if (!function_exists('despachante_is_front_like')) {
    function despachante_is_front_like() {
        return is_front_page() || is_home();
    }
}

/*
|--------------------------------------------------------------------------
| Carregamento de CSS e JS
|--------------------------------------------------------------------------
*/
add_action('wp_enqueue_scripts', function () {
    $theme_version = wp_get_theme()->get('Version');

    wp_dequeue_style('bootstrap');
    wp_deregister_style('bootstrap');

    wp_dequeue_style('bootstrap-custom');
    wp_deregister_style('bootstrap-custom');

    wp_dequeue_script('bootstrap');
    wp_deregister_script('bootstrap');

    wp_dequeue_script('bootstrap-bundle');
    wp_deregister_script('bootstrap-bundle');

    wp_dequeue_script('popper');
    wp_deregister_script('popper');

    //$bootstrap_default_rel = '/assets/css/bootstrap-custom.css';
    $bootstrap_default_rel  = '/assets/css/bootstrap.min.css';
    $bootstrap_default_path = get_template_directory() . $bootstrap_default_rel;
    $theme_style_dependencies = array();

    if (file_exists($bootstrap_default_path)) {
        wp_enqueue_style(
            'bootstrap-custom',
            get_template_directory_uri() . $bootstrap_default_rel,
            array(),
            despachante_get_asset_version($bootstrap_default_rel, '4.6.2')
        );

        $theme_style_dependencies[] = 'bootstrap-custom';
    }

    wp_enqueue_style(
        'theme-style',
        get_template_directory_uri() . '/assets/css/estyle.css',
        $theme_style_dependencies,
        despachante_get_asset_version('/assets/css/estyle.css', $theme_version)
    );

    wp_enqueue_script(
        'theme-script',
        get_template_directory_uri() . '/assets/js/script.js',
        array(),
        despachante_get_asset_version('/assets/js/script.js', $theme_version),
        true
    );

    wp_enqueue_script(
        'lgpd-script',
        get_template_directory_uri() . '/assets/js/lgpd.js',
        array(),
        despachante_get_asset_version('/assets/js/lgpd.js', $theme_version),
        true
    );

    wp_enqueue_script(
        'pre-analise-script',
        get_template_directory_uri() . '/assets/js/handlePreAnalise.js',
        array('jquery'),
        despachante_get_asset_version('/assets/js/handlePreAnalise.js', $theme_version),
        true
    );
}, 15);

/*
|--------------------------------------------------------------------------
| JS do Customizer - CEP automático do rodapé
|--------------------------------------------------------------------------
*/
add_action('customize_controls_enqueue_scripts', function () {
    $script_rel = '/assets/js/admin-footer-cep.js';
    $script_path = get_template_directory() . $script_rel;
    $script_uri  = get_template_directory_uri() . $script_rel;

    if (!file_exists($script_path)) {
        return;
    }

    wp_enqueue_script(
        'despachante-admin-footer-cep',
        $script_uri,
        array('jquery', 'customize-controls'),
        despachante_get_asset_version($script_rel, '1.0'),
        true
    );

    wp_localize_script('despachante-admin-footer-cep', 'despachanteFooterCep', array(
        'viacepBase' => 'https://viacep.com.br/ws/',
        'messages'   => array(
            'invalid'  => 'Informe um CEP válido com 8 dígitos.',
            'error'    => 'Não foi possível buscar o CEP agora. Tente novamente.',
            'notfound' => 'CEP não encontrado.',
        ),
    ));
});

/*
|--------------------------------------------------------------------------
| Otimizações de performance
|--------------------------------------------------------------------------
*/
add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');

    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));

    /*
     * Tamanhos focados no hero:
     * desktop: 1600x900
     * mobile: 768x432
     */
    add_image_size('hero-desktop', 1600, 900, true);
    add_image_size('hero-mobile', 768, 432, true);
});

add_action('init', function () {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_generator');
});

/*
|--------------------------------------------------------------------------
| Imagens em conteúdo
|--------------------------------------------------------------------------
*/
add_filter('the_content', function ($content) {
    return preg_replace_callback('/<img[^>]*>/i', function ($matches) {
        $img = $matches[0];

        if (stripos($img, 'alt=') === false) {
            $img = preg_replace('/<img/i', '<img alt="Despachante Digital"', $img, 1);
        }

        if (stripos($img, 'loading=') === false) {
            $img = preg_replace('/<img/i', '<img loading="lazy"', $img, 1);
        }

        if (stripos($img, 'decoding=') === false) {
            $img = preg_replace('/<img/i', '<img decoding="async"', $img, 1);
        }

        return $img;
    }, $content);
});

add_filter('wp_get_attachment_image_attributes', function ($attr, $attachment, $size) {
    if (in_array($size, array('hero-desktop', 'hero-mobile'), true)) {
        $attr['loading'] = 'eager';
        $attr['fetchpriority'] = 'high';
        $attr['decoding'] = 'async';

        if (empty($attr['alt'])) {
            $attr['alt'] = get_bloginfo('name');
        }

        return $attr;
    }

    if (empty($attr['loading'])) {
        $attr['loading'] = 'lazy';
    }

    if (empty($attr['decoding'])) {
        $attr['decoding'] = 'async';
    }

    return $attr;
}, 10, 3);

/*
|--------------------------------------------------------------------------
| CSS assíncrono
|--------------------------------------------------------------------------
*/
add_filter('style_loader_tag', function ($tag, $handle, $href, $media) {
    $async_styles = array('font-awesome', 'bootstrap-custom', 'theme-style');

    if (!in_array($handle, $async_styles, true)) {
        return $tag;
    }

    $media_attr = $media ? " media='" . esc_attr($media) . "'" : '';

    return '<link rel="preload" href="' . esc_url($href) . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'"' . $media_attr . '>'
        . '<noscript><link rel="stylesheet" href="' . esc_url($href) . '"' . $media_attr . '></noscript>';
}, 10, 4);

add_action('wp_head', function () {
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
    echo '<link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>' . "\n";
}, 1);

/*
|--------------------------------------------------------------------------
| Scripts com defer
|--------------------------------------------------------------------------
*/
add_filter('script_loader_tag', function ($tag, $handle, $src) {
    if (is_admin() || empty($src)) {
        return $tag;
    }

    $scripts = array(
        'jquery',
        'jquery-core',
        'jquery-migrate',
        'theme-script',
        'lgpd-script',
        'pre-analise-script',
        'slick',
        'wp-google-review-slider',
    );

    if (!in_array($handle, $scripts, true)) {
        return $tag;
    }

    if (strpos($tag, ' defer ') !== false) {
        return $tag;
    }

    return str_replace('<script ', '<script defer ', $tag);
}, 10, 3);

/*
|--------------------------------------------------------------------------
| Validação de upload de imagem para hero
|--------------------------------------------------------------------------
*/
if (!function_exists('despachante_validate_hero_image_upload')) {
    function despachante_validate_hero_image_upload($file) {
        if (empty($file['tmp_name']) || empty($file['type'])) {
            return $file;
        }

        if (strpos((string) $file['type'], 'image/') !== 0) {
            return $file;
        }

        $image_size = @getimagesize($file['tmp_name']);

        if (!$image_size || empty($image_size[0]) || empty($image_size[1])) {
            return $file;
        }

        $width  = (int) $image_size[0];
        $height = (int) $image_size[1];
        $bytes  = !empty($file['size']) ? (int) $file['size'] : 0;

        $min_width  = 1200;
        $min_height = 900;
        $max_width  = 3200;
        $max_height = 3200;
        $max_bytes  = 4 * 1024 * 1024;

        if ($width < $min_width || $height < $min_height) {
            $file['error'] = 'A imagem do hero é muito pequena. Use pelo menos 1200x900 pixels.';
            return $file;
        }

        if ($width > $max_width || $height > $max_height) {
            $file['error'] = 'A imagem do hero é muito grande. Use no máximo 3200x3200 pixels.';
            return $file;
        }

        if ($bytes > $max_bytes) {
            $file['error'] = 'A imagem excede 4 MB. Envie uma versão mais leve.';
            return $file;
        }

        return $file;
    }
}
add_filter('wp_handle_upload_prefilter', 'despachante_validate_hero_image_upload');

/*
|--------------------------------------------------------------------------
| Hero helpers
|--------------------------------------------------------------------------
*/
if (!function_exists('despachante_maybe_get_local_webp_url')) {
    function despachante_maybe_get_local_webp_url($url) {
        if (empty($url) || !is_string($url)) {
            return $url;
        }

        if (!preg_match('/\.(jpe?g|png)$/i', $url)) {
            return $url;
        }

        $uploads  = wp_upload_dir();
        $theme_uri = get_template_directory_uri();
        $theme_dir = get_template_directory();

        $webp_url = preg_replace('/\.(jpe?g|png)$/i', '.webp', $url);

        if (strpos($url, $uploads['baseurl']) === 0) {
            $relative  = ltrim(str_replace($uploads['baseurl'], '', $url), '/');
            $webp_path = trailingslashit($uploads['basedir']) . preg_replace('/\.(jpe?g|png)$/i', '.webp', $relative);

            if (file_exists($webp_path)) {
                return $webp_url;
            }
        }

        if (strpos($url, $theme_uri) === 0) {
            $relative  = ltrim(str_replace($theme_uri, '', $url), '/');
            $webp_path = trailingslashit($theme_dir) . preg_replace('/\.(jpe?g|png)$/i', '.webp', $relative);

            if (file_exists($webp_path)) {
                return $webp_url;
            }
        }

        return $url;
    }
}

/*
|--------------------------------------------------------------------------
| Conversão automática para WebP
|--------------------------------------------------------------------------
*/
if (!function_exists('despachante_generate_webp_for_attachment_files')) {
    function despachante_generate_webp_for_attachment_files($metadata, $attachment_id) {
        $uploads = wp_upload_dir();
        $files_to_process = array();

        if (!empty($metadata['file'])) {
            $files_to_process[] = trailingslashit($uploads['basedir']) . $metadata['file'];
        }

        if (!empty($metadata['sizes']) && is_array($metadata['sizes'])) {
            $base_dir = trailingslashit(dirname(trailingslashit($uploads['basedir']) . $metadata['file']));

            foreach ($metadata['sizes'] as $size_data) {
                if (!empty($size_data['file'])) {
                    $files_to_process[] = $base_dir . $size_data['file'];
                }
            }
        }

        $files_to_process = array_unique($files_to_process);

        foreach ($files_to_process as $file_path) {
            if (!file_exists($file_path)) {
                continue;
            }

            $extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));

            if (!in_array($extension, array('jpg', 'jpeg', 'png'), true)) {
                continue;
            }

            $webp_path = preg_replace('/\.(jpe?g|png)$/i', '.webp', $file_path);

            if (file_exists($webp_path)) {
                continue;
            }

            $editor = wp_get_image_editor($file_path);

            if (is_wp_error($editor)) {
                continue;
            }

            $editor->set_quality(78);
            $saved = $editor->save($webp_path, 'image/webp');

            if (is_wp_error($saved)) {
                continue;
            }
        }

        return $metadata;
    }
}
add_filter('wp_generate_attachment_metadata', 'despachante_generate_webp_for_attachment_files', 10, 2);

/*
|--------------------------------------------------------------------------
| Geração dedicada das versões do hero
|--------------------------------------------------------------------------
*/
if (!function_exists('despachante_generate_hero_derivatives')) {
    function despachante_generate_hero_derivatives($metadata, $attachment_id) {
        $mime = get_post_mime_type($attachment_id);

        if (!in_array($mime, array('image/jpeg', 'image/png', 'image/webp'), true)) {
            return $metadata;
        }

        $full_path = get_attached_file($attachment_id);

        if (!$full_path || !file_exists($full_path)) {
            return $metadata;
        }

        $variants = array(
            'hero-desktop' => array(
                'width'  => 1600,
                'height' => 900,
            ),
            'hero-mobile' => array(
                'width'  => 768,
                'height' => 432,
            ),
        );

        foreach ($variants as $size_name => $size) {
            $variant_editor = wp_get_image_editor($full_path);

            if (is_wp_error($variant_editor)) {
                continue;
            }

            $variant_editor->resize($size['width'], $size['height'], true);
            $variant_editor->set_quality(78);

            $saved = $variant_editor->save();

            if (is_wp_error($saved) || empty($saved['file'])) {
                continue;
            }

            if (empty($metadata['sizes']) || !is_array($metadata['sizes'])) {
                $metadata['sizes'] = array();
            }

            $metadata['sizes'][$size_name] = array(
                'file'      => wp_basename($saved['file']),
                'width'     => (int) $saved['width'],
                'height'    => (int) $saved['height'],
                'mime-type' => $saved['mime-type'],
            );

            if (!empty($saved['path']) && preg_match('/\.(jpe?g|png)$/i', $saved['path'])) {
                $webp_editor = wp_get_image_editor($saved['path']);

                if (!is_wp_error($webp_editor)) {
                    $webp_editor->set_quality(78);
                    $webp_editor->save(
                        preg_replace('/\.(jpe?g|png)$/i', '.webp', $saved['path']),
                        'image/webp'
                    );
                }
            }
        }

        return $metadata;
    }
}
add_filter('wp_generate_attachment_metadata', 'despachante_generate_hero_derivatives', 20, 2);

if (!function_exists('despachante_get_hero_sources')) {
    function despachante_get_hero_sources() {
        $default = get_template_directory_uri() . '/assets/img/hero.webp';
        $hero_setting = get_theme_mod('hero_bg_image', '');
        $desktop = '';
        $mobile  = '';
        $default_source = $default;

        if (is_numeric($hero_setting)) {
            $attachment_id = absint($hero_setting);

            $desktop = wp_get_attachment_image_url($attachment_id, 'hero-desktop');
            $mobile  = wp_get_attachment_image_url($attachment_id, 'hero-mobile');
            $full    = wp_get_attachment_image_url($attachment_id, 'full');

            if (!$desktop) {
                $desktop = $full;
            }

            if (!$mobile) {
                $mobile = $desktop;
            }

            $desktop = despachante_maybe_get_local_webp_url($desktop);
            $mobile  = despachante_maybe_get_local_webp_url($mobile);
            $default_source = $desktop ?: $default;
        } elseif (!empty($hero_setting) && is_string($hero_setting)) {
            $attachment_id = attachment_url_to_postid($hero_setting);

            if ($attachment_id) {
                $desktop = wp_get_attachment_image_url($attachment_id, 'hero-desktop');
                $mobile  = wp_get_attachment_image_url($attachment_id, 'hero-mobile');
                $full    = wp_get_attachment_image_url($attachment_id, 'full');

                if (!$desktop) {
                    $desktop = $full ?: $hero_setting;
                }

                if (!$mobile) {
                    $mobile = $desktop;
                }

                $desktop = despachante_maybe_get_local_webp_url($desktop);
                $mobile  = despachante_maybe_get_local_webp_url($mobile);
                $default_source = $desktop ?: $default;
            } else {
                $desktop = despachante_maybe_get_local_webp_url($hero_setting);
                $mobile  = $desktop;
                $default_source = $desktop ?: $default;
            }
        }

        if (empty($desktop)) {
            $desktop = $default;
        }

        if (empty($mobile)) {
            $mobile = $desktop;
        }

        return array(
            'desktop' => $desktop,
            'mobile'  => $mobile,
            'default' => $default_source ?: $default,
        );
    }
}

if (!function_exists('despachante_render_hero_picture')) {
    function despachante_render_hero_picture($sources, $alt = '') {
        $desktop = !empty($sources['desktop']) ? $sources['desktop'] : '';
        $mobile  = !empty($sources['mobile']) ? $sources['mobile'] : $desktop;

        if (empty($desktop)) {
            return '';
        }

        $alt = $alt !== '' ? $alt : get_bloginfo('name');

        ob_start();
        ?>
        <picture class="hero-media__picture" aria-hidden="true">
            <?php if (!empty($mobile) && $mobile !== $desktop) : ?>
                <source media="(max-width: 768px)" srcset="<?php echo esc_url($mobile); ?>">
            <?php endif; ?>
            <img
                class="hero-media__image"
                src="<?php echo esc_url($desktop); ?>"
                alt="<?php echo esc_attr($alt); ?>"
                width="1600"
                height="900"
                fetchpriority="high"
                loading="eager"
                decoding="async">
        </picture>
        <?php
        return trim(ob_get_clean());
    }
}

/*
|--------------------------------------------------------------------------
| Preload do hero
|--------------------------------------------------------------------------
*/
add_action('wp_head', function () {
    if (!despachante_is_front_like()) {
        return;
    }

    $hero_sources = despachante_get_hero_sources();

    if (empty($hero_sources['desktop'])) {
        return;
    }

    echo '<link rel="preload" as="image" fetchpriority="high" href="' . esc_url($hero_sources['desktop']) . '">' . "\n";
}, 2);

/*
|--------------------------------------------------------------------------
| Limpeza de assets do front
|--------------------------------------------------------------------------
*/
add_action('wp_enqueue_scripts', function () {
    if (!is_user_logged_in()) {
        wp_deregister_style('dashicons');
    }
}, 100);

/*
|--------------------------------------------------------------------------
| SMTP do WordPress (Titan Email)
|--------------------------------------------------------------------------
|
| Configure as constantes no wp-config.php:
|
| define('DESPACHANTE_SMTP_HOST', 'smtp.titan.email');
| define('DESPACHANTE_SMTP_PORT', 465);
| define('DESPACHANTE_SMTP_SECURE', 'ssl'); // ssl para 465 | tls para 587
| define('DESPACHANTE_SMTP_AUTH', true);
| define('DESPACHANTE_SMTP_USER', 'contato@seudominio.com');
| define('DESPACHANTE_SMTP_PASS', 'SUA_SENHA_AQUI');
| define('DESPACHANTE_MAIL_FROM', 'contato@seudominio.com');
| define('DESPACHANTE_MAIL_FROM_NAME', 'Despachante Digital Flow');
|
*/
if (!function_exists('despachante_mailer_is_enabled')) {
    function despachante_mailer_is_enabled() {
        return defined('DESPACHANTE_SMTP_HOST')
            && defined('DESPACHANTE_SMTP_PORT')
            && defined('DESPACHANTE_SMTP_USER')
            && defined('DESPACHANTE_SMTP_PASS')
            && !empty(DESPACHANTE_SMTP_HOST)
            && !empty(DESPACHANTE_SMTP_PORT)
            && !empty(DESPACHANTE_SMTP_USER)
            && !empty(DESPACHANTE_SMTP_PASS);
    }
}

add_action('phpmailer_init', function ($phpmailer) {
    if (!despachante_mailer_is_enabled()) {
        return;
    }

    $phpmailer->isSMTP();
    $phpmailer->Host = DESPACHANTE_SMTP_HOST;
    $phpmailer->Port = (int) DESPACHANTE_SMTP_PORT;
    $phpmailer->SMTPAuth = defined('DESPACHANTE_SMTP_AUTH') ? (bool) DESPACHANTE_SMTP_AUTH : true;
    $phpmailer->Username = DESPACHANTE_SMTP_USER;
    $phpmailer->Password = DESPACHANTE_SMTP_PASS;

    $secure = defined('DESPACHANTE_SMTP_SECURE') ? strtolower((string) DESPACHANTE_SMTP_SECURE) : 'ssl';

    if ($secure === 'tls') {
        $phpmailer->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
    } else {
        $phpmailer->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
    }

    $phpmailer->CharSet = 'UTF-8';
    $phpmailer->Encoding = 'base64';

    if (defined('DESPACHANTE_MAIL_FROM') && is_email(DESPACHANTE_MAIL_FROM)) {
        $phpmailer->From = DESPACHANTE_MAIL_FROM;
        $phpmailer->Sender = DESPACHANTE_MAIL_FROM;
    }

    if (defined('DESPACHANTE_MAIL_FROM_NAME') && DESPACHANTE_MAIL_FROM_NAME !== '') {
        $phpmailer->FromName = DESPACHANTE_MAIL_FROM_NAME;
    }
}, 20);

add_filter('wp_mail_from', function ($from) {
    if (defined('DESPACHANTE_MAIL_FROM') && is_email(DESPACHANTE_MAIL_FROM)) {
        return DESPACHANTE_MAIL_FROM;
    }

    return $from;
});

add_filter('wp_mail_from_name', function ($name) {
    if (defined('DESPACHANTE_MAIL_FROM_NAME') && DESPACHANTE_MAIL_FROM_NAME !== '') {
        return DESPACHANTE_MAIL_FROM_NAME;
    }

    return $name;
});

add_action('wp_mail_failed', function ($wp_error) {
    if (!is_wp_error($wp_error)) {
        return;
    }

    error_log('Despachante wp_mail_failed: ' . $wp_error->get_error_message());

    $data = $wp_error->get_error_data();

    if (!empty($data)) {
        error_log('Despachante wp_mail_failed data: ' . wp_json_encode($data));
    }
});


