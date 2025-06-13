<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Http\Request;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the result of the authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        // IMPORTANT: This creates a Sanctum PlainTextToken upon successful login.
        // In a production SPA, you'd typically rely on session cookies.
        // This is primarily for API testing/mobile apps.
        $token = $request->user()->createToken('api-token', ['*'])->plainTextToken; // '*' grants all abilities

        return $request->wantsJson()
                    ? new JsonResponse(['message' => 'Logged in successfully.', 'token' => $token], 200)
                    : new JsonResponse(['message' => 'OKOK', 'token' => "YYY"], 200);
    }
}