<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;



/**
 * @OA\Get(
 *     path="/api/users",
 *     operationId="getUsersList",
 *     tags={"Users"},
 *     summary="Get list of users with roles and permissions",
 *     description="Returns list of users",
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/User")
 *         ),
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad request"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error"
 *     )
 * )
 */
class UserController extends Controller
{
    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $users = User::with(['roles.permissions'])->get()->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles->map(function($role) {
                    return [
                        'id' => $role->id,
                        'name' => $role->name,
                        'permissions' => $role->permissions->map(function($permission) {
                            return [
                                'id' => $permission->id,
                                'name' => $permission->name,
                            ];
                        }),
                    ];
                }),
                'permissions' => $user->getDirectPermissions()->map(function($permission) {
                    return [
                        'id' => $permission->id,
                        'name' => $permission->name,
                    ];
                }),
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $users,
        ], 200);
    }


    /**
     * Display the specified user.
     *
     * @OA\Get(
     *     path="/api/users/{id}",
     *     operationId="getUserById",
     *     tags={"Users"},
     *     summary="Get user information",
     *     description="Returns user data",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="User ID"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $user = User::with(['roles.permissions'])->find($id);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found',
            ], 404);
        }

        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'roles' => $user->roles->map(function ($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                    'permissions' => $role->permissions->map(function ($permission) {
                        return [
                            'id' => $permission->id,
                            'name' => $permission->name,
                        ];
                    }),
                ];
            }),
            'permissions' => $user->getDirectPermissions()->map(function ($permission) {
                return [
                    'id' => $permission->id,
                    'name' => $permission->name,
                ];
            }),
        ];

        return response()->json([
            'status' => 'success',
            'data' => $userData,
        ], 200);
    }

}
