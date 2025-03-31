<?php
namespace App\Models;

use App\Enums\Status;
use App\Enums\UserType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Nette\Utils\Random;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

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

    public function isRoot(): bool
    {
        return $this->type === UserType::Root;
    }

    public function renovateVerificationToken(): string
    {
        $verificationToken = Random::generate(32);
        $verificationTokenData = ['token' => $verificationToken];

        if ($this->verificationToken) {
            $this->verificationToken->update($verificationTokenData);
        } else {
            $this->verificationToken()->create($verificationTokenData);
        }

        return $verificationToken;
    }

    public function verifyVerificationToken(string $providedVerificationToken): bool
    {
        $verificationToken = $this->verificationToken;

        if (! $verificationToken) {
            return false;
        }

        return Hash::check($providedVerificationToken, $verificationToken->token);
    }

    public function verificationToken(): HasOne
    {
        return $this->hasOne(VerificationToken::class);
    }
}
