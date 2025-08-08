<?php

namespace App\Http\Controllers;

use App\Helpers\validation\validation;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
       /**
     * @OA\Get(
     *     path="/api/invoice/lists",
     *     summary="Get list of invoices",
     *     tags={"Invoice"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */

    public function index(){
        return Invoice::all();
    }


    function lists(Request $request)
    {
        $data = Invoice::all(); // Fetch all invoices
        return response()->json([
            'status' => 'success',
            'data' => $data,
            'status_code' => 200
        ]);
    }

        /**
     * @OA\Post(
     *     path="/api/invoice/create",
     *     summary="Create a new invoice",
     *     description="Creates a new invoice record with user ID and total amount.",
     *     operationId="createInvoice",
     *     tags={"Invoice"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id", "total"},
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="total", type="number", format="float", example=150.75)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Invoice created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="new_data", type="object",
     *                 @OA\Property(property="id", type="integer", example=10),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="total", type="number", format="float", example=150.75),
     *                 @OA\Property(property="created_at", type="string", example="2025-08-07T10:00:00.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", example="2025-08-07T10:00:00.000000Z")
     *             ),
     *             @OA\Property(property="status_code", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="error", type="object",
     *                 @OA\Property(property="user_id", type="string", example="The user id field is required."),
     *                 @OA\Property(property="total", type="string", example="The total must be a number.")
     *             ),
     *             @OA\Property(property="status_code", type="integer", example=422)
     *         )
     *     )
     * )
     */



     function create(Request $request){
        $validated = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'total' => 'required|numeric',
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
        $invoice = new Invoice();
        $invoice -> user_id = $request -> user_id;
        $invoice -> total = $request -> total;
        $invoice -> save();
        return response()->json([
            'status' => 'success',
            'new_data' => $invoice,
            'status_code' => 200
        ]);
    }
       /**
     * @OA\Post(
     *     path="/api/invoice/update",
     *     summary="Update an existing invoice",
     *     description="Updates an invoice record by given ID with new user ID and total amount.",
     *     operationId="updateInvoice",
     *     tags={"Invoice"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id", "user_id", "total"},
     *             @OA\Property(property="id", type="integer", example=10),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="total", type="number", format="float", example=200.50)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Invoice updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="update_data", type="object",
     *                 @OA\Property(property="id", type="integer", example=10),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="total", type="number", format="float", example=200.50),
     *                 @OA\Property(property="created_at", type="string", example="2025-08-07T10:00:00.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", example="2025-08-07T12:00:00.000000Z")
     *             ),
     *             @OA\Property(property="status_code", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Invoice not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="resource not found"),
     *             @OA\Property(property="status_code", type="integer", example=404)
     *         )
     *     )
     * )
     */



    function update(Request $request){
        $invoice = Invoice::find($request->id);
        if($invoice != null){
            $invoice -> user_id = $request -> user_id;
            $invoice -> total = $request -> total;
            $invoice -> save();
            return response()->json([
            'status' => 'success',
            'update_data' => $invoice,
            'status_code' => 200
        ]);
        }
    }
       /**
     * @OA\Post(
     *     path="/api/invoice/delete",
     *     summary="Delete an invoice by ID",
     *     description="Deletes an invoice record by the given ID.",
     *     operationId="deleteInvoice",
     *     tags={"Invoice"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id"},
     *             @OA\Property(property="id", type="integer", example=10)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Invoice deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="delete_data", type="object",
     *                 @OA\Property(property="id", type="integer", example=10),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="total", type="number", format="float", example=150.75),
     *                 @OA\Property(property="created_at", type="string", example="2025-08-07T10:00:00.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", example="2025-08-07T12:00:00.000000Z")
     *             ),
     *             @OA\Property(property="status_code", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Invoice not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="resource not found"),
     *             @OA\Property(property="status_code", type="integer", example=404)
     *         )
     *     )
     * )
     */


    function delete(Request $request){
        $invoice = Invoice::find($request->id);
        if($invoice != null){
            $invoice ->delete();
             return response()->json([
            'status' => 'success',
            'delete_data' => $invoice,
            'status_code' => 200
            ]);
        }else{
             return response()->json([
            'status' => 'resource not found',
            'status_code' => 404
            ]);
        }
    }
}
