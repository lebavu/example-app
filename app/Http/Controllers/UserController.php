<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;
use App\Http\Repositories\UserRepository;

/**
 * @OA\Info(
 *     title="Example App",
 *     version="0.1"
 * )
 */
class UserController extends Controller
{
    protected $userRepository;
    protected $success;
    protected $errors;

    public function __construct(UserRepository $userRepository)
    {
        $this->success = false;
        $this->errors = [];
        $this->userRepository = $userRepository;
    }
    /**
    * @OA\Get (
    *     path="/api/users",
    *     tags={"Users"},
    *     summary="Get list of users",
    *     description="Return list of users",
    *     @OA\Response(
    *          response="200",
    *          description="Successful Operation"
    *      ),
    *     @OA\Response(
    *          response="401",
    *          description="Unauthenticated"
    *      ),
    *     @OA\Response(
    *          response="403",
    *          description="Forbidden"
    *      ),
    *     @OA\Response(
    *          response="404",
    *          description="Not Found"
    *      ),
    *
    * )
    */
    public function index()
    {
        $users = $this->userRepository->paginate($this->errors, $this->success);
        if(!empty($this->errors)){
            return $this->responseErrors($this->errors, $this->success);
        }
        return new UserCollection($users, $this->success);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }


   /**
    * @OA\Post(
    *     path="/api/user",
    *     operationId="storeUser",
    *     tags={"Users"},
    *     summary="Store new user",
    *     description="Return user data",
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
    *                          property="status",
    *                          type="string"
    *                      )
    *                 ),
    *                 example={
    *                     "title":"example name",
    *                     "body":"example status"
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
    public function store(Request $request)
    {
        return new UserResource($this->userRepository->store($request));
    }

   /**
    * @OA\Get(
    *     path="/api/user/{id}",
    *     tags={"Users"},
    *     description="Show a user",
    *     summary="Show a user",
    *     @OA\Parameter(
    *          in="path",
    *          name="id",
    *          required=true,
    *          @OA\Schema(type="integer")
    *      ),
    *      @OA\Response(
    *          response=200,
    *          description="Successful operation"
    *       )
    * )
    *
    */
    public function show($id)
    {
        $user = $this->userRepository->show($id, $this->errors, $this->success);
        if(!empty($this->errors)){
            return $this->responseErrors($this->errors, $this->success);
        }
        return new UserResource($user, $this->success, true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }


   /**
    * @OA\Put(
    *     path="/api/user/{id}",
    *     tags={"Users"},
    *     description="Update a user",
    *     summary="Update a user",
    *     @OA\Parameter(
    *          in="path",
    *          name="id",
    *          required=true,
    *          @OA\Schema(type="integer")
    *      ),
    *     @OA\RequestBody(
    *          required=true,
    *          @OA\MediaType(
    *              mediaType="application/json",
    *              @OA\Schema(
    *                  type="object",
    *                  @OA\Property(
    *                      property="name",
    *                      type="string"
    *                  ),
    *                  @OA\Property(
    *                      property="status",
    *                      type="string"
    *                  ),
    *                  example={
    *                      "title": "A better name",
    *                      "body" : "A better description"
    *                  }
    *              )
    *          )
    *      ),
    *     @OA\Response(
    *          response=200,
    *          description="Success"
    *      )
    * )
    *
    */
    public function update(Request $request, $id)
    {
        $user = $this->userRepository->update($request, $id, $this->errors, $this->success);
        if(!empty($this->errors)){
            return $this->responseErrors($this->errors, $this->success);
        }
        $user = new UserResource($user,$this->success, true);
        return $user;
    }

   /**
    * @OA\Delete(
    *     path="/api/user/{id}",
    *     tags={"Users"},
    *     description="Delete a user",
    *     summary="Delete a user",
    *     @OA\Parameter(
    *          in="path",
    *          name="id",
    *          required=true,
    *          @OA\Schema(type="integer")
    *      ),
    *     @OA\Response(
    *          response=200,
    *          description="Success"
    *      )
    * )
    *
    */
    public function destroy($id)
    {
        $user = $this->userRepository->delete($id, $this->errors, $this->success);
        if(!empty($this->errors)){
            return $this->responseErrors($this->errors, $this->success);
        }
        return new UserResource($user, $this->success, true);
    }
}
