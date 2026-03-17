<?php
if (!defined('ABSPATH')) {
    exit;
}

/* ======================================
VALORES DO RODAPÉ
====================================== */
$footer_cta_title    = despachante_get_theme_mod_compat('footer_cta_title', array(), 'Interessado em ter um sistema assim para sua empresa?');
$footer_cta_subtitle = despachante_get_theme_mod_compat('footer_cta_subtitle', array('footer_cta_text'), 'Esta é uma Landing Page de demonstração. Modernize seu atendimento local hoje mesmo.');
$footer_bg_color     = despachante_get_theme_mod_compat('footer_bg_color', array(), '#1d2d3d');
$footer_copy         = despachante_get_theme_mod_compat('footer_copy', array('footer_copyright'), 'Todos os direitos reservados.');

$footer_address    = despachante_get_theme_mod_compat('footer_address', array(), '');
$footer_zipcode    = despachante_get_theme_mod_compat('footer_zipcode', array('footer_cep'), '');
$footer_city_state = despachante_get_theme_mod_compat('footer_city_state', array(), '');
$footer_phone      = despachante_get_theme_mod_compat('footer_phone', array(), '');
$footer_whatsapp   = despachante_get_theme_mod_compat('footer_whatsapp', array(), '');
$footer_email      = despachante_get_theme_mod_compat('footer_email', array(), '');
$footer_hours      = despachante_get_theme_mod_compat('footer_hours', array(), '');

$social_instagram = despachante_get_theme_mod_compat('social_instagram', array(), '');
$social_facebook  = despachante_get_theme_mod_compat('social_facebook', array(), '');
$social_linkedin  = despachante_get_theme_mod_compat('social_linkedin', array(), '');
$social_x         = despachante_get_theme_mod_compat('social_x', array(), '');
$social_style     = despachante_get_theme_mod_compat('social_style', array('social_icon_style'), 'circle');

$dev_whatsapp_enabled = despachante_get_theme_mod_bool_compat('dev_whatsapp_enabled', array('show_developer_whatsapp'), true);
$dev_whatsapp_number  = despachante_get_theme_mod_compat('whatsapp_number', array('developer_whatsapp_number'), '');
$dev_whatsapp_message = despachante_get_theme_mod_compat('whatsapp_message', array('developer_whatsapp_message'), 'Olá! Gostaria de falar com o desenvolvedor.');
$dev_whatsapp_text    = despachante_get_theme_mod_compat('developer_whatsapp_text', array(), 'Falar com o Desenvolvedor');

$office_whatsapp_enabled = despachante_get_theme_mod_bool_compat('office_whatsapp_enabled', array('show_floating_whatsapp'), true);
$office_whatsapp_number  = despachante_get_theme_mod_compat('office_whatsapp_number', array('floating_whatsapp_number'), '');
$office_whatsapp_message = despachante_get_theme_mod_compat('office_whatsapp_message', array('floating_whatsapp_message'), 'Olá! Gostaria de falar com o escritório.');
$office_whatsapp_text    = despachante_get_theme_mod_compat('office_whatsapp_text', array(), 'Fale com o escritório');

/* ======================================
PAGAMENTOS
====================================== */
$payment_show_section          = despachante_get_theme_mod_bool_compat('payment_show_section', array(), false);
$payment_section_title         = despachante_get_theme_mod_compat('payment_section_title', array(), 'Formas de pagamento');
$payment_support_text          = despachante_get_theme_mod_compat('payment_support_text', array(), 'Aceitamos os meios de pagamento abaixo.');
$payment_show_pix              = despachante_get_theme_mod_bool_compat('payment_show_pix', array(), false);
$payment_show_credit_card      = despachante_get_theme_mod_bool_compat('payment_show_credit_card', array(), false);
$payment_show_visa             = despachante_get_theme_mod_bool_compat('payment_show_visa', array(), false);
$payment_show_mastercard       = despachante_get_theme_mod_bool_compat('payment_show_mastercard', array(), false);
$payment_show_mercado_pago     = despachante_get_theme_mod_bool_compat('payment_show_mercado_pago', array(), false);
$payment_use_generic_card_icon = despachante_get_theme_mod_bool_compat('payment_use_generic_card_icon', array(), false);
$payment_enable_installments   = despachante_get_theme_mod_bool_compat('payment_enable_installments_text', array(), false);
$payment_installments_text     = despachante_get_theme_mod_compat('payment_installments_text', array(), 'Consulte condições.');

$has_payment_methods = (
    $payment_show_pix ||
    $payment_show_credit_card ||
    $payment_show_visa ||
    $payment_show_mastercard ||
    $payment_show_mercado_pago
);

$show_payment_block = $payment_show_section && (
    $has_payment_methods ||
    !empty($payment_support_text) ||
    ($payment_enable_installments && !empty($payment_installments_text))
);

/* ======================================
LINKS UTILITÁRIOS
====================================== */
$build_whatsapp_link = static function ($number, $message) {
    $clean = preg_replace('/\D+/', '', (string) $number);

    if (empty($clean)) {
        return '';
    }

    if (strlen($clean) <= 11) {
        $clean = '55' . $clean;
    }

    return 'https://wa.me/' . rawurlencode($clean) . '?text=' . rawurlencode((string) $message);
};

$build_tel_link = static function ($number) {
    $clean = preg_replace('/\D+/', '', (string) $number);

    if (empty($clean)) {
        return '';
    }

    return 'tel:+' . $clean;
};

$build_maps_link = static function ($address, $zipcode, $cityState) {
    $parts = array_filter(array(
        trim((string) $address),
        trim((string) $zipcode),
        trim((string) $cityState),
    ));

    if (empty($parts)) {
        return '';
    }

    return 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode(implode(', ', $parts));
};

$dev_whatsapp_link    = $build_whatsapp_link($dev_whatsapp_number, $dev_whatsapp_message);
$office_whatsapp_link = $build_whatsapp_link($office_whatsapp_number, $office_whatsapp_message);
$footer_phone_link    = $build_tel_link($footer_phone);
$footer_whatsapp_link = $build_whatsapp_link($footer_whatsapp, 'Olá! Gostaria de atendimento.');
$footer_maps_link     = $build_maps_link($footer_address, $footer_zipcode, $footer_city_state);

/* ======================================
CLASSES VISUAIS
====================================== */
$social_class = 'footer-socials--icon';

if ($social_style === 'circle') {
    $social_class = 'footer-socials--circle';
} elseif ($social_style === 'rounded-square') {
    $social_class = 'footer-socials--rounded-square';
} elseif ($social_style === 'square') {
    $social_class = 'footer-socials--square';
}

$has_contact_info = (
    !empty($footer_address) ||
    !empty($footer_zipcode) ||
    !empty($footer_city_state) ||
    !empty($footer_phone) ||
    !empty($footer_whatsapp) ||
    !empty($footer_email) ||
    !empty($footer_hours)
);

$has_socials = (
    !empty($social_instagram) ||
    !empty($social_facebook) ||
    !empty($social_linkedin) ||
    !empty($social_x)
);
?>

<footer class="footer-cta py-5 text-white" style="background-color: <?php echo esc_attr($footer_bg_color); ?>;">
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
                    <a
                        href="<?php echo esc_url($dev_whatsapp_link); ?>"
                        class="btn btn-success btn-lg px-5 py-3 rounded-pill shadow-lg"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        <i class="fab fa-whatsapp mr-2" aria-hidden="true"></i>
                        <?php echo esc_html($dev_whatsapp_text); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($has_contact_info || $has_socials || $show_payment_block) : ?>
            <div class="row footer-content-row">

                <?php if ($has_contact_info) : ?>
                    <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                        <h5 class="font-weight-bold mb-3">Contato</h5>

                        <ul class="footer-contact-list list-unstyled mb-0">
                            <?php if (!empty($footer_address)) : ?>
                                <li class="mb-2">
                                    <i class="fas fa-map-marker-alt mr-2" aria-hidden="true"></i>
                                    <?php if (!empty($footer_maps_link)) : ?>
                                        <a class="footer-link" href="<?php echo esc_url($footer_maps_link); ?>" target="_blank" rel="noopener noreferrer">
                                            <?php echo esc_html($footer_address); ?>
                                        </a>
                                    <?php else : ?>
                                        <?php echo esc_html($footer_address); ?>
                                    <?php endif; ?>
                                </li>
                            <?php endif; ?>

                            <?php if (!empty($footer_zipcode)) : ?>
                                <li class="mb-2">
                                    <i class="fas fa-mail-bulk mr-2" aria-hidden="true"></i>
                                    <?php if (!empty($footer_maps_link)) : ?>
                                        <a class="footer-link" href="<?php echo esc_url($footer_maps_link); ?>" target="_blank" rel="noopener noreferrer">
                                            CEP: <?php echo esc_html($footer_zipcode); ?>
                                        </a>
                                    <?php else : ?>
                                        CEP: <?php echo esc_html($footer_zipcode); ?>
                                    <?php endif; ?>
                                </li>
                            <?php endif; ?>

                            <?php if (!empty($footer_city_state)) : ?>
                                <li class="mb-2">
                                    <i class="fas fa-city mr-2" aria-hidden="true"></i>
                                    <?php if (!empty($footer_maps_link)) : ?>
                                        <a class="footer-link" href="<?php echo esc_url($footer_maps_link); ?>" target="_blank" rel="noopener noreferrer">
                                            <?php echo esc_html($footer_city_state); ?>
                                        </a>
                                    <?php else : ?>
                                        <?php echo esc_html($footer_city_state); ?>
                                    <?php endif; ?>
                                </li>
                            <?php endif; ?>

                            <?php if (!empty($footer_phone)) : ?>
                                <li class="mb-2">
                                    <i class="fas fa-phone-alt mr-2" aria-hidden="true"></i>
                                    <?php if (!empty($footer_phone_link)) : ?>
                                        <a class="footer-link" href="<?php echo esc_url($footer_phone_link); ?>">
                                            <?php echo esc_html($footer_phone); ?>
                                        </a>
                                    <?php else : ?>
                                        <?php echo esc_html($footer_phone); ?>
                                    <?php endif; ?>
                                </li>
                            <?php endif; ?>

                            <?php if (!empty($footer_whatsapp)) : ?>
                                <li class="mb-2">
                                    <i class="fab fa-whatsapp mr-2" aria-hidden="true"></i>
                                    <?php if (!empty($footer_whatsapp_link)) : ?>
                                        <a class="footer-link" href="<?php echo esc_url($footer_whatsapp_link); ?>" target="_blank" rel="noopener noreferrer">
                                            WhatsApp: <?php echo esc_html($footer_whatsapp); ?>
                                        </a>
                                    <?php else : ?>
                                        WhatsApp: <?php echo esc_html($footer_whatsapp); ?>
                                    <?php endif; ?>
                                </li>
                            <?php endif; ?>

                            <?php if (!empty($footer_email)) : ?>
                                <li class="mb-2">
                                    <i class="fas fa-envelope mr-2" aria-hidden="true"></i>
                                    <a class="footer-link" href="mailto:<?php echo antispambot(esc_attr($footer_email)); ?>">
                                        <?php echo antispambot(esc_html($footer_email)); ?>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if (!empty($footer_hours)) : ?>
                                <li class="mb-2">
                                    <i class="fas fa-clock mr-2" aria-hidden="true"></i>
                                    <?php echo esc_html($footer_hours); ?>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if ($has_socials) : ?>
                    <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                        <h5 class="font-weight-bold mb-3">Redes Sociais</h5>

                        <div class="footer-socials <?php echo esc_attr($social_class); ?>">
                            <?php if (!empty($social_instagram)) : ?>
                                <a class="footer-social-link" href="<?php echo esc_url($social_instagram); ?>" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                                    <i class="fab fa-instagram" aria-hidden="true"></i>
                                </a>
                            <?php endif; ?>

                            <?php if (!empty($social_facebook)) : ?>
                                <a class="footer-social-link" href="<?php echo esc_url($social_facebook); ?>" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                                    <i class="fab fa-facebook-f" aria-hidden="true"></i>
                                </a>
                            <?php endif; ?>

                            <?php if (!empty($social_linkedin)) : ?>
                                <a class="footer-social-link" href="<?php echo esc_url($social_linkedin); ?>" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
                                    <i class="fab fa-linkedin-in" aria-hidden="true"></i>
                                </a>
                            <?php endif; ?>

                            <?php if (!empty($social_x)) : ?>
                                <a class="footer-social-link" href="<?php echo esc_url($social_x); ?>" target="_blank" rel="noopener noreferrer" aria-label="X">
                                    <i class="fab fa-x-twitter" aria-hidden="true"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($show_payment_block) : ?>
                    <div class="col-lg-4 col-md-12 mt-4 mt-lg-0">
                        <h5 class="font-weight-bold mb-3"><?php echo esc_html($payment_section_title); ?></h5>

                        <?php if (!empty($payment_support_text)) : ?>
                            <p class="mb-3 text-white-50"><?php echo esc_html($payment_support_text); ?></p>
                        <?php endif; ?>

                        <div class="footer-payment-icons d-flex flex-wrap align-items-center">
                            <?php if ($payment_show_pix) : ?>
                                <span class="footer-payment-pill">PIX</span>
                            <?php endif; ?>

                            <?php if ($payment_show_credit_card) : ?>
                                <span class="footer-payment-pill">
                                    <?php echo $payment_use_generic_card_icon ? 'Cartão' : 'Crédito'; ?>
                                </span>
                            <?php endif; ?>

                            <?php if ($payment_show_visa) : ?>
                                <span class="footer-payment-pill">Visa</span>
                            <?php endif; ?>

                            <?php if ($payment_show_mastercard) : ?>
                                <span class="footer-payment-pill">Mastercard</span>
                            <?php endif; ?>

                            <?php if ($payment_show_mercado_pago) : ?>
                                <span class="footer-payment-pill">Mercado Pago</span>
                            <?php endif; ?>
                        </div>

                        <?php if ($payment_enable_installments && !empty($payment_installments_text)) : ?>
                            <p class="mt-3 mb-0 text-white-50">
                                <?php echo esc_html($payment_installments_text); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            </div>
        <?php endif; ?>

        <div class="footer-copy-wrap mt-5 pt-4 border-top">
            <small>© <?php echo esc_html(date_i18n('Y')); ?> — <?php echo esc_html($footer_copy); ?></small>
        </div>
    </div>
</footer>

<?php if ($office_whatsapp_enabled && !empty($office_whatsapp_link)) : ?>
    <a
        href="<?php echo esc_url($office_whatsapp_link); ?>"
        class="whatsapp-float"
        target="_blank"
        rel="noopener noreferrer"
        aria-label="<?php echo esc_attr($office_whatsapp_text); ?>"
    >
        <i class="fab fa-whatsapp" aria-hidden="true"></i>
        <span><?php echo esc_html($office_whatsapp_text); ?></span>
    </a>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>