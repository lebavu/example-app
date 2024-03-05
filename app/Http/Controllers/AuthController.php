<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use App\Http\Repositories\AuthRepository;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    protected $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Authentication"},
     *     summary="Login to get Token",
     *     description="Login to get Token",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="email",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="password",
     *                          type="string"
     *                      )
     *                 ),
     *                 example={
     *                     "email":"email A",
     *                     "password":"123456",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       )
     * )
     */
    public function login(Request $request)
    {
        return $this->authRepository->login($request);
    }

    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"Authentication"},
     *     summary="Register an new user",
     *     description="Register an new user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="email",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="role id",
     *                          type="integer"
     *                      ),
     *                      @OA\Property(
     *                          property="Phone Number",
     *                          type="integer"
     *                      ),
     *                      @OA\Property(
     *                          property="password",
     *                          type="string"
     *                      )
     *                 ),
     *                 example={
     *                     "name":"User A",
     *                     "email":"email A",
     *                     "role_id":"1",
     *                     "handphone_number":"0909555563",
     *                     "password":"123456",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       )
     * )
     */
    public function register(Request $request)
    {
        return $this->authRepository->register($request);
    }
    /**
     * @OA\Get(
     *     security={{"bearerAuth":{}}},
     *     path="/api/logout",
     *     tags={"Authentication"},
     *     summary="Logout",
     *     description="Logout",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       )
     * )
     */
    public function logout(Request $request)
    {
        return $this->authRepository->logout($request);
    }

    /**
     * @OA\Post(
     *     path="/api/refresh-token",
     *     summary="Refresh User Token",
     *     description="Invalidate existing token(s) and issue a new access token.",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="token",
     *                          type="string"
     *                      )
     *                 ),
     *                 example={
     *                     "token":"string",
     *                }
     *             )
     *         )
     *      ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
    */
    public function refreshToken(Request $request)
    {
        return $this->authRepository->refreshToken($request);
    }
}
