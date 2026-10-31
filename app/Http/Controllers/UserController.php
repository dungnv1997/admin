<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Utils\ApiResponse;
use App\Constants\ErrorMessage;


/**
 * @OA\Info(
 *     title="API Documentation",
 *     version="1.0.0",
 *     description="API Documentation using Swagger",
 * )
 */
class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Login user.
     *
     * @param AuthRequest $request
     * @return UserResource
     *
     *  @OA\Post(
     *      path="/api/login",
     *      tags={"User"},
     *      operationId="loginUser",
     *      summary="Login User",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/auth"),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Logged in",
     *      ),
     *  )
     */
    public function login(AuthRequest $request)
    {
        $tenantId = config('app.current_tenant_id');
        $credentials = [
            'tenant_id' => $tenantId,
            'email'     => $request->email,
            'password'  => $request->password,
        ];

        if (!auth()->attempt($credentials)) {
            return ApiResponse::unauthorized(ErrorMessage::INVALID_CREDENTIALS);
        }

        $user = $this->userService->detail(auth()->user(), [
            'tenant',
            'branch',
            'roles.permissions'
        ]);
        $this->userService->updateToken($user);

        return new UserResource($user);
    }


    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="Get all users",
     *     @OA\Response(
     *         response=200,
     *         description="A list of users"
     *     )
     * )
     */
    public function index(Request $request)
    {
        return UserResource::collection($this->userService->lists($request->all()));
    }

    /**
     * @OA\Post(
     *     path="/api/users",
     *     summary="Create a new user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreUserRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User created successfully"
     *     )
     * )
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['tenant_id'] = config('app.current_tenant_id');
        $data['password'] = bcrypt($request->password);
        $user = $this->userService->create($data);

        return new UserResource($user);
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     summary="Get a user by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A single user"
     *     )
     * )
     */
    public function show($id)
    {
        $user = $this->userService->getById($id);
        if (!$user) {
            return ApiResponse::notFound(ErrorMessage::USER_NOT_FOUND);
        }
        return new UserResource($user->load(['tenant', 'branch', 'roles.permissions']));
    }

    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     summary="Update a user",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreUserRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User updated successfully"
     *     )
     * )
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $data = $request->validated();
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }
        $user = $this->userService->update($id, $data);

        return new UserResource($user);
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     summary="Delete a user",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="User deleted successfully"
     *     )
     * )
     */
    public function destroy($id)
    {
        $this->userService->delete($id);
        return response()->noContent();
    }
}
