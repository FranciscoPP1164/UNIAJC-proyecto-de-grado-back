<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use SoftDeletes, HasFactory, HasUuids;

    protected $fillable = ['name', 'birthdate', 'direction', 'document_identification'];

    protected $hidden = ['pivot'];

    public function conditions(): HasMany
    {
        return $this->hasMany(Condition::class);
    }

    public function appointments(): BelongsToMany
    {
        return $this->belongsToMany(Appointment::class)->using(AppointmentPatient::class);
    }
}
