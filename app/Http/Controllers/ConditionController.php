<?php

namespace App\Http\Controllers;

use App\Models\Condition;
use App\Models\Patient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConditionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function store(Request $request, Patient $patient): JsonResponse
    {
        $validatedRequestBody = $request->validate([
            'description' => 'bail|string|required',
        ]);

        $createdCondition = $patient->conditions()->create($validatedRequestBody);

        return response()->json($createdCondition);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function update(Request $request, Patient $patient, Condition $condition): JsonResponse
    {
        $validatedRequestBody = $request->validate([
            'description' => 'bail|string|nullable',
        ]);

        $condition->update($validatedRequestBody);

        return response()->json($condition);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient, Condition $condition): JsonResponse
    {
        $condition->delete();

        return response()->json($condition);
    }
}
