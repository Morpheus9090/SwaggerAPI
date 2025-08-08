<?php

namespace App\Http\Controllers;

use App\Helpers\validation\validation;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
       /**
     * @OA\Get(
     *     path="/api/product/lists",
     *     summary="Get list of product",
     *     tags={"Product"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */

    public function index(){
        return Product::all();
    }


    function lists(Request $request)
    {
        $data = Product::all();
        return response()->json([
            'status' => 'success',
            'data' => $data,
            'status_code' => 200
        ]);
    }


        /**
     * @OA\Post(
     *     path="/api/product/create",
     *     summary="Create a new product",
     *     tags={"Product"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "cost", "price", "category_id"},
     *             @OA\Property(property="name", type="string", example="Laptop"),
     *             @OA\Property(property="cost", type="number", format="float", example=500),
     *             @OA\Property(property="price", type="number", format="float", example=750),
     *             @OA\Property(property="image", type="string", example="product.jpg"),
     *             @OA\Property(property="description", type="string", example="High-performance laptop"),
     *             @OA\Property(property="category_id", type="integer", example=2),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product created successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed"
     *     )
     * )
     */



     function create(Request $request){
        $validated = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'cost' => 'required|numeric',
            'price' => 'required|numeric',
            'image' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|integer|',
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
        $product = new Product();
        $product -> name = $request -> name;
        $product -> cost = $request -> cost;
        $product -> price = $request -> price;
        $product -> image = $request -> image;
        $product -> description = $request -> description;
        $product -> category_id = $request -> category_id;
        $product -> save();
        return response()->json([
            'status' => 'success',
            'new_data' => $product,
            'status_code' => 200
        ]);
    }
       /**
     * @OA\Post(
     *     path="/api/product/update",
     *     summary="Update an existing product",
     *     tags={"Product"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id", "name", "cost", "price", "category_id"},
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Updated Product Name"),
     *             @OA\Property(property="cost", type="number", format="float", example=15.00),
     *             @OA\Property(property="price", type="number", format="float", example=25.00),
     *             @OA\Property(property="image", type="string", example="updated-image.jpg"),
     *             @OA\Property(property="description", type="string", example="Updated description."),
     *             @OA\Property(property="category_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="update_data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Updated Product Name"),
     *                 @OA\Property(property="cost", type="number", example=15.00),
     *                 @OA\Property(property="price", type="number", example=25.00),
     *                 @OA\Property(property="image", type="string", example="updated-image.jpg"),
     *                 @OA\Property(property="description", type="string", example="Updated description."),
     *                 @OA\Property(property="category_id", type="integer", example=2)
     *             ),
     *             @OA\Property(property="status_code", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Product not found"),
     *             @OA\Property(property="status_code", type="integer", example=404)
     *         )
     *     )
     * )
     */



    function update(Request $request){
        $product = Product::find($request->id);
        if($product != null){
            $product -> name = $request -> name;
            $product -> cost = $request -> cost;
            $product -> price = $request -> price;
            $product -> image = $request -> image;
            $product -> description = $request -> description;
            $product -> category_id = $request -> category_id;
            $product -> save();
            return response()->json([
            'status' => 'success',
            'update_data' => $product,
            'status_code' => 200
        ]);
        }
    }
       /**
     * @OA\Post(
     *     path="/api/product/delete",
     *     summary="Delete a product by ID",
     *     description="Deletes a product record by given ID.",
     *     operationId="deleteProduct",
     *     tags={"Product"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id"},
     *             @OA\Property(property="id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product deleted successfully or resource not found",
     *         @OA\JsonContent(
     *             oneOf={
     *                 @OA\Schema(
     *                     @OA\Property(property="status", type="string", example="success"),
     *                     @OA\Property(property="delete_data", type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="Example Product"),
     *                         @OA\Property(property="description", type="string", example="This is a sample product."),
     *                         @OA\Property(property="price", type="number", format="float", example=29.99),
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
        $product = Product::find($request->id);
        if($product != null){
            $product ->delete();
             return response()->json([
            'status' => 'success',
            'delete_data' => $product,
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
