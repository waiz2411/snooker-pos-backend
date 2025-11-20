<?php

namespace App\Http\Controllers;

use App\Models\Club;
use Illuminate\Http\Request;

class ClubController extends Controller
{
    public function deleteClub($id)
    {
        $club = Club::find($id);

        if (!$club) {
            return response()->json(['error' => 'Club not found'], 404);
        }

        $club->delete();

        return response()->json(['message' => 'Club deleted successfully']);
    }

    public function updateClub(Request $request, $id)
    {
        $club = Club::find($id);

        if (!$club) {
            return response()->json(['error' => 'Club not found'], 404);
        }

        $request->validate([
            'email' => 'email',
            'password' => 'nullable|min:6',
        ]);

        // Update fields
        $club->club_name = $request->club_name ?? $club->club_name;
        $club->owner_name = $request->owner_name ?? $club->owner_name;
        $club->email = $request->email ?? $club->email;
        $club->phone = $request->phone ?? $club->phone;
        $club->account_status = $request->account_status ?? $club->account_status;
        $club->payment_status = $request->payment_status ?? $club->payment_status;
        $club->last_paid = $request->last_paid ?? $club->last_paid;
        $club->expiry_date = $request->expiry_date ?? $club->expiry_date;

        // Only update password if provided
        if ($request->password) {
            $club->password = Hash::make($request->password);
        }

        $club->save();

        return response()->json([
            'message' => 'Club updated successfully',
            'club' => $club
        ]);
    }

    public function getAllClubs()
    {
        $clubs = Club::all();

        return response()->json([
            'message' => 'All clubs fetched successfully',
            'clubs' => $clubs
        ]);
    }

    public function getClubById(Request $request, $id)
    {
        // Optional: Only allow users to view their own club 
        if ($request->user()->id !== (int)$id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    
        $club = Club::find($id);
    
        if (!$club) {
            return response()->json([
                'message' => 'Club not found',
            ], 404);
        }
    
        return response()->json([
            'club' => $club
        ]);
    }




}
