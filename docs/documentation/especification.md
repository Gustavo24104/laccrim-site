# LACCRIM/UFU — Especificação Completa do Portal Web e Sistema de Gestão

Liga Acadêmica de Ciências Criminais · Faculdade de Direito Professor Jacy de Assis (FADIR) · Universidade Federal de Uberlândia (UFU)

## Como ler este documento

Este documento descreve o projeto completo, como um único desenvolvimento coeso. Ele não divide o escopo em MVP e fases posteriores. A equipe de TI avaliará, por conta própria, o que é viável, em que ordem construir e o que ajustar. Quando uma funcionalidade depender de serviço externo ou de autorização institucional, isso é indicado na [seção 14](#14-dependências-externas-e-itens-a-confirmar).

**Convenções**

| Termo | Significado |
| --- | --- |
| DEVE | Requisito obrigatório |
| DEVERIA | Recomendação forte |
| PODE | Opcional |
| [Premissa] | Decisão assumida na redação para fechar lacunas; pode ser confirmada ou alterada pela Liga |

---

## 1. Visão geral

### 1.1 Propósito

A LACCRIM é um programa de extensão da UFU, vinculado à FADIR e à PROEXC. A plataforma centraliza a divulgação científica, a publicação de editais e atas, as inscrições em eventos, o protocolo eletrônico de solicitações e a gestão interna da Liga (tarefas, diretorias, frequência e certificados).

### 1.2 Cinco áreas integradas

| Área | Público | Função |
| --- | --- | --- |
| Site institucional público | Visitantes, estudantes, pesquisadores | Divulgação, publicações, eventos, verificação de certificados |
| Protocolo Virtual (Mini-SEI) | Qualquer pessoa | Solicitações formais com número único, tramitação e peticionamento |
| Portal do Ligante | Membros autenticados | Dashboard, tarefas, aprovações, painéis de diretoria e coordenação, frequência |
| Administração do Site (CMS) | Presidência e Diretoria de Comunicação | Edição de todo o conteúdo e estrutura do site, sempre com aprovação |
| Administração do Sistema | Presidência e Equipe de TI | Infraestrutura, usuários técnicos, logs e configurações globais, sem poder de aprovar conteúdo |

### 1.3 Princípios obrigatórios

- **Persistência real.** Nenhuma tela pode simular operação. Tudo o que aparece como concluído deve estar gravado no banco de dados.
- **Segurança no servidor.** Toda regra de permissão é validada no servidor, nunca apenas na interface.
- **Aprovação da Presidência sobre toda alteração do site público.** Sem exceções ([seção 7](#7-módulo-de-administração-do-site-cms)).
- **Auditoria.** Operações relevantes deixam registro imutável com autor, data, hora e valores anterior e novo.
- **Configurável sem código.** Textos, menus, categorias, tipos de solicitação e status são editáveis por interface.
- **LGPD desde a concepção.** Coleta mínima, acesso restrito e prazos de retenção ([seção 11](#11-segurança-privacidade-e-lgpd)).
- **Sem aparência de sistema oficial.** O Protocolo Virtual é ferramenta administrativa da Liga e não se apresenta como SEI, sistema da UFU ou de órgão público.
- **Sem conteúdo inventado.** Nomes, fotos, titulações e cargos demonstrativos devem ser claramente identificados como provisórios.

### 1.4 Decisões consolidadas

| Tema | Decisão |
| --- | --- |
| Diretorias | As 4 do Estatuto (Pesquisa, Extensão, Financeira, Comunicação), mais Gestão de Pessoas vinculada à Vice-Presidência |
| Coordenações | Cada uma tem perfil próprio e painel próprio |
| Administradores do Site | Presidente e Diretor de Comunicação (Comunicação edita, Presidência aprova) |
| Aprovação | Toda alteração no site público passa pela Presidência; a Vice-Presidência pode aprovar na ausência do Presidente |
| Tecnologia da Informação | Perfil técnico "Administrador do Sistema", fora da hierarquia, sem aprovar conteúdo |
| CMS | Pacote completo: textos, menu, páginas, documentos, banners, seções da home, tema, categorias, tipos de solicitação e status |
| Editor de texto | Visual simples (negrito, títulos, listas, links, imagens) |
| Versões | Histórico com restauração em um clique (a restauração também passa por aprovação) |
| Tipos de solicitação | Os 9 tipos da v2 |
| Denúncia anônima | Sem nome nem e-mail; chave de acompanhamento exibida uma única vez |
| Roteamento de protocolos | Todos entram na Presidência, que encaminha manualmente |
| Tarefas | Atribuição livre entre quaisquer membros, com aceite ou recusa motivada |
| Frequência | Controle de presença em reuniões e eventos, com relatório |
| Certificados | Emissão automática em PDF com código de verificação pública |
| Escopo | Tudo, sem corte e sem divisão em fases |

### 1.5 Divergências resolvidas entre os documentos anteriores

- **Cor azul-marinho:** o Escopo Sintético usava `#1B2A4A` e a v2 usava `#0A192F`. Adota-se `#0A192F` como valor padrão, configurável por variável de tema. **[Premissa]** O logotipo e a paleta definitivos estão em produção pelo Marketing; a troca deve exigir somente a alteração de variáveis ([seção 2](#2-identidade-visual-e-padrões-de-interface)).
- **Diretorias:** a v2 listava Acadêmica, Administrativa, Comunicação e RH. Prevalece o Estatuto: Pesquisa, Extensão, Financeira e Comunicação. Gestão de Pessoas fica com a Vice-Presidência.
- **Tipos de solicitação:** prevalecem os 9 da v2. Os tipos do Prompt (Dúvida, Parceria institucional, Inscrição em atividade) ficam cobertos por Pedido de Informação, Sugestão e Solicitação Geral, e o administrador pode criar tipos adicionais.
- **Protocolos:** a v2 sugeria atribuição a relator ou diretoria; a decisão final é a entrada única pela Presidência, que encaminha.

---

## 2. Identidade visual e padrões de interface

A referência visual são os protótipos de navegação enviados (site público em azul-marinho e dourado, portal com menu lateral azul-marinho e conteúdo claro). A equipe de TI deve observar hierarquia tipográfica, espaçamento, cartões, filtros, modais e o quadro Kanban dos vídeos, melhorando usabilidade, acessibilidade e segurança.

| Token | Valor padrão | Uso |
| --- | --- | --- |
| `--cor-primaria` | `#0A192F` | Cabeçalhos, banners, menu lateral, cartões institucionais |
| `--cor-destaque` | `#C59B27` | Botões de ação, bordas ativas, selos, expressão em destaque no título |
| `--cor-fundo` | `#FFFFFF` / `#F7FAFC` | Áreas de leitura, formulários, modais |
| `--cor-texto` | `#2D3748` | Texto principal |

- Todas as cores DEVEM ser variáveis globais (CSS variables ou configuração Tailwind), alteráveis também pelo CMS ([seção 7](#7-módulo-de-administração-do-site-cms)).
- Tipografia: fonte serifada elegante para títulos e sans-serif legível para o corpo, conforme os protótipos.
- Ícones discretos, bordas suaves, sombras leves, layout limpo e sem imagens genéricas de tribunais ou martelos.
- **Logotipo oficial:** criar espaço de inserção e não inventar logotipo definitivo. O logotipo é configurável pelo CMS.
- Contraste mínimo WCAG 2.1 AA, navegação por teclado, foco visível, rótulos em todos os campos e textos alternativos em imagens.
- Idioma pt-BR, datas em `dd/mm/aaaa`, fuso `America/Sao_Paulo` na exibição e armazenamento em UTC no banco.
- O site público e o portal devem parecer partes do mesmo sistema.

---

## 3. Hierarquia institucional e permissões

### 3.1 Estrutura conforme o Estatuto

| Instância | Atribuições estatutárias | Papel no sistema |
| --- | --- | --- |
| Conselho Consultivo e Docente Orientador | Articulação com a Presidência | Perfil de consulta, com acesso a painéis gerais e relatórios |
| Presidência | Representação, direção geral, articulação com o docente, coordenação do Plano de Ação, convocação da Assembleia, zelo pelo Estatuto | Aprovação final de todo conteúdo e alterações do site, recebimento e encaminhamento de protocolos, gestão geral |
| Vice-Presidência | Supervisão de prazos e operações internas, atribuições de Gestão de Pessoas (RH e processo seletivo), substituição da Presidência | Supervisão de tarefas e prazos, frequência e processo seletivo, aprovação em substituição à Presidência |
| Diretoria de Pesquisa | Eixo científico e artigos | Painel próprio de propostas |
| Diretoria de Extensão | Eventos e minicursos | Painel próprio; gestão de eventos |
| Diretoria Financeira | Caixa, atas e certidões | Painel próprio; atas e livro-caixa |
| Diretoria de Comunicação | Mídias e identidade visual | Administrador do Site (CMS): propõe todas as alterações; painel próprio |
| Coordenação de Pesquisa | Eixos e publicações | Perfil e painel próprios |
| Coordenação de Eventos | Logística e parcerias | Perfil e painel próprios; inscrições e presenças |
| Coordenação de Secretaria | Tesouraria e documentos | Perfil e painel próprios; documentos e certificados |
| Coordenação de Mídias | Redação e design | Perfil e painel próprios; rascunho de publicações |
| Coordenação de Integração | Seleção e membros | Perfil e painel próprios; membros e frequência |
| Membros efetivos | Resumos, artigos, grupos de estudo e suporte às comissões | Perfil de Ligante |

### 3.2 Perfis de acesso

| Perfil | Descrição |
| --- | --- |
| Administrador do Sistema (TI) | Perfil técnico fora da hierarquia da Liga. Gerencia infraestrutura, contas técnicas, logs e configurações globais. Não aprova conteúdo, não decide protocolos e não edita o site. |
| Presidência | Aprova conteúdo e alterações; recebe e encaminha protocolos; vê todos os módulos |
| Vice-Presidência | Aprova na ausência da Presidência (delegação registrada); supervisiona prazos, frequência e seleção |
| Diretor de Comunicação | Administrador do Site: cria e edita alterações, que dependem de aprovação |
| Diretores de Pesquisa, Extensão e Financeiro | Gestão de propostas do setor, tarefas e conteúdo do setor |
| Coordenadores (5 perfis distintos) | Painel do setor, criação de tarefas, rascunhos e registros do setor |
| Ligante (membro efetivo) | Aceite de tarefas, andamentos, rascunhos, consulta, registro de frequência própria |
| Consulta | Acesso somente leitura (Conselho Consultivo, docente orientador) |
| Visitante | Acesso público sem login |

Os perfis e as permissões DEVEM ser armazenados em banco (perfis, permissões, vínculos), nunca fixos no código, para que cargos e níveis sejam ajustados sem reconstruir a aplicação. Uma pessoa PODE acumular mais de um perfil.

### 3.3 Matriz de permissões

Legenda: **T** = total, **P** = propor (exige aprovação), **S** = apenas do próprio setor, **C** = consulta, **—** = sem acesso.

| Recurso | Adm. Sistema | Presidência | Vice-Presidência | Dir. Comunicação | Demais Diretores | Coordenadores | Ligantes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Propor alteração no site (CMS) | P | T | — | P | — | — | — |
| Aprovar alteração no site | — | P | T (na ausência da Presidência) | — | — | — | — |
| Criar publicação (notícia, edital, ata) | — | P | P | P | P (S) | P (S) | P (rascunho) |
| Aprovar publicação | — | T | T (delegada) | — | — | — | — |
| Gerenciar eventos | — | T | T | P | T (Extensão) | T (Eventos) | C |
| Ver inscritos e exportar | — | T | T | C | S | S (Eventos) | — |
| Triar e encaminhar protocolos | — | T | C | — | — | — | — |
| Tratar protocolo encaminhado | — | T | T | S | S | S | — |
| Criar e atribuir tarefas | — | T | T | T | T | T | T |
| Painel de propostas semestrais | — | T | T | S | S | S | C |
| Frequência (lançar, relatório) | — | T | T | C | C | S (Integração) | própria |
| Emitir certificados | — | T | T | — | S | S (Secretaria) | — |
| Gerenciar usuários e perfis | T (técnico) | T (cargos) | C | — | — | S (Integração: membros) | — |
| Ver logs de auditoria | T | T | C | — | — | — | — |
| Configurações globais e infraestrutura | T | C | — | — | — | — | — |

### 3.4 Regras de governança

- **Separação de funções:** quem propõe uma alteração não a aprova. Se a Presidência for autora, a Vice-Presidência aprova. **[Premissa]**
- **Delegação:** a Presidência pode registrar ausência (período, motivo), habilitando a Vice-Presidência como aprovadora durante o intervalo. Toda aprovação feita por delegação é marcada como tal na auditoria.
- **Conflito de interesse:** protocolo ou denúncia que envolva a Presidência é encaminhado automaticamente à Vice-Presidência, e o sistema impede que a pessoa envolvida o visualize. **[Premissa]**
- **Usuários:** criados apenas por convite ou por administrador autorizado. Não haverá cadastro público.
- **Auditoria de permissões:** toda mudança de perfil ou permissão é registrada.
- **Mandatos:** o sistema deve permitir encerrar o vínculo de um membro, preservando o histórico, e transferir tarefas e responsabilidades a outro membro.

---

## 4. Site institucional público

### 4.1 Cabeçalho e rodapé

- Cabeçalho em todas as páginas públicas, responsivo (menu hambúrguer em celulares): logotipo e nome LACCRIM/UFU, Página Inicial, Sobre Nós, Notícias, Editais e Atas, Eventos, Protocolo Virtual e o botão destacado Portal da Liga.
- O menu é dinâmico: abas criadas ou removidas no CMS (após aprovação) aparecem aqui automaticamente.
- Rodapé institucional configurável: endereço da FADIR/UFU, e-mail `laccrim@fadir.ufu.br`, Instagram, selos de parceiros (NUAVIDAS, Projeto Livre, PROEXC, UFMT, UFMA, FBDG), links de política de privacidade e termos.

### 4.2 Página inicial

- Banner hero azul-marinho, conteúdo à esquerda. Identificação "UNIVERSIDADE FEDERAL DE UBERLÂNDIA"; título "O estudo crítico das Ciências Criminais a serviço da justiça." (expressão em dourado); texto complementar sobre Direito Penal, Processual Penal e Criminologia; botões "Conheça nossa Liga" e "Protocolo Virtual".
- Seção "Três pilares, um propósito": cartões de Ensino, Pesquisa e Extensão, horizontais em telas grandes e verticais em celulares, cada um com ícone, título e descrição breve.
- Seções dinâmicas: últimas notícias e editais aprovados, próximos eventos, publicações em destaque, acesso rápido ao Protocolo Virtual e botão para conhecer os integrantes. Atualizam-se automaticamente conforme o conteúdo aprovado.
- Chamada final ("Participe da LACCRIM") e rodapé.
- Banner, textos, seções (ordem, exibição, ocultação) e carrossel são editáveis pelo CMS.

### 4.3 Página "Sobre Nós"

- Banner com título "Sobre a LACCRIM" e texto introdutório sobre a Liga como entidade estudantil vinculada à FADIR.
- Seção "Quem somos": história, missão, objetivos e princípios (editável).
- Estatuto Social em PDF, com leitor embutido e botão de download.
- Corpo Social: cartões com foto ou avatar, nome, cargo e breve descrição, organizados conforme a hierarquia do Estatuto (Presidência, Vice-Presidência, Diretorias, Coordenações, membros).
- Professores Orientadores: nome, foto, titulação, área de atuação e apresentação.
- Integrantes e professores podem ser cadastrados, editados, reordenados e removidos pelo CMS (com aprovação). O cadastro de membros do portal e o do Corpo Social são vinculáveis, mas o membro escolhe se aparece publicamente.

### 4.4 Notícias, Editais e Atas

- Filtros: Todas, Editais, Atas, Notícias. Campo de busca por título, palavra-chave ou conteúdo.
- Cartão com categoria, título, data, resumo, autor ou setor, botão "Visualizar" e link de download do PDF quando houver anexo.
- Página de detalhe com conteúdo completo, arquivos anexos e visualizador de PDF.
- Apenas publicações com status Aprovado e Publicado aparecem aqui. Rejeitadas e pendentes nunca são públicas.
- Paginação ou carregamento progressivo. Categorias editáveis pelo CMS.

### 4.5 Eventos

- Cartões com imagem de capa, categoria, título, resumo, data e hora, local ou modalidade, situação das inscrições e botão de detalhes ou inscrição.
- Filtros por categoria e situação (abertos, encerrados, vagas esgotadas) e busca por título.
- Página ou janela de detalhes: programação, palestrantes, local, datas, orientações aos participantes.
- Inscrição por modal: nome completo, e-mail, instituição, curso ou vínculo e CPF ou matrícula UFU somente quando o evento o exigir. O servidor valida os campos, impede inscrição duplicada, controla vagas, aplica prazo e encerramento automático, gerencia lista de espera e cancelamento, grava a inscrição e envia e-mail de confirmação.
- Dados dos inscritos nunca são públicos.

### 4.6 Verificação pública de certificados

Página em que qualquer pessoa informa o código de verificação de um certificado e vê se ele é válido, com nome do titular, evento, carga horária e data de emissão ([seção 6.8](#68-certificados)).

### 4.7 Páginas e abas criadas pelo CMS

Páginas novas criadas no CMS são exibidas com a mesma identidade visual, no menu e no rodapé conforme configurado, após aprovação.

### 4.8 Páginas legais, SEO e desempenho

- Política de Privacidade (LGPD), Termos de Uso e aviso de cookies, editáveis.
- Metadados e Open Graph editáveis por página, mapa do site e robots.
- URLs amigáveis. Imagens otimizadas. Metas de desempenho e acessibilidade verificadas no teste final.

---

## 5. Protocolo Virtual · Mini-SEI

Nome da área: **"Protocolo Virtual · Mini-SEI"**. Subtítulo: "Envie solicitações à LACCRIM e acompanhe a tramitação por meio do número de protocolo." Duas áreas em cartões: Nova Solicitação e Consultar Protocolo. Aviso visível de que se trata de ferramenta administrativa da Liga e não de sistema oficial da UFU.

### 5.1 Tipos de solicitação

| Nº | Tipo | Regra |
| --- | --- | --- |
| 1 | Requerimento Geral | Pedidos formais de ligantes ou discentes da UFU |
| 2 | Contestação de Edital | Recursos e impugnações contra resultados de processos seletivos |
| 3 | Pedido de Informação | Consultas sobre atividades, certificados e dados públicos |
| 4 | Ouvidoria | Manifestações sobre o atendimento ou a condução das atividades |
| 5 | Reclamação | Registros sobre descumprimento de prazos ou normas |
| 6 | Solicitação Geral | Demandas não enquadradas nas demais |
| 7 | Elogio | Reconhecimento a diretores, membros ou docentes |
| 8 | Sugestão | Propostas de projetos, eventos ou parcerias |
| 9 | Denúncia (identificada ou anônima) | Irregularidades, com opção de anonimato e proteção LGPD |

A lista é editável pelo CMS (criar, renomear, desativar, definir se exige CPF, anexos e se admite anonimato).

### 5.2 Formulário de nova solicitação

Campos: nome completo, e-mail, tipo, assunto, mensagem detalhada, anexos em PDF (limite de tamanho e validação de tipo real do arquivo) e CPF somente quando o tipo exigir. O servidor valida tudo, grava a solicitação com data e hora, salva anexos em armazenamento privado, gera o número de protocolo, exibe a confirmação e envia o e-mail de recibo.

### 5.3 Numeração e chave de acesso

- Número único e sequencial no padrão `LACCRIM-AAAA-NNNNNN` (exemplo de formato: `LACCRIM-2026-000001`), gerado pelo servidor com controle de concorrência (sequência atômica no banco), sem duplicidade e sem preenchimento manual. Reinicia a sequência a cada ano. O formato é configurável. O exemplo nunca é usado como protocolo real.
- Cada protocolo gera também uma chave de acompanhamento aleatória, exibida ao solicitante na confirmação e enviada por e-mail. No banco, a chave é armazenada apenas como hash.

### 5.4 Denúncia anônima

- Ao escolher o modo anônimo, o formulário não coleta nome, e-mail nem CPF. O sistema não registra IP nem metadados identificadores nos dados da denúncia.
- Ao final, é gerada uma chave de acompanhamento exibida uma única vez, com aviso claro de que não poderá ser recuperada. Apenas o hash é guardado.
- O denunciante acompanha o andamento e peticiona usando número e chave.
- Anexos anônimos devem passar por remoção de metadados quando viável, com aviso ao denunciante.
- Quem trata a denúncia nunca vê dado identificador, pois ele não existe no banco.

### 5.5 Consulta pública e peticionamento

- Para consultar, o usuário informa número do protocolo e chave de acompanhamento (ou e-mail e chave). Somente o número nunca basta.
- Exibição: número, data de recebimento, tipo, assunto, situação atual, histórico das movimentações públicas, data da última atualização e resposta ou orientação disponível.
- Peticionamento contínuo: o solicitante pode enviar réplicas, justificativas e novos documentos PDF no mesmo processo, enquanto o processo estiver em situação que o permita. Cada petição vira uma movimentação na linha do tempo.
- Observações internas, CPF, e-mails e documentos restritos nunca aparecem na consulta pública.
- Proteção contra consultas automatizadas: limite de tentativas por IP e por número, atraso progressivo e CAPTCHA após falhas.

### 5.6 Ciclo de vida e status

Status iniciais (configuráveis pelo CMS):

| Status | Comportamento |
| --- | --- |
| Aberto / Recebido | Protocolado; recibo emitido; e-mail enviado; protocolo cai na fila da Presidência |
| Em Análise | Encaminhado à pessoa ou ao setor responsável para parecer |
| Diligência / Aguardando Informações | Informações adicionais solicitadas ao requerente; abre-se prazo para resposta |
| Despacho / Parecer Emitido | Parecer formal (PDF opcional) e decisão registrados |
| Aguardando Prazo Recursal | Período para recurso ou contestação |
| Concluído / Indeferido | Decisão final registrada |
| Arquivado | Estado final; nenhuma nova petição aceita |

### 5.7 Tramitação interna

- **Roteamento (decisão):** todos os protocolos entram na caixa da Presidência, que encaminha manualmente a diretoria, coordenação ou membro responsável. A Presidência pode retomar a tramitação a qualquer momento e vê todos os protocolos.
- **Operações:** alterar status, registrar despacho, encaminhar internamente, solicitar informações, registrar resposta ao solicitante, concluir ou arquivar.
- Cada movimentação guarda responsável, data, hora, status anterior, novo status, descrição e visibilidade (pública ou interna). Nada se apaga: correções entram como nova movimentação.
- Prazos de resposta configuráveis por tipo, com alerta interno quando se aproximarem do vencimento. **[Premissa]**
- Regra de conflito de interesse ([seção 3.4](#34-regras-de-governança)) aplica-se ao encaminhamento.

### 5.8 E-mails do protocolo

Recibo de abertura, solicitação de diligência, resposta ao solicitante, mudança de status e encerramento. Para denúncia anônima não há e-mail.

---

## 6. Portal do Ligante (área interna)

O acesso ocorre pelo botão "Portal da Liga". Interface com menu lateral azul-marinho fixo ou recolhível, conteúdo central claro, cartões e botões de ação. O menu se adapta ao perfil do usuário e destaca a seção ativa.

### 6.1 Autenticação

- Tela independente com logotipo, título "Bem-vindo de volta", e-mail, senha com opção de mostrar ou ocultar, botão "Entrar", recuperação segura de senha e mensagens claras de erro.
- Autenticação real, senhas com hash forte (Argon2id ou bcrypt), sessões protegidas (cookies HttpOnly, Secure, SameSite), expiração por inatividade, encerramento remoto de sessões e bloqueio temporário após tentativas falhas.
- Autenticação em dois fatores (2FA) obrigatória para Presidência, Vice-Presidência, Diretor de Comunicação e Administrador do Sistema; opcional para os demais. **[Premissa]**
- Contas criadas somente por convite com link de uso único e prazo de validade. Sem cadastro público e sem login fictício.
- Primeiro acesso: o convidado define a senha e aceita o termo de uso e a política de privacidade.

### 6.2 Painel Geral (dashboard)

- Cartões calculados em tempo real a partir do banco: Conteúdo Pendente, Tarefas Pendentes, Tarefas em Andamento, Tarefas Concluídas, Protocolos em Análise, Total de Protocolos, Total de Membros e Alterações de Site Pendentes. Cada cartão tem número, descrição curta e link para os registros.
- Valores nunca fixos. Sem registros, exibe zero e orienta o usuário.
- Atalhos: Ver Kanban, Aprovar Conteúdo, Tramitar Protocolos (conforme o perfil).
- Seção "Sobre o Portal" com a finalidade administrativa.
- Os cartões respeitam o perfil: cada pessoa vê indicadores do seu escopo, e a Presidência vê o consolidado.

### 6.3 Gestão de Tarefas (Kanban peer-to-peer)

- Três colunas: Pendente, Em andamento, Concluído, mais o estado Pendente de Aceite (exibido antes de Pendente) e Recusada.
- Cartão: título, descrição resumida, criador, responsável, prazo, prioridade, categoria ou setor, data de criação, status e barra de progresso (0 a 100%).
- Atribuição livre: qualquer membro cria e atribui tarefa a qualquer outro membro, sem restrição hierárquica.
- Aceite ou recusa motivada: o responsável recebe e-mail e notificação no portal. Deve Aceitar ou Recusar, sendo obrigatória justificativa na recusa. A recusa devolve a tarefa ao criador para reatribuição e fica registrada. Tarefa sem resposta em prazo configurável gera lembrete e alerta à Vice-Presidência.
- Prazos e alertas: data e hora limite; e-mails automáticos 48 h e 24 h antes do vencimento; destaque visual para tarefas atrasadas; notificação ao criador quando vencer.
- Diário de Andamentos: em cada cartão, os membros registram atualizações datadas, anexam arquivos de trabalho e atualizam o percentual. Histórico imutável.
- Ações: criar, editar, ver detalhes, alterar status (arrastar e soltar e menu acessível por teclado), trocar responsável, definir prazo, observações, excluir (somente criador, Vice-Presidência ou Presidência, com registro de auditoria; preferir arquivamento).
- Filtros por responsável, status, setor, prioridade, prazo; visão "minhas tarefas".
- A Vice-Presidência tem visão consolidada de prazos e atrasos de toda a Liga.
- Atualização do quadro sem recarregar a página; todas as mudanças persistidas.

### 6.4 Aprovação de Conteúdo (publicações)

- Fluxo: Rascunho → Pendente de Aprovação → Aprovado e Publicado ou Rejeitado.
- Qualquer membro autorizado cria publicação (notícia, edital, ata) com título, categoria, resumo, conteúdo, PDF e setor. Publicações de membros e coordenadores podem ser pré-validadas pelo diretor do setor antes de chegar à Presidência (configurável). **[Premissa]**
- Tela de revisão com filtros Pendentes, Aprovados, Rejeitados, Todos. Cada item mostra categoria, título, resumo, autor, data de envio e status.
- Ações Aprovar e Rejeitar, ambas com confirmação. A rejeição exige justificativa. Aprovar permite publicação imediata ou agendada.
- Registro do decisor, data e hora. Rejeitados permanecem no sistema interno e nunca aparecem no site. Histórico de decisões consultável.
- Quem propõe não aprova a própria publicação.
- A aprovação atualiza o banco e reflete-se imediatamente no site público (invalidação de cache).
- Despublicação e retificação de publicação já publicada também passam por aprovação.

### 6.5 Painel do Protocolo

- Lista com número, solicitante (conforme permissão; omitido em denúncias anônimas), tipo, assunto, data de recebimento, status, responsável atual e data da última movimentação. Busca por número e filtros por situação, tipo e responsável.
- Caixa de entrada da Presidência: protocolos novos não encaminhados, ordenados por prazo.
- Ao abrir o protocolo: dados, anexos autorizados (com download protegido e auditado), linha do tempo completa e ações de tramitação descritas na [seção 5.7](#57-tramitação-interna).
- Respostas ao solicitante e observações internas são visualmente distintas; as internas jamais saem na consulta pública.

### 6.6 Hub das Diretorias e Coordenações

Cada diretoria e cada coordenação tem painel próprio para cadastrar e acompanhar Propostas do Semestre (estados: Em Planejamento, Em Execução, Em Validação, Concluída). Campos: título, descrição e meta, responsáveis, semestre, indicadores, anexos, histórico de atualizações. O diretor valida propostas de sua coordenação; a Presidência e a Vice-Presidência consultam todas.

| Setor | Escopo estatutário | Exemplos de propostas e funções do painel |
| --- | --- | --- |
| Diretoria de Pesquisa | Eixo científico e artigos | Ciclos de estudo, grupos de trabalho, artigos, calendário de submissões |
| Coordenação de Pesquisa | Eixos e publicações | Acompanhamento de eixos, resumos e artigos dos membros |
| Diretoria de Extensão | Eventos e minicursos | Calendário de eventos, minicursos, ações em escolas e parcerias |
| Coordenação de Eventos | Logística e parcerias | Logística, inscritos, presenças, parceiros |
| Diretoria Financeira | Caixa, atas e certidões | Livro-caixa, orçamento de eventos, atas das assembleias |
| Coordenação de Secretaria | Tesouraria e documentos | Acervo de documentos, tesouraria, emissão de certificados |
| Diretoria de Comunicação | Mídias e identidade visual | Calendário editorial, podcast, cobertura de eventos, CMS do site |
| Coordenação de Mídias | Redação e design | Pauta de posts, redação, peças de design |
| Vice-Presidência (Gestão de Pessoas) | RH e processo seletivo | Processo seletivo, treinamentos, frequência, procedimentos disciplinares |
| Coordenação de Integração | Seleção e membros | Candidaturas, entrada de membros, acompanhamento de vínculos |

### 6.7 Frequência dos membros

- Registro de presença em reuniões, assembleias, atividades e eventos, por lista de chamada pelo responsável ou por código de presença (QR Code com validade) lido pelo membro autenticado.
- Justificativa de ausência com anexo; aprovação pela Vice-Presidência ou Coordenação de Integração.
- Relatórios por membro, por período e por atividade, com percentual de frequência e exportação (CSV e PDF). O membro vê a própria frequência.
- Limites mínimos de frequência configuráveis, com alerta à Vice-Presidência. O sistema não aplica sanção automática: instrui o procedimento, e a decisão é humana.
- Presença em eventos alimenta a emissão de certificados.

### 6.8 Certificados

- Emissão automática em PDF para participantes de eventos com presença confirmada, e para membros (atividades e carga horária), a partir de modelos editáveis.
- Cada certificado tem código de verificação único e QR Code que leva à página pública de verificação ([seção 4.6](#46-verificação-pública-de-certificados)). A verificação exibe o mínimo de dados necessários.
- Emissão em lote, reemissão, revogação (com justificativa registrada) e envio por e-mail. Responsabilidade da Coordenação de Secretaria, com supervisão da Presidência.
- Assinaturas digitalizadas ficam em armazenamento restrito. O controle de certificados SIEX/PROEXC segue procedimento institucional à parte e pode ser registrado no sistema.

### 6.9 Gestão de membros e usuários

Cadastro de membros com cargo, setor, período de vínculo, foto e biografia, convite de acesso, troca e encerramento de vínculo, associação ao Corpo Social público e transferência de responsabilidades. Coordenação de Integração e Presidência gerenciam membros; o Administrador do Sistema gerencia apenas a parte técnica das contas.

### 6.10 Notificações

Central de notificações no portal (tarefas, aprovações, protocolos, prazos) com e-mail correspondente e preferências de envio por usuário, exceto alertas obrigatórios.

---

## 7. Módulo de Administração do Site (CMS)

O CMS permite alterar todo o site público por interface, sem mexer no código. É operado pelo Diretor de Comunicação (edita) e pela Presidência (edita e aprova). A Presidência aprova toda alteração, e a Vice-Presidência pode aprovar na ausência do Presidente.

### 7.1 O que é editável

| Grupo | Itens |
| --- | --- |
| Textos e páginas | Textos de qualquer página, páginas novas, remoção de páginas, URLs, metadados de SEO |
| Menu e estrutura | Criar, renomear, reordenar, ocultar e remover abas do menu e links do rodapé, submenus |
| Biblioteca de documentos | Enviar, substituir, organizar e remover documentos (Estatuto, editais, atas, formulários), com categoria, data e visibilidade |
| Home | Banner hero (título, subtítulo, botões, destaque dourado), seções, ordem das seções, carrossel e destaques |
| Corpo Social | Integrantes e professores: cadastro, edição, ordem, fotos, remoção |
| Identidade e tema | Logotipo, cores (variáveis do tema), tipografia entre opções pré-aprovadas, favicon |
| Informações institucionais | Contatos oficiais, endereço, redes sociais, selos de parceiros, rodapé |
| Categorias | Categorias de publicação e de eventos |
| Protocolo | Tipos de solicitação, status de tramitação, formato da numeração, prazos por tipo, textos e avisos |
| Regras de inscrição | Campos exigidos, vagas, prazo, lista de espera, mensagens de e-mail |
| Páginas legais | Política de privacidade, termos, aviso de cookies |

### 7.2 Editor

Editor visual simples (WYSIWYG) com negrito, itálico, títulos, listas, links, imagens com texto alternativo, citações e tabelas básicas. O HTML gerado é sanitizado no servidor (sem scripts nem estilos arbitrários). Pré-visualização fiel ao site antes de enviar para aprovação.

### 7.3 Fluxo de aprovação das alterações

1. O editor cria ou altera conteúdo. As edições ficam em Rascunho, visíveis apenas internamente.
2. O editor agrupa uma ou mais alterações em uma solicitação de alteração com título e justificativa e a envia, passando a Pendente de Aprovação.
3. A Presidência (ou a Vice-Presidência, na ausência) vê a comparação lado a lado (antes e depois) e a pré-visualização.
4. Aprova (publica na hora ou agenda) ou Rejeita (justificativa obrigatória). A decisão pode ser total ou item a item.
5. Aprovada, a versão publicada substitui a anterior e o site público é atualizado. Rejeitada, volta ao editor com a justificativa.

- Nenhuma alteração chega ao site público sem passar por esse fluxo, inclusive as feitas pela própria Presidência (nesse caso, aprovação pela Vice-Presidência). **[Premissa]**
- Alterações urgentes (por exemplo, remover conteúdo indevido) têm a opção de despublicação emergencial pela Presidência, com justificativa e registro destacado na auditoria.
- O Administrador do Sistema não edita nem aprova conteúdo; atua apenas na infraestrutura.

### 7.4 Histórico de versões e restauração

- Cada página, seção, documento e configuração guarda todas as versões aprovadas e as submetidas, com autor, aprovador, data, hora e justificativa.
- Comparação entre versões (diff) e restauração em um clique. A restauração cria uma nova versão em Pendente de Aprovação, como qualquer outra alteração, e não apaga o histórico.
- Arquivos substituídos ficam preservados e inacessíveis ao público, conforme política de retenção.

### 7.5 Garantias técnicas do CMS

- Conteúdo estruturado em banco (não em arquivos do repositório), versionado e com cache invalidado ao publicar.
- Proteção das páginas essenciais (home, login, protocolo): não podem ser excluídas, apenas editadas.
- Validação de contraste: o CMS alerta se uma combinação de cores personalizada violar o mínimo de acessibilidade.
- Bloqueio de edição concorrente e aviso de conflito.

---

## 8. Notificações e e-mails transacionais

| Evento | Destinatário |
| --- | --- |
| Confirmação de inscrição em evento, lista de espera, cancelamento | Inscrito |
| Lembrete de evento | Inscrito |
| Recibo de protocolo, diligência, resposta, encerramento | Solicitante identificado |
| Novo protocolo recebido | Presidência |
| Protocolo encaminhado | Responsável designado |
| Tarefa atribuída, aceita, recusada | Criador e responsável |
| Lembretes de prazo (48 h e 24 h) e atraso | Responsável e criador |
| Publicação ou alteração pendente de aprovação | Presidência (ou Vice-Presidência na ausência) |
| Decisão de aprovação ou rejeição | Autor |
| Convite, recuperação de senha, alerta de login novo | Usuário |
| Certificado emitido | Titular |

Envio por serviço SMTP transacional (Resend ou SendGrid), com remetente institucional, filas com retentativa, registro de entrega e modelos editáveis. Configurar SPF, DKIM e DMARC para o domínio.

---

## 9. Modelo de dados (relacional)

Banco relacional (PostgreSQL). Todas as tabelas têm identificador, datas de criação e alteração, e exclusão lógica quando aplicável.

| Domínio | Tabelas |
| --- | --- |
| Acesso | `usuarios`, `perfis`, `permissoes`, `usuarios_perfis`, `convites`, `sessoes`, `delegacoes_aprovacao` |
| Pessoas | `membros`, `cargos`, `setores`, `vinculos_membros`, `corpo_social`, `professores_orientadores` |
| CMS | `paginas`, `paginas_versoes`, `menu_itens`, `secoes_home`, `documentos_biblioteca`, `documentos_versoes`, `configuracoes_site`, `temas`, `solicitacoes_alteracao`, `solicitacoes_alteracao_itens`, `decisoes_aprovacao` |
| Publicações | `publicacoes`, `publicacoes_versoes`, `categorias_publicacao`, `anexos` |
| Eventos | `eventos`, `categorias_evento`, `inscricoes_eventos`, `lista_espera`, `presencas`, `certificados`, `modelos_certificado` |
| Protocolo | `protocolos`, `tipos_solicitacao`, `status_protocolo`, `movimentacoes_protocolo`, `peticionamentos`, `anexos_protocolo`, `encaminhamentos`, `sequencias_protocolo` |
| Tarefas | `tarefas`, `andamentos_tarefas`, `aceites_tarefas`, `observacoes_tarefas` |
| Gestão | `propostas_semestrais`, `atualizacoes_propostas`, `frequencias`, `justificativas_ausencia` |
| Segurança | `logs_auditoria`, `tentativas_acesso`, `notificacoes`, `emails_enviados`, `consentimentos_lgpd` |

**Regras de modelagem**

- Movimentações, andamentos, versões e auditoria são somente de inserção (append-only); não há UPDATE nem DELETE nessas tabelas.
- A sequência do protocolo usa contador por ano com bloqueio transacional.
- Chaves de acompanhamento e códigos de verificação guardados como hash.
- Denúncias anônimas não têm colunas de identificação preenchidas.
- Colunas sensíveis (CPF) criptografadas em repouso e mascaradas nas telas.
- Row Level Security (RLS) no banco em complemento às regras do servidor.

---

## 10. Arquitetura e infraestrutura

- **Aplicação:** Next.js (React com TypeScript) ou Laravel (PHP). A escolha final é da equipe de TI, desde que atenda: front-end responsivo com componentes reutilizáveis, camada de servidor para toda operação protegida, validação no cliente e no servidor.
- **Banco e autenticação:** PostgreSQL (Supabase ou Neon) com RLS.
- **Arquivos:** armazenamento privado (Supabase Storage ou S3 compatível) com URLs assinadas e de curta duração para anexos restritos; documentos públicos aprovados em bucket público separado. Limite de tamanho, validação de tipo real (assinatura do arquivo) e antivírus quando disponível.
- **Hospedagem:** Vercel ou Render para a aplicação. E-mail: Resend ou SendGrid.
- **Tarefas agendadas:** lembretes de prazo, encerramento automático de inscrições, publicações agendadas e limpeza de dados por retenção.
- **Domínio:** subdomínio institucional `laccrim.fadir.ufu.br` via CNAME, solicitado pela FADIR por processo SEI ao CTIC/UFU (Portaria REITO nº 332/2022). Enquanto não liberado, usar domínio provisório.
- **Ambientes:** desenvolvimento, homologação e produção, com segredos em variáveis de ambiente e nenhuma credencial no código do navegador.
- **Versionamento:** repositório oficial no GitHub, com branches protegidas, revisão de código, CI com testes e varredura de dependências.
- **Backups:** diários do banco e do armazenamento, com teste periódico de restauração. Monitoramento de erros e disponibilidade.

---

## 11. Segurança, privacidade e LGPD

- Controle de acesso no servidor em toda rota e ação, por perfil e por escopo de setor. Teste de acesso direto por requisição (sem passar pela interface).
- Proteções contra ataques comuns: injeção SQL, XSS, CSRF, SSRF, upload malicioso, enumeração de protocolos, força bruta. Cabeçalhos de segurança (CSP, HSTS), HTTPS obrigatório e limitação de taxa.
- Base legal e finalidade documentadas para cada coleta. Minimização: CPF não é obrigatório por padrão e só é pedido quando houver finalidade (por exemplo, certificado ou exigência do evento).
- Direitos do titular (Lei nº 13.709/2018): canal para acesso, correção, anonimização e eliminação de dados, com procedimento e prazo definidos. Encarregado (DPO) indicado na política de privacidade. **[Premissa]**
- Retenção: prazos configuráveis por tipo de dado (inscrições, protocolos arquivados, logs), com eliminação ou anonimização automática ao final.
- Dados dos inscritos e solicitantes nunca públicos. Exportações de listas exigem permissão, geram log e têm marca de uso restrito.
- Auditoria: registro de login, alteração de permissão, aprovação, rejeição, publicação, restauração de versão, movimentação de protocolo, download de anexo restrito e exportação de dados.
- Incidentes: procedimento documentado para vazamentos, com comunicação à Presidência e às autoridades quando aplicável.
- Erros tratados sem expor credenciais, caminhos internos ou dados de terceiros.

---

## 12. Experiência do usuário, acessibilidade e desempenho

- Responsivo em computadores, tablets e celulares. No portal, menu lateral recolhível; tabelas legíveis; cartões reorganizados na vertical em celulares.
- Estados de carregamento, erro de rede, vazio (com orientação) e sucesso (somente após confirmação do servidor).
- Confirmação em operações importantes (aprovar, rejeitar, excluir, arquivar, restaurar versão).
- Formulários com validação em tempo real e mensagens claras, e prevenção de envio duplicado.
- Paginação ou carregamento progressivo, busca e filtros funcionais.
- WCAG 2.1 AA: teclado, leitor de tela, contraste, rótulos, foco visível, textos alternativos. Sem botões decorativos, links quebrados ou formulários que não enviam dados.
- Desempenho: imagens otimizadas, carregamento lento de mídia, cache de páginas públicas.

---

## 13. Testes e critérios de aceite

O sistema só é considerado concluído quando todos os fluxos abaixo forem aprovados em ambiente de homologação, e o código estiver versionado no GitHub oficial.

| Nº | Fluxo | Roteiro de validação ponta a ponta |
| --- | --- | --- |
| 1 | Publicações | Membro cria notícia → status Pendente → Presidência aprova → aparece no site público; rejeitada com justificativa permanece interna |
| 2 | CMS e aprovação | Diretor de Comunicação altera texto, cria aba e envia documento → nada aparece no site → Presidência compara versões e aprova → site atualiza → restauração de versão gera nova solicitação pendente |
| 3 | Aprovação delegada | Presidência registra ausência → Vice-Presidência aprova com marca de delegação → autor não consegue aprovar a própria alteração |
| 4 | Eventos | Cadastrar evento → inscrição pelo modal → e-mail enviado e inscrito salvo → limite de vagas, duplicidade e lista de espera funcionam |
| 5 | Frequência e certificados | Registrar presença por QR Code → emitir certificado em PDF → verificar código na página pública → revogar e confirmar invalidez |
| 6 | Protocolo identificado | Protocolar requerimento → número único sequencial → cai na caixa da Presidência → encaminhar → solicitante consulta com número e chave → peticiona réplica em PDF → tramitar até Arquivado |
| 7 | Denúncia anônima | Enviar sem identificação → chave exibida uma única vez → acompanhar com a chave → confirmar que nenhum dado identificador existe no banco |
| 8 | Concorrência de protocolos | Envio simultâneo de vários protocolos sem duplicar número |
| 9 | Tarefas | Atribuir tarefa livremente → e-mail recebido → recusar sem justificativa é bloqueado → aceitar → andamento com 50% → lembretes 48 h e 24 h → concluir → dashboard atualizado |
| 10 | Painéis de diretorias e coordenações | Cada setor vê e edita apenas o próprio painel; Presidência e Vice-Presidência veem todos |
| 11 | Autenticação | Rota interna sem login é bloqueada; 2FA exigido nos perfis definidos; convite expira; sessão expira por inatividade |
| 12 | Segurança e LGPD | Acesso direto a API, anexo, protocolo de terceiros, lista de inscritos e CMS sem permissão é negado; Administrador do Sistema não consegue aprovar conteúdo; auditoria registra as tentativas |
| 13 | Acessibilidade e responsividade | Navegação por teclado, leitor de tela, contraste e telas de celular nos fluxos principais |
| 14 | Robustez | Formulários inválidos, anexos fora do limite ou do formato, estados vazios, falha de rede e queda do serviço de e-mail tratados sem perda de dados |

---

## 14. Dependências externas e itens a confirmar

Estes itens independem do desenvolvimento e podem afetar prazos e escolhas técnicas:

- Logotipo oficial e paleta definitiva (Marketing da Liga).
- Numeração definitiva dos artigos do Estatuto e texto final do Estatuto em PDF.
- Subdomínio `laccrim.fadir.ufu.br`: processo SEI da FADIR junto ao CTIC/UFU.
- Conta de e-mail transacional e domínio verificado (SPF, DKIM, DMARC), e uso do endereço `laccrim@fadir.ufu.br`.
- Indicação do Encarregado de dados (DPO) e revisão jurídica da política de privacidade e dos prazos de retenção.
- Confirmação das premissas marcadas: 2FA obrigatório, aprovação das alterações da Presidência pela Vice-Presidência, tratamento de conflito de interesse, pré-validação por diretor, prazos de resposta de protocolos.
- Definição de quem seguirá como responsável pela manutenção técnica após a entrega, pois o Estatuto não prevê a função de TI.
- Conteúdo inicial real (história, missão, membros, professores). Até lá, usar conteúdo demonstrativo claramente identificado.
- Cronograma, ordem de construção e eventual redução de escopo ficam a critério da equipe de TI.
