<?php
/**
 * Footer - Despachante Digital Flow
 */

if (!defined('ABSPATH')) {
    exit;
}

/* ======================================
CTA DO RODAPÉ
====================================== */
$footer_cta_title = despachante_get_theme_mod_compat(
    'footer_cta_title',
    array(),
    'Interessado em ter um sistema assim para sua empresa?'
);

$footer_cta_subtitle = despachante_get_theme_mod_compat(
    'footer_cta_subtitle',
    array('footer_cta_text'),
    'Esta é uma Landing Page de demonstração. Modernize seu atendimento local hoje mesmo.'
);

$footer_copy = despachante_get_theme_mod_compat(
    'footer_copy',
    array('footer_copyright'),
    '© 2026 — Todos os direitos reservados.'
);

/* ======================================
WHATSAPP - DESENVOLVEDOR
====================================== */
$dev_whatsapp_enabled = despachante_get_theme_mod_bool_compat(
    'dev_whatsapp_enabled',
    array('show_developer_whatsapp'),
    true
);

$dev_whatsapp_number = despachante_get_theme_mod_compat(
    'whatsapp_number',
    array('developer_whatsapp_number'),
    ''
);

$dev_whatsapp_message = despachante_get_theme_mod_compat(
    'whatsapp_message',
    array('developer_whatsapp_message'),
    'Olá! Gostaria de fazer um orçamento.'
);

$dev_whatsapp_text = despachante_get_theme_mod_compat(
    'developer_whatsapp_text',
    array(),
    'Falar com o Desenvolvedor'
);

/* ======================================
WHATSAPP FLUTUANTE - ESCRITÓRIO
====================================== */
$office_whatsapp_enabled = despachante_get_theme_mod_bool_compat(
    'office_whatsapp_enabled',
    array('show_floating_whatsapp'),
    true
);

$office_whatsapp_number = despachante_get_theme_mod_compat(
    'office_whatsapp_number',
    array('floating_whatsapp_number'),
    ''
);

$office_whatsapp_message = despachante_get_theme_mod_compat(
    'office_whatsapp_message',
    array('floating_whatsapp_message'),
    'Olá! Gostaria de solicitar mais informações sobre os serviços do despachante.'
);

$office_whatsapp_text = despachante_get_theme_mod_compat(
    'office_whatsapp_text',
    array('floating_whatsapp_text'),
    'Fale com o escritório'
);

/* ======================================
CONTATO NO RODAPÉ
====================================== */
$footer_address    = despachante_get_theme_mod_compat('footer_address', array(), '');
$footer_zipcode    = despachante_get_theme_mod_compat('footer_zipcode', array('footer_cep'), '');
$footer_city_state = despachante_get_theme_mod_compat('footer_city_state', array(), '');
$footer_phone      = despachante_get_theme_mod_compat('footer_phone', array(), '');
$footer_whatsapp   = despachante_get_theme_mod_compat('footer_whatsapp', array(), '');
$footer_email      = despachante_get_theme_mod_compat('footer_email', array(), '');
$footer_hours      = despachante_get_theme_mod_compat('footer_hours', array(), '');
$footer_bg_color   = despachante_get_theme_mod_compat('footer_bg_color', array(), '#0f2238');

/* ======================================
REDES SOCIAIS
====================================== */
$social_instagram = despachante_get_theme_mod_compat('social_instagram', array(), '');
$social_facebook  = despachante_get_theme_mod_compat('social_facebook', array(), '');
$social_linkedin  = despachante_get_theme_mod_compat('social_linkedin', array(), '');
$social_x         = despachante_get_theme_mod_compat('social_x', array(), '');

$social_style = despachante_get_theme_mod_compat(
    'social_style',
    array('social_icon_style'),
    'icon'
);

/* ======================================
FORMAS DE PAGAMENTO
====================================== */
$payment_show_section          = despachante_get_theme_mod_bool_compat('payment_show_section', array(), true);
$payment_section_title         = despachante_get_theme_mod_compat('payment_section_title', array(), 'Formas de pagamento');
$payment_support_text          = despachante_get_theme_mod_compat('payment_support_text', array(), 'Aceitamos pagamento via Pix, cartão de crédito e Mercado Pago.');
$payment_show_pix              = despachante_get_theme_mod_bool_compat('payment_show_pix', array(), true);
$payment_show_credit_card      = despachante_get_theme_mod_bool_compat('payment_show_credit_card', array(), true);
$payment_show_visa             = despachante_get_theme_mod_bool_compat('payment_show_visa', array(), false);
$payment_show_mastercard       = despachante_get_theme_mod_bool_compat('payment_show_mastercard', array(), false);
$payment_show_mercado_pago     = despachante_get_theme_mod_bool_compat('payment_show_mercado_pago', array(), true);
$payment_use_generic_card_icon = despachante_get_theme_mod_bool_compat('payment_use_generic_card_icon', array(), true);
$payment_enable_installments   = despachante_get_theme_mod_bool_compat('payment_enable_installments_text', array(), false);
$payment_installments_text     = despachante_get_theme_mod_compat('payment_installments_text', array(), 'Parcelamento disponível. Consulte condições.');

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
LINKS DE WHATSAPP
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

$dev_whatsapp_link    = $build_whatsapp_link($dev_whatsapp_number, $dev_whatsapp_message);
$office_whatsapp_link = $build_whatsapp_link($office_whatsapp_number, $office_whatsapp_message);

/* ======================================
CLASSES VISUAIS
====================================== */
$social_class = 'footer-socials--icon';
if ($social_style === 'circle') {
    $social_class = 'footer-socials--circle';
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
                                    <?php echo esc_html($footer_address); ?>
                                </li>
                            <?php endif; ?>

                            <?php if (!empty($footer_zipcode)) : ?>
                                <li class="mb-2">
                                    <i class="fas fa-mail-bulk mr-2" aria-hidden="true"></i>
                                    CEP: <?php echo esc_html($footer_zipcode); ?>
                                </li>
                            <?php endif; ?>

                            <?php if (!empty($footer_city_state)) : ?>
                                <li class="mb-2">
                                    <i class="fas fa-city mr-2" aria-hidden="true"></i>
                                    <?php echo esc_html($footer_city_state); ?>
                                </li>
                            <?php endif; ?>

                            <?php if (!empty($footer_phone)) : ?>
                                <li class="mb-2">
                                    <i class="fas fa-phone-alt mr-2" aria-hidden="true"></i>
                                    <?php echo esc_html($footer_phone); ?>
                                </li>
                            <?php endif; ?>

                            <?php if (!empty($footer_whatsapp)) : ?>
                                <li class="mb-2">
                                    <i class="fab fa-whatsapp mr-2" aria-hidden="true"></i>
                                    WhatsApp: <?php echo esc_html($footer_whatsapp); ?>
                                </li>
                            <?php endif; ?>

                            <?php if (!empty($footer_email)) : ?>
                                <li class="mb-2">
                                    <i class="fas fa-envelope mr-2" aria-hidden="true"></i>
                                    <a href="mailto:<?php echo esc_attr($footer_email); ?>" class="footer-link">
                                        <?php echo esc_html($footer_email); ?>
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

                <?php if ($show_payment_block) : ?>
                    <div class="col-lg-4 col-md-12 mb-4 mb-lg-0">
                        <div class="footer-payment-block">
                            <?php if (!empty($payment_section_title)) : ?>
                                <h5 class="font-weight-bold mb-3"><?php echo esc_html($payment_section_title); ?></h5>
                            <?php endif; ?>

                            <?php if ($has_payment_methods) : ?>
                                <div class="footer-payment-methods d-flex flex-wrap">
                                    <?php if ($payment_show_pix) : ?>
                                        <div class="footer-payment-item" title="Pix">
                                            <span class="footer-payment-icon">
                                                <i class="fas fa-qrcode" aria-hidden="true"></i>
                                            </span>
                                            <span class="footer-payment-label">Pix</span>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($payment_show_credit_card && $payment_use_generic_card_icon) : ?>
                                        <div class="footer-payment-item" title="Cartão de crédito">
                                            <span class="footer-payment-icon">
                                                <i class="far fa-credit-card" aria-hidden="true"></i>
                                            </span>
                                            <span class="footer-payment-label">Cartão</span>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($payment_show_visa && !$payment_use_generic_card_icon) : ?>
                                        <div class="footer-payment-item" title="Visa">
                                            <span class="footer-payment-icon">
                                                <i class="far fa-credit-card" aria-hidden="true"></i>
                                            </span>
                                            <span class="footer-payment-label">Visa</span>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($payment_show_mastercard && !$payment_use_generic_card_icon) : ?>
                                        <div class="footer-payment-item" title="MasterCard">
                                            <span class="footer-payment-icon">
                                                <i class="far fa-credit-card" aria-hidden="true"></i>
                                            </span>
                                            <span class="footer-payment-label">MasterCard</span>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($payment_show_credit_card && !$payment_use_generic_card_icon && !$payment_show_visa && !$payment_show_mastercard) : ?>
                                        <div class="footer-payment-item" title="Cartão de crédito">
                                            <span class="footer-payment-icon">
                                                <i class="far fa-credit-card" aria-hidden="true"></i>
                                            </span>
                                            <span class="footer-payment-label">Cartão</span>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($payment_show_mercado_pago) : ?>
                                        <div class="footer-payment-item" title="Mercado Pago">
                                            <span class="footer-payment-icon">
                                                <i class="fas fa-wallet" aria-hidden="true"></i>
                                            </span>
                                            <span class="footer-payment-label">Mercado Pago</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($payment_support_text)) : ?>
                                <p class="footer-payment-text mt-3 mb-2">
                                    <?php echo esc_html($payment_support_text); ?>
                                </p>
                            <?php endif; ?>

                            <?php if ($payment_enable_installments && !empty($payment_installments_text)) : ?>
                                <p class="footer-payment-installments mb-0">
                                    <?php echo esc_html($payment_installments_text); ?>
                                </p>
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
    <a
        href="<?php echo esc_url($office_whatsapp_link); ?>"
        class="whatsapp-float"
        target="_blank"
        rel="noopener noreferrer"
        aria-label="WhatsApp do escritório"
    >
        <i class="fab fa-whatsapp" aria-hidden="true"></i>
        <span><?php echo esc_html($office_whatsapp_text); ?></span>
    </a>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>