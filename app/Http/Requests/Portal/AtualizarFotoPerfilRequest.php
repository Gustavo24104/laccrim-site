<?php

namespace App\Http\Requests\Portal;

use Illuminate\Foundation\Http\FormRequest;

class AtualizarFotoPerfilRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'foto' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'foto.required' => 'Selecione uma imagem.',
            'foto.image' => 'O arquivo deve ser uma imagem.',
            'foto.mimes' => 'Envie uma imagem JPG, PNG ou WEBP.',
            'foto.max' => 'A imagem deve ter no máximo 2 MB.',
        ];
    }
}
