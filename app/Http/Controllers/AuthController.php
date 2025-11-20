<?php

namespace App\Http\Controllers;

use App\Models\Club;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // SIGNUP
    public function signup(Request $request)
    {
        $request->validate([
            'club_name' => 'required',
            'owner_name' => 'required',
            'email' => 'required|email|unique:clubs',
            'password' => 'required|min:6'
        ]);

        $club = Club::create([
            'club_name' => $request->club_name,
            'owner_name' => $request->owner_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'account_status' => true,
            'payment_status' => false,
            'expiry_date' => now(), // sets default expiry today
        ]);

        return response()->json(['message' => 'Club registered successfully'], 201);
    }

    // LOGIN
    public function login(Request $request)
    {
        $club = Club::where('email', $request->email)->first();

        if (!$club || !Hash::check($request->password, $club->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        // Block inactive clubs
        if (!$club->account_status) {
            return response()->json(['error' => 'Account inactive. Contact support.'], 403);
        }

        // Auto update payment status
        if ($club->expiry_date && $club->expiry_date < now()) {
            $club->payment_status = false;
            $club->save();
        }

        // Block unpaid clubs
        if (!$club->payment_status) {
            return response()->json(['error' => 'Payment expired'], 403);
        }

        $token = $club->createToken('club_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'club' => $club
        ]);
    }
}
