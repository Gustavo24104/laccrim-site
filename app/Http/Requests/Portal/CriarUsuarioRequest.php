<?php

namespace App\Http\Requests\Portal;

use App\Enums\PermissaoPortal;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CriarUsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can(PermissaoPortal::CriarUsuarios->value) ?? false;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)],
            'id_ufu' => ['required', 'string', 'max:30'],
            'data_nascimento' => ['required', 'date', 'before:today'],
            'perfil_id' => ['required', 'integer', Rule::exists('perfis', 'id')],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nome completo',
            'id_ufu' => 'ID UFU',
            'data_nascimento' => 'data de nascimento',
            'perfil_id' => 'cargo',
        ];
    }
}
