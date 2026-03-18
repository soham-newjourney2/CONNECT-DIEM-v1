document.addEventListener('DOMContentLoaded', () => {
    const body = document.body;
    const modeBtn = document.getElementById('modeToggle');
    const mobileToggle = document.getElementById('mobileMenuToggle');
    const mobileMenu = document.getElementById('mobileMenu');

    // ---------------- Dark/Light Mode ----------------
    const savedMode = localStorage.getItem('mode');
    if(savedMode){
        body.classList.remove('light-mode','dark-mode');
        body.classList.add(savedMode + '-mode');
    }

    // Set initial button icon
    modeBtn.textContent = body.classList.contains('dark-mode') ? '☀️' : '🌙';

    modeBtn.addEventListener('click', () => {
        if(body.classList.contains('light-mode')){
            body.classList.remove('light-mode');
            body.classList.add('dark-mode');
            localStorage.setItem('mode','dark');
            modeBtn.textContent = '☀️';
        } else {
            body.classList.remove('dark-mode');
            body.classList.add('light-mode');
            localStorage.setItem('mode','light');
            modeBtn.textContent = '🌙';
        }
    });

    // ---------------- Mobile Menu Toggle ----------------
    mobileToggle.addEventListener('click', () => {
        mobileMenu.classList.toggle('active');
    });
});
