<?php

namespace App\Models;

use App\Enums\AppointmentStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use SoftDeletes, HasFactory, HasUuids;

    protected $fillable = ['tittle', 'description', 'color', 'text_color', 'start_datetime', 'end_datetime', 'status'];

    protected $attributes = [
        'status' => AppointmentStatus::Pending,
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'status' => AppointmentStatus::class,
    ];

    public function nurses(): BelongsToMany
    {
        return $this->belongsToMany(Nurse::class)->using(AppointmentNurse::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function patients(): BelongsToMany
    {
        return $this->belongsToMany(Patient::class)->using(AppointmentPatient::class);
    }
}
