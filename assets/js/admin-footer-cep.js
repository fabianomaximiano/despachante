(function ($, api) {
    'use strict';

    if (!api || typeof despachanteFooterCep === 'undefined') {
        return;
    }

    const selectors = {
        zipcode: '#customize-control-footer_zipcode input, #customize-control-footer_cep input',
        address: '#customize-control-footer_address input',
        cityState: '#customize-control-footer_city_state input'
    };

    function onlyDigits(value) {
        return String(value || '').replace(/\D+/g, '');
    }

    function maskCep(value) {
        const digits = onlyDigits(value).slice(0, 8);

        if (digits.length <= 5) {
            return digits;
        }

        return digits.slice(0, 5) + '-' + digits.slice(5);
    }

    function setValue(selector, value) {
        const $field = $(selector).first();

        if ($field.length) {
            $field.val(value).trigger('input').trigger('change');
        }
    }

    function setNotice(message, type) {
        let $notice = $('#despachante-footer-cep-notice');

        if (!$notice.length) {
            const $target = $('#customize-control-footer_zipcode, #customize-control-footer_cep').first();

            if (!$target.length) {
                return;
            }

            $notice = $('<div id="despachante-footer-cep-notice" />').css({
                marginTop: '8px',
                fontSize: '12px',
                lineHeight: '1.4'
            });

            $target.append($notice);
        }

        const color = type === 'error'
            ? '#b32d2e'
            : (type === 'success' ? '#008a20' : '#555d66');

        $notice.text(message).css('color', color);
    }

    function clearNotice() {
        $('#despachante-footer-cep-notice').remove();
    }

    async function fetchCepData(cep) {
        const url = despachanteFooterCep.viacepBase + cep + '/json/';

        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error('http_error');
        }

        const data = await response.json();

        if (data.erro) {
            throw new Error('not_found');
        }

        return data;
    }

    async function handleCepLookup(rawCep) {
        const cep = onlyDigits(rawCep);

        if (!cep) {
            clearNotice();
            return;
        }

        if (cep.length !== 8) {
            setNotice(despachanteFooterCep.messages.invalid, 'error');
            return;
        }

        try {
            setNotice('Buscando endereço...', 'info');

            const data = await fetchCepData(cep);

            const logradouro = (data.logradouro || '').trim();
            const bairro = (data.bairro || '').trim();
            const cidade = (data.localidade || '').trim();
            const uf = (data.uf || '').trim();

            let addressValue = '';
            if (logradouro && bairro) {
                addressValue = logradouro + ' - ' + bairro;
            } else if (logradouro) {
                addressValue = logradouro;
            } else if (bairro) {
                addressValue = bairro;
            }

            let cityStateValue = '';
            if (cidade && uf) {
                cityStateValue = cidade + ' - ' + uf;
            } else if (cidade) {
                cityStateValue = cidade;
            } else if (uf) {
                cityStateValue = uf;
            }

            setValue(selectors.zipcode, maskCep(cep));

            if (addressValue) {
                setValue(selectors.address, addressValue);
            }

            if (cityStateValue) {
                setValue(selectors.cityState, cityStateValue);
            }

            setNotice('Endereço preenchido automaticamente. Confira e complemente o número, se necessário.', 'success');
        } catch (error) {
            if (error && error.message === 'not_found') {
                setNotice(despachanteFooterCep.messages.notfound, 'error');
            } else {
                setNotice(despachanteFooterCep.messages.error, 'error');
            }
        }
    }

    function bindCepField() {
        const $cepFields = $(selectors.zipcode);

        if (!$cepFields.length) {
            return;
        }

        $cepFields.each(function () {
            const $field = $(this);

            $field.on('input', function () {
                const masked = maskCep($field.val());

                if ($field.val() !== masked) {
                    $field.val(masked);
                }
            });

            $field.on('blur', function () {
                handleCepLookup($field.val());
            });
        });
    }

    $(function () {
        bindCepField();
    });

})(jQuery, wp.customize);