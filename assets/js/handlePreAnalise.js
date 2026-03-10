document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formPreAnalise');
    const feedback = document.getElementById('formFeedback');
    const submitButton = document.getElementById('submitPreAnalise');
    const servicoSelect = document.getElementById('servicoSelect');
    const checklistContainer = document.getElementById('serviceDocumentsChecklist');
    const extraFilesInput = document.getElementById('extraFilesInput');
    const selectedExtraFiles = document.getElementById('selectedExtraFiles');

    if (!form) {
        return;
    }

    const serviceDocs = (window.wp_ajax_obj && wp_ajax_obj.service_docs) ? wp_ajax_obj.service_docs : {};
    const messages = (window.wp_ajax_obj && wp_ajax_obj.messages) ? wp_ajax_obj.messages : {};

    function showFeedback(message, type) {
        feedback.innerHTML = '<div class="alert alert-' + type + ' mb-0">' + message + '</div>';
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

    function renderChecklist(serviceId) {
        if (!checklistContainer) return;

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

    if (servicoSelect) {
        servicoSelect.addEventListener('change', function () {
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
                    throw new Error(data && data.data && data.data.message ? data.data.message : 'Não foi possível enviar sua solicitação.');
                }

                return data;
            })
            .then(function (data) {
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