<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use Illuminate\Http\Request;
use App\User;
use Carbon\Carbon;
use Auth;

/**
 * Class AuthController
 * @package App\Http\Controllers
 */
class AuthController extends Controller
{
    /**
     * Register a user to the system
     * @param UserRegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function signup(UserRegisterRequest $request)
    {
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $user->save();

        return response()->json(['message' => 'Successfully created user!'], 201);
    }

    /**
     * Login user using passport
     * @param UserLoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(UserLoginRequest $request)
    {
        $credentials = request(['email', 'password']);

        // Validate credentials through user
        if (!Auth::guard('web')->attempt($credentials, false, false)) {
            return response()->json(['message' => 'Wrong password or email'], 401);
        }

        // Get current user
        $user = User::where(['email' => $credentials['email']])->first();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        // Extend token lifetime
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }

        // Set expiration date
        $token_expires = Carbon::parse($tokenResult->token->expires_at)->toDateTimeString();

        // Save user
        $token->save();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => $token_expires,
        ]);
    }

    /**
     * Logout User, revoke access token
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Get logged in user information
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {
        return response()->json($request->user());

    }
}
