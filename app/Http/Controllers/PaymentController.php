<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{

    public function submitPaymentProof(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_code' => 'required|exists:bookings,booking_code',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ]);
        }

        $file = $request->file('payment_proof');
        $filename = 'proof_' . $request->booking_code . '_' . time() . '.' . $file->getClientOriginalExtension();
        $relativePath = $file->storeAs('payment_proofs', $filename, 'public');
        $publicPath = 'storage/' . $relativePath;

        $booking = Booking::where('booking_code', $request->booking_code)->first();
        $booking->payment_proof = $publicPath;
        $booking->save();

        $payment = Payment::create([
            'booking_code' => $request->booking_code,
            'payment_proof' => $publicPath,
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Payment proof uploaded successfully',
            'data' => [
                'booking' => $booking,
                'payment' => $payment
            ]
        ]);
    }

    public function getAll(){
        $allProof = Payment::all();

        return response()->json([
            'status' => 200,
            'message' => "data retrified success",
            'data' => $allProof,
        ]);
    }
}
