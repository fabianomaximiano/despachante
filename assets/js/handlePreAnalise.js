document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formPreAnalise');
    const feedback = document.getElementById('formFeedback');
    const submitButton = document.getElementById('submitPreAnalise');
    const servicoSelect = document.getElementById('servicoSelect');
    const checklistContainer = document.getElementById('serviceDocumentsChecklist');
    const extraFilesInput = document.getElementById('extraFilesInput');
    const selectedExtraFiles = document.getElementById('selectedExtraFiles');
    const telefoneInput = form ? form.querySelector('input[name="telefone"]') : null;
    const emailInput = form ? form.querySelector('input[name="email"]') : null;
    const nomeInput = form ? form.querySelector('input[name="nome"]') : null;
    const objetivoInput = form ? form.querySelector('select[name="objetivo_atendimento"]') : null;
    const mensagemInput = form ? form.querySelector('textarea[name="mensagem"]') : null;

    if (!form) {
        return;
    }

    const serviceDocs = (window.wp_ajax_obj && wp_ajax_obj.service_docs) ? wp_ajax_obj.service_docs : {};
    const messages = (window.wp_ajax_obj && wp_ajax_obj.messages) ? wp_ajax_obj.messages : {};

    function showFeedback(message, type) {
        if (!feedback) return;
        feedback.innerHTML = '<div class="alert alert-' + type + ' mb-0">' + message + '</div>';
    }

    function clearFeedback() {
        if (!feedback) return;
        feedback.innerHTML = '';
    }

    function ensureFieldErrorElement(field) {
        if (!field) return null;

        let errorEl = field.parentNode.querySelector('.field-error-message');

        if (!errorEl) {
            errorEl = document.createElement('div');
            errorEl.className = 'field-error-message text-danger small mt-2';
            errorEl.style.display = 'none';
            field.parentNode.appendChild(errorEl);
        }

        return errorEl;
    }

    function showFieldError(field, message) {
        if (!field) return;

        field.style.borderColor = '#dc3545';
        field.style.boxShadow = '0 0 0 0.2rem rgba(220,53,69,0.15)';

        const errorEl = ensureFieldErrorElement(field);
        if (errorEl) {
            errorEl.textContent = message;
            errorEl.style.display = 'block';
        }
    }

    function clearFieldError(field) {
        if (!field) return;

        field.style.borderColor = '';
        field.style.boxShadow = '';

        const errorEl = ensureFieldErrorElement(field);
        if (errorEl) {
            errorEl.textContent = '';
            errorEl.style.display = 'none';
        }
    }

    function scrollToField(field) {
        if (!field) return;
        field.scrollIntoView({ behavior: 'smooth', block: 'center' });
        field.focus();
    }

    function clearAllFieldErrors() {
        [nomeInput, telefoneInput, emailInput, servicoSelect, objetivoInput, mensagemInput].forEach(function (field) {
            clearFieldError(field);
        });
    }

    function renderSelectedFiles(target, files) {
        if (!target) return;

        if (!files || !files.length) {
            target.innerHTML = '';
            return;
        }

        let html = '<strong>Arquivos selecionados:</strong><ul class="mb-0 pl-3">';
        Array.from(files).forEach(function (file) {
            html += '<li>' + file.name + ' (' + Math.round(file.size / 1024) + ' KB)</li>';
        });
        html += '</ul>';

        target.innerHTML = html;
    }

    function slugify(text) {
        return text
            .toString()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '_')
            .replace(/^_+|_+$/g, '');
    }

    function onlyDigits(value) {
        return String(value || '').replace(/\D/g, '');
    }

    function applyPhoneMask(value) {
        const digits = onlyDigits(value).slice(0, 11);

        if (digits.length <= 2) {
            return digits.length ? '(' + digits : '';
        }

        if (digits.length <= 6) {
            return '(' + digits.slice(0, 2) + ') ' + digits.slice(2);
        }

        if (digits.length <= 10) {
            return '(' + digits.slice(0, 2) + ') ' + digits.slice(2, 6) + '-' + digits.slice(6);
        }

        return '(' + digits.slice(0, 2) + ') ' + digits.slice(2, 7) + '-' + digits.slice(7);
    }

    function isValidPhone(value) {
        const digits = onlyDigits(value);
        return digits.length >= 10 && digits.length <= 11;
    }

    function isValidEmail(value) {
        const email = String(value || '').trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;
        return emailRegex.test(email);
    }

    function clearChecklistErrors() {
        if (!checklistContainer) return;

        const items = checklistContainer.querySelectorAll('.document-item');

        items.forEach(function (item) {
            item.classList.remove('document-item--error');
            item.style.border = '';
            item.style.background = '';

            const errorBox = item.querySelector('.document-item__error');
            if (errorBox) {
                errorBox.style.display = 'none';
                errorBox.innerText = '';
            }
        });
    }

    function renderChecklist(serviceId) {
        if (!checklistContainer) return;

        clearChecklistErrors();

        const docs = serviceDocs[serviceId] || [];

        if (!serviceId) {
            checklistContainer.innerHTML = '<div class="documents-checklist__empty text-muted">' + (messages.select_service || 'Selecione um serviço.') + '</div>';
            return;
        }

        if (!docs.length) {
            checklistContainer.innerHTML = '<div class="documents-checklist__empty text-muted">' + (messages.no_docs || 'Sem documentos.') + '</div>';
            return;
        }

        let html = '<div class="documents-checklist__grid">';

        docs.forEach(function (doc, index) {
            const slug = slugify(doc || ('documento_' + index));

            html += ''
                + '<div class="document-item" data-doc-slug="' + slug + '">'
                +   '<div class="document-item__header">'
                +       '<span class="document-item__label">' + doc + ' <span style="color:#a00;">*</span></span>'
                +   '</div>'
                +   '<input type="hidden" name="document_labels[' + slug + ']" value="' + String(doc).replace(/"/g, '&quot;') + '">'
                +   '<div class="document-item__body">'
                +       '<input type="file" name="documentos_checklist[' + slug + ']" class="form-control-file documento-checklist-input" data-label="' + String(doc).replace(/"/g, '&quot;') + '" accept=".jpg,.jpeg,.png,.pdf">'
                +       '<small class="text-muted d-block mt-2">Envie ' + doc + ' em JPG, PNG ou PDF.</small>'
                +       '<div class="document-item__error text-danger small mt-2" style="display:none;"></div>'
                +   '</div>'
                + '</div>';
        });

        html += '</div>';
        checklistContainer.innerHTML = html;
    }

    function validateChecklistFiles() {
        if (!checklistContainer) {
            return true;
        }

        clearChecklistErrors();

        const inputs = checklistContainer.querySelectorAll('.documento-checklist-input');

        if (!inputs.length) {
            return true;
        }

        let firstInvalidItem = null;
        const missingDocs = [];

        inputs.forEach(function (input) {
            if (!input.files || !input.files.length) {
                const label = input.getAttribute('data-label') || 'Documento obrigatório';
                missingDocs.push(label);

                const item = input.closest('.document-item');
                if (item) {
                    if (!firstInvalidItem) {
                        firstInvalidItem = item;
                    }

                    item.classList.add('document-item--error');
                    item.style.border = '1px solid #dc3545';
                    item.style.background = '#fff5f5';

                    const errorBox = item.querySelector('.document-item__error');
                    if (errorBox) {
                        errorBox.innerText = 'Este documento é obrigatório.';
                        errorBox.style.display = 'block';
                    }
                }
            }
        });

        if (missingDocs.length) {
            if (firstInvalidItem) {
                firstInvalidItem.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            return false;
        }

        return true;
    }

    function validateFormFields() {
        clearAllFieldErrors();
        clearFeedback();

        const nome = nomeInput ? nomeInput.value.trim() : '';
        const telefone = telefoneInput ? telefoneInput.value.trim() : '';
        const email = emailInput ? emailInput.value.trim() : '';
        const servico = servicoSelect ? servicoSelect.value : '';

        if (!nome) {
            showFieldError(nomeInput, 'Informe seu nome.');
            scrollToField(nomeInput);
            return false;
        }

        if (!telefone) {
            showFieldError(telefoneInput, 'Informe seu WhatsApp.');
            scrollToField(telefoneInput);
            return false;
        }

        if (!isValidPhone(telefone)) {
            showFieldError(telefoneInput, 'Informe um WhatsApp válido com DDD.');
            scrollToField(telefoneInput);
            return false;
        }

        if (!email) {
            showFieldError(emailInput, 'Informe seu e-mail.');
            scrollToField(emailInput);
            return false;
        }

        if (!isValidEmail(email)) {
            showFieldError(emailInput, 'Informe um e-mail válido.');
            scrollToField(emailInput);
            return false;
        }

        if (!servico) {
            showFieldError(servicoSelect, 'Selecione um serviço.');
            scrollToField(servicoSelect);
            return false;
        }

        if (!validateChecklistFiles()) {
            return false;
        }

        return true;
    }

    if (telefoneInput) {
        telefoneInput.setAttribute('inputmode', 'numeric');
        telefoneInput.setAttribute('autocomplete', 'tel');
        telefoneInput.setAttribute('placeholder', '(11) 99999-9999');

        telefoneInput.addEventListener('input', function () {
            this.value = applyPhoneMask(this.value);
            clearFieldError(this);
        });

        telefoneInput.addEventListener('blur', function () {
            this.value = applyPhoneMask(this.value);
        });
    }

    if (emailInput) {
        emailInput.setAttribute('autocomplete', 'email');
        emailInput.setAttribute('placeholder', 'seuemail@dominio.com');

        emailInput.addEventListener('input', function () {
            clearFieldError(this);
        });

        emailInput.addEventListener('blur', function () {
            const value = this.value.trim();

            if (!value) {
                showFieldError(this, 'Informe seu e-mail.');
                return;
            }

            if (!isValidEmail(value)) {
                showFieldError(this, 'Informe um e-mail válido.');
            } else {
                clearFieldError(this);
            }
        });
    }

    if (nomeInput) {
        nomeInput.addEventListener('input', function () {
            clearFieldError(this);
        });
    }

    if (servicoSelect) {
        servicoSelect.addEventListener('change', function () {
            clearFieldError(this);
            renderChecklist(this.value);
        });

        if (servicoSelect.value) {
            renderChecklist(servicoSelect.value);
        }
    }

    if (extraFilesInput) {
        extraFilesInput.addEventListener('change', function () {
            renderSelectedFiles(selectedExtraFiles, this.files);
        });
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        if (!window.wp_ajax_obj || !wp_ajax_obj.ajax_url) {
            showFeedback('Configuração AJAX não encontrada.', 'danger');
            return;
        }

        if (!validateFormFields()) {
            return;
        }

        const formData = new FormData(form);

        if (!formData.get('nonce') && wp_ajax_obj.nonce) {
            formData.append('nonce', wp_ajax_obj.nonce);
        }

        submitButton.disabled = true;
        submitButton.innerText = 'Enviando...';
        showFeedback(messages.sending || 'Enviando sua solicitação...', 'info');

        fetch(wp_ajax_obj.ajax_url, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        })
            .then(async function (response) {
                let data = null;

                try {
                    data = await response.json();
                } catch (e) {
                    throw new Error('Resposta inválida do servidor.');
                }

                if (!response.ok || !data.success) {
                    throw new Error(
                        data && data.data && data.data.message
                            ? data.data.message
                            : 'Não foi possível enviar sua solicitação.'
                    );
                }

                return data;
            })
            .then(function (data) {
                clearAllFieldErrors();
                clearChecklistErrors();
                showFeedback(data.data.message || 'Solicitação enviada com sucesso.', 'success');
                form.reset();
                renderSelectedFiles(selectedExtraFiles, []);
                renderChecklist('');
            })
            .catch(function (error) {
                showFeedback(error.message || 'Erro ao enviar formulário.', 'danger');
            })
            .finally(function () {
                submitButton.disabled = false;
                submitButton.innerText = 'Enviar Agora';
            });
    });
});