import './leitor-estatuto';

const botaoMenu = document.querySelector('[data-menu-alternar]');
const menu = botaoMenu ? document.getElementById(botaoMenu.getAttribute('aria-controls')) : null;

function definirMenuAberto(aberto) {
    botaoMenu.setAttribute('aria-expanded', String(aberto));
    botaoMenu.setAttribute('aria-label', aberto ? 'Fechar menu' : 'Abrir menu');
    menu.classList.toggle('hidden', !aberto);
}

if (botaoMenu && menu) {
    botaoMenu.addEventListener('click', () => {
        definirMenuAberto(botaoMenu.getAttribute('aria-expanded') !== 'true');
    });

    document.addEventListener('keydown', (evento) => {
        if (evento.key === 'Escape' && botaoMenu.getAttribute('aria-expanded') === 'true') {
            definirMenuAberto(false);
            botaoMenu.focus();
        }
    });

    window.matchMedia('(min-width: 64rem)').addEventListener('change', (consulta) => {
        if (consulta.matches) {
            definirMenuAberto(false);
        }
    });
}
