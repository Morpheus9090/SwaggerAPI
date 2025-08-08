<?php

namespace App\Http\Controllers;

use App\Helpers\validation\validation;
use App\Models\Position;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class PositionController extends Controller
{
      /**
     * @OA\Get(
     *     path="/api/position/lists",
     *     summary="Get list of positions",
     *     tags={"Position"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */

    public function index(){
        return Position::all();
    }


    function lists(Request $request)
    {
        $data = Position::all();
         return response()->json([
            'status' => 'success',
            'data' => $data,
            'status_code' => 200
        ]);
    }


        /**
     * @OA\Post(
     *     path="/api/position/create",
     *     summary="Create a new position",
     *     description="Creates a new position with branch_id, name, and optional description.",
     *     operationId="createPosition",
     *     tags={"Position"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"branch_id", "name"},
     *             @OA\Property(property="branch_id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="IT Officer"),
     *             @OA\Property(property="description", type="string", example="Handles all IT-related tasks", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Position created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="new_data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="branch_id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="IT Officer"),
     *                 @OA\Property(property="description", type="string", example="Handles all IT-related tasks"),
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
     *                 @OA\Property(property="branch_id", type="string", example="The branch_id field is required."),
     *                 @OA\Property(property="name", type="string", example="The name field is required.")
     *             ),
     *             @OA\Property(property="status_code", type="integer", example=422)
     *         )
     *     )
     * )
     */


     function create(Request $request){
        $validated = Validator::make($request->all(), [
            'branch_id' => 'required|numeric|',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
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
        $position = new Position();
        $position -> branch_id = $request -> branch_id;
        $position -> name = $request -> name;
        $position -> description = $request -> description;
        $position -> save();
        return response()->json([
            'status' => 'success',
            'new_data' => $position,
            'status_code' => 200
        ]);
    }


        /**
     * @OA\Post(
     *     path="/api/position/update",
     *     summary="Update a position",
     *     description="Updates an existing position using its ID",
     *     operationId="updatePosition",
     *     tags={"Position"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id", "branch_id", "name"},
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="branch_id", type="integer", example=2),
     *             @OA\Property(property="name", type="string", example="IT Supervisor"),
     *             @OA\Property(property="description", type="string", example="Manages IT department", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Position updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="update_data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="branch_id", type="integer", example=2),
     *                 @OA\Property(property="name", type="string", example="IT Supervisor"),
     *                 @OA\Property(property="description", type="string", example="Manages IT department"),
     *                 @OA\Property(property="created_at", type="string", example="2025-08-06T12:00:00.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", example="2025-08-06T12:10:00.000000Z")
     *             ),
     *             @OA\Property(property="status_code", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Position not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="not found"),
     *             @OA\Property(property="status_code", type="integer", example=404)
     *         )
     *     )
     * )
     */



    function update(Request $request){
        $position = Position::find($request->id);
        if($position != null){
            $position -> branch_id = $request -> branch_id;
            $position -> name = $request -> name;
            $position -> description = $request -> description;
            $position -> save();
            return response()->json([
            'status' => 'success',
            'update_data' => $position,
            'status_code' => 200
        ]);
        }
    }

      /**
     * @OA\Post(
     *     path="/api/position/delete",
     *     summary="Delete a position",
     *     description="Deletes a position by its ID",
     *     operationId="deletePosition",
     *     tags={"Position"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id"},
     *             @OA\Property(property="id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Position deleted successfully or not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="delete_data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="branch_id", type="integer", example=2),
     *                 @OA\Property(property="name", type="string", example="IT Supervisor"),
     *                 @OA\Property(property="description", type="string", example="Manages IT department"),
     *                 @OA\Property(property="created_at", type="string", example="2025-08-06T12:00:00.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", example="2025-08-06T12:10:00.000000Z")
     *             ),
     *             @OA\Property(property="status_code", type="integer", example=200)
     *         )
     *     )
     * )
     */



    function delete(Request $request){
        $position = Position::find($request->id);
        if($position != null){
            $position ->delete();
             return response()->json([
            'status' => 'success',
            'delete_data' => $position,
            'status_code' => 200
            ]);
        }else{
             return response()->json([
            'status' => 'resouce not found',
            'status_code' => 200
            ]);
        }
    }

}
