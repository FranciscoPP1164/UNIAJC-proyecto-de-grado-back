<?php

namespace App\Models;

use App\Enums\Status;
use App\Enums\UserType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Nette\Utils\Random;

class User extends Authenticatable
{
    use SoftDeletes, HasApiTokens, HasFactory, Notifiable, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'status',
    ];

    protected $attributes = [
        'status' => Status::Active,
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'email_verified_at' => 'datetime',
        'type' => UserType::class,
        'status' => Status::class,
    ];

    protected function accessToken(): Attribute
    {
        return Attribute::make();
    }

    public function renovateVerificationToken(): string
    {
        $verificationToken = Random::generate(6);
        $verificationTokenData = ['token' => $verificationToken];

        if ($this->has('verificationToken')) {
            $this->verificationToken->update($verificationTokenData);
        } else {
            $this->verificationToken()->create($verificationTokenData);
        }

        return $verificationToken;
    }

    public function verifyVerificationToken(string $providedVerificationToken): bool
    {
        $verificationToken = $this->verificationToken;

        if (!$verificationToken) {
            return false;
        }

        return $providedVerificationToken === $verificationToken->token;
    }

    private function verificationToken(): HasOne
    {
        return $this->hasOne(VerificationToken::class);
    }
}
