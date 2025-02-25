<?php

namespace App\Http\Controllers\Api\v1\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Services\AuthSV;
use App\Http\Controllers\Api\v1\BaseAPI;

class UserAuthController extends BaseAPI
{
    protected $AuthSV;

    public function __construct()
    {
        $this->AuthSV = new AuthSV();
    }
    // Register User
    public function register(StoreUserRequest $request)
    {
        try{
            $params = [];
            $params['email'] = $request->email;
            $params['password'] = $request->password;
            $params['name'] = $request->name;
            $params['role'] = 'user';
            $user = $this->AuthSV->register($params);
            return $this->successResponse($user, 'User registered successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }

    }
    // Login User
    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');
            $userData = $request->only('email', 'name');
            $role = 'user';
            $user = $this->AuthSV->login($credentials, $userData, $role);
            return $user;
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Refresh Token
    public function refreshToken()
    {
        try {
            $token = $this->AuthSV->refreshToken();
            return $token;
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Logout
    public function logout(Request $request)
    {
        try {
            $this->AuthSV->logout($request->user());
            return $this->successResponse(null, 'User logged out successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
