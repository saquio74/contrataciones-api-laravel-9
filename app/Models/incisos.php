<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class incisos extends Model
{
    use HasFactory;
    protected $table = 'incisos';

    protected $primaryKey = 'id';
    protected $fillable=['inciso','valor'];

}
