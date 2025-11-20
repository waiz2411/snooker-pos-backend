<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    // CREATE TABLE
    public function createTable(Request $request, $club_id)
    {
        $request->validate([
            'table_name' => 'required|string',
            'status' => 'required|string'
        ]);

        $table = Table::create([
            'club_id' => $club_id,
            'table_name' => $request->table_name,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'Table created successfully',
            'table' => $table
        ]);
    }

    // GET ALL TABLES OF A CLUB
    public function getTables($club_id)
    {
        $tables = Table::where('club_id', $club_id)->get();

        return response()->json([
            'tables' => $tables,
        ]);
    }

    // GET SINGLE TABLE
    public function getTable($table_id)
    {
        $table = Table::find($table_id);

        if (!$table) {
            return response()->json(['message' => 'Table not found'], 404);
        }

        return response()->json(['table' => $table]);
    }

    // UPDATE TABLE
    public function updateTable(Request $request, $table_id)
    {
        $table = Table::find($table_id);

        if (!$table) {
            return response()->json(['message' => 'Table not found'], 404);
        }

        $table->update($request->only(['table_name', 'status']));

        return response()->json([
            'message' => 'Table updated successfully',
            'table' => $table
        ]);
    }

    // DELETE TABLE
    public function deleteTable($table_id)
    {
        $table = Table::find($table_id);

        if (!$table) {
            return response()->json(['message' => 'Table not found'], 404);
        }

        $table->delete();

        return response()->json(['message' => 'Table deleted successfully']);
    }
}
