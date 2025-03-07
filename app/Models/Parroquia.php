<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Parroquia extends Model
{
    use HasFactory;
    protected $table = 'parroquia';

    protected $fillable = ['parroquia', 'id_canton'];

    public function canton(): BelongsTo
    {
        return $this->belongsTo(Canton::class, 'id_canton');
    }
}
