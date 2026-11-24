<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\FailedPasswordResetLinkRequestResponse;
use Laravel\Fortify\Contracts\SuccessfulPasswordResetLinkRequestResponse;

/**
 * Mesma resposta para e-mail existente ou não, para não revelar quem possui conta.
 */
class RespostaLinkRedefinicaoSenha implements FailedPasswordResetLinkRequestResponse, SuccessfulPasswordResetLinkRequestResponse
{
    public const MENSAGEM = 'Se houver uma conta com este e-mail, enviaremos um link para redefinir a senha.';

    public function toResponse($request): JsonResponse|RedirectResponse
    {
        /** @var Request $request */
        if ($request->wantsJson()) {
            return new JsonResponse(['message' => self::MENSAGEM], 200);
        }

        return back()->with('status', self::MENSAGEM);
    }
}
