<?php

namespace App\Http\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Enums\TokenAbility;
use Laravel\Sanctum\PersonalAccessToken;

class AuthRepository
{
    public function login(Request $request)
{
    $credentials = $request->only('email', 'password');
    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        // Calculate expiration dates for access token and refresh token
        $accessTokenExpiresAt = Carbon::now()->addMinutes(config('sanctum.expiration'));
        $refreshTokenExpiresAt = Carbon::now()->addMinutes(config('sanctum.rt_expiration'));

        $accessToken = $user->createToken('access_token', [TokenAbility::getValue('ACCESS_API')], $accessTokenExpiresAt);
        $refreshToken = $user->createToken('refresh_token', [TokenAbility::getValue('ISSUE_ACCESS_TOKEN')], $refreshTokenExpiresAt);

        return response()->json([
            'token' => $accessToken->plainTextToken,
            'refresh_token' => $refreshToken->plainTextToken,
            'expires_in' => config('sanctum.expiration'),
        ]);
    }
    return response()->json(['message' => 'Invalid credentials'], 401);
}

    public function register(Request $request)
    {
        $messages = [
            'email.email' => "Error Email !",
            'password.required' => 'Password required !'
        ];

        $validate = Validator::make($request->all(), [
            'email' => 'required|string|email|unique:users',
            'password' => 'required',
            'handphone_number' => 'required|numeric',
            'role_id' => 'required|exists:roles,id',
        ], $messages);
        if ($validate->fails()) {
            return $validate->errors();
        }
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'handphone_number' => $request->handphone_number,
            'role_id' => $request->role_id,
            'password' => Hash::make($request->password),
            'verified_code_forgot' => '',
        ]);
        return response()->json([
            'message' => 'Created'
        ]);
    }
    public function logout($request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }
    public function refreshToken(Request $request)
    {
        $tokenRefresh = PersonalAccessToken::findToken($request->input('token'));
        if($tokenRefresh == null){
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $tokenCreatedRefresh = $tokenRefresh->created_at;
        $expireRefresh = Carbon::parse($tokenCreatedRefresh)->addMinutes(config('sanctum.rt_expiration'));
        if($tokenRefresh && $tokenRefresh->name == 'refresh_token' && Carbon::now()<=$expireRefresh){
            $userId = $tokenRefresh->tokenable_id;
            $user = User::find($userId);
            $accessToken = $user->createToken('access_token', [TokenAbility::getValue('ACCESS_API')], config('sanctum.expiration'));
            $token = $accessToken->plainTextToken;
            return response()->json(
                [
                    'token' => $token,
                    'refresh_token' => $request->input('token'),
                    'expires_in' =>config('sanctum.expiration'),
                ]
            , 200);
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
