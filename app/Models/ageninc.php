<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ageninc extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'agenincs';
    protected $fillable = [
        'agente_id',
        'inciso_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    public function inciso()
    {
        return $this->belongsTo(incisos::class, 'inciso_id');
    }
}
