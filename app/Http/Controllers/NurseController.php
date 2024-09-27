<?php

namespace App\Http\Controllers;

use App\Enums\Genre;
use App\Enums\Status;
use App\Models\Nurse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NurseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $rowsPerPage = (int) $request->query('rowsPerPage') ?? 10;
        $nurses = Nurse::simplePaginate($rowsPerPage);
        $numberOfRows = count($nurses);

        return response()->json([
            'current_page' => $nurses->currentPage(),
            'rowsPerPage' => $rowsPerPage,
            'count' => $numberOfRows,
            'data' => $nurses->items(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validatedRequestBody = $request->validate([
            'name' => 'bail|string|required',
            'genre' => ['bail', 'required', Rule::enum(Genre::class)],
            'email' => 'bail|email|required',
            'phone' => 'bail|string|numeric|required',
            'document_identification' => 'bail|string|numeric|required|unique:nurses',
            'status' => ['bail', 'nullable', Rule::enum(Status::class)],
        ]);

        $createdNurse = Nurse::create($validatedRequestBody);

        return response()->json($createdNurse);
    }

    /**
     * Display the specified resource.
     */
    public function show(Nurse $nurse): JsonResponse
    {
        return response()->json($nurse);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Nurse $nurse): JsonResponse
    {
        $validatedRequestBody = $request->validate([
            'name' => 'bail|string|nullable',
            'genre' => ['bail', 'nullable', Rule::enum(Genre::class)],
            'email' => 'bail|email|nullable',
            'phone' => 'bail|string|numeric|nullable',
            'document_identification' => 'bail|string|numeric|nullable',
            'status' => ['bail', 'nullable', Rule::enum(Status::class)],
        ]);

        $nurse->update($validatedRequestBody);
        return response()->json($nurse);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Nurse $nurse): JsonResponse
    {
        $nurse->delete();
        return response()->json($nurse);
    }

    public function restore(Nurse $nurse): JsonResponse
    {
        if (!$nurse->trashed()) {
            return response()->json(null, 406);
        }

        $nurse->restore();
        return response()->json($nurse);
    }

    public function destroyPermanently(Nurse $nurse): JsonResponse
    {
        if (!$nurse->trashed()) {
            return response()->json(null, 406);
        }

        $nurse->forceDelete();
        return response()->json($nurse);
    }
}
