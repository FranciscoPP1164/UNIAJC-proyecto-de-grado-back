<?php
namespace App\Http\Controllers;

use App\Enums\Status;
use App\Enums\UserType;
use App\Mail\CreatedAccountNotification;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $rowsPerPage = (int) $request->query('rowsPerPage') ?? 10;
        $users = User::simplePaginate($rowsPerPage);
        $numberOfRows = count($users);

        return response()->json([
            'current_page' => $users->currentPage(),
            'rowsPerPage' => $rowsPerPage,
            'count' => $numberOfRows,
            'data' => $users->items(),
        ]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validatedRequestBody = $request->validate([
            'name' => 'bail|string|required',
            'email' => 'bail|email|required|unique:users,email',
            'type' => ['bail', 'required', Rule::enum(UserType::class)],
            'status' => ['bail', 'nullable', Rule::enum(Status::class)],
        ]);

        $createdUser = User::create($validatedRequestBody);

        Mail::to($createdUser->email)->send(new CreatedAccountNotification($createdUser));

        return response()->json($createdUser, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): JsonResponse
    {
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user): JsonResponse
    {
        $validatedRequestBody = $request->validate([
            'name' => 'bail|string|nullable',
            'email' => 'bail|email|nullable',
            'type' => ['bail', 'nullable', Rule::enum(UserType::class)],
            'status' => ['bail', 'nullable', Rule::enum(Status::class)],
        ]);

        $user->update($validatedRequestBody);
        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): JsonResponse
    {
        $user->delete();
        return response()->json($user);

    }
}
