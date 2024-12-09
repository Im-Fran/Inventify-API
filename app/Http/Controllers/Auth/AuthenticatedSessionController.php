<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\{Response, JsonResponse};
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller {
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse {
        $request->authenticate();

        $request->session()->regenerate();

        return response()->json([
            'token' => $request->user()->createToken(str_replace('-', '', \Str::uuid()))->plainTextToken,
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // todo: invalidar viejas tokens

        return response()->noContent();
    }
}
