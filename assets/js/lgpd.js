document.addEventListener('DOMContentLoaded', function () {
    const banner = document.getElementById('despachanteLgpdBanner');
    const acceptButton = document.getElementById('despachanteLgpdAccept');
    const form = document.getElementById('formPreAnalise');

    const consentKey = 'despachante_lgpd_accepted';
    const consentDays = 15;
    const consentVersion = '1.0';
    const consentSource = 'banner_formulario';

    //alert("Carregou!");

    function setConsent(days) {
        const expires = new Date();
        expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
        document.cookie =
            consentKey +
            '=1; expires=' +
            expires.toUTCString() +
            '; path=/; SameSite=Lax';
    }

    function hasConsent() {
        return document.cookie
            .split(';')
            .map(function (item) {
                return item.trim();
            })
            .some(function (item) {
                return item.indexOf(consentKey + '=1') === 0;
            });
    }

    function hideBanner() {
        if (banner) {
            banner.style.display = 'none';
        }
    }

    function showBanner() {
        if (banner) {
            banner.style.display = 'block';
        }
    }

    function ensureFormConsentFeedback() {
        if (!form) {
            return null;
        }

        let feedback = document.getElementById('lgpdBannerFeedback');

        if (!feedback) {
            feedback = document.createElement('div');
            feedback.id = 'lgpdBannerFeedback';
            feedback.style.marginTop = '12px';
            feedback.style.fontSize = '14px';
            feedback.style.color = '#b91c1c';

            const submitButton = document.getElementById('submitPreAnalise');
            if (submitButton && submitButton.parentNode) {
                submitButton.parentNode.insertBefore(feedback, submitButton.nextSibling);
            } else {
                form.appendChild(feedback);
            }
        }

        return feedback;
    }

    function clearFormConsentFeedback() {
        const feedback = document.getElementById('lgpdBannerFeedback');
        if (feedback) {
            feedback.textContent = '';
        }
    }

    function ensureHiddenField(name, value) {
        if (!form) {
            return null;
        }

        let field = form.querySelector('input[name="' + name + '"]');

        if (!field) {
            field = document.createElement('input');
            field.type = 'hidden';
            field.name = name;
            form.appendChild(field);
        }

        field.value = value;
        return field;
    }

    function syncConsentFields() {
        if (!form) {
            return;
        }

        const accepted = hasConsent() ? '1' : '0';

        ensureHiddenField('lgpd_banner_accepted', accepted);
        ensureHiddenField('lgpd_consent_version', consentVersion);
        ensureHiddenField(
            'lgpd_consent_source',
            accepted === '1' ? consentSource : ''
        );
    }

    function blockSubmitWithoutBannerConsent(event) {
        syncConsentFields();

        if (hasConsent()) {
            clearFormConsentFeedback();
            return;
        }

        event.preventDefault();

        const feedback = ensureFormConsentFeedback();
        if (feedback) {
            feedback.textContent = 'Antes de enviar, aceite o banner de privacidade LGPD.';
        }

        showBanner();

        if (banner) {
            banner.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }
    }

    if (!form) {
        if (!banner) {
            return;
        }

        if (hasConsent()) {
            hideBanner();
        } else {
            showBanner();
        }

        if (acceptButton) {
            acceptButton.addEventListener('click', function () {
                setConsent(consentDays);
                hideBanner();
            });
        }

        return;
    }

    syncConsentFields();

    if (!banner) {
        return;
    }

    if (hasConsent()) {
        hideBanner();
    } else {
        showBanner();
    }

    if (acceptButton) {
        acceptButton.addEventListener('click', function () {
            setConsent(consentDays);
            syncConsentFields();
            hideBanner();
            clearFormConsentFeedback();
        });
    }

    form.addEventListener('submit', function () {
        syncConsentFields();
    }, true);

    form.addEventListener('submit', blockSubmitWithoutBannerConsent, true);
});

//alert("Chegou ao final!");