<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Http\Responses\RespostaLinkRedefinicaoSenha;
use App\Models\Portal\Convite;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Contracts\FailedPasswordResetLinkRequestResponse;
use Laravel\Fortify\Contracts\SuccessfulPasswordResetLinkRequestResponse;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SuccessfulPasswordResetLinkRequestResponse::class, RespostaLinkRedefinicaoSenha::class);
        $this->app->singleton(FailedPasswordResetLinkRequestResponse::class, RespostaLinkRedefinicaoSenha::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);

        Fortify::loginView(function () {
            return view('paginas.portal.auth.login', ['titulo' => 'Portal']);
        });

        Fortify::registerView(function (Request $req) {
            $tokenRecebido = hash('sha512', $req->query('token'));
            $consulta = Convite::where('token', $tokenRecebido)->whereNull('usado_em')->where('expira_em', '>', now());

            if ((! $tokenRecebido ||
                ! $consulta->exists())) {
                abort(403, 'Token invalido');
            }

            return view('paginas.portal.auth.register',
                [
                    'titulo' => 'Registro',
                    'email_registrado' => $consulta->value('email'),
                    'token' => $tokenRecebido,
                ]);
        });

        Fortify::requestPasswordResetLinkView(function () {
            return view('paginas.portal.auth.esqueci-senha', ['titulo' => 'Esqueci a senha']);
        });

        Fortify::resetPasswordView(function (Request $request) {
            return view('paginas.portal.auth.redefinir-senha', [
                'titulo' => 'Redefinir senha',
                'token' => $request->route('token'),
                'email' => $request->query('email'),
            ]);
        });

        ResetPassword::toMailUsing(function (User $usuario, string $token) {
            return (new MailMessage)
                ->subject('Redefinição de senha - Portal da Liga LACCRIM')
                ->greeting("Olá, {$usuario->name}!")
                ->line('Recebemos um pedido para redefinir a senha da sua conta no Portal da Liga.')
                ->action('Redefinir senha', route('password.reset', ['token' => $token, 'email' => $usuario->email]))
                ->line('O link expira em '.config('auth.passwords.users.expire').' minutos.')
                ->line('Se você não fez este pedido, ignore este e-mail.');
        });

        Fortify::twoFactorChallengeView(function () {
            return view('paginas.portal.auth.dois_fatores_desafio', ['titulo' => 'Desafio 2 fatores']);
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        RateLimiter::for('passkeys', function (Request $request) {
            $credentialId = $request->input('credential.id');

            return Limit::perMinute(10)->by(
                ($credentialId ?: $request->session()->getId()).'|'.$request->ip()
            );
        });
    }
}
