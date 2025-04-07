<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class AuthSV
{
    public function getQuery()
    {
        return User::query();
    }

    /**
     * Get a JWT via given credentials.
     */
    public function login($credentials, $userData, $role)
    {
        if (!$credentials) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $user = User::where('email', $credentials['email'])
            ->where('role', $role)
            ->first();

        if (!$user) {
            return response()->json(['error' => 'Email or Password is incorrect!'], 401);
        }

        if ($user->status == 'inactive') {
            return response()->json(['error' => 'User is deactivated!'], 401);
        }

        if (!Hash::check($credentials['password'], $user->password)) {
            return response()->json(['error' => 'Email or Password is incorrect!'], 401);
        }

        $guard = $role === 'user' ? 'api' : 'admin';
        if (!$token = Auth::guard($guard)->claims([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ])->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token, $guard);
    }

    /**
     * Register a User.
     */
    public function register($data)
    {
        $query = $this->getQuery();

        return $query->create(attributes: [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role']
        ]);
    }

    /**
     * Get the authenticated User.
     */
    public function GetProfile($role)
    {
        try {
            $guard = 'api';

            return response()->json(Auth::guard($guard)->user());
        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'Token has expired'], 401);
        }
    }

    /**
     * Log the user out (Invalidate the token).
     */
    public function logout($role)
    {
        $guard = 'api';
        Auth::guard($guard)->logout();
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     */
    public function refreshToken()
    {
        try {
            $newToken = JWTAuth::refresh(JWTAuth::getToken());
        } catch (TokenExpiredException $e) {
            throw new Exception('Token has expired', 401);
        } catch (TokenExpiredException $e) {
            throw new Exception('Token is invalid', 401);
        } catch (TokenExpiredException $e) {
            throw new Exception('Token is absent', 401);
        }

        return $this->respondWithRefreshToken($newToken);
    }

    /**
     * Get the token array structure.
     */
    protected function respondWithToken($token, $guard)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in_second' => Auth::guard($guard)->factory()->getTTL() * 60
        ]);
    }

    protected function respondWithRefreshToken($token)
    {
        return response()->json([
            'refresh_token' => $token,
            'token_type' => 'bearer',
            'expires_in_second' => config('jwt.refresh_ttl') * 60,
        ]);
    }
}
