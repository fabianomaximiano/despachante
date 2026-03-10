<?php
/**
 * Footer - Despachante Digital Flow
 */

/* CTA do rodapé - desenvolvedor */
$footer_cta_title    = get_theme_mod('footer_cta_title', 'Interessado em ter um sistema assim para sua empresa?');
$footer_cta_subtitle = get_theme_mod('footer_cta_subtitle', 'Esta é uma Landing Page de demonstração. Modernize seu atendimento local hoje mesmo.');
$footer_copy         = get_theme_mod('footer_copy', '© 2026 — Todos os direitos reservados.');

$dev_whatsapp_enabled = get_theme_mod('dev_whatsapp_enabled', true);
$dev_whatsapp_number  = get_theme_mod('whatsapp_number', '');
$dev_whatsapp_message = get_theme_mod('whatsapp_message', 'Olá! Gostaria de fazer um orçamento.');

/* WhatsApp flutuante - escritório */
$office_whatsapp_enabled = get_theme_mod('office_whatsapp_enabled', true);
$office_whatsapp_number  = get_theme_mod('office_whatsapp_number', '');
$office_whatsapp_message = get_theme_mod('office_whatsapp_message', 'Olá! Gostaria de solicitar mais informações sobre os serviços do despachante.');
$office_whatsapp_text    = get_theme_mod('office_whatsapp_text', 'Fale com o escritório');

/* Contato no rodapé */
$footer_address    = get_theme_mod('footer_address', '');
$footer_city_state = get_theme_mod('footer_city_state', '');
$footer_phone      = get_theme_mod('footer_phone', '');
$footer_email      = get_theme_mod('footer_email', '');
$footer_hours      = get_theme_mod('footer_hours', '');

/* Redes sociais */
$social_instagram = get_theme_mod('social_instagram', '');
$social_facebook  = get_theme_mod('social_facebook', '');
$social_linkedin  = get_theme_mod('social_linkedin', '');
$social_x         = get_theme_mod('social_x', '');
$social_style     = get_theme_mod('social_style', 'icon');

/* Sanitização dos links de WhatsApp */
$dev_whatsapp_clean    = preg_replace('/[^0-9]/', '', $dev_whatsapp_number);
$office_whatsapp_clean = preg_replace('/[^0-9]/', '', $office_whatsapp_number);

$dev_whatsapp_link = '';
if (!empty($dev_whatsapp_clean)) {
    $dev_whatsapp_link = 'https://wa.me/' . $dev_whatsapp_clean . '?text=' . rawurlencode($dev_whatsapp_message);
}

$office_whatsapp_link = '';
if (!empty($office_whatsapp_clean)) {
    $office_whatsapp_link = 'https://wa.me/' . $office_whatsapp_clean . '?text=' . rawurlencode($office_whatsapp_message);
}

/* Classes de estilo das redes */
$social_class = 'footer-socials--icon';
if ($social_style === 'circle') {
    $social_class = 'footer-socials--circle';
} elseif ($social_style === 'square') {
    $social_class = 'footer-socials--square';
}

$has_contact_info = !empty($footer_address) || !empty($footer_city_state) || !empty($footer_phone) || !empty($footer_email) || !empty($footer_hours);
$has_socials      = !empty($social_instagram) || !empty($social_facebook) || !empty($social_linkedin) || !empty($social_x);
?>

<footer class="footer-cta py-5 text-white">
    <div class="container">

        <div class="row justify-content-center text-center mb-5">
            <div class="col-lg-8">
                <?php if (!empty($footer_cta_title)) : ?>
                    <h2 class="h1 font-weight-bold mb-3">
                        <?php echo esc_html($footer_cta_title); ?>
                    </h2>
                <?php endif; ?>

                <?php if (!empty($footer_cta_subtitle)) : ?>
                    <p class="lead mb-4">
                        <?php echo esc_html($footer_cta_subtitle); ?>
                    </p>
                <?php endif; ?>

                <?php if ($dev_whatsapp_enabled && !empty($dev_whatsapp_link)) : ?>
                    <a href="<?php echo esc_url($dev_whatsapp_link); ?>"
                       class="btn btn-success btn-lg px-5 py-3 rounded-pill shadow-lg"
                       target="_blank"
                       rel="noopener noreferrer">
                        <i class="fab fa-whatsapp mr-2"></i>
                        Falar com o Desenvolvedor
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($has_contact_info || $has_socials) : ?>
            <div class="row footer-content-row">
                <?php if ($has_contact_info) : ?>
                    <div class="col-lg-6 mb-4 mb-lg-0">
                        <h5 class="font-weight-bold mb-3">Contato</h5>

                        <ul class="footer-contact-list list-unstyled mb-0">
                            <?php if (!empty($footer_address)) : ?>
                                <li class="mb-2">
                                    <i class="fas fa-map-marker-alt mr-2"></i>
                                    <?php echo esc_html($footer_address); ?>
                                </li>
                            <?php endif; ?>

                            <?php if (!empty($footer_city_state)) : ?>
                                <li class="mb-2">
                                    <i class="fas fa-city mr-2"></i>
                                    <?php echo esc_html($footer_city_state); ?>
                                </li>
                            <?php endif; ?>

                            <?php if (!empty($footer_phone)) : ?>
                                <li class="mb-2">
                                    <i class="fas fa-phone-alt mr-2"></i>
                                    <?php echo esc_html($footer_phone); ?>
                                </li>
                            <?php endif; ?>

                            <?php if (!empty($footer_email)) : ?>
                                <li class="mb-2">
                                    <i class="fas fa-envelope mr-2"></i>
                                    <a href="mailto:<?php echo esc_attr($footer_email); ?>" class="footer-link">
                                        <?php echo esc_html($footer_email); ?>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if (!empty($footer_hours)) : ?>
                                <li class="mb-2">
                                    <i class="fas fa-clock mr-2"></i>
                                    <?php echo esc_html($footer_hours); ?>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if ($has_socials) : ?>
                    <div class="col-lg-6">
                        <h5 class="font-weight-bold mb-3">Redes Sociais</h5>

                        <div class="footer-socials <?php echo esc_attr($social_class); ?>">
                            <?php if (!empty($social_instagram)) : ?>
                                <a href="<?php echo esc_url($social_instagram); ?>" class="footer-social-link" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            <?php endif; ?>

                            <?php if (!empty($social_facebook)) : ?>
                                <a href="<?php echo esc_url($social_facebook); ?>" class="footer-social-link" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            <?php endif; ?>

                            <?php if (!empty($social_linkedin)) : ?>
                                <a href="<?php echo esc_url($social_linkedin); ?>" class="footer-social-link" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            <?php endif; ?>

                            <?php if (!empty($social_x)) : ?>
                                <a href="<?php echo esc_url($social_x); ?>" class="footer-social-link" target="_blank" rel="noopener noreferrer" aria-label="X">
                                    <i class="fab fa-x-twitter"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="mt-5 pt-3 footer-copy-wrap">
            <small><?php echo esc_html($footer_copy); ?></small>
        </div>
    </div>
</footer>

<?php if ($office_whatsapp_enabled && !empty($office_whatsapp_link)) : ?>
    <a href="<?php echo esc_url($office_whatsapp_link); ?>"
       class="whatsapp-float"
       target="_blank"
       rel="noopener noreferrer"
       aria-label="WhatsApp do escritório">
        <i class="fab fa-whatsapp"></i>
        <span><?php echo esc_html($office_whatsapp_text); ?></span>
    </a>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>