<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'room_id' => 'required|exists:rooms,id',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'personal_request' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 403,
                'message' => "store data failed",
                'errors' => $validator->errors(),
            ]);
        }

        $data = $validator->validated();

        $existingBookings = Booking::where('room_id', $data['room_id'])->get();

        foreach ($existingBookings as $booking) {
            if (
                $data['check_in'] <= $booking->check_out &&
                $data['check_out'] >= $booking->check_in
            ) {
                return response()->json([
                    'message' => 'Kamar sudah dibooking di tanggal tersebut.'
                ], 409);
            }
        }

        $room = Room::find($data['room_id']);
        $pricePerNight = $room->price_per_night;

        $checkIn = Carbon::parse($data['check_in']);
        $checkOut = Carbon::parse($data['check_out']);
        $days = $checkIn->diffInDays($checkOut);

        $totalPrice = $days * $pricePerNight;

        $data['booking_code'] = strtoupper('BK-' . uniqid());
        $data['total_price'] = $totalPrice;

        Booking::create($data);

        return response()->json([
            'status' => 200,
            'message' => "data created success",
            'booking_code' => $data['booking_code'],
            'total_price' => $totalPrice,
            'nights' => $days,
            'price_per_night' => $pricePerNight,
        ]);
    }


    public function getAll(){
        $bookings = Booking::all();

        return response()->json([
            'status' => 200,
            'message' => 'data catch successfully',
            'data' => $bookings,
        ]);
    }
}
