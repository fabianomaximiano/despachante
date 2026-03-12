<?php
if (!defined('ABSPATH')) {
    exit;
}

/* ======================================
BANCO DE DADOS - LEADS E ARQUIVOS
====================================== */

function despachante_get_db_version() {
    return '1.3.0';
}

function despachante_create_database_tables() {
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();
    $leads_table     = $wpdb->prefix . 'despachante_leads';
    $files_table     = $wpdb->prefix . 'despachante_lead_files';

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    $sql_leads = "CREATE TABLE {$leads_table} (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        nome VARCHAR(255) NOT NULL,
        whatsapp VARCHAR(50) NOT NULL,
        email VARCHAR(255) DEFAULT '' NOT NULL,
        servico_id BIGINT(20) UNSIGNED DEFAULT 0 NOT NULL,
        servico_nome VARCHAR(255) DEFAULT '' NOT NULL,
        objetivo_atendimento VARCHAR(100) DEFAULT '' NOT NULL,
        mensagem LONGTEXT NULL,
        lgpd_aceito TINYINT(1) DEFAULT 0 NOT NULL,
        lgpd_consent_at DATETIME NULL,
        lgpd_consent_ip VARCHAR(100) DEFAULT '' NOT NULL,
        lgpd_consent_version VARCHAR(50) DEFAULT '' NOT NULL,
        lgpd_consent_source VARCHAR(100) DEFAULT '' NOT NULL,
        ip_address VARCHAR(100) DEFAULT '' NOT NULL,
        user_agent TEXT NULL,
        status VARCHAR(50) DEFAULT 'novo' NOT NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY  (id),
        KEY servico_id (servico_id),
        KEY status (status),
        KEY created_at (created_at),
        KEY lgpd_consent_at (lgpd_consent_at)
    ) {$charset_collate};";

    $sql_files = "CREATE TABLE {$files_table} (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        lead_id BIGINT(20) UNSIGNED NOT NULL,
        tipo_documento VARCHAR(150) NOT NULL,
        document_slug VARCHAR(150) DEFAULT '' NOT NULL,
        is_checklist TINYINT(1) DEFAULT 0 NOT NULL,
        file_name VARCHAR(255) DEFAULT '' NOT NULL,
        file_url TEXT NULL,
        file_path TEXT NULL,
        mime_type VARCHAR(150) DEFAULT '' NOT NULL,
        attachment_id BIGINT(20) UNSIGNED DEFAULT 0 NOT NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY  (id),
        KEY lead_id (lead_id),
        KEY tipo_documento (tipo_documento),
        KEY document_slug (document_slug)
    ) {$charset_collate};";

    dbDelta($sql_leads);
    dbDelta($sql_files);

    update_option('despachante_db_version', despachante_get_db_version());
}

function despachante_maybe_create_database_tables() {
    $installed_version = get_option('despachante_db_version');

    if ($installed_version !== despachante_get_db_version()) {
        despachante_create_database_tables();
    }
}

add_action('after_switch_theme', 'despachante_create_database_tables');
add_action('admin_init', 'despachante_maybe_create_database_tables');