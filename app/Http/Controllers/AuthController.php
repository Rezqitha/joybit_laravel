<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Display the login page.
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * Process login request.
     */
    public function login(Request $request): JsonResponse
    {
        $username = $request->input('username');
        $password = $request->input('password');

        if (empty($username) || empty($password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Username dan password harus diisi!',
            ]);
        }

        $user = User::where('username', $username)
            ->where('password', $password)
            ->first();

        if ($user) {
            return response()->json([
                'status' => 'success',
                'message' => 'Login berhasil!',
                'data' => [
                    'id_user' => $user->id_user,
                    'username' => $user->username,
                    'role' => $user->role,
                ],
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Username atau password salah!',
        ]);
    }
}
