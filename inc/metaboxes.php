<?php
if (!defined('ABSPATH')) {
    exit;
}

/* ======================================
DOCUMENTOS POR SERVIÇO
====================================== */

function despachante_get_default_required_documents() {
    return array(
        'RG',
        'CPF',
        'CNH',
        'Comprovante de residência',
        'CRLV',
        'Comprovante de pagamento',
        'Procuração',
    );
}

function despachante_get_service_required_documents($post_id) {
    $saved = get_post_meta($post_id, '_serv_documentos_requeridos', true);

    if (is_array($saved) && !empty($saved)) {
        return array_values(array_filter(array_map('despachante_normalize_document_label', $saved)));
    }

    return despachante_get_default_required_documents();
}

function despachante_get_service_documents_map() {
    $map = array();

    $services = get_posts(array(
        'post_type'      => 'servicos',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'fields'         => 'ids',
    ));

    if (!empty($services)) {
        foreach ($services as $service_id) {
            $map[$service_id] = despachante_get_service_required_documents($service_id);
        }
    }

    return $map;
}

/* ======================================
META BOXES DOS SERVIÇOS
====================================== */

function despachante_add_service_metaboxes() {
    add_meta_box(
        'mb_serv_icon',
        'Ícone do Card',
        'serv_icon_html',
        'servicos',
        'side'
    );

    add_meta_box(
        'mb_serv_docs',
        'Checklist de Documentos',
        'serv_docs_html',
        'servicos',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'despachante_add_service_metaboxes');

function serv_icon_html($post) {
    $icone = get_post_meta($post->ID, '_serv_icone', true);

    wp_nonce_field('save_servico_meta_boxes', 'servico_meta_nonce');

    $icon_options = array(
        'Transferência de propriedade de veículos'            => 'fas fa-exchange-alt',
        'Primeiro emplacamento (veículos zero km)'            => 'fas fa-car-side',
        'Licenciamento anual e regularização de IPVA/multas'  => 'fas fa-calendar-check',
        'Alteração de características (cor, motor, etc.)'     => 'fas fa-palette',
        'Comunicação de venda de veículos'                    => 'fas fa-handshake',
        'Baixa de gravame (alienação fiduciária)'             => 'fas fa-unlock',
        '2ª via de documentos (CRLV-e/CRV)'                   => 'fas fa-copy',
        'Regularização de motor e remarcação de chassi'       => 'fas fa-cogs',
    );
    ?>
    <p>
        <label for="serv_icone"><strong>Classe do Ícone (Font Awesome)</strong></label><br>
        <input
            type="text"
            id="serv_icone"
            name="serv_icone"
            value="<?php echo esc_attr($icone); ?>"
            placeholder="ex: fas fa-car-side"
            style="width:100%;"
        >
    </p>

    <p style="margin:10px 0 6px;">
        <strong>Opções sugeridas para serviços de despachante:</strong>
    </p>

    <div style="display:flex; flex-direction:column; gap:8px;">
        <?php foreach ($icon_options as $label => $class) : ?>
            <button
                type="button"
                class="button button-secondary despachante-icon-option"
                data-icon="<?php echo esc_attr($class); ?>"
                style="display:flex; align-items:center; justify-content:flex-start; gap:10px; text-align:left;"
            >
                <i class="<?php echo esc_attr($class); ?>" style="width:20px;"></i>
                <span><?php echo esc_html($label); ?> — <code><?php echo esc_html($class); ?></code></span>
            </button>
        <?php endforeach; ?>
    </div>

    <p style="margin-top:10px; color:#666; font-size:12px;">
        Clique em uma opção para preencher automaticamente o campo acima.
    </p>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var input = document.getElementById('serv_icone');
            var buttons = document.querySelectorAll('.despachante-icon-option');

            buttons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var iconClass = this.getAttribute('data-icon');
                    if (input) {
                        input.value = iconClass;
                        input.focus();
                    }
                });
            });
        });
    </script>
    <?php
}

function serv_docs_html($post) {
    $docs = despachante_get_service_required_documents($post->ID);
    ?>
    <p>Informe <strong>um documento por linha</strong>. Esse checklist aparecerá no formulário quando este serviço for selecionado.</p>
    <textarea name="serv_documentos_requeridos" rows="10" style="width:100%;"><?php echo esc_textarea(implode("\n", $docs)); ?></textarea>
    <p style="margin-top:8px;color:#666;font-size:12px;">
        Exemplo:<br>
        RG<br>
        CPF<br>
        CNH<br>
        Comprovante de residência
    </p>
    <?php
}

function despachante_save_servico_meta($post_id) {
    if (!isset($_POST['servico_meta_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['servico_meta_nonce'], 'save_servico_meta_boxes')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!isset($_POST['post_type']) || $_POST['post_type'] !== 'servicos') {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['serv_icone'])) {
        update_post_meta($post_id, '_serv_icone', sanitize_text_field($_POST['serv_icone']));
    }

    if (isset($_POST['serv_documentos_requeridos'])) {
        $raw_lines = explode("\n", wp_unslash($_POST['serv_documentos_requeridos']));
        $docs      = array();

        foreach ($raw_lines as $line) {
            $line = despachante_normalize_document_label($line);

            if (!empty($line)) {
                $docs[] = $line;
            }
        }

        update_post_meta($post_id, '_serv_documentos_requeridos', $docs);
    }
}
add_action('save_post', 'despachante_save_servico_meta');