<?php

namespace App\Http\Requests\Portal;

use App\Enums\PerfilAcesso;
use Illuminate\Foundation\Http\FormRequest;

class DefinirAusenciaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->possuiPerfil(PerfilAcesso::Presidencia) ?? false;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'ausente' => ['required', 'boolean'],
        ];
    }
}
