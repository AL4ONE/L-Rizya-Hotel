<?php

namespace App\Http\Controllers;

use App\Models\RoomFacility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoomFacilityController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => 200,
            'message' => 'room facilities fetched successfully',
            'data' => RoomFacility::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => "required|unique:room_facilities,name",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 403,
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ]);
        }

        $validated = $validator->validated();
        $facilities = RoomFacility::create($validated);

        return response()->json([
            'status' => 200,
            'message' => 'create facilities room successfully',
            'data' => $facilities
        ]);
    }
}
