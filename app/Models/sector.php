<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sector extends Model
{
    use HasFactory;
    protected $table = 'sector';

    protected $primaryKey = 'id';
    protected $fillable=['sector'];
}
