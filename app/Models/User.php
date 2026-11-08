<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

#[Fillable(['name', 'email', 'password', 'must_change_password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getActivePermissions(): array
    {
        $output = DB::table('user_roles')
        ->join('role_permissions', 'user_roles.role_id', '=', 'role_permissions.role_id')
        ->join('permissions', 'role_permissions.permission_id', '=', 'permissions.id')
        ->where('user_roles.user_id', $this->id)
        ->where('user_roles.is_active', true)
        ->where('user_roles.start_date', '<=', now())
        ->where(function ($query) {
            $query->whereNull('user_roles.end_date')
                ->orWhere('user_roles.end_date', '>=', now());
        })
        ->selectRaw('UPPER(permissions.name) as permission_name')
        ->distinct()
        ->pluck('permission_name')
        ->toArray();

        return $output;
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }
}
