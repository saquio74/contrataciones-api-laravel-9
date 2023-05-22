<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class permissions extends Model
{
    use HasFactory;

    protected $filliable = [
        'slug',
        'name',
        'description',
        'deleted_at',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function permissionsrole()
    {
        return $this->hasMany(permissionsrole::class, 'permmissions_id', 'id');
    }
}
