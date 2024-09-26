<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['name', 'age', 'direction', 'document_identification'];

    public function conditions(): HasMany
    {
        return $this->hasMany(Condition::class);
    }

    public function appointments(): BelongsToMany
    {
        return $this->belongsToMany(Appointment::class)->using(AppointmentPatient::class);
    }
}
