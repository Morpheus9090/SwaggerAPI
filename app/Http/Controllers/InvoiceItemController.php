<?php

namespace App\Http\Controllers;
use App\Helpers\validation\validation;
use App\Models\InvoiceItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InvoiceItemController extends Controller
{
      /**
     * @OA\Get(
     *     path="/api/invoiceitem/lists",
     *     summary="Get list of invoice item",
     *     tags={"Invoice Item"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */

    public function index(){
        return Invoiceitem::all();
    }


    function lists(Request $request)
    {
        $data = Invoiceitem::all();
        return response()->json([
            'status' => 'success',
            'data' => $data,
            'status_code' => 200
        ]);
    }


        /**
     * @OA\Post(
     *     path="/api/invoiceitem/create",
     *     summary="Create a new invoice item",
     *     description="Creates a new invoice item record with invoice ID, product ID, quantity, and price.",
     *     operationId="createInvoiceItem",
     *     tags={"Invoice Item"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"invoice_id", "product_id", "qty", "price"},
     *             @OA\Property(property="invoice_id", type="integer", example=1),
     *             @OA\Property(property="product_id", type="integer", example=2),
     *             @OA\Property(property="qty", type="number", format="float", example=3),
     *             @OA\Property(property="price", type="number", format="float", example=150.50)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Invoice item created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="new_data", type="object",
     *                 @OA\Property(property="id", type="integer", example=10),
     *                 @OA\Property(property="invoice_id", type="integer", example=1),
     *                 @OA\Property(property="product_id", type="integer", example=2),
     *                 @OA\Property(property="qty", type="number", format="float", example=3),
     *                 @OA\Property(property="price", type="number", format="float", example=150.50),
     *                 @OA\Property(property="created_at", type="string", example="2025-08-07T10:00:00.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", example="2025-08-07T10:10:00.000000Z")
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
     *                 @OA\Property(property="invoice_id", type="string", example="The invoice id field is required."),
     *                 @OA\Property(property="product_id", type="string", example="The product id field is required."),
     *                 @OA\Property(property="qty", type="string", example="The qty must be a number."),
     *                 @OA\Property(property="price", type="string", example="The price must be a number.")
     *             ),
     *             @OA\Property(property="status_code", type="integer", example=422)
     *         )
     *     )
     * )
     */


    function create(Request $request){
        $validated = Validator::make($request->all(), [
            'invoice_id' => 'required|integer',
            'product_id' => 'required|integer',
            'qty' => 'required|numeric',
            'price' => 'required|numeric',
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
        $invoiceItem = new Invoiceitem();
        $invoiceItem -> invoice_id = $request -> invoice_id;
        $invoiceItem -> product_id = $request -> product_id;
        $invoiceItem -> qty = $request -> qty;
        $invoiceItem -> price = $request -> price;
        $invoiceItem -> save();
        return response()->json([
            'status' => 'success',
            'new_data' => $invoiceItem,
            'status_code' => 200
        ]);
    }


        /**
     * @OA\Post(
     *     path="/api/invoiceitem/update",
     *     summary="Update an existing invoice item",
     *     description="Updates an invoice item record by given ID with new invoice ID, product ID, quantity, and price.",
     *     operationId="updateInvoiceItem",
     *     tags={"Invoice Item"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id", "invoice_id", "product_id", "qty", "price"},
     *             @OA\Property(property="id", type="integer", example=10),
     *             @OA\Property(property="invoice_id", type="integer", example=1),
     *             @OA\Property(property="product_id", type="integer", example=2),
     *             @OA\Property(property="qty", type="number", format="float", example=5),
     *             @OA\Property(property="price", type="number", format="float", example=250.75)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Invoice item updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="update_data", type="object",
     *                 @OA\Property(property="id", type="integer", example=10),
     *                 @OA\Property(property="invoice_id", type="integer", example=1),
     *                 @OA\Property(property="product_id", type="integer", example=2),
     *                 @OA\Property(property="qty", type="number", format="float", example=5),
     *                 @OA\Property(property="price", type="number", format="float", example=250.75),
     *                 @OA\Property(property="created_at", type="string", example="2025-08-07T10:00:00.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", example="2025-08-07T12:00:00.000000Z")
     *             ),
     *             @OA\Property(property="status_code", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Invoice item not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="resource not found"),
     *             @OA\Property(property="status_code", type="integer", example=404)
     *         )
     *     )
     * )
     */


    function update(Request $request){
        $invoiceItem = Invoiceitem::find($request->id);
        if($invoiceItem != null){
            $invoiceItem -> invoice_id = $request -> invoice_id;
            $invoiceItem -> product_id = $request -> product_id;
            $invoiceItem -> qty = $request -> qty;
            $invoiceItem -> price = $request -> price;
            $invoiceItem -> save();
            return response()->json([
            'status' => 'success',
            'update_data' => $invoiceItem,
            'status_code' => 200
        ]);
        }
    }
      /**
     * @OA\Post(
     *     path="/api/invoiceitem/delete",
     *     summary="Delete an invoice item by ID",
     *     description="Deletes an invoice item record by the given ID.",
     *     operationId="deleteInvoiceItem",
     *     tags={"Invoice Item"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id"},
     *             @OA\Property(property="id", type="integer", example=10)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Invoice item deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="delete_data", type="object",
     *                 @OA\Property(property="id", type="integer", example=10),
     *                 @OA\Property(property="invoice_id", type="integer", example=1),
     *                 @OA\Property(property="product_id", type="integer", example=2),
     *                 @OA\Property(property="qty", type="number", format="float", example=5),
     *                 @OA\Property(property="price", type="number", format="float", example=250.75),
     *                 @OA\Property(property="created_at", type="string", example="2025-08-07T10:00:00.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", example="2025-08-07T12:00:00.000000Z")
     *             ),
     *             @OA\Property(property="status_code", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Invoice item not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="resource not found"),
     *             @OA\Property(property="status_code", type="integer", example=404)
     *         )
     *     )
     * )
     */


    function delete(Request $request){
        $invoiceItem = Invoiceitem::find($request->id);
        if($invoiceItem != null){
            $invoiceItem ->delete();
             return response()->json([
            'status' => 'success',
            'delete_data' => $invoiceItem,
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


