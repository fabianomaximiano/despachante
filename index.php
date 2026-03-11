<?php get_header(); ?>

<?php
$hero_bg = get_theme_mod(
    'hero_bg_image',
    'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=1920&q=80'
);

$hero_title       = get_theme_mod('hero_title', 'Despachante Digital Flow');
$hero_subtitle    = get_theme_mod('hero_subtitle', 'Agilidade, segurança e atendimento profissional para regularização de veículos.');
$hero_button_text = get_theme_mod('hero_button_text', 'Começar Agora');
$hero_button_link = get_theme_mod('hero_button_link', '#pre-analise');

$services_icon_shape   = get_theme_mod('services_icon_shape', 'rounded-square');
$services_hover_effect = get_theme_mod('services_hover_effect', 'lift');
$google_reviews_shortcode = get_theme_mod('google_reviews_shortcode', '[wp-review-slider]');

$icon_shape_class = ($services_icon_shape === 'circle')
    ? 'service-icon-box--circle'
    : 'service-icon-box--rounded-square';
$hover_effect_class = 'service-card--lift';

if ($services_hover_effect === 'glow') {
    $hover_effect_class = 'service-card--glow';
} elseif ($services_hover_effect === 'zoom-icon') {
    $hover_effect_class = 'service-card--zoom-icon';
}
?>

<header class="hero-section d-flex align-items-center" style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('<?php echo esc_url($hero_bg); ?>') center/cover no-repeat; height: 80vh;">
    <div class="container text-center text-white">
        <h1 class="display-4 font-weight-bold"><?php echo esc_html($hero_title); ?></h1>

        <?php if (!empty($hero_subtitle)) : ?>
            <p class="lead mt-3 mb-4"><?php echo esc_html($hero_subtitle); ?></p>
        <?php endif; ?>

        <a href="<?php echo esc_attr($hero_button_link); ?>" class="btn btn-success btn-lg px-5 mt-2">
            <?php echo esc_html($hero_button_text); ?>
        </a>
    </div>
</header>

<section class="py-5 bg-light">
    <div class="container text-center">
        <h2 class="font-weight-bold mb-5">Nossos Serviços</h2>

        <div class="row services-grid">
            <?php
            $query_servicos = new WP_Query(array(
                'post_type'      => 'servicos',
                'posts_per_page' => -1
            ));

            if ($query_servicos->have_posts()) :
                while ($query_servicos->have_posts()) :
                    $query_servicos->the_post();
                    $icone = get_post_meta(get_the_ID(), '_serv_icone', true);
                    $icone = !empty($icone) ? $icone : 'fas fa-check';
            ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card service-card <?php echo esc_attr($hover_effect_class); ?> h-100 text-center p-4">
                            <div class="service-icon-box <?php echo esc_attr($icon_shape_class); ?> mx-auto mb-4">
                                <i class="<?php echo esc_attr($icone); ?>" aria-hidden="true"></i>
                            </div>

                            <h4 class="font-weight-bold service-card__title mb-3"><?php the_title(); ?></h4>

                            <div class="service-card__content text-muted">
                                <?php the_content(); ?>
                            </div>
                        </div>
                    </div>
            <?php
                endwhile;
                wp_reset_postdata();
            else :
            ?>
                <div class="col-12">
                    <p class="text-center w-100">Cadastre serviços no painel.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="reviews-section py-5 bg-white">
    <div class="container">
        <h2 class="text-center font-weight-bold mb-5">Avaliações dos nossos clientes no Google</h2>

        <?php if (shortcode_exists('wp-review-slider')) : ?>
            <div class="reviews-slider-wrapper">
                <?php echo do_shortcode($google_reviews_shortcode); ?>
            </div>
        <?php else : ?>
            <div class="alert alert-warning text-center shadow-sm border-0" style="border-radius: 12px;">
                <strong>Atenção:</strong> para exibir esta seção de avaliações, instale e ative o plugin
                <strong>WP Google Review Slider</strong>.
                <br>
                Após instalar, configure o slider no painel do plugin e cole o shortcode em
                <strong>Aparência → Personalizar → Avaliações Google</strong>.
            </div>
        <?php endif; ?>
    </div>
</section>

<section id="faq" class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center font-weight-bold mb-5">Perguntas Frequentes</h2>
        <div class="accordion" id="faqAccordion">
            <?php
            $faqs = new WP_Query(array(
                'post_type'      => 'faq',
                'posts_per_page' => -1
            ));
            $count = 0;

            while ($faqs->have_posts()) :
                $faqs->the_post();
                $count++;
            ?>
                <div class="card mb-2 border-0 shadow-sm">
                    <div class="card-header bg-white p-0">
                        <button class="btn btn-link text-dark font-weight-bold w-100 text-left d-flex justify-content-between align-items-center py-3 px-4 <?php echo ($count > 1) ? 'collapsed' : ''; ?>" data-toggle="collapse" data-target="#collapse<?php echo esc_attr($count); ?>" type="button">
                            <?php the_title(); ?>
                        </button>
                    </div>
                    <div id="collapse<?php echo esc_attr($count); ?>" class="collapse <?php echo ($count === 1) ? 'show' : ''; ?>" data-parent="#faqAccordion">
                        <div class="card-body text-muted"><?php the_content(); ?></div>
                    </div>
                </div>
            <?php
            endwhile;
            wp_reset_postdata();
            ?>
        </div>
    </div>
</section>

<section id="pre-analise" class="py-5 bg-white">
    <div class="container">
        <div class="card shadow-sm border-0 mx-auto p-4 p-md-5" style="max-width: 820px; border-radius: 15px;">
            <h3 class="text-center font-weight-bold mb-4">Inicie sua Pré-Análise</h3>

            <form id="formPreAnalise" enctype="multipart/form-data" novalidate>
                <input type="hidden" name="action" value="despachante_pre_analise_submit">
                <input type="hidden" name="nonce" value="<?php echo esc_attr(wp_create_nonce('despachante_pre_analise_nonce')); ?>">

                <div class="form-group mb-4">
                    <label class="font-weight-bold">Nome completo</label>
                    <input type="text" name="nome" class="form-control custom-input" autocomplete="name" required>
                </div>

                <div class="form-group mb-4">
                    <label class="font-weight-bold">WhatsApp</label>
                    <input type="text" name="telefone" class="form-control custom-input" autocomplete="tel" maxlength="15" required>
                </div>

                <div class="form-group mb-4">
                    <label class="font-weight-bold">E-mail</label>
                    <input type="email" name="email" class="form-control custom-input" autocomplete="email" required>
                </div>

                <div class="form-group mb-4">
                    <label class="font-weight-bold">Objetivo do atendimento</label>
                    <select name="objetivo_atendimento" class="form-control custom-input">
                        <option value="">Selecione...</option>
                        <option value="tirar_duvidas">Quero tirar dúvidas</option>
                        <option value="iniciar_processo">Quero iniciar meu processo</option>
                        <option value="enviar_documentos">Quero enviar documentos para análise</option>
                    </select>
                </div>

                <div class="form-group mb-4">
                    <label class="font-weight-bold">Serviço</label>
                    <select name="servico" id="servicoSelect" class="form-control custom-input" required>
                        <option value="">Selecione...</option>
                        <?php
                        $q_s = new WP_Query(array(
                            'post_type'      => 'servicos',
                            'posts_per_page' => -1
                        ));

                        while ($q_s->have_posts()) :
                            $q_s->the_post();
                            echo '<option value="' . esc_attr(get_the_ID()) . '">' . esc_html(get_the_title()) . '</option>';
                        endwhile;
                        wp_reset_postdata();
                        ?>
                    </select>
                </div>

                <div class="form-group mb-4">
                    <label class="font-weight-bold">Checklist de documentos obrigatórios</label>
                    <div id="serviceDocumentsChecklist" class="documents-checklist">
                        <div class="documents-checklist__empty text-muted">
                            Selecione um serviço para exibir os documentos necessários.
                        </div>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label class="font-weight-bold">Documentos extras</label>
                    <div class="upload-container text-center border p-4" style="border-radius:10px;">
                        <input type="file" name="documentos_extras[]" id="extraFilesInput" class="d-none" multiple accept=".jpg,.jpeg,.png,.pdf">
                        <label for="extraFilesInput" style="cursor: pointer;" class="mb-0">
                            <i class="fas fa-paperclip fa-2x mb-2 text-muted"></i>
                            <p class="mb-1 text-muted">Anexe documentos adicionais aqui</p>
                            <small class="text-muted d-block">Formatos aceitos: JPG, PNG e PDF. Limite de 8MB por arquivo.</small>
                        </label>
                        <div id="selectedExtraFiles" class="mt-3 text-left small"></div>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label class="font-weight-bold">Mensagem</label>
                    <textarea name="mensagem" rows="4" class="form-control" style="border-radius:10px;" placeholder="Descreva sua necessidade, se quiser."></textarea>
                </div>

                <div class="form-group mb-4">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="lgpdAceito" name="lgpd_aceito" value="1">
                        <label class="custom-control-label" for="lgpdAceito">
                            Autorizo o envio e armazenamento dos meus dados para retorno do atendimento.
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-success btn-lg btn-block font-weight-bold py-3" id="submitPreAnalise">
                    Enviar Agora
                </button>

                <div id="formFeedback" class="mt-3 text-center"></div>
            </form>
        </div>
    </div>
</section>

<?php get_footer(); ?>