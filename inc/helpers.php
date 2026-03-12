<?php
if (!defined('ABSPATH')) {
    exit;
}

/* ======================================
SANITIZERS
====================================== */

function despachante_sanitize_icon_shape($value) {
    $valid = array('rounded-square', 'circle');
    return in_array($value, $valid, true) ? $value : 'rounded-square';
}

function despachante_sanitize_hover_effect($value) {
    $valid = array('lift', 'glow', 'zoom-icon');
    return in_array($value, $valid, true) ? $value : 'lift';
}

function despachante_sanitize_rgba_color($value) {
    $value = trim((string) $value);

    if ($value === '') {
        return 'rgba(0,0,0,0.05)';
    }

    if (preg_match('/^rgba?\(\s*(\d{1,3}\s*,\s*){2,3}(\d*\.?\d+)?\s*\)$/', $value)) {
        return $value;
    }

    return 'rgba(0,0,0,0.05)';
}

function despachante_sanitize_social_icon_style($value) {
    $valid = array('icon', 'circle', 'square');
    return in_array($value, $valid, true) ? $value : 'icon';
}

/* ======================================
UTILS
====================================== */

function despachante_slugify($text) {
    $text = remove_accents((string) $text);
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9]+/', '_', $text);
    $text = trim($text, '_');

    return !empty($text) ? $text : 'documento';
}

function despachante_normalize_document_label($label) {
    return trim(wp_strip_all_tags((string) $label));
}

function despachante_table_has_column($table_name, $column_name) {
    global $wpdb;

    $table_name  = preg_replace('/[^a-zA-Z0-9_]/', '', (string) $table_name);
    $column_name = preg_replace('/[^a-zA-Z0-9_]/', '', (string) $column_name);

    if (empty($table_name) || empty($column_name)) {
        return false;
    }

    $result = $wpdb->get_var("SHOW COLUMNS FROM `{$table_name}` LIKE '{$column_name}'");

    return !empty($result);
}

function despachante_get_runtime_base_url() {
    $scheme = is_ssl() ? 'https' : 'http';
    $host   = '';

    if (!empty($_SERVER['HTTP_HOST'])) {
        $host = sanitize_text_field(wp_unslash($_SERVER['HTTP_HOST']));
    }

    if (empty($host) || $host === '0.0.0.0' || $host === '127.0.0.1') {
        $site_url = site_url();
        $parts    = wp_parse_url($site_url);

        if (!empty($parts['scheme'])) {
            $scheme = $parts['scheme'];
        }

        if (!empty($parts['host']) && !in_array($parts['host'], array('0.0.0.0', '127.0.0.1'), true)) {
            $host = $parts['host'];

            if (!empty($parts['port'])) {
                $host .= ':' . $parts['port'];
            }
        }
    }

    if (empty($host) || $host === '0.0.0.0' || $host === '127.0.0.1') {
        $host = 'localhost:8085';
    }

    return $scheme . '://' . $host;
}

function despachante_build_public_upload_url($absolute_file_path) {
    $absolute_file_path = wp_normalize_path($absolute_file_path);
    $content_dir        = wp_normalize_path(WP_CONTENT_DIR);

    $relative_path = '';

    if (strpos($absolute_file_path, $content_dir) === 0) {
        $relative_path = 'wp-content/' . ltrim(str_replace($content_dir, '', $absolute_file_path), '/');
    } else {
        $base_dir = wp_normalize_path(ABSPATH);
        $relative_path = ltrim(str_replace($base_dir, '', $absolute_file_path), '/');
    }

    $relative_path = ltrim(str_replace('\\', '/', $relative_path), '/');

    return esc_url_raw(trailingslashit(despachante_get_runtime_base_url()) . $relative_path);
}

function despachante_normalize_public_url($url, $file_path = '') {
    $url = trim((string) $url);

    if (!empty($file_path)) {
        $normalized_from_path = despachante_build_public_upload_url($file_path);

        if (
            empty($url) ||
            strpos($url, '0.0.0.0') !== false ||
            strpos($url, '127.0.0.1') !== false
        ) {
            return $normalized_from_path;
        }
    }

    if (empty($url)) {
        return '';
    }

    $parts = wp_parse_url($url);

    if (empty($parts['host'])) {
        return $url;
    }

    if (in_array($parts['host'], array('0.0.0.0', '127.0.0.1'), true)) {
        $runtime = wp_parse_url(despachante_get_runtime_base_url());
        $scheme  = !empty($runtime['scheme']) ? $runtime['scheme'] : 'http';
        $host    = !empty($runtime['host']) ? $runtime['host'] : 'localhost';
        $port    = !empty($runtime['port']) ? ':' . $runtime['port'] : '';
        $path    = !empty($parts['path']) ? $parts['path'] : '';
        $query   = !empty($parts['query']) ? '?' . $parts['query'] : '';
        $frag    = !empty($parts['fragment']) ? '#' . $parts['fragment'] : '';

        return esc_url_raw($scheme . '://' . $host . $port . $path . $query . $frag);
    }

    return esc_url_raw($url);
}

function despachante_only_digits($value) {
    return preg_replace('/\D+/', '', (string) $value);
}

function despachante_is_valid_whatsapp($value) {
    $digits = despachante_only_digits($value);
    $length = strlen($digits);

    return ($length >= 10 && $length <= 11);
}