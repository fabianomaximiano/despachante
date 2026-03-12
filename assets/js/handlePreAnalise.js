document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formPreAnalise');
    const feedback = document.getElementById('formFeedback');
    const submitButton = document.getElementById('submitPreAnalise');
    const servicoSelect = document.getElementById('servicoSelect');
    const tipoClienteSelect = document.getElementById('tipoClienteSelect');
    const objetivoAtendimentoSelect = document.getElementById('objetivoAtendimentoSelect');
    const checklistWrapper = document.getElementById('checklistDocumentsWrapper');
    const checklistDescription = document.getElementById('checklistDocumentsDescription');
    const checklistContainer = document.getElementById('serviceDocumentsChecklist');
    const extraDocumentsWrapper = document.getElementById('extraDocumentsWrapper');
    const extraFilesInput = document.getElementById('extraFilesInput');
    const selectedExtraFiles = document.getElementById('selectedExtraFiles');
    const telefoneInput = form ? form.querySelector('input[name="telefone"]') : null;

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
        return (value || '').replace(/\D+/g, '');
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

    function setupPhoneMask() {
        if (!telefoneInput) return;

        telefoneInput.addEventListener('input', function () {
            this.value = applyPhoneMask(this.value);
        });

        telefoneInput.addEventListener('blur', function () {
            this.value = applyPhoneMask(this.value);
        });
    }

    function getTipoCliente() {
        return tipoClienteSelect ? tipoClienteSelect.value : 'pf';
    }

    function getObjetivoAtendimento() {
        return objetivoAtendimentoSelect ? objetivoAtendimentoSelect.value : '';
    }

    function mustShowDocumentsSection() {
        return getObjetivoAtendimento() === 'enviar_documentos';
    }

    function getPfDocuments(serviceId) {
        return serviceDocs[serviceId] || [];
    }

    function getPjDocuments(serviceId) {
        const pfDocs = getPfDocuments(serviceId);

        if (!pfDocs.length) {
            return [
                'CNPJ',
                'Contrato social',
                'Documento do responsável',
                'CRLV',
                'Comprovante de pagamento',
                'Procuração'
            ];
        }

        const mapped = [];
        const seen = new Set();

        pfDocs.forEach(function (doc) {
            const normalized = (doc || '').toString().trim().toLowerCase();
            let replacement = doc;

            if (normalized === 'cpf') {
                replacement = 'CNPJ';
            } else if (normalized === 'rg') {
                replacement = 'Contrato social';
            } else if (normalized === 'cnh') {
                replacement = 'Documento do responsável';
            } else if (normalized === 'comprovante de residência') {
                replacement = 'Documento do responsável';
            }

            const key = replacement.toLowerCase();
            if (!seen.has(key)) {
                seen.add(key);
                mapped.push(replacement);
            }
        });

        if (!seen.has('cnpj')) {
            mapped.unshift('CNPJ');
        }

        if (!seen.has('contrato social')) {
            mapped.push('Contrato social');
        }

        if (!seen.has('documento do responsável')) {
            mapped.push('Documento do responsável');
        }

        return Array.from(new Set(mapped));
    }

    function getDocumentsForCurrentSelection(serviceId) {
        if (!serviceId) {
            return [];
        }

        return getTipoCliente() === 'pj'
            ? getPjDocuments(serviceId)
            : getPfDocuments(serviceId);
    }

    function updateDocumentsVisibility() {
        const shouldShow = mustShowDocumentsSection();

        if (checklistWrapper) {
            checklistWrapper.style.display = shouldShow ? 'block' : 'none';
        }

        if (extraDocumentsWrapper) {
            extraDocumentsWrapper.style.display = shouldShow ? 'block' : 'none';
        }

        if (!shouldShow && checklistContainer) {
            checklistContainer.innerHTML = '<div class="documents-checklist__empty text-muted">Os documentos só precisam ser enviados quando o objetivo for "Quero enviar documentos para análise".</div>';
        }

        if (checklistDescription) {
            checklistDescription.textContent = shouldShow
                ? 'Selecione um serviço e envie os documentos necessários para análise.'
                : 'Os documentos serão solicitados apenas quando você escolher a opção de envio para análise.';
        }
    }

    function renderChecklist(serviceId) {
        if (!checklistContainer) return;

        if (!mustShowDocumentsSection()) {
            updateDocumentsVisibility();
            return;
        }

        const docs = getDocumentsForCurrentSelection(serviceId);

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
                + '<div class="document-item">'
                +   '<div class="document-item__header">'
                +       '<span class="document-item__label">' + doc + '</span>'
                +   '</div>'
                +   '<input type="hidden" name="document_labels[' + slug + ']" value="' + doc.replace(/"/g, '&quot;') + '">'
                +   '<div class="document-item__body">'
                +       '<input type="file" name="documentos_checklist[' + slug + ']" class="form-control-file" accept=".jpg,.jpeg,.png,.pdf">'
                +       '<small class="text-muted d-block mt-2">Envie ' + doc + ' em JPG, PNG ou PDF.</small>'
                +   '</div>'
                + '</div>';
        });

        html += '</div>';
        checklistContainer.innerHTML = html;
    }

    function refreshDocumentsArea() {
        updateDocumentsVisibility();
        renderChecklist(servicoSelect ? servicoSelect.value : '');
    }

    function resetFormState() {
        form.reset();

        if (tipoClienteSelect) {
            tipoClienteSelect.value = 'pf';
        }

        if (objetivoAtendimentoSelect) {
            objetivoAtendimentoSelect.value = '';
        }

        if (servicoSelect) {
            servicoSelect.value = '';
        }

        if (telefoneInput) {
            telefoneInput.value = '';
        }

        renderSelectedFiles(selectedExtraFiles, []);
        refreshDocumentsArea();
    }

    if (servicoSelect) {
        servicoSelect.addEventListener('change', function () {
            renderChecklist(this.value);
        });
    }

    if (tipoClienteSelect) {
        tipoClienteSelect.addEventListener('change', function () {
            renderChecklist(servicoSelect ? servicoSelect.value : '');
        });
    }

    if (objetivoAtendimentoSelect) {
        objetivoAtendimentoSelect.addEventListener('change', function () {
            refreshDocumentsArea();
        });
    }

    if (extraFilesInput) {
        extraFilesInput.addEventListener('change', function () {
            renderSelectedFiles(selectedExtraFiles, this.files);
        });
    }

    setupPhoneMask();
    refreshDocumentsArea();

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        clearFeedback();

        if (!window.wp_ajax_obj || !wp_ajax_obj.ajax_url) {
            showFeedback('Configuração AJAX não encontrada.', 'danger');
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
                const data = await response.json();

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
                resetFormState();
                showFeedback(data.data.message || 'Solicitação enviada com sucesso.', 'success');
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