<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\permissions;
use App\Models\roles;

class permissionsrole extends Model
{
    use HasFactory;
    protected $filliable = [
        'permmissions_id',
        'roles_id'
    ];

    public function permissions()
    {
        return $this->belongsTo(permissions::class, 'permmissions_id');
    }
    public function roles()
    {
        return $this->belongsTo(roles::class, 'roles_id');
    }
}
