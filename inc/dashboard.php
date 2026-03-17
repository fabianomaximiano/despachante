<?php
if (!defined('ABSPATH')) {
    exit;
}

/* ======================================
MENU DASHBOARD
====================================== */

function despachante_register_dashboard_submenu() {
    add_submenu_page(
        'despachante-leads',
        'Dashboard',
        'Dashboard',
        'manage_options',
        'despachante-dashboard',
        'despachante_render_dashboard_page'
    );
}
add_action('admin_menu', 'despachante_register_dashboard_submenu');

/* ======================================
HELPERS
====================================== */

function despachante_dashboard_get_period_options() {
    return array(
        'today'   => 'Hoje',
        '7days'   => 'Últimos 7 dias',
        '30days'  => 'Últimos 30 dias',
        '90days'  => 'Últimos 90 dias',
        'all'     => 'Todo o período',
    );
}

function despachante_dashboard_get_status_map() {
    return array(
        'novo' => array(
            'label' => 'Novo',
            'color' => '#f59e0b',
        ),
        'aguardando_documentos' => array(
            'label' => 'Aguardando documentos',
            'color' => '#f97316',
        ),
        'em_analise' => array(
            'label' => 'Em análise',
            'color' => '#3b82f6',
        ),
        'em_andamento' => array(
            'label' => 'Em andamento',
            'color' => '#6366f1',
        ),
        'concluido' => array(
            'label' => 'Concluído',
            'color' => '#22c55e',
        ),
        'cancelado' => array(
            'label' => 'Cancelado',
            'color' => '#ef4444',
        ),
    );
}

function despachante_dashboard_get_objective_label($objective) {
    $labels = array(
        'tirar_duvidas'     => 'Quero tirar dúvidas',
        'iniciar_processo'  => 'Quero iniciar meu processo',
        'enviar_documentos' => 'Quero enviar documentos para análise',
    );

    return isset($labels[$objective]) ? $labels[$objective] : $objective;
}

function despachante_dashboard_get_status_badge($status) {
    $map = despachante_dashboard_get_status_map();

    if (!isset($map[$status])) {
        return esc_html($status);
    }

    return '<span style="
        display:inline-block;
        padding:6px 10px;
        border-radius:999px;
        background:' . esc_attr($map[$status]['color']) . ';
        color:#fff;
        font-size:12px;
        font-weight:700;
    ">' . esc_html($map[$status]['label']) . '</span>';
}

function despachante_dashboard_card($title, $value, $color = '#2563eb', $url = '') {
    $content = '
        <div style="
            background:#fff;
            border:1px solid #e5e7eb;
            border-left:6px solid ' . esc_attr($color) . ';
            border-radius:12px;
            padding:20px;
            box-shadow:0 3px 10px rgba(0,0,0,0.04);
            transition:all .2s ease;
            min-height:112px;
        ">
            <div style="
                font-size:14px;
                color:#6b7280;
                margin-bottom:8px;
                font-weight:600;
            ">' . esc_html($title) . '</div>
            <div style="
                font-size:34px;
                line-height:1;
                font-weight:700;
                color:#111827;
            ">' . esc_html($value) . '</div>
        </div>
    ';

    if (!empty($url)) {
        return '<a href="' . esc_url($url) . '" style="text-decoration:none; display:block;">' . $content . '</a>';
    }

    return $content;
}

function despachante_dashboard_get_period_start_date($period) {
    $today = current_time('Y-m-d');

    switch ($period) {
        case 'today':
            return $today;
        case '7days':
            return gmdate('Y-m-d', strtotime($today . ' -6 days'));
        case '30days':
            return gmdate('Y-m-d', strtotime($today . ' -29 days'));
        case '90days':
            return gmdate('Y-m-d', strtotime($today . ' -89 days'));
        case 'all':
        default:
            return '';
    }
}

function despachante_dashboard_get_period_days_for_chart($period) {
    switch ($period) {
        case 'today':
            return 1;
        case '7days':
            return 7;
        case '30days':
            return 30;
        case '90days':
            return 90;
        case 'all':
        default:
            return 30;
    }
}

function despachante_dashboard_build_leads_admin_url($args = array()) {
    $base = array(
        'page' => 'despachante-leads',
    );

    $args = array_merge($base, $args);

    return add_query_arg($args, admin_url('admin.php'));
}

function despachante_dashboard_render_bar_chart($title, $items, $value_key, $label_key, $color = '#2563eb', $empty_message = 'Nenhum dado disponível.') {
    echo '<div style="
        background:#fff;
        border:1px solid #e5e7eb;
        border-radius:12px;
        padding:20px;
        box-shadow:0 3px 10px rgba(0,0,0,0.04);
    ">';
    echo '<h2 style="margin-top:0; margin-bottom:20px;">' . esc_html($title) . '</h2>';

    if (empty($items)) {
        echo '<p>' . esc_html($empty_message) . '</p>';
        echo '</div>';
        return;
    }

    $max = 0;
    foreach ($items as $item) {
        $value = isset($item->$value_key) ? (int) $item->$value_key : 0;
        if ($value > $max) {
            $max = $value;
        }
    }

    foreach ($items as $item) {
        $label = isset($item->$label_key) ? $item->$label_key : '';
        $value = isset($item->$value_key) ? (int) $item->$value_key : 0;
        $width = $max > 0 ? max(6, ($value / $max) * 100) : 0;

        echo '<div style="margin-bottom:14px;">';
        echo '<div style="display:flex; justify-content:space-between; gap:12px; margin-bottom:6px;">';
        echo '<span style="font-size:13px; color:#374151; font-weight:600;">' . esc_html($label) . '</span>';
        echo '<span style="font-size:13px; color:#111827; font-weight:700;">' . esc_html($value) . '</span>';
        echo '</div>';
        echo '<div style="width:100%; background:#f3f4f6; border-radius:999px; height:10px; overflow:hidden;">';
        echo '<div style="height:10px; width:' . esc_attr($width) . '%; background:' . esc_attr($color) . '; border-radius:999px;"></div>';
        echo '</div>';
        echo '</div>';
    }

    echo '</div>';
}

function despachante_dashboard_render_leads_timeline_chart($title, $items, $empty_message = 'Nenhum dado disponível.') {
    echo '<div style="
        background:#fff;
        border:1px solid #e5e7eb;
        border-radius:12px;
        padding:20px;
        box-shadow:0 3px 10px rgba(0,0,0,0.04);
    ">';
    echo '<h2 style="margin-top:0; margin-bottom:20px;">' . esc_html($title) . '</h2>';

    if (empty($items)) {
        echo '<p>' . esc_html($empty_message) . '</p>';
        echo '</div>';
        return;
    }

    $max = 0;
    foreach ($items as $item) {
        $value = isset($item->total) ? (int) $item->total : 0;
        if ($value > $max) {
            $max = $value;
        }
    }

    echo '<div style="display:flex; align-items:flex-end; gap:10px; min-height:220px; overflow-x:auto; padding-top:10px;">';

    foreach ($items as $item) {
        $value = isset($item->total) ? (int) $item->total : 0;
        $date  = isset($item->lead_date) ? $item->lead_date : '';
        $height = $max > 0 ? max(8, ($value / $max) * 180) : 8;

        echo '<div style="display:flex; flex-direction:column; align-items:center; min-width:44px;">';
        echo '<div style="font-size:12px; font-weight:700; color:#111827; margin-bottom:8px;">' . esc_html($value) . '</div>';
        echo '<div style="width:28px; height:' . esc_attr($height) . 'px; background:#2563eb; border-radius:8px 8px 0 0;"></div>';
        echo '<div style="font-size:11px; color:#6b7280; margin-top:8px; white-space:nowrap;">' . esc_html(date_i18n('d/m', strtotime($date))) . '</div>';
        echo '</div>';
    }

    echo '</div>';
    echo '</div>';
}

/* ======================================
RENDER DASHBOARD
====================================== */

function despachante_render_dashboard_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    global $wpdb;

    $leads_table = $wpdb->prefix . 'despachante_leads';
    $period_options = despachante_dashboard_get_period_options();
    $period = isset($_GET['period']) ? sanitize_text_field(wp_unslash($_GET['period'])) : '30days';

    if (!isset($period_options[$period])) {
        $period = '30days';
    }

    $where = '';
    $params = array();

    $period_start = despachante_dashboard_get_period_start_date($period);

    if (!empty($period_start)) {
        $where = 'WHERE DATE(created_at) >= %s';
        $params[] = $period_start;
    }

    $total_query = "SELECT COUNT(*) FROM {$leads_table} {$where}";
    $today_query = "SELECT COUNT(*) FROM {$leads_table} WHERE DATE(created_at) = %s";

    if (!empty($params)) {
        $total_leads = (int) $wpdb->get_var($wpdb->prepare($total_query, $params));
    } else {
        $total_leads = (int) $wpdb->get_var($total_query);
    }

    $today_leads = (int) $wpdb->get_var(
        $wpdb->prepare($today_query, current_time('Y-m-d'))
    );

    $status_query = "SELECT status, COUNT(*) as total FROM {$leads_table} {$where} GROUP BY status ORDER BY total DESC";
    if (!empty($params)) {
        $status_rows = $wpdb->get_results($wpdb->prepare($status_query, $params));
    } else {
        $status_rows = $wpdb->get_results($status_query);
    }

    $latest_query = "SELECT * FROM {$leads_table} {$where} ORDER BY created_at DESC LIMIT 10";
    if (!empty($params)) {
        $latest_leads = $wpdb->get_results($wpdb->prepare($latest_query, $params));
    } else {
        $latest_leads = $wpdb->get_results($latest_query);
    }

    $services_query = "SELECT servico_nome, COUNT(*) as total FROM {$leads_table} {$where} GROUP BY servico_nome ORDER BY total DESC LIMIT 5";
    if (!empty($params)) {
        $top_services = $wpdb->get_results($wpdb->prepare($services_query, $params));
    } else {
        $top_services = $wpdb->get_results($services_query);
    }

    $objectives_query = "SELECT objetivo_atendimento, COUNT(*) as total FROM {$leads_table} {$where} GROUP BY objetivo_atendimento ORDER BY total DESC LIMIT 5";
    if (!empty($params)) {
        $top_objectives = $wpdb->get_results($wpdb->prepare($objectives_query, $params));
    } else {
        $top_objectives = $wpdb->get_results($objectives_query);
    }

    $chart_days = despachante_dashboard_get_period_days_for_chart($period);
    $chart_start = gmdate('Y-m-d', strtotime(current_time('Y-m-d') . ' -' . ($chart_days - 1) . ' days'));

    $timeline_rows = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT DATE(created_at) as lead_date, COUNT(*) as total
             FROM {$leads_table}
             WHERE DATE(created_at) >= %s
             GROUP BY DATE(created_at)
             ORDER BY DATE(created_at) ASC",
            $chart_start
        )
    );

    $timeline_map = array();
    if (!empty($timeline_rows)) {
        foreach ($timeline_rows as $row) {
            $timeline_map[$row->lead_date] = (int) $row->total;
        }
    }

    $timeline_items = array();
    for ($i = 0; $i < $chart_days; $i++) {
        $date = gmdate('Y-m-d', strtotime($chart_start . ' +' . $i . ' days'));
        $obj = new stdClass();
        $obj->lead_date = $date;
        $obj->total = isset($timeline_map[$date]) ? $timeline_map[$date] : 0;
        $timeline_items[] = $obj;
    }

    $status_chart_items = array();
    foreach ($status_rows as $row) {
        $obj = new stdClass();
        $obj->label = despachante_dashboard_get_status_map();
        $obj->label = isset($obj->label[$row->status]['label']) ? $obj->label[$row->status]['label'] : $row->status;
        $obj->total = (int) $row->total;
        $status_chart_items[] = $obj;
    }

    ?>
    <div class="wrap">
        <h1 style="margin-bottom:20px;">Dashboard do Despachante</h1>

        <form method="get" style="margin:0 0 20px 0; display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
            <input type="hidden" name="page" value="despachante-dashboard">

            <select name="period" style="min-width:220px;">
                <?php foreach ($period_options as $key => $label) : ?>
                    <option value="<?php echo esc_attr($key); ?>" <?php selected($period, $key); ?>>
                        <?php echo esc_html($label); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button class="button button-primary">Aplicar período</button>

            <a href="<?php echo esc_url(admin_url('admin.php?page=despachante-dashboard')); ?>" class="button">Limpar</a>
        </form>

        <div style="
            display:grid;
            grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));
            gap:16px;
            margin-bottom:30px;
        ">
            <?php
            echo despachante_dashboard_card(
                'Leads hoje',
                $today_leads,
                '#2563eb',
                despachante_dashboard_build_leads_admin_url()
            );

            echo despachante_dashboard_card(
                'Total no período',
                $total_leads,
                '#111827',
                despachante_dashboard_build_leads_admin_url()
            );

            $status_map = despachante_dashboard_get_status_map();

            foreach ($status_map as $key => $status_data) {
                $count = 0;
                foreach ($status_rows as $row) {
                    if ($row->status === $key) {
                        $count = (int) $row->total;
                        break;
                    }
                }

                echo despachante_dashboard_card(
                    $status_data['label'],
                    $count,
                    $status_data['color'],
                    despachante_dashboard_build_leads_admin_url(array('status' => $key))
                );
            }
            ?>
        </div>

        <div style="
            display:grid;
            grid-template-columns:2fr 1fr;
            gap:20px;
            align-items:start;
            margin-bottom:30px;
        ">
            <?php
            despachante_dashboard_render_leads_timeline_chart(
                'Leads por dia',
                $timeline_items
            );

            despachante_dashboard_render_bar_chart(
                'Leads por status',
                $status_chart_items,
                'total',
                'label',
                '#6366f1'
            );
            ?>
        </div>

        <div style="
            display:grid;
            grid-template-columns:2fr 1fr 1fr;
            gap:20px;
            align-items:start;
            margin-bottom:30px;
        ">
            <div style="
                background:#fff;
                border:1px solid #e5e7eb;
                border-radius:12px;
                padding:20px;
                box-shadow:0 3px 10px rgba(0,0,0,0.04);
            ">
                <h2 style="margin-top:0; margin-bottom:20px;">Últimos leads recebidos</h2>

                <?php if (empty($latest_leads)) : ?>

                    <p>Nenhum lead recebido ainda.</p>

                <?php else : ?>

                    <table class="widefat striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>WhatsApp</th>
                                <th>Serviço</th>
                                <th>Objetivo</th>
                                <th>Status</th>
                                <th>Data</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($latest_leads as $lead) : ?>
                                <?php $view_url = admin_url('admin.php?page=despachante-leads&lead_id=' . absint($lead->id)); ?>
                                <tr>
                                    <td><?php echo esc_html($lead->id); ?></td>
                                    <td><?php echo esc_html($lead->nome); ?></td>
                                    <td><?php echo esc_html($lead->whatsapp); ?></td>
                                    <td><?php echo esc_html($lead->servico_nome); ?></td>
                                    <td><?php echo esc_html(despachante_dashboard_get_objective_label($lead->objetivo_atendimento)); ?></td>
                                    <td><?php echo despachante_dashboard_get_status_badge($lead->status); ?></td>
                                    <td><?php echo esc_html($lead->created_at); ?></td>
                                    <td>
                                        <a class="button" href="<?php echo esc_url($view_url); ?>">Ver detalhes</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                <?php endif; ?>
            </div>

            <div style="
                background:#fff;
                border:1px solid #e5e7eb;
                border-radius:12px;
                padding:20px;
                box-shadow:0 3px 10px rgba(0,0,0,0.04);
            ">
                <h2 style="margin-top:0; margin-bottom:20px;">Serviços mais solicitados</h2>

                <?php if (empty($top_services)) : ?>
                    <p>Nenhum dado disponível.</p>
                <?php else : ?>
                    <table class="widefat striped">
                        <thead>
                            <tr>
                                <th>Serviço</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($top_services as $item) : ?>
                                <tr>
                                    <td><?php echo esc_html($item->servico_nome); ?></td>
                                    <td><?php echo esc_html($item->total); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

            <div style="
                background:#fff;
                border:1px solid #e5e7eb;
                border-radius:12px;
                padding:20px;
                box-shadow:0 3px 10px rgba(0,0,0,0.04);
            ">
                <h2 style="margin-top:0; margin-bottom:20px;">Objetivos mais frequentes</h2>

                <?php if (empty($top_objectives)) : ?>
                    <p>Nenhum dado disponível.</p>
                <?php else : ?>
                    <table class="widefat striped">
                        <thead>
                            <tr>
                                <th>Objetivo</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($top_objectives as $item) : ?>
                                <tr>
                                    <td><?php echo esc_html(despachante_dashboard_get_objective_label($item->objetivo_atendimento)); ?></td>
                                    <td><?php echo esc_html($item->total); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
}