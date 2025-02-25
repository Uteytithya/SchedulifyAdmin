<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use App\Services\BaseService;


class AuthSV extends BaseService
{

    // use BaseService;
    public function getQuery()
    {
        return User::query();
    }

   /**
   * Get a JWT via given credentials.
   *
   * @return \Illuminate\Http\JsonResponse
   */
    public function login($credentials, $userData, $role)
    {
        if (!$credentials) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $user = User::where('email', $credentials['email'])
                    ->where('role', $role)
                    ->first();

        $isDeactivated = $user->status == 'inactive' ? 0 : 1;
        if (!$user) { //Incorrect email
            return response()->json(['error' => 'Email or Password is incorrect!'], 401);
        }
        else if ($isDeactivated == 0) {
            return response()->json(['error' => 'User is deactivated!'], 401);
        }
        else {

            $encryptedPassword = $user->password;
            if (!Hash::check($credentials['password'], $encryptedPassword)) { //Incorrect password
            return response()->json(['error' => 'Email or Password is incorrect!'], 401);
            }
            if ($role == 'admin') {
                if (!$token = Auth::guard('api')->attempt($credentials)) {
                    return response()->json(['error' => 'Unauthorized'], 401);
                }
            }
            if (!$token = Auth::guard('api-user')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
            }
        }
        return $this->respondWithToken($token, $userData, $role);
    }

    /**
     *
     * Register a User
     *
     */
    public function register($data, $role)
    {
        $query = $this->getQuery();
        $user = $query->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $role
        ]);

        return response()->json(['message' => 'User created successfully'], 201);
    }

   /**
    * Get the authenticated User.
    *
    * @return \Illuminate\Http\JsonResponse
    */
   public function GetProfile($role)
   {
        try {
            # Here we just get information about current user
            if ($role == 'admin') {
                return response()->json(Auth::guard('api')->user());
            }
            return response()->json(Auth::guard('api-user')->user());
        }  catch (TokenExpiredException $e) {
            return response()->json(['error' => 'Token has expired'], 401);
        }
   }

   /**
    * Log the user out (Invalidate the token).
    *
    * @return \Illuminate\Http\JsonResponse
    */
   public function logout($role)
   {
        if ($role == 'admin') {
            Auth::guard('api')->logout();
            return response()->json(['message' => 'Successfully logged out']);
        }
        Auth::guard('api-user')->logout();
      return response()->json(['message' => 'Successfully logged out']);
   }

   /**
    * Refresh a token.
    *
    * @return \Illuminate\Http\JsonResponse
    */
   public function refreshToken($role)
    {
        # When access token will be expired, we are going to generate a new one with this function
        if ($role == 'admin') {
            return $this->respondWithRefreshToken(Auth::guard('api')->setTTL(config('jwt.ttl'))->refresh(), Auth::guard($role)->user(), $role);
        }
        return $this->respondWithRefreshToken(Auth::guard('api-user')->setTTL(config('jwt.refresh_ttl'))->refresh());
    }

   /**
    * Get the token array structure.
    *
    * @param  string $token
    *
    * @return \Illuminate\Http\JsonResponse
    */
   protected function respondWithToken($token, $user = null, $role)
   {
      # This function is used to make JSON response with new
      # access token of current user
        $expiresIn = Auth::guard('api-user')->factory()->getTTL() * 60;
        if ($role == 'admin') {
            $expiresIn = Auth::guard('api')->factory()->getTTL() * 60;
        }
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'data' => [
                'user' => $user
            ],
            'expires_in_second' => $expiresIn
        ]);
   }

   protected function respondWithRefreshToken($token, $user = null)
   {
      # This function is used to make JSON response with new
      # refresh token of current user
      return response()->json([
         'refresh_token' => $token,
         'token_type' => 'bearer',
         // 'data' => [
         //    'user' => $user
         // ],
         'expires_in_second' => config('jwt.refresh_ttl') * 60
      ]);
   }
}
