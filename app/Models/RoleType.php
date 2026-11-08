<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoleType extends Model
{
    use SoftDeletes;
    protected $table = 'role_types';
    protected $fillable = [
        'name',
        'description',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'is_deleted',
        'deleted_at',
    ];

    public function roles()
    {
        return $this->hasMany(Role::class, 'role_type_id', 'id');
    }
}
