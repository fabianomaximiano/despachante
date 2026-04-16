document.addEventListener('DOMContentLoaded', function () {
    // ===============================
    // ANIMAÇÃO AO SCROLL
    // ===============================
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

    document.querySelectorAll('.animate-fade-up').forEach(el => {
        observer.observe(el);
    });

    // ===============================
    // FAQ / ACCORDION
    // ===============================
   const accordionButtons = document.querySelectorAll('[data-toggle="collapse"], [data-bs-toggle="collapse"]');

document.querySelectorAll('.collapse').forEach(el => {
    el.style.maxHeight = null;
});

accordionButtons.forEach(button => {
        button.addEventListener('click', function () {
            const targetSelector =
                this.getAttribute('data-target') ||
                this.getAttribute('data-bs-target');

            if (!targetSelector) return;

            const target = document.querySelector(targetSelector);
            if (!target) return;

            const isOpen = target.classList.contains('show');

            document.querySelectorAll('.collapse.show').forEach(el => {
                el.classList.remove('show');
                el.style.maxHeight = null;
            });

            accordionButtons.forEach(btn => {
                btn.classList.add('collapsed');
                btn.setAttribute('aria-expanded', 'false');
            });

            if (!isOpen) {
                target.classList.add('show');
                target.style.maxHeight = target.scrollHeight + 'px';
                this.classList.remove('collapsed');
                this.setAttribute('aria-expanded', 'true');
            }
        });
    });


    // ===============================
    // MENU MOBILE
    // ===============================
    const navbarToggler = document.querySelector('.navbar-toggler');

    if (navbarToggler) {
        navbarToggler.addEventListener('click', function () {
            const targetSelector =
                this.getAttribute('data-target') ||
                this.getAttribute('data-bs-target');

            if (!targetSelector) return;

            const target = document.querySelector(targetSelector);
            if (target) {
                target.classList.toggle('show');
            }
        });
    }

    console.log('✅ Script sem jQuery carregado com sucesso');
});