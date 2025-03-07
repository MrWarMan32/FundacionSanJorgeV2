<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Provincia extends Model
{
    use HasFactory;

    protected $fillable = ['provincia'];

    public function canton(): HasMany
    {
        return $this->hasMany(Canton::class, 'id_provincia');
    }
}
