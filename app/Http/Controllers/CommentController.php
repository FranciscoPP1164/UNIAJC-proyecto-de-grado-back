<?php

namespace App\Http\Controllers;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Appointment $appointment): JsonResponse
    {
        if ($appointment->status !== AppointmentStatus::Started) {
            return response()->json([
                'message' => "this appointment has not been started",
            ], 406);
        }

        $validatedRequestBody = $request->validate([
            'description' => 'bail|string|required',
        ]);

        $createdComment = $appointment->comments()->create($validatedRequestBody);

        return response()->json($createdComment, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment, Comment $comment): JsonResponse
    {
        $validatedRequestBody = $request->validate([
            'description' => 'bail|string|nullable',
        ]);

        $comment->update($validatedRequestBody);

        return response()->json($comment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment, Comment $comment): JsonResponse
    {
        $comment->delete();

        return response()->json($comment);
    }
}
