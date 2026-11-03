# Spec 01 – Vitrine pública e página inicial: Requisitos

Status: **aprovada** (30/10/2026)

## Escopo

**Dentro**

- Layout público compartilhado: cabeçalho responsivo (menu hambúrguer) e rodapé institucional.
- Página inicial completa (seção 4.2 do documento de requisitos).
- Rotas com URLs amigáveis para as demais abas do menu, exibindo página "Em construção" com a mesma identidade visual. Navegação na mesma aba.
- Conteúdo lido por uma camada de repositório (hoje `config/laccrim.php`), substituível pelo CMS sem alterar views.

**Fora**

- CMS, autenticação de administradores e fluxo de aprovação.
- Banco de dados de notícias/eventos, inscrições e certificados.
- Conteúdo real de Sobre Nós, Notícias, Eventos e páginas legais.
- Carrossel, mapa do site e Open Graph editável por página (o layout já reserva os metadados).

## Requisitos e critérios de aceite

**R1 – Cabeçalho**
- Presente em todas as páginas públicas, com espaço para logotipo e o texto "LACCRIM/UFU".
- Itens: Página Inicial, Sobre Nós, Notícias, Editais e Atas, Eventos, Protocolo Virtual e o botão destacado Portal da Liga.
- Os itens vêm do repositório: incluir ou remover um item na fonte de dados o faz aparecer ou sumir sem tocar no template.
- O item da página atual tem `aria-current="page"` e indicação visual dourada.
- Abaixo de 1024px vira hambúrguer com `aria-expanded`/`aria-controls`; Esc fecha; operável por teclado.

**R2 – Rodapé**
- Endereço da FADIR/UFU, e-mail `laccrim@fadir.ufu.br` (link `mailto:`), Instagram, selos de parceiros (NUAVIDAS, Projeto Livre, PROEXC, UFMT, UFMA, FBDG) e links de Política de Privacidade e Termos de Uso.
- Todos os dados vêm da configuração. Selos são espaços reservados com o nome em texto, sem logotipos inventados.

**R3 – Hero**
- Fundo azul-marinho, conteúdo à esquerda.
- Identificação "UNIVERSIDADE FEDERAL DE UBERLÂNDIA".
- Título "O estudo crítico das Ciências Criminais a serviço da justiça." com "Ciências Criminais" em dourado (trecho configurável).
- Texto complementar sobre Direito Penal, Processual Penal e Criminologia.
- Botões "Conheça nossa Liga" (Sobre Nós) e "Protocolo Virtual".

**R4 – "Três pilares, um propósito"**
- Cartões Ensino, Pesquisa e Extensão com ícone, título e descrição breve.
- Empilhados no celular, lado a lado a partir de 768px.

**R5 – Seções dinâmicas**
- Últimas notícias e editais, próximos eventos, publicações em destaque, acesso rápido ao Protocolo Virtual e botão "Conheça os integrantes".
- Coleção vazia exibe aviso amigável, nunca espaço em branco.
- Itens de exemplo nesta fase. Datas em dd/mm/aaaa no fuso America/Sao_Paulo; armazenamento em UTC.
- Próximos eventos só exibe eventos que ainda não começaram.

**R6 – Chamada final** "Participe da LACCRIM".

**R7 – Seções configuráveis**: ordem e exibição/ocultação vêm da fonte de dados; seção oculta não é renderizada.

**R8 – Identidade visual**
- Cores como variáveis globais no `@theme` do Tailwind: `--color-primaria #0A192F`, `--color-destaque #C59B27`, `--color-fundo #FFFFFF`, `--color-fundo-alt #F7FAFC`, `--color-texto #2D3748`.
- O layout sobrescreve as variáveis em `:root` com valores da configuração (gancho do CMS). Apenas cores hexadecimais válidas são aceitas.
- Títulos em serifada (Playfair Display), corpo em Instrument Sans.
- Bordas suaves, sombras leves, sem imagens de tribunais ou martelos.

**R9 – Acessibilidade e idioma**
- `lang="pt-BR"`, link "Pular para o conteúdo", foco visível dourado, textos alternativos, contraste WCAG 2.1 AA.
- Botões dourados usam texto azul-marinho (≈ 6,8:1). Texto branco sobre dourado (≈ 2,6:1) reprova e não é usado.

**R10 – Navegação**: links `<a href="{{ route(...) }}">`, carregados na mesma aba.
