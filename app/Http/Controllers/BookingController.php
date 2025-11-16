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
            'check_in' => 'required|date|after_or_equal:today',
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

        // Validate dates are not in the past
        $today = Carbon::today();
        $checkInDate = Carbon::parse($data['check_in']);
        $checkOutDate = Carbon::parse($data['check_out']);

        if ($checkInDate->lt($today)) {
            return response()->json([
                'status' => 422,
                'message' => 'Check-in date cannot be in the past.',
            ], 422);
        }

        // Check for overlapping bookings
        $existingBookings = Booking::where('room_id', $data['room_id'])->get();

        foreach ($existingBookings as $booking) {
            $existingCheckIn = Carbon::parse($booking->check_in);
            $existingCheckOut = Carbon::parse($booking->check_out);

            // Check if dates overlap
            // Overlap occurs if: new_check_in < existing_check_out AND new_check_out > existing_check_in
            if (
                $checkInDate->lt($existingCheckOut) &&
                $checkOutDate->gt($existingCheckIn)
            ) {
                return response()->json([
                    'status' => 409,
                    'message' => 'Kamar sudah dibooking di tanggal tersebut. Silakan pilih tanggal lain.'
                ], 409);
            }
        }

        $room = Room::find($data['room_id']);
        $pricePerNight = $room->price_per_night;

        $days = $checkInDate->diffInDays($checkOutDate);
        $totalPrice = $days * $pricePerNight;

        $data['booking_code'] = strtoupper('BK-' . uniqid());
        $data['total_price'] = $totalPrice;

        $booking = Booking::create($data);
        
        return response()->json([
            'status' => 200,
            'message' => "data created success",
            'booking_code' => $data['booking_code'],
            'total_price' => $totalPrice,
            'nights' => $days,
            'price_per_night' => $pricePerNight,
        ]);
    }


    public function getAll() {
        $bookings = Booking::with('room')->get();
    
        return response()->json([
            'status' => 200,
            'message' => 'data catch successfully',
            'data' => $bookings,
        ]);
    }
    
    
}
