<?php

namespace App\Http\Requests\Portal;

use App\Enums\PermissaoPortal;
use App\Services\Portal\Cms\AreaConteudo;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SalvarConteudoRequest extends FormRequest
{
    public const string ACAO_RASCUNHO = 'rascunho';

    public const string ACAO_SOLICITAR = 'solicitar';

    public const string ACAO_PUBLICAR = 'publicar';

    private const array TAMANHO_PADRAO = [
        'texto' => 160,
        'texto_longo' => 600,
        'html' => 20000,
        'email' => 120,
        'url' => 255,
    ];

    public function authorize(): bool
    {
        $usuario = $this->user();

        if (! $usuario?->can(PermissaoPortal::EditarSite->value)) {
            return false;
        }

        return $this->input('acao') !== self::ACAO_PUBLICAR || $usuario->can(PermissaoPortal::AprovarSite->value);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $regras = [
            'acao' => ['required', Rule::in([self::ACAO_RASCUNHO, self::ACAO_SOLICITAR, self::ACAO_PUBLICAR])],
            'campos' => ['required', 'array'],
        ];

        foreach ($this->area()->campos as $campo) {
            $regras["campos.{$campo['nome']}"] = [
                ($campo['obrigatorio'] ?? true) ? 'required' : 'nullable',
                'string',
                'max:'.($campo['max'] ?? self::TAMANHO_PADRAO[$campo['tipo']]),
                ...match ($campo['tipo']) {
                    'email' => ['email'],
                    'url' => ['url:https'],
                    default => [],
                },
            ];
        }

        return $regras;
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        $atributos = [];

        foreach ($this->area()->campos as $campo) {
            $atributos["campos.{$campo['nome']}"] = mb_strtolower($campo['rotulo']);
        }

        return $atributos;
    }

    public function area(): AreaConteudo
    {
        return $this->route('area');
    }
}
