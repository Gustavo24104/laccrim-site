import { Editor } from '@tiptap/core';
import StarterKit from '@tiptap/starter-kit';

const COMANDOS = [
    { nome: 'negrito', rotulo: 'Negrito', texto: 'N', classes: 'font-bold', ativo: (e) => e.isActive('bold'), executar: (c) => c.toggleBold() },
    { nome: 'italico', rotulo: 'Itálico', texto: 'I', classes: 'italic', ativo: (e) => e.isActive('italic'), executar: (c) => c.toggleItalic() },
    { nome: 'sublinhado', rotulo: 'Sublinhado', texto: 'S', classes: 'underline', ativo: (e) => e.isActive('underline'), executar: (c) => c.toggleUnderline() },
    { nome: 'subtitulo', rotulo: 'Subtítulo', texto: 'T', classes: 'font-serif font-bold', ativo: (e) => e.isActive('heading', { level: 3 }), executar: (c) => c.toggleHeading({ level: 3 }) },
    { nome: 'lista', rotulo: 'Lista com marcadores', texto: '•', ativo: (e) => e.isActive('bulletList'), executar: (c) => c.toggleBulletList() },
    { nome: 'lista-numerada', rotulo: 'Lista numerada', texto: '1.', ativo: (e) => e.isActive('orderedList'), executar: (c) => c.toggleOrderedList() },
    { nome: 'citacao', rotulo: 'Citação', texto: '“', ativo: (e) => e.isActive('blockquote'), executar: (c) => c.toggleBlockquote() },
    { nome: 'link', rotulo: 'Link', texto: 'Link', ativo: (e) => e.isActive('link'), executar: alternarLink },
    { nome: 'desfazer', rotulo: 'Desfazer', texto: '↶', executar: (c) => c.undo() },
    { nome: 'refazer', rotulo: 'Refazer', texto: '↷', executar: (c) => c.redo() },
];

function alternarLink(cadeia, editor) {
    if (editor.isActive('link')) {
        return cadeia.unsetLink();
    }

    const endereco = window.prompt('Endereço do link (https://… ou mailto:…)');

    if (!endereco) {
        return cadeia;
    }

    return cadeia.extendMarkRange('link').setLink({ href: endereco.trim() });
}

function criarBarra(rotuloCampo) {
    const barra = document.createElement('div');
    barra.setAttribute('role', 'toolbar');
    barra.setAttribute('aria-label', `Formatação: ${rotuloCampo}`);
    barra.className = 'flex flex-wrap gap-1 border-b border-linha bg-fundo-alt p-2';

    const botoes = COMANDOS.map((comando) => {
        const botao = document.createElement('button');
        botao.type = 'button';
        botao.textContent = comando.texto;
        botao.title = comando.rotulo;
        botao.setAttribute('aria-label', comando.rotulo);
        botao.className = `min-w-9 rounded-md px-2 py-1 text-sm text-titulo hover:bg-linha aria-pressed:bg-primaria aria-pressed:text-white ${comando.classes ?? ''}`;

        if (comando.ativo) {
            botao.setAttribute('aria-pressed', 'false');
        }

        barra.append(botao);

        return { botao, comando };
    });

    return { barra, botoes };
}

function iniciarEditor(conteiner) {
    const entrada = conteiner.querySelector('[data-editor-entrada]');
    const rotulo = document.getElementById(`${entrada.id}-rotulo`);
    const { barra, botoes } = criarBarra(rotulo?.textContent.trim() ?? 'texto');
    const area = document.createElement('div');

    conteiner.prepend(barra);
    conteiner.append(area);
    entrada.hidden = true;

    const editor = new Editor({
        element: area,
        content: entrada.value,
        extensions: [
            StarterKit.configure({
                code: false,
                codeBlock: false,
                horizontalRule: false,
                heading: { levels: [3, 4] },
                link: { openOnClick: false, defaultProtocol: 'https', protocols: ['http', 'https', 'mailto'] },
            }),
        ],
        editorProps: {
            attributes: {
                role: 'textbox',
                'aria-multiline': 'true',
                'aria-labelledby': rotulo?.id ?? '',
                'aria-describedby': entrada.getAttribute('aria-describedby') ?? '',
                class: 'min-h-48 px-3 py-2 text-texto focus:outline-none [&_a]:underline [&_blockquote]:border-l-4 [&_blockquote]:border-destaque [&_blockquote]:pl-3 [&_h3]:font-serif [&_h3]:text-lg [&_h3]:font-semibold [&_ol]:list-decimal [&_ol]:pl-6 [&_p]:my-2 [&_ul]:list-disc [&_ul]:pl-6',
            },
        },
        onUpdate: ({ editor: atual }) => {
            entrada.value = atual.isEmpty ? '' : atual.getHTML();
        },
        onTransaction: ({ editor: atual }) => {
            botoes.forEach(({ botao, comando }) => {
                if (comando.ativo) {
                    botao.setAttribute('aria-pressed', String(comando.ativo(atual)));
                }
            });
        },
    });

    botoes.forEach(({ botao, comando }) => {
        botao.addEventListener('click', () => {
            comando.executar(editor.chain().focus(), editor).run();
        });
    });

    rotulo?.addEventListener('click', () => editor.commands.focus());
}

export function iniciarEditoresRicos() {
    document.querySelectorAll('[data-editor-rico]').forEach(iniciarEditor);
}
