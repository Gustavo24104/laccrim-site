<?php

namespace App\Actions\Fortify;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Validation\Rules\Password;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array<int, Rule|array<mixed>|string>
     */
    protected function passwordRules(): array
    {
        return ['required', 'string', Password::min(9)->mixedCase()->numbers()->symbols(), 'confirmed'];
    }

    /**
     * @return array<string, string>
     */
    protected function mensagensSenha(): array
    {
        return [
            'password.required' => 'Informe a nova senha.',
            'password.min' => 'A senha deve ter mais de 8 caracteres.',
            'password.mixed' => 'A senha deve ter ao menos uma letra maiúscula e uma minúscula.',
            'password.numbers' => 'A senha deve ter ao menos um número.',
            'password.symbols' => 'A senha deve ter ao menos um símbolo.',
            'password.confirmed' => 'A confirmação da senha não confere.',
        ];
    }
}
