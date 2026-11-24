<?php

namespace App\Contracts;

/**
 * Limpa o HTML vindo do editor visual, mantendo apenas a formatação permitida.
 */
interface SanitizadorHtml
{
    public function limpar(string $html): string;
}
