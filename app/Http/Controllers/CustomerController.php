<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Club;
use Illuminate\Http\Request;

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
        // Get all customers of this club
        $customers = Customer::where('club_id', $club_id)->get();

        // Calculate totals
        $totalPaid = $customers->sum('paid_amount');
        $totalPending = $customers->sum('pending_amount');

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
