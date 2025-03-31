<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes, HasFactory, HasUuids;

    protected $fillable = ['name', 'email', 'phone', 'document_identification'];

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
}
