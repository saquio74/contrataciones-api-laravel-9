<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class agenfac extends Model
{
    use HasFactory;
    protected $table = 'agenfac';
    protected $primaryKey = 'id';
    protected $fillable = [
        'leg',
        'periodo',
        'anio',
        'horas',
        'inc',
        'valor',
        'bonificacion',
        'subtot',
        'bonvalor',
        'total',
        'hospital',
    ];
}
