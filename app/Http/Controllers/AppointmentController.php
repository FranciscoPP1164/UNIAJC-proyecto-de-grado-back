<?php

namespace App\Http\Controllers;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Patient;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $rowsPerPage = (int) $request->query('rowsPerPage') ?? 10;
        $appointments = Appointment::simplePaginate($rowsPerPage);
        $numberOfRows = count($appointments);

        return response()->json([
            'current_page' => $appointments->currentPage(),
            'rowsPerPage' => $rowsPerPage,
            'count' => $numberOfRows,
            'data' => $appointments->items(),
        ]);
    }

    private function storePatients(array $patients): array
    {
        $storedPatientsIDS = [];

        foreach ($patients as $patient) {
            $createdPatient = Patient::create($patient);

            $storedPatientsIDS[] = $createdPatient->id;

            if (!array_key_exists('conditions', $patient)) {
                continue;
            }

            foreach ($patient['conditions'] as $condition) {
                $createdPatient->conditions()->create([
                    'description' => $condition,
                ]);
            }
        }

        return $storedPatientsIDS;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'tittle' => 'bail|string|required',
            'description' => 'bail|string|required',
            'color' => 'bail|hex_color|required',
            'text_color' => 'bail|hex_color|required',
            'start_datetime' => 'bail|string|date|date_format:Y-m-d\\TH:i:s|required',
            'end_datetime' => 'bail|string|date|date_format:Y-m-d\\TH:i:s|required',
            'status' => ['bail', 'nullable', Rule::enum(AppointmentStatus::class)],

            'client_id' => 'bail|string|required|uuid|exists:clients,id',

            'nurses_ids' => 'array|min:1|required',
            'nurses_ids.*' => 'bail|string|uuid|exists:nurses,id',

            'patients_ids' => 'array|min:1|required_without:patients',
            'patients_ids.*' => 'bail|string|uuid|exists:patients,id',

            'patients' => 'array|min:1|required_without:patients_ids',
            'patients.*.name' => 'bail|string|required',
            'patients.*.age' => 'bail|string|numeric|required',
            'patients.*.direction' => 'bail|string|required',
            'patients.*.document_identification' => 'bail|string|numeric|required|unique:patients',

            'patients.*.conditions' => 'array|nullable|min:1',
            'patients.*.conditions.*' => 'bail|string',
        ]);

        $existsShockingAppointments = Appointment::whereHas('nurses', function (Builder $query) use ($request) {
            $query->
                whereBetween('appointments.start_datetime', [$request->start_datetime, $request->end_datetime])->whereIn('nurses.id', $request->nurses_ids)->
                orWhereBetween('appointments.end_datetime', [$request->start_datetime, $request->end_datetime])->whereIn('nurses.id', $request->nurses_ids)->
                orWhere('appointments.start_datetime', '<', $request->start_datetime)->where('appointments.end_datetime', '>', $request->end_datetime)->whereIn('nurses.id', $request->nurses_ids);
        })->exists();

        if ($existsShockingAppointments) {
            return response()->json([
                'message' => 'There is one or more appointments that interfere with the specified date or time',
            ], 406);
        }

        $client = Client::find($request->client_id);
        $createdAppointment = $client->appointments()->create($request->only(['tittle', 'description', 'color', 'text_color', 'start_datetime', 'end_datetime', 'status']));

        if ($request->patients) {
            $storedPatientsIDS = $this->storePatients($request->patients);
            $createdAppointment->patients()->attach($storedPatientsIDS);
        }

        if ($request->patients_ids) {
            $createdAppointment->patients()->attach($request->patients_ids);
        }

        $createdAppointment->nurses()->attach($request->nurses_ids);

        $createdAppointment->load(['client', 'nurses', 'patients', 'patients.conditions']);

        return response()->json($createdAppointment);
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment): JsonResponse
    {
        $appointment->load(['client', 'nurses', 'patients', 'patients.conditions']);
        return response()->json($appointment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment): JsonResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment): JsonResponse
    {
        //
    }
}
