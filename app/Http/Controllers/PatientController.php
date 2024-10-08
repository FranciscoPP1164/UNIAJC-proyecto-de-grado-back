<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $rowsPerPage = (int) $request->rowsPerPage ?? 10;

        if ($request->name) {
            $patients = Patient::whereLike('name', "%$request->name%")->simplePaginate($rowsPerPage);
        } elseif ($request->documentIdentification) {
            $patients = Patient::whereLike('document_identification', "%$request->documentIdentification%")->simplePaginate($rowsPerPage);
        } else {
            $patients = Patient::simplePaginate($rowsPerPage);
        }

        $numberOfRows = count($patients);

        return response()->json([
            'current_page' => $patients->currentPage(),
            'rowsPerPage' => $rowsPerPage,
            'count' => $numberOfRows,
            'data' => $patients->items(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validatedRequestBody = $request->validate([
            'name' => 'bail|string|required',
            'age' => 'bail|string|numeric|required',
            'direction' => 'bail|string|required',
            'document_identification' => 'bail|string|numeric|required|unique:patients',
        ]);

        $createdPatient = Patient::create($validatedRequestBody);

        return response()->json($createdPatient);
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient): JsonResponse
    {
        return response()->json($patient->load('conditions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient): JsonResponse
    {
        $validatedRequestBody = $request->validate([
            'name' => 'bail|string|nullable',
            'age' => 'bail|string|numeric|nullable',
            'direction' => 'bail|string|nullable',
            'document_identification' => 'bail|string|numeric|nullable',
        ]);

        $patient->update($validatedRequestBody);
        return response()->json($patient);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient): JsonResponse
    {
        $patient->delete();
        return response()->json($patient);
    }

    public function restore(Patient $patient): JsonResponse
    {
        if (!$patient->trashed()) {
            return response()->json(null, 406);
        }

        $patient->restore();
        return response()->json($patient);
    }

    public function destroyPermanently(Patient $patient): JsonResponse
    {
        if (!$patient->trashed()) {
            return response()->json(null, 406);
        }

        $patient->forceDelete();
        return response()->json($patient);
    }
}
