<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Customer;
use Illuminate\Http\Request;

class GameController extends Controller
{
    // CREATE GAME
    public function createGame(Request $request, $club_id)
    {
        $request->validate([
            'table_id' => 'required',
            'player1_id' => 'required',
            'player2_id' => 'required',
            'billing_type' => 'required|in:per_minute,full_game',
            'price_per_minute' => 'nullable|numeric',
            'full_game_price' => 'nullable|numeric',
        ]);

        $game = Game::create([
            'club_id' => $club_id,
            'table_id' => $request->table_id,
            'player1_id' => $request->player1_id,
            'player2_id' => $request->player2_id,
            'player3_id' => $request->player3_id,
            'player4_id' => $request->player4_id,
            'billing_type' => $request->billing_type,
            'price_per_minute' => $request->price_per_minute,
            'full_game_price' => $request->full_game_price,
            'start_time' => now(),
        ]);

        return response()->json([
            'message' => 'Game created successfully',
            'game' => $game
        ]);
    }


    // COMPLETE GAME + BILLING AUTOMATION
    public function completeGame(Request $request, $game_id)
    {
        $request->validate([
            'winners' => 'required|array',
            'losers' => 'required|array',
        ]);

        $game = Game::find($game_id);

        if (!$game) {
            return response()->json(['message' => 'Game not found'], 404);
        }

        // Save winners & losers
        $game->update([
            'winners' => implode(',', $request->winners),
            'losers' => implode(',', $request->losers),
            'status' => 'completed'
        ]);


        return response()->json([
            "message" => "Game completed successfully.",
        ]);
    }


    // GET ALL GAMES FOR A CLUB
    public function getGames($club_id)
    {
        $games = Game::where('club_id', $club_id)->get();
        return response()->json(['games' => $games]);
    }

    // GET SINGLE GAME
    public function getGameById($game_id)
    {
        $game = Game::find($game_id);

        if (!$game) {
            return response()->json(['message' => 'Game not found'], 404);
        }

        return response()->json(['game' => $game]);
    }

    // UPDATE GAME (not completion)
    public function updateGame(Request $request, $game_id)
    {
        $game = Game::find($game_id);

        if (!$game) {
            return response()->json(['message' => 'Game not found'], 404);
        }

        $game->update($request->all());

        return response()->json([
            'message' => 'Game updated successfully',
            'game' => $game
        ]);
    }

    // DELETE GAME
    public function deleteGame($game_id)
    {
        $game = Game::find($game_id);

        if (!$game) {
            return response()->json(['message' => 'Game not found'], 404);
        }

        $game->delete();

        return response()->json(['message' => 'Game deleted successfully']);
    }
}
