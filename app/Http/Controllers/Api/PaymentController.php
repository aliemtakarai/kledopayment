<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use Validator;

class PaymentController extends Controller
{
    /**
     * Get Payment Using Pagination
     */
    public function getData(Request $request)
    {
        try {
            $limit = isset($limit) ? $request->limit : 10;

            $data = Payment::orderBy('id', 'DESC')->paginate($limit);

            if (count($data) >= 1) {
                return response()->json($data, 200);
            } else {
                return response()->json(['message' => 'Empty Collection'], 200);
            }

        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Add New Payment Name
     */
    public function store(Request $request)
    {
        // validate payment name input
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'fill in the blank required fields'], 500);
        }

        // store process
        try {
            $payment = new Payment;
            $payment->payment_name = $request->name;
            $payment->save();

            return response()->json(['message' => 'success'], 201);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Delele Payment Name
     * @params $id
     */
    public function delete($id)
    {
        try {
            $data = explode(',',$id);
            for ($i=0; $i < count($data); $i++) {
                dispatch(new \App\Jobs\DeletePayment($data[$i]));
            }

            return response()->json(['message'=>'success'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
}
