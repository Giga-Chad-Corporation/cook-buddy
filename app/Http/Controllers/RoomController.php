<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Building;
use App\Models\Room;

class RoomController extends Controller
{
    /**
     * Get the rooms for a building.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRooms(Request $request)
    {
        $building = Building::find($request->input('building_id'));

        if (!$building) {
            return response()->json(['error' => 'Building not found'], 404);
        }

        $rooms = $building->rooms;

        return response()->json($rooms);
    }

    /**
     * Get the details for a room.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRoomDetails(Request $request)
    {
        $room = Room::find($request->input('room_id'));

        if (!$room) {
            return response()->json(['error' => 'Room not found'], 404);
        }

        return response()->json($room);
    }

    public function getAvailableRooms(Request $request)
    {
        // Get the building id from the request
        $building_id = $request->input('building_id');

        // Fetch the rooms from the database where is_reserved is false and belong to the selected building
        $rooms = Room::where('building_id', $building_id)
            ->where('is_reserved', false)
            ->get();

        // Return the rooms as JSON
        return response()->json($rooms);
    }

}
