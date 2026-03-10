document.addEventListener('DOMContentLoaded', function() {
    // 1. Lógica de animação ao scroll (Intersection Observer)
    const observerOptions = {
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, observerOptions);

    // Aplica a animação em elementos com a classe
    document.querySelectorAll('.animate-fade-up').forEach(el => {
        observer.observe(el);
    });

    console.log("Landing Page Despachante Digital carregada!");
});