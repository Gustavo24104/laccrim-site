<?php

namespace App\Actions\Fortify;

use App\Contracts\UsuarioRepository;
use App\Models\Portal\Convite;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function __construct(private UsuarioRepository $usuarios) {}

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     *
     * @throws ValidationException
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
            'token' => ['required',
                'string',
                Rule::exists('convites', 'token')->where(function ($query) {
                    $query->whereNull('usado_em')->where('expira_em', '>', now());
                }),
            ],
        ], $this->mensagensSenha())->validate();

        $convite = Convite::query()->with('perfil')->where('token', $input['token'])->firstOrFail();

        if ($convite->email !== $input['email']) {
            throw ValidationException::withMessages([
                'email' => 'O e-mail informado não corresponde ao e-mail do convite.',
            ]);
        }

        if ($convite->perfil === null) {
            throw ValidationException::withMessages([
                'token' => 'Este convite não tem cargo definido. Solicite um novo convite.',
            ]);
        }

        return DB::transaction(function () use ($convite, $input): User {
            $convite->forceFill(['usado_em' => now(), 'expira_em' => now()])->save();

            return $this->usuarios->criarComPerfil([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => $input['password'],
            ], $convite->perfil);
        });
    }
}
