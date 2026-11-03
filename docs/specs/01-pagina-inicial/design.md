# Spec 01 – Vitrine pública e página inicial: Design

## Camadas

```
routes/web.php                         rotas nomeadas: home, sobre, noticias, editais, eventos,
                                       protocolo, portal, privacidade, termos
app/Http/Controllers/
  HomeController.php                   delega ao HomeService e retorna a view
  PaginaEmConstrucaoController.php     página provisória das demais rotas
app/Services/HomeService.php           seções visíveis, ordenadas, com dados prontos para exibição
app/Contracts/
  ConteudoSiteRepository.php           identidade, cores, menu, rodapé e blocos da home
  ConteudoDinamicoRepository.php       publicações, eventos e publicações em destaque
app/Repositories/
  ConfigConteudoSiteRepository.php     implementação lendo config/laccrim.php
  ConfigConteudoDinamicoRepository.php implementação lendo config/laccrim.php
app/View/Composers/LayoutComposer.php  entrega identidade, menu, rodapé e cores ao layout
app/Providers/AppServiceProvider.php   bindings das interfaces e registro do composer
config/laccrim.php                     conteúdo editável (futuro CMS) e itens de exemplo
```

## Views

```
components/layout.blade.php            <head>, metadados, sobrescrita de cores, skip link
components/site/{header,footer,logo}.blade.php
components/ui/{button,section-heading,card,badge,empty-state,icone}.blade.php
components/home/{hero,pilares,noticias,eventos,publicacoes,protocolo,integrantes,cta}.blade.php
pagina_inicial/home.blade.php          percorre as seções com <x-dynamic-component>
paginas/em-construcao.blade.php
```

## Decisões

- **Seções dinâmicas**: cada bloco é `{chave, visivel, ordem}`. O `HomeService` filtra os visíveis, ordena e associa a chave a um componente Blade. Nova seção = nova entrada na configuração + novo componente.
- **Menu**: item com `rota` (nome de rota) ou `url` (páginas futuras do CMS). O composer resolve `href` e o estado ativo.
- **Cores**: `@theme` define os padrões; o composer só repassa ao layout valores que casem com `#RGB`/`#RRGGBB`, evitando injeção de CSS.
- **Datas**: `app.timezone` permanece UTC; o service converte para `laccrim.fuso_exibicao` (America/Sao_Paulo) e formata `d/m/Y`.
- **DaisyUI removido**: apenas Tailwind v4 e componentes Blade próprios.
- **Ícones**: SVG inline em `x-ui.icone`, decorativos (`aria-hidden`).
- **CMS futuro**: implementar `Eloquent*Repository` e trocar o binding; views, service e controller não mudam.
