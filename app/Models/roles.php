<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models;
class roles extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $filliable = [
        'name',
        'description',
        'special',
        'deleted_at',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    public function user(){
        return $this->hasMany(Models\User::class, 'role_id', 'id');
    }
    public function permissionsrole(){
        return $this->hasMany(Models\permissionsrole::class,'roles_id','id'); 
    }
}
