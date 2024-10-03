<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VerificationToken extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['token'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected $hidden = [
        'token',
    ];

    protected $casts = [
        'token' => 'hashed',
    ];
}
