<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medication;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;


class MedicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Eager load the user relationship
        $medications = Medication::with('user')->get();
        return response()->json($medications);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0'
        ]);

        $data['user_id'] = Auth::user()->id;

        $medication = Medication::create($data);

        return response()->json($medication, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $medication = Medication::with('user')->findOrFail($id);
            return response()->json($medication);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Medication not found'], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string',
                'description' => 'nullable|string',
                'quantity' => 'required|integer|min:0'
            ]);

            $data['user_id'] = Auth::user()->id;

            $medication = Medication::findOrFail($id);
            $medication->update($data);

            return response()->json($medication);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Medication not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $medication = Medication::findOrFail($id);
            $medication->delete();

            return response()->json(['message' => 'Medication deleted successfully']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Medication not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    public function permanantDelete($id)
    {
        try {
            $medication = Medication::onlyTrashed()->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Medication not found'], 404);
        }

        // Check if the authenticated user has permission
        if (!auth()->user()->role === 'owner') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $medication->forceDelete();

        return response()->json(['message' => 'Medication permanently deleted']);
    }
}
