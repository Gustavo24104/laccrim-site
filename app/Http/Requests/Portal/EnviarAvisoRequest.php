<?php

namespace App\Http\Requests\Portal;

use App\Enums\PermissaoPortal;
use Illuminate\Foundation\Http\FormRequest;

class EnviarAvisoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can(PermissaoPortal::EnviarAvisos->value) ?? false;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'titulo' => ['required', 'string', 'max:120'],
            'mensagem' => ['required', 'string', 'max:2000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'titulo' => 'título',
        ];
    }
}
