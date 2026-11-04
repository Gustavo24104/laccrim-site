const ESCALA_MINIMA = 1;
const ESCALA_MAXIMA = 2.5;
const PASSO_ESCALA = 0.25;

function iniciarLeitor(leitor) {
    const palco = leitor.querySelector('[data-leitor-palco]');
    const quadro = leitor.querySelector('[data-leitor-quadro]');
    const controles = leitor.querySelector('[data-leitor-controles]');
    const ampliar = leitor.querySelector('[data-leitor-ampliar]');
    const reduzir = leitor.querySelector('[data-leitor-reduzir]');

    if (!palco || !quadro || !controles || !ampliar || !reduzir) {
        return;
    }

    // O PDF permanece visível. O clique revela o zoom e entrega a rolagem ao arquivo.
    const ativar = document.createElement('button');
    ativar.type = 'button';
    ativar.className = 'absolute inset-0 z-10 cursor-pointer bg-transparent';
    ativar.setAttribute('aria-label', leitor.dataset.rotuloAtivar || 'Ativar visualização avançada');
    palco.append(ativar);

    let escala = ESCALA_MINIMA;
    let avancado = false;
    let larguraBase = 0;
    let alturaBase = 0;

    function aplicarEscala() {
        quadro.style.width = `${larguraBase * escala}px`;
        quadro.style.height = `${alturaBase * escala}px`;
        ampliar.disabled = escala >= ESCALA_MAXIMA;
        reduzir.disabled = escala <= ESCALA_MINIMA;
    }

    function ativarModoAvancado() {
        if (avancado) {
            return;
        }

        avancado = true;
        ativar.remove();
        larguraBase = palco.clientWidth;
        alturaBase = palco.clientHeight;
        controles.hidden = false;
        palco.classList.remove('overflow-hidden');
        palco.classList.add('overflow-auto');
        aplicarEscala();
        ampliar.focus();
    }

    ativar.addEventListener('click', ativarModoAvancado);

    ampliar.addEventListener('click', () => {
        escala = Math.min(ESCALA_MAXIMA, Math.round((escala + PASSO_ESCALA) * 100) / 100);
        aplicarEscala();
    });

    reduzir.addEventListener('click', () => {
        escala = Math.max(ESCALA_MINIMA, Math.round((escala - PASSO_ESCALA) * 100) / 100);
        aplicarEscala();
    });

    palco.addEventListener('wheel', (evento) => {
        if (!avancado || evento.target.closest('iframe')) {
            return;
        }

        const podeRolar = palco.scrollHeight > palco.clientHeight || palco.scrollWidth > palco.clientWidth;

        if (!podeRolar) {
            return;
        }

        evento.preventDefault();
        palco.scrollTop += evento.deltaY;
        palco.scrollLeft += evento.deltaX;
    }, { passive: false });
}

document.querySelectorAll('[data-leitor-pdf][data-tem-arquivo="1"]').forEach(iniciarLeitor);
