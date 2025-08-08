<?php

namespace App\Http\Controllers;

use App\Helpers\validation\validation;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

      /**
     * @OA\Get(
     *     path="/api/user/lists",
     *     summary="Get list of user",
     *     tags={"User"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */


    public function index(){
        return User::all();
    }


    function lists(Request $request)
    {
        $data = User::all();
        return response()->json([
            'status' => 'success',
            'data' => $data,
            'status_code' => 200
        ]);
    }


        /**
     * @OA\Post(
     *     path="/api/user/create",
     *     summary="Create a new user",
     *     description="Creates a user with username, password, and linked staff ID.",
     *     operationId="createUser",
     *     tags={"User"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"username", "password", "staff_id"},
     *             @OA\Property(property="username", type="string", maxLength=255, example="admin123"),
     *             @OA\Property(property="password", type="string", minLength=8, example="12345678a"),
     *             @OA\Property(property="staff_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="new_data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="username", type="string", example="admin123"),
     *                 @OA\Property(property="staff_id", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", example="2025-08-06T12:00:00.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", example="2025-08-06T12:00:00.000000Z")
     *             ),
     *             @OA\Property(property="status_code", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="error", type="object",
     *                 @OA\Property(property="username", type="string", example="The username field is required."),
     *                 @OA\Property(property="password", type="string", example="The password must be at least 8 characters."),
     *                 @OA\Property(property="staff_id", type="string", example="The staff_id field is required.")
     *             ),
     *             @OA\Property(property="status_code", type="integer", example=422)
     *         )
     *     )
     * )
     */



     function create(Request $request){
        $validated = Validator::make($request->all(), [
            'username' => 'required|string|max:255|',
            'password' => 'required|string|min:8',
            'staff_id' => 'required|integer',
        ]);

        $flatErrors = collect($validated->errors()->messages())->mapWithKeys(function($message, $field){
            return [$field => $message[0]];
        })->toArray();
        if ($validated->fails()) {
            return response()->json([
                'status' => 'error',
                'error' => $flatErrors,
                'status_code' => 422
            ]);
        }
        $user = new User();
        $user -> username = $request -> username;
        $user -> password = $request -> password;
        $user -> staff_id = $request -> staff_id;
        $user -> save();
        return response()->json([
            'status' => 'success',
            'new_data' => $user,
            'status_code' => 200
        ]);
    }


        /**
     * @OA\Post(
     *     path="/api/user/update",
     *     summary="Update user",
     *     description="Update user details using POST method.",
     *     operationId="postUpdateUser",
     *     tags={"User"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id", "username", "password", "staff_id"},
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="username", type="string", example="new_username"),
     *             @OA\Property(property="password", type="string", example="newpassword123"),
     *             @OA\Property(property="staff_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User updated successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */


    function update(Request $request){
        $user = User::find($request->id);
        if($user != null){
            $user -> username = $request -> username;
            $user -> password = $request -> password;
            $user -> staff_id = $request -> staff_id;
            $user -> save();
            return response()->json([
            'status' => 'success',
            'update_data' => $user,
            'status_code' => 200
        ]);
        }
    }


        /**
     * @OA\Post(
     *     path="/api/user/delete",
     *     summary="Delete a user",
     *     description="Delete user by ID using POST",
     *     operationId="deleteUser",
     *     tags={"User"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id"},
     *             @OA\Property(property="id", type="integer", example=3)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     )
     * )
     */



    function delete(Request $request){
        $user = User::find($request->id);
        if($user != null){
            $user ->delete();
             return response()->json([
            'status' => 'success',
            'delete_data' => $user,
            'status_code' => 200
            ]);
        }else{
             return response()->json([
            'status' => 'resource not found',
            'status_code' => 200
            ]);
        }
    }
}
