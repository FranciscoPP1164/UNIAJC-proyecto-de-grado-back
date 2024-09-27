<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $rowsPerPage = (int) $request->query('rowsPerPage') ?? 10;
        $clients = Client::simplePaginate($rowsPerPage);
        $numberOfRows = count($clients);

        return response()->json([
            'current_page' => $clients->currentPage(),
            'rowsPerPage' => $rowsPerPage,
            'count' => $numberOfRows,
            'data' => $clients->items(),
        ]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validatedRequestBody = $request->validate([
            'name' => 'bail|string|required',
            'email' => 'bail|email|required',
            'phone' => 'bail|string|numeric|required',
            'document_identification' => 'bail|string|numeric|required|unique:nurses',
            'status' => ['bail', 'nullable', Rule::enum(Status::class)],
        ]);

        $createdClient = Client::create($validatedRequestBody);

        return response()->json($createdClient);

    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client): JsonResponse
    {
        return response()->json($client);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client): JsonResponse
    {
        $validatedRequestBody = $request->validate([
            'name' => 'bail|string|nullable',
            'email' => 'bail|email|nullable',
            'phone' => 'bail|string|numeric|nullable',
            'document_identification' => 'bail|string|numeric|nullable',
            'status' => ['bail', 'nullable', Rule::enum(Status::class)],
        ]);

        $client->update($validatedRequestBody);
        return response()->json($client);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client): JsonResponse
    {
        $client->delete();
        return response()->json($client);
    }

    public function restore(Client $client): JsonResponse
    {
        if (!$client->trashed()) {
            return response()->json(null, 406);
        }

        $client->restore();
        return response()->json($client);
    }

    public function destroyPermanently(Client $client): JsonResponse
    {
        if (!$client->trashed()) {
            return response()->json(null, 406);
        }

        $client->forceDelete();
        return response()->json($client);
    }
}
