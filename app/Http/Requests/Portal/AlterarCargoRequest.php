<?php

namespace App\Http\Requests\Portal;

use App\Enums\PermissaoPortal;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class AlterarCargoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can(PermissaoPortal::AlterarCargos->value) ?? false;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'perfil_id' => ['required', 'integer', Rule::exists('perfis', 'id')],
        ];
    }

    /**
     * Impede que alguém altere o próprio cargo e perca o acesso por engano.
     *
     * @return array<int, callable>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($this->route('usuario')?->is($this->user())) {
                    $validator->errors()->add('perfil_id', 'Você não pode alterar o próprio cargo.');
                }
            },
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'perfil_id' => 'cargo',
        ];
    }
}
