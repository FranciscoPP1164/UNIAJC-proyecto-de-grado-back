<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['name', 'email', 'phone', 'document_identification', 'status'];

    protected $attributes = [
        'status' => Status::Active,
    ];

    protected $casts = [
        'status' => Status::class,
    ];

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
}
