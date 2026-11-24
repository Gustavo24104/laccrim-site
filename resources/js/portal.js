const menuLateral = document.querySelector('[data-sidebar]');

if (menuLateral) {
    const fundo = document.querySelector('[data-sidebar-fundo]');
    const botaoAbrir = document.querySelector('[data-sidebar-abrir]');
    const botaoFechar = document.querySelector('[data-sidebar-fechar]');
    const botaoRecolher = document.querySelector('[data-sidebar-recolher]');
    const chaveRecolhida = 'portal.menu-lateral.recolhido';

    const definirAberto = (aberto) => {
        menuLateral.dataset.aberta = String(aberto);
        fundo?.classList.toggle('hidden', !aberto);
        botaoAbrir?.setAttribute('aria-expanded', String(aberto));
    };

    const definirRecolhido = (recolhido) => {
        menuLateral.dataset.recolhida = String(recolhido);
        botaoRecolher?.setAttribute('aria-expanded', String(!recolhido));
        botaoRecolher?.setAttribute('aria-label', recolhido ? 'Expandir menu lateral' : 'Recolher menu lateral');
        localStorage.setItem(chaveRecolhida, String(recolhido));
    };

    definirRecolhido(localStorage.getItem(chaveRecolhida) === 'true');

    botaoAbrir?.addEventListener('click', () => definirAberto(true));
    botaoFechar?.addEventListener('click', () => definirAberto(false));
    fundo?.addEventListener('click', () => definirAberto(false));
    botaoRecolher?.addEventListener('click', () => definirRecolhido(menuLateral.dataset.recolhida !== 'true'));

    document.addEventListener('keydown', (evento) => {
        if (evento.key === 'Escape' && menuLateral.dataset.aberta === 'true') {
            definirAberto(false);
            botaoAbrir?.focus();
        }
    });
}

const botaoNotificacoes = document.querySelector('[data-notificacoes-alternar]');
const painelNotificacoes = document.querySelector('[data-notificacoes-painel]');

if (botaoNotificacoes && painelNotificacoes) {
    const definirPainelAberto = (aberto) => {
        botaoNotificacoes.setAttribute('aria-expanded', String(aberto));
        painelNotificacoes.classList.toggle('hidden', !aberto);
    };

    botaoNotificacoes.addEventListener('click', () => {
        definirPainelAberto(botaoNotificacoes.getAttribute('aria-expanded') !== 'true');
    });

    document.addEventListener('click', (evento) => {
        if (!botaoNotificacoes.contains(evento.target) && !painelNotificacoes.contains(evento.target)) {
            definirPainelAberto(false);
        }
    });

    document.addEventListener('keydown', (evento) => {
        if (evento.key === 'Escape' && botaoNotificacoes.getAttribute('aria-expanded') === 'true') {
            definirPainelAberto(false);
            botaoNotificacoes.focus();
        }
    });
}
