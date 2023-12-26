<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class provhosps extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'hospital_id',
        'proveedor_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    public function hospital()
    {
        return $this->belongsTo(hospitales::class, 'hospital_id');
    }
    public function proveedor()
    {
        return $this->belongsTo(proveedors::class, 'proveedor_id');
    }
}
