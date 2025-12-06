<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Club;
use Illuminate\Http\Request;
use App\Models\Game;

class CustomerController extends Controller
{
    // Create customer
    public function createCustomer(Request $request, $club_id)
    {
        $club = Club::find($club_id);

        if (!$club) {
            return response()->json(['error' => 'Club not found'], 404);
        }

        $customer = Customer::create([
            'club_id' => $club_id,
            'name' => $request->name,
            'phoneNum' => $request->phoneNum,
            'wins' => $request->wins ?? 0,
            'losses' => $request->losses ?? 0,
            'billed_amount' => $request->billed_amount ?? 0,
            'paid_amount' => $request->paid_amount ?? 0,
        ]);

        return response()->json(['message' => 'Customer created', 'customer' => $customer]);
    }

    // Get all customers under a club
    public function getCustomers($club_id)
    {
        $customers = Customer::where('club_id', $club_id)->get();

        $totalPaid = $customers->sum('paid_amount');
        $totalPending = $customers->sum('pending_amount');

        $today = now()->toDateString();

        $todaysGames = Game::where('club_id', $club_id)
            ->whereDate('start_time', $today)
            ->get();

        foreach ($customers as $customer) {
            $winsToday = 0;
            $lossesToday = 0;

            foreach ($todaysGames as $game) {

                // --- SAFE winners decode ---
                $winners = $game->winners;
                if (!is_array($winners)) {
                    $winners = json_decode($winners, true);
                    $winners = is_array($winners) ? $winners : ($winners ? [$winners] : []);
                }

                // --- SAFE losers decode ---
                $losers = $game->losers;
                if (!is_array($losers)) {
                    $losers = json_decode($losers, true);
                    $losers = is_array($losers) ? $losers : ($losers ? [$losers] : []);
                }

                if (in_array($customer->id, $winners)) {
                    $winsToday++;
                }

                if (in_array($customer->id, $losers)) {
                    $lossesToday++;
                }
            }

            $customer->wins_today = $winsToday;
            $customer->losses_today = $lossesToday;
        }

        return response()->json([
            'customers' => $customers,
            'total_paid_amount' => $totalPaid,
            'total_pending_amount' => $totalPending,
        ]);
    }




    // Get single customer
    public function getCustomer($club_id, $customer_id)
    {
        $customer = Customer::where('club_id', $club_id)->where('id', $customer_id)->first();

        if (!$customer) return response()->json(['error' => 'Customer not found'], 404);

        return response()->json(['customer' => $customer]);
    }

    // Update customer
    public function updateCustomer(Request $request, $club_id, $customer_id)
    {
        $customer = Customer::where('club_id', $club_id)->where('id', $customer_id)->first();

        if (!$customer) return response()->json(['error' => 'Customer not found'], 404);

        $customer->update($request->all());

        return response()->json(['message' => 'Customer updated', 'customer' => $customer]);
    }

    // Delete customer
    public function deleteCustomer($club_id, $customer_id)
    {
        $customer = Customer::where('club_id', $club_id)->where('id', $customer_id)->first();

        if (!$customer) return response()->json(['error' => 'Customer not found'], 404);

        $customer->delete();

        return response()->json(['message' => 'Customer deleted']);
    }
}
