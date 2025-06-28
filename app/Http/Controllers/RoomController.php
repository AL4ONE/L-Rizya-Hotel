<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{
    public function index(){
        $rooms = Room::all();

        return response()->json([
            'status' => 200,
            'message' => "get data success",
            'data' => $rooms,
        ]);
    }

    public function show($id){
        $roomData = Room::where('id', $id)->first();

        return response()->json([
            'status' => 200,
            'message' => "data show success",
            'data' => $roomData,
        ]);
    }

    public function available(Request $request)
    {
        $checkIn = $request->query('check_in');
        $checkOut = $request->query('check_out');

        if (!$checkIn || !$checkOut || $checkOut <= $checkIn) {
            return response()->json([
                'message' => 'Tanggal check-in / check-out tidak valid.'
            ], 422);
        }

        $availableRooms = Room::whereDoesntHave('bookings', function ($query) use ($checkIn, $checkOut) {
            $query->where(function ($q) use ($checkIn, $checkOut) {
                $q->where('check_in', '<', $checkOut)
                    ->where('check_out', '>', $checkIn);
            });
        })->with('facilities')->get();

        return response()->json([
            'available_rooms' => $availableRooms,
            'check_in' => $checkIn,
            'check_out' => $checkOut,
        ]);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price_per_night' => 'required|numeric|min:0',
            'max_guest' => 'required|integer|min:1|max:10',
            'availability' => 'required|in:available,unavailable',
            'wifi' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'facilities' => 'nullable|array',
            'facilities.*' => 'exists:room_facilities,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 403,
                'message' => 'Invalid data',
                'errors' => $validator->errors()
            ]);
        }

        $validated = $validator->validated();

        $facilities = $validated['facilities'] ?? [];
        unset($validated['facilities']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('room_images', 'public');
        }

        $room = Room::create($validated);

        if (!empty($facilities)) {
            $room->roomFacilities()->attach($facilities);
        }

        return response()->json([
            'status' => 200,
            'message' => "Room created successfully",
            'data' => $room,
            'data' => $room->load('roomFacilities'),
        ]);
    }

    public function destroy($id){
        $room = Room::where("id", $id)->first();

        $room->delete();

        return response()->json([
            'status' => 200,
            'message' => "Room deleted successfully",
        ]);
    }
}
