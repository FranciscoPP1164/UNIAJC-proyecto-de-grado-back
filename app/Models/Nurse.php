<?php
namespace App\Models;

use App\Enums\Genre;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nurse extends Model
{
    use SoftDeletes, HasFactory, HasUuids, SoftDeletes;

    protected $fillable = ['name', 'genre', 'email', 'phone', 'document_identification', 'status'];

    protected $attributes = [
        'status' => Status::Active,
    ];

    protected $casts = [
        'genre' => Genre::class,
        'status' => Status::class,
    ];

    protected $hidden = ['pivot'];

    public function appointments(): BelongsToMany
    {
        return $this->belongsToMany(Appointment::class)->using(AppointmentNurse::class);
    }
}
