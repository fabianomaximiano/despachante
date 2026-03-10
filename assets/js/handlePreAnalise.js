/**
 * Manipulação do formulário de Pré-Análise v2.0
 * Inclui validação, feedback de upload e animação de envio.
 */
async function handlePreAnalise(event) {
    event.preventDefault();
    
    const form = event.target;
    const feedback = document.getElementById('formFeedback');
    const submitBtn = form.querySelector('button[type="submit"]');
    const formData = new FormData(form);
    
    // 1. Validação de Campos Obrigatórios (Nome, Telefone e Serviço)
    const nome = form.querySelector('input[name="nome"]').value;
    const telefone = form.querySelector('input[name="telefone"]').value;
    const servico = form.querySelector('select[name="servico"]').value;

    if (!nome || !telefone || !servico) {
        feedback.innerHTML = '<div class="alert alert-warning shadow-sm">Por favor, preencha todos os campos obrigatórios.</div>';
        return;
    }

    // 2. Validação de Tamanho de Arquivos (Limite de 2MB por arquivo)
    const files = form.querySelector('input[type="file"]').files;
    for (let file of files) {
        if (file.size > 2 * 1024 * 1024) {
            feedback.innerHTML = `<div class="alert alert-danger shadow-sm">O arquivo <strong>${file.name}</strong> é muito grande (máx. 2MB).</div>`;
            return;
        }
    }

    // 3. Feedback Visual de Envio
    const originalBtnText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Enviando...';
    submitBtn.disabled = true;
    feedback.innerHTML = '';

    try {
        // Envio via Fetch para o endpoint AJAX do WordPress
        const response = await fetch(wp_ajax_obj.ajax_url + '?action=process_pre_analise', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();
        
        if (result.success) {
            feedback.innerHTML = `<div class="alert alert-success shadow-sm border-0"><i class="fas fa-check-circle mr-2"></i> ${result.data || 'Enviado com sucesso!'}</div>`;
            form.reset();
            
            // Reseta o texto da área de upload
            const fileLabel = document.getElementById('file-label');
            if (fileLabel) fileLabel.innerText = "Arraste arquivos aqui ou clique para selecionar";
        } else {
            throw new Error(result.data || 'Erro no processamento.');
        }

    } catch (error) {
        feedback.innerHTML = `<div class="alert alert-danger shadow-sm border-0"><i class="fas fa-exclamation-triangle mr-2"></i> Erro ao enviar: ${error.message}</div>`;
    } finally {
        // Restaura o botão
        submitBtn.innerHTML = originalBtnText;
        submitBtn.disabled = false;
    }
}

// Inicialização e Listeners
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('formPreAnalise');
    const fileInput = document.getElementById('fileInput');
    const fileLabel = document.getElementById('file-label');

    if (form) {
        form.addEventListener('submit', handlePreAnalise);
    }

    // Atualiza o nome dos arquivos na interface ao selecionar
    if (fileInput && fileLabel) {
        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                fileLabel.innerHTML = `<strong>${this.files.length} arquivo(s) selecionado(s)</strong>`;
                fileLabel.closest('.upload-container').style.borderColor = '#2da44e';
            } else {
                fileLabel.innerText = "Arraste arquivos aqui ou clique para selecionar";
                fileLabel.closest('.upload-container').style.borderColor = '#ced4da';
            }
        });
    }
});