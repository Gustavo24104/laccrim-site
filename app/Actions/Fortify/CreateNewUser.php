<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use App\Models\Convite;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

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
            Rule::exists('convites','token')->where(function ($query) {
                $query->whereNull('usado_em')->where('expira_em', '>', now());
            }),
            ],
        ])->validate();


        $convite = Convite::where('token', $input['token'])->first();

        if($convite->email !== $input['email']) {
        throw ValidationException::withMessages([
            'email' => 'O e-mail informado não corresponde ao e-mail do convite.'
            ]);
        }

        $convite->usado_em = now();
        $convite->expira_em = now();
        $convite->save();

        
        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
    }
}
