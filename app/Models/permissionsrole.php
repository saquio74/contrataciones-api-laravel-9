<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\permissions;
class permissionsrole extends Model
{
    use HasFactory;
    protected $filliable = [
        'permmissions_id',
        'roles_id'
    ];

    public function permissions(){
        return $this->belongsTo(permissions::class, 'permmissions_id');
    }
}
