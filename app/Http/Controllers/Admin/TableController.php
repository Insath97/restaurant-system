<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTableRequest;
use App\Http\Requests\UpdateTableRequest;
use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::all();
        return view('admin.table.index', compact('tables'));
    }

    public function create() {}

    public function store(CreateTableRequest $request)
    {
        try {
            $table = new Table();
            $table->name = $request->validated('name');
            $table->code = $request->validated('code');
            $table->capacity = $request->validated('capacity');
            $table->description = $request->validated('description');
            $table->save();

            toast('Table created successfully!', 'success');
            return redirect()->route('admin.tables.index');
        } catch (\Throwable $th) {
            toast($th->getMessage(), 'error');
            return redirect()->back()
                ->with('error', 'Failed to create table: ' . $th->getMessage())
                ->withInput();
        }
    }

    public function show(string $id) {}

    public function edit(string $id)
    {
        $table = Table::findOrFail($id);
        return response()->json($table);
    }

    public function update(UpdateTableRequest $request, string $id)
    {
        try {
            $table = Table::findOrFail($id);
            $table->name = $request->validated('name');
            $table->code = $request->validated('code');
            $table->capacity = $request->validated('capacity');
            $table->description = $request->validated('description');
            $table->save();

            toast('Table Update successfully!', 'success');
            return redirect()->route('admin.tables.index');
        } catch (\Throwable $th) {
            toast($th->getMessage(), 'error');
            return redirect()->back()
                ->with('error', 'Failed to Update table: ' . $th->getMessage())
                ->withInput();
        }
    }

    public function destroy(string $id)
    {
        try {
            $category = Table::findOrFail($id);
            $category->delete();

            return response(['status' => 'success', 'message' => 'Table deleted successfully!']);
        } catch (\Throwable $th) {
            return response(['status' => 'error', 'message' => $th->getMessage()]);
        }
    }

    public function toggleAvailability($id)
    {
        try {
            $table = Table::find($id);

            if (!$table) {
                return response()->json([
                    'success' => false,
                    'message' => 'Table not found'
                ], 404);
            }

            $table->is_available = !$table->is_available;
            $table->save();

            return response()->json([
                'success' => true,
                'is_available' => $table->is_available,
                'message' => 'Availability updated successfully'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating availability: ' . $e->getMessage()
            ], 500);
        }
    }
}
