<?php
if (!defined('ABSPATH')) {
    exit;
}

/* ======================================
HELPERS DE LEADS
====================================== */

function despachante_admin_get_leads_table_name() {
    global $wpdb;
    return $wpdb->prefix . 'despachante_leads';
}

function despachante_admin_leads_table_exists() {
    global $wpdb;
    $table = despachante_admin_get_leads_table_name();
    return $wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $table)) === $table;
}

function despachante_admin_get_leads_counts() {
    global $wpdb;

    $counts = array(
        'today'       => 0,
        'total'       => 0,
        'em_analise'  => 0,
        'concluido'   => 0,
        'novos'       => 0,
    );

    if (!despachante_admin_leads_table_exists()) {
        return $counts;
    }

    $table = despachante_admin_get_leads_table_name();
    $today = current_time('Y-m-d');

    $counts['today'] = (int) $wpdb->get_var(
        $wpdb->prepare(
            "SELECT COUNT(*) FROM {$table} WHERE DATE(created_at) = %s",
            $today
        )
    );

    $counts['total'] = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$table}");

    $counts['em_analise'] = (int) $wpdb->get_var(
        $wpdb->prepare(
            "SELECT COUNT(*) FROM {$table} WHERE status = %s",
            'em_analise'
        )
    );

    $counts['concluido'] = (int) $wpdb->get_var(
        $wpdb->prepare(
            "SELECT COUNT(*) FROM {$table} WHERE status = %s",
            'concluido'
        )
    );

    $counts['novos'] = (int) $wpdb->get_var(
        $wpdb->prepare(
            "SELECT COUNT(*) FROM {$table} WHERE status = %s",
            'novo'
        )
    );

    return $counts;
}

function despachante_admin_get_recent_leads($limit = 5) {
    global $wpdb;

    if (!despachante_admin_leads_table_exists()) {
        return array();
    }

    $table = despachante_admin_get_leads_table_name();

    $sql = $wpdb->prepare(
        "SELECT id, nome, whatsapp, servico_nome, status, created_at
         FROM {$table}
         ORDER BY id DESC
         LIMIT %d",
        absint($limit)
    );

    return $wpdb->get_results($sql);
}

function despachante_admin_format_status_label($status) {
    $map = array(
        'novo'                    => 'Novo',
        'aguardando_documentos'   => 'Aguardando documentos',
        'em_analise'              => 'Em análise',
        'em_andamento'            => 'Em andamento',
        'concluido'               => 'Concluído',
        'cancelado'               => 'Cancelado',
    );

    return isset($map[$status]) ? $map[$status] : ucfirst(str_replace('_', ' ', (string) $status));
}

/* ======================================
OCULTAR MENUS DO ADMIN
====================================== */

function despachante_hide_admin_menus() {
    if (!current_user_can('manage_options')) {
        return;
    }

    // remove_menu_page('index.php');
    // remove_menu_page('edit.php');
    // remove_menu_page('upload.php');
    // remove_menu_page('edit.php?post_type=page');
    // remove_menu_page('edit-comments.php');
    // remove_menu_page('themes.php');
    // remove_menu_page('plugins.php');
    // remove_menu_page('tools.php');
    // remove_menu_page('options-general.php');
    // remove_menu_page('wp-mail-smtp');
    // remove_menu_page('trustindex-google-reviews');
    // remove_menu_page('ti_widget');
}
add_action('admin_menu', 'despachante_hide_admin_menus', 999);

/* ======================================
PÁGINA ADMIN PRÓPRIA
====================================== */

function despachante_register_admin_home_page() {
    add_menu_page(
        'Painel',
        'Painel',
        'manage_options',
        'despachante-admin-home',
        'despachante_render_admin_home_page',
        'dashicons-dashboard',
        2
    );
}
add_action('admin_menu', 'despachante_register_admin_home_page', 1);

/* ======================================
BADGE NO MENU LEADS
====================================== */

function despachante_admin_menu_badge_output($title, $count) {
    if ((int) $count <= 0) {
        return $title;
    }

    return $title . ' <span class="awaiting-mod count-' . intval($count) . '"><span class="pending-count">' . intval($count) . '</span></span>';
}

function despachante_admin_add_leads_badge() {
    global $menu, $submenu;

    if (!current_user_can('manage_options')) {
        return;
    }

    $counts = despachante_admin_get_leads_counts();
    $new_count = (int) $counts['novos'];

    if (!empty($menu)) {
        foreach ($menu as $index => $item) {
            if (!isset($item[2])) {
                continue;
            }

            if ($item[2] === 'despachante-leads') {
                $menu[$index][0] = despachante_admin_menu_badge_output('Leads', $new_count);
            }
        }
    }

    if (!empty($submenu['despachante-leads'])) {
        foreach ($submenu['despachante-leads'] as $index => $item) {
            if (!isset($item[2])) {
                continue;
            }

            if ($item[2] === 'despachante-leads') {
                $submenu['despachante-leads'][$index][0] = despachante_admin_menu_badge_output('Leads', $new_count);
            }
        }
    }
}
add_action('admin_menu', 'despachante_admin_add_leads_badge', 1000);

/* ======================================
REDIRECIONAR DASHBOARD PADRÃO
====================================== */

function despachante_redirect_default_dashboard() {
    if (!is_admin() || !current_user_can('manage_options')) {
        return;
    }

    global $pagenow;

    if ($pagenow === 'index.php') {
        wp_safe_redirect(admin_url('admin.php?page=despachante-admin-home'));
        exit;
    }
}
add_action('admin_init', 'despachante_redirect_default_dashboard');

/* ======================================
CARREGAR ASSETS
====================================== */

function despachante_admin_ui_assets($hook) {
    if ($hook !== 'toplevel_page_despachante-admin-home') {
        return;
    }

    $css_path = get_template_directory() . '/assets/css/admin.css';
    $css_uri  = get_template_directory_uri() . '/assets/css/admin.css';
    $css_ver  = '3.5.0';

    if (file_exists($css_path)) {
        $css_ver = (string) filemtime($css_path);

        wp_enqueue_style(
            'despachante-admin-ui',
            $css_uri,
            array(),
            $css_ver
        );
    }
}
add_action('admin_enqueue_scripts', 'despachante_admin_ui_assets');

/* ======================================
OCULTAR NOTICES NESSA PÁGINA
====================================== */

function despachante_admin_ui_hide_notices_css() {
    $screen = get_current_screen();

    if (!$screen || $screen->id !== 'toplevel_page_despachante-admin-home') {
        return;
    }

    echo '<style>
        .toplevel_page_despachante-admin-home .notice,
        .toplevel_page_despachante-admin-home .update-nag,
        .toplevel_page_despachante-admin-home .updated,
        .toplevel_page_despachante-admin-home .error,
        .toplevel_page_despachante-admin-home .is-dismissible,
        .toplevel_page_despachante-admin-home .components-notice {
            display: none !important;
        }
    </style>';
}
add_action('admin_head', 'despachante_admin_ui_hide_notices_css', 999);

/* ======================================
RENDER DA HOME ADMIN
====================================== */

function despachante_render_admin_home_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    $customizer_url = admin_url('customize.php');
    $leads_url      = admin_url('admin.php?page=despachante-leads');
    $dashboard_url  = admin_url('admin.php?page=despachante-dashboard');
    $services_url   = admin_url('edit.php?post_type=servicos');
    $faq_url        = admin_url('edit.php?post_type=faq');

    $counts       = despachante_admin_get_leads_counts();
    $recent_leads = despachante_admin_get_recent_leads(6);
    ?>
    <div class="wrap despachante-admin-page">
        <div class="despachante-admin-home">

            <section class="despachante-admin-hero">
                <div class="despachante-admin-badge">Painel principal</div>
                <h1>Bem-vindo ao Despachante Digital Flow</h1>
                <p>
                    Use este painel para acessar rapidamente as áreas mais importantes do sistema,
                    acompanhar leads e manter o site atualizado.
                </p>
                <div class="despachante-admin-hero__actions">
                    <a href="<?php echo esc_url($customizer_url); ?>" class="despachante-admin-btn despachante-admin-btn--primary">
                        Personalizar site
                    </a>
                    <a href="<?php echo esc_url($leads_url); ?>" class="despachante-admin-btn despachante-admin-btn--ghost">
                        Ver leads
                    </a>
                </div>
            </section>

            <section class="despachante-admin-metrics">
                <a href="<?php echo esc_url($leads_url); ?>" class="despachante-admin-metric metric-blue">
                    <span>Leads hoje</span>
                    <strong><?php echo esc_html($counts['today']); ?></strong>
                </a>

                <a href="<?php echo esc_url($leads_url); ?>" class="despachante-admin-metric metric-purple">
                    <span>Leads totais</span>
                    <strong><?php echo esc_html($counts['total']); ?></strong>
                </a>

                <a href="<?php echo esc_url(add_query_arg('status', 'em_analise', $leads_url)); ?>" class="despachante-admin-metric metric-orange">
                    <span>Leads em análise</span>
                    <strong><?php echo esc_html($counts['em_analise']); ?></strong>
                </a>

                <a href="<?php echo esc_url(add_query_arg('status', 'concluido', $leads_url)); ?>" class="despachante-admin-metric metric-green">
                    <span>Processos concluídos</span>
                    <strong><?php echo esc_html($counts['concluido']); ?></strong>
                </a>
            </section>

            <section class="despachante-admin-main-grid">
                <a href="<?php echo esc_url($customizer_url); ?>" class="despachante-admin-card">
                    <div class="despachante-admin-card__icon">
                        <span class="dashicons dashicons-admin-customizer"></span>
                    </div>
                    <div class="despachante-admin-card__content">
                        <strong>Personalizar site</strong>
                        <span>Acesse Aparência → Personalizar e edite visual, textos, LGPD, footer e identidade do site.</span>
                    </div>
                </a>

                <a href="<?php echo esc_url($leads_url); ?>" class="despachante-admin-card">
                    <div class="despachante-admin-card__icon">
                        <span class="dashicons dashicons-id"></span>
                    </div>
                    <div class="despachante-admin-card__content">
                        <strong>Leads</strong>
                        <span>Visualize, filtre, exporte e acompanhe os leads recebidos pelo formulário.</span>
                    </div>
                </a>

                <a href="<?php echo esc_url($dashboard_url); ?>" class="despachante-admin-card">
                    <div class="despachante-admin-card__icon">
                        <span class="dashicons dashicons-chart-area"></span>
                    </div>
                    <div class="despachante-admin-card__content">
                        <strong>Dashboard</strong>
                        <span>Veja estatísticas, volume de leads, status e panorama geral do atendimento.</span>
                    </div>
                </a>

                <a href="<?php echo esc_url($services_url); ?>" class="despachante-admin-card">
                    <div class="despachante-admin-card__icon">
                        <span class="dashicons dashicons-admin-tools"></span>
                    </div>
                    <div class="despachante-admin-card__content">
                        <strong>Serviços</strong>
                        <span>Cadastre, edite e organize os serviços do escritório e os checklists vinculados.</span>
                    </div>
                </a>

                <a href="<?php echo esc_url($faq_url); ?>" class="despachante-admin-card">
                    <div class="despachante-admin-card__icon">
                        <span class="dashicons dashicons-editor-help"></span>
                    </div>
                    <div class="despachante-admin-card__content">
                        <strong>FAQ</strong>
                        <span>Atualize as perguntas frequentes exibidas na landing page.</span>
                    </div>
                </a>
            </section>

            <section class="despachante-admin-grid-2">
                <div class="despachante-admin-section">
                    <div class="despachante-admin-section__header">
                        <h2>Atividade recente</h2>
                        <p>Últimos leads recebidos pelo formulário do site.</p>
                    </div>

                    <?php if (!empty($recent_leads)) : ?>
                        <div class="despachante-admin-recent-list">
                            <?php foreach ($recent_leads as $lead) : ?>
                                <a
                                    href="<?php echo esc_url(admin_url('admin.php?page=despachante-leads&lead_id=' . intval($lead->id))); ?>"
                                    class="despachante-admin-recent-item"
                                >
                                    <div class="despachante-admin-recent-item__main">
                                        <strong><?php echo esc_html($lead->nome ?: 'Lead sem nome'); ?></strong>
                                        <span><?php echo esc_html($lead->servico_nome ?: 'Serviço não informado'); ?></span>
                                    </div>

                                    <div class="despachante-admin-recent-item__meta">
                                        <small><?php echo esc_html(despachante_admin_format_status_label($lead->status)); ?></small>
                                        <small><?php echo esc_html(mysql2date('d/m/Y H:i', $lead->created_at)); ?></small>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else : ?>
                        <p class="despachante-admin-empty">Nenhum lead encontrado até o momento.</p>
                    <?php endif; ?>
                </div>

                <div class="despachante-admin-section">
                    <div class="despachante-admin-section__header">
                        <h2>Plugins recomendados</h2>
                        <p>Ferramentas úteis para acompanhar métricas, desempenho e resultados do site.</p>
                    </div>

                    <div class="despachante-admin-plugin-grid">
                        <article class="despachante-admin-plugin-card">
                            <div class="despachante-admin-plugin-card__icon">
                                <span class="dashicons dashicons-google"></span>
                            </div>
                            <div class="despachante-admin-plugin-card__content">
                                <strong>Site Kit by Google</strong>
                                <span>Integra Google Analytics, Search Console e AdSense diretamente dentro do WordPress.</span>
                            </div>
                        </article>

                        <article class="despachante-admin-plugin-card">
                            <div class="despachante-admin-plugin-card__icon">
                                <span class="dashicons dashicons-chart-line"></span>
                            </div>
                            <div class="despachante-admin-plugin-card__content">
                                <strong>MonsterInsights</strong>
                                <span>Exibe relatórios do Google Analytics 4 (GA4) no painel do WordPress.</span>
                            </div>
                        </article>
                    </div>
                </div>
            </section>

        </div>
    </div>
    <?php
}