<?php

namespace App\Models;

use App\Enums\Genre;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Nurse extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['name', 'genre', 'email', 'phone', 'document_identification', 'status'];

    protected $attributes = [
        'status' => Status::Active,
    ];

    protected $casts = [
        'genre' => Genre::class,
        'status' => Status::class,
    ];

    public function appointments(): BelongsToMany
    {
        return $this->belongsToMany(Appointment::class)->using(AppointmentNurse::class);
    }
}
