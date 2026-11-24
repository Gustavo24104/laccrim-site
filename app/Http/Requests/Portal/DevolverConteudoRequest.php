<?php

namespace App\Http\Requests\Portal;

use App\Enums\PermissaoPortal;
use Illuminate\Foundation\Http\FormRequest;

class DevolverConteudoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can(PermissaoPortal::AprovarSite->value) ?? false;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'motivo' => ['nullable', 'string', 'max:500'],
        ];
    }
}
