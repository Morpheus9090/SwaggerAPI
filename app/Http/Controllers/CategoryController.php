<?php

namespace App\Http\Controllers;

use App\Helpers\validation\validation;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
        /**
     * @OA\Get(
     *     path="/api/category/lists",
     *     summary="Get list of categories",
     *     tags={"Category"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */

    public function index(){
        return Category::all();
    }

    function lists(Request $request)
    {
        $data = Category::all();
        return response()->json([
            'status' => 'success',
            'data' => $data,
            'status_code' => 200
        ]);
    }

        /**
     * @OA\Post(
     *     path="/api/category/create",
     *     summary="Create new category",
     *     tags={"Category"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="IT Equipment"),
     *             @OA\Property(property="description", type="string", example="Category for all IT-related items")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category created successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */


       function create(Request $request){
        $validated = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
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
        $category = new Category();
        $category -> name = $request -> name;
        $category -> description = $request -> description;
        $category -> save();
        return response()->json([
            'status' => 'success',
            'new_data' => $category,
            'status_code' => 200
        ]);
    }


            /**
     * @OA\Post(
     *     path="/api/category/update",
     *     summary="Update an existing category",
     *     tags={"Category"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id", "name"},
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Updated Category Name"),
     *             @OA\Property(property="description", type="string", example="Updated category description")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category updated successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */


    function update(Request $request){
        $category = Category::find($request->id);
        if($category != null){
            $category -> name = $request -> name;
            $category -> description = $request -> description;
            $category -> save();
            return response()->json([
            'status' => 'success',
            'update_data' => $category,
            'status_code' => 200
        ]);
        }
    }
      /**
     * @OA\Post(
     *     path="/api/category/delete",
     *     summary="Delete a category by ID",
     *     tags={"Category"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id"},
     *             @OA\Property(property="id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */


    function delete(Request $request){
        $category = Category::find($request->id);
        if($category != null){
            $category ->delete();
             return response()->json([
            'status' => 'success',
            'delete_data' => $category,
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


