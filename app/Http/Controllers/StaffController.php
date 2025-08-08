<?php

namespace App\Http\Controllers;

use App\Helpers\validation\validation;
use App\Models\Staff;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller
{
     /**
     * @OA\Get(
     *     path="/api/staff/lists",
     *     summary="Get list of staff",
     *     tags={"Staff"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */

    public function index(){
        return Staff::all();
    }

    function lists(Request $request)
    {
        $data = Staff::all();
        return response()->json([
            'status' => 'success',
            'data' => $data,
            'status_code' => 200
        ]);
    }


        /**
     * @OA\Post(
     *     path="/api/staff/create",
     *     summary="Create a new staff",
     *     description="Creates a new staff member with all required personal information.",
     *     operationId="createStaff",
     *     tags={"Staff"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"position_id", "name", "gender", "date_of_birth", "place_of_birth", "address", "phone", "nation_id_card"},
     *             @OA\Property(property="position_id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="gender", type="string", example="Male"),
     *             @OA\Property(property="date_of_birth", type="string", format="date", example="1995-01-01"),
     *             @OA\Property(property="place_of_birth", type="string", example="Phnom Penh"),
     *             @OA\Property(property="address", type="string", example="123 Street, Phnom Penh"),
     *             @OA\Property(property="phone", type="string", example="012345678"),
     *             @OA\Property(property="nation_id_card", type="string", example="N123456789")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Staff created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="new_data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="position_id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="gender", type="string", example="Male"),
     *                 @OA\Property(property="date_of_birth", type="string", example="1995-01-01"),
     *                 @OA\Property(property="place_of_birth", type="string", example="Phnom Penh"),
     *                 @OA\Property(property="address", type="string", example="123 Street, Phnom Penh"),
     *                 @OA\Property(property="phone", type="string", example="012345678"),
     *                 @OA\Property(property="nation_id_card", type="string", example="N123456789"),
     *                 @OA\Property(property="created_at", type="string", example="2025-08-06T12:00:00.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", example="2025-08-06T12:00:00.000000Z")
     *             ),
     *             @OA\Property(property="status_code", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="error", type="object",
     *                 @OA\Property(property="name", type="string", example="The name field is required.")
     *             ),
     *             @OA\Property(property="status_code", type="integer", example=422)
     *         )
     *     )
     * )
     */
    function create(Request $request){
        $validated = Validator::make($request->all(), [
            'position_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'gender' => 'required|string|max:10',
            'date_of_birth' => 'required|date',
            'place_of_birth' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'nation_id_card' => 'required|string|max:20',
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
        $staff = new Staff();
        $staff -> position_id = $request -> position_id;
        $staff -> name = $request -> name;
        $staff -> gender = $request -> gender;
        $staff -> date_of_birth = $request -> date_of_birth;
        $staff -> place_of_birth = $request -> place_of_birth;
        $staff -> address = $request -> address;
        $staff -> phone = $request -> phone;
        $staff -> nation_id_card = $request -> nation_id_card;
        $staff -> save();
        return response()->json([
            'status' => 'success',
            'new_data' => $staff,
            'status_code' => 200
        ]);
    }


        /**
     * @OA\Post(
     *     path="/api/staff/update",
     *     summary="Update staff information",
     *     description="Updates an existing staff record by ID",
     *     operationId="updateStaff",
     *     tags={"Staff"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id", "position_id", "name", "gender", "date_of_birth", "place_of_birth", "address", "phone", "nation_id_card"},
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="position_id", type="integer", example=2),
     *             @OA\Property(property="name", type="string", example="Jane Doe"),
     *             @OA\Property(property="gender", type="string", example="Female"),
     *             @OA\Property(property="date_of_birth", type="string", format="date", example="1990-05-15"),
     *             @OA\Property(property="place_of_birth", type="string", example="Siem Reap"),
     *             @OA\Property(property="address", type="string", example="456 Main St, Siem Reap"),
     *             @OA\Property(property="phone", type="string", example="0987654321"),
     *             @OA\Property(property="nation_id_card", type="string", example="N987654321")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Staff updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="update_data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="position_id", type="integer", example=2),
     *                 @OA\Property(property="name", type="string", example="Jane Doe"),
     *                 @OA\Property(property="gender", type="string", example="Female"),
     *                 @OA\Property(property="date_of_birth", type="string", example="1990-05-15"),
     *                 @OA\Property(property="place_of_birth", type="string", example="Siem Reap"),
     *                 @OA\Property(property="address", type="string", example="456 Main St, Siem Reap"),
     *                 @OA\Property(property="phone", type="string", example="0987654321"),
     *                 @OA\Property(property="nation_id_card", type="string", example="N987654321"),
     *                 @OA\Property(property="created_at", type="string", example="2025-08-06T12:00:00.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", example="2025-08-06T12:10:00.000000Z")
     *             ),
     *             @OA\Property(property="status_code", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Staff not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="resource not found"),
     *             @OA\Property(property="status_code", type="integer", example=404)
     *         )
     *     )
     * )
     */



    function update(Request $request){
        $staff = Staff::find($request->id);
        if($staff != null){
            $staff -> position_id = $request -> position_id;
            $staff -> name = $request -> name;
            $staff -> gender = $request -> gender;
            $staff -> date_of_birth = $request -> date_of_birth;
            $staff -> place_of_birth = $request -> place_of_birth;
            $staff -> address = $request -> address;
            $staff -> phone = $request -> phone;
            $staff -> nation_id_card = $request -> nation_id_card;
            $staff -> save();
            return response()->json([
            'status' => 'success',
            'update_data' => $staff,
            'status_code' => 200
        ]);
        }
    }


        /**
     * @OA\Post(
     *     path="/api/staff/delete",
     *     summary="Delete a staff by ID",
     *     description="Deletes a staff record by given ID.",
     *     operationId="deleteStaff",
     *     tags={"Staff"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id"},
     *             @OA\Property(property="id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Staff deleted successfully or resource not found",
     *         @OA\JsonContent(
     *             oneOf={
     *                 @OA\Schema(
     *                     @OA\Property(property="status", type="string", example="success"),
     *                     @OA\Property(property="delete_data", type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="position_id", type="integer", example=2),
     *                         @OA\Property(property="name", type="string", example="Jane Doe"),
     *                         @OA\Property(property="gender", type="string", example="Female"),
     *                         @OA\Property(property="date_of_birth", type="string", example="1990-05-15"),
     *                         @OA\Property(property="place_of_birth", type="string", example="Siem Reap"),
     *                         @OA\Property(property="address", type="string", example="456 Main St, Siem Reap"),
     *                         @OA\Property(property="phone", type="string", example="0987654321"),
     *                         @OA\Property(property="nation_id_card", type="string", example="N987654321"),
     *                         @OA\Property(property="created_at", type="string", example="2025-08-06T12:00:00.000000Z"),
     *                         @OA\Property(property="updated_at", type="string", example="2025-08-06T12:10:00.000000Z")
     *                     ),
     *                     @OA\Property(property="status_code", type="integer", example=200)
     *                 ),
     *                 @OA\Schema(
     *                     @OA\Property(property="status", type="string", example="resource not found"),
     *                     @OA\Property(property="status_code", type="integer", example=200)
     *                 )
     *             }
     *         )
     *     )
     * )
     */
    function delete(Request $request){
        $staff = Staff::find($request->id);
        if($staff != null){
            $staff ->delete();
             return response()->json([
            'status' => 'success',
            'delete_data' => $staff,
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
