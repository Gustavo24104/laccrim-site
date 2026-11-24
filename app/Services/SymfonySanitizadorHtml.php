<?php

namespace App\Services;

use App\Contracts\SanitizadorHtml;
use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

/**
 * Lista restrita às marcações que o editor do CMS produz: parágrafos, ênfases,
 * subtítulos, listas, citações e links (http, https ou e-mail).
 */
class SymfonySanitizadorHtml implements SanitizadorHtml
{
    private const int TAMANHO_MAXIMO = 50_000;

    private HtmlSanitizer $sanitizador;

    public function __construct()
    {
        $this->sanitizador = new HtmlSanitizer(
            (new HtmlSanitizerConfig)
                ->allowElement('p')
                ->allowElement('br')
                ->allowElement('strong')
                ->allowElement('em')
                ->allowElement('u')
                ->allowElement('s')
                ->allowElement('h3')
                ->allowElement('h4')
                ->allowElement('ul')
                ->allowElement('ol')
                ->allowElement('li')
                ->allowElement('blockquote')
                ->allowElement('a', ['href'])
                ->allowLinkSchemes(['http', 'https', 'mailto'])
                ->forceAttribute('a', 'rel', 'noopener noreferrer')
                ->withMaxInputLength(self::TAMANHO_MAXIMO),
        );
    }

    public function limpar(string $html): string
    {
        $limpo = trim($this->sanitizador->sanitize($html));

        return trim(strip_tags($limpo)) === '' ? '' : $limpo;
    }
}
