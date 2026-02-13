<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'RoleID',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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

    public function role()
    {
        return $this->belongsTo(Role::class, 'RoleID');
    }

    public function hasPermission(string $entityName, string $actionName): bool
    {
        if (!$this->role) {
            return false;
        }

        $cacheKey = "role_permissions_{$this->RoleID}";

        $permissions = Cache::remember($cacheKey, 60 * 60, function () {
            return DB::table('rolesrights')
                ->join('entities', 'rolesrights.entity_id', '=', 'entities.EntityID')
                ->join('actions', 'rolesrights.action_id', '=', 'actions.ActionID')
                ->where('rolesrights.role_id', $this->RoleID)
                ->select(DB::raw("CONCAT(entities.EntityName, '.', actions.ActionName) as permission_key"))
                ->pluck('permission_key')
                ->toArray();
        });

        return in_array("{$entityName}.{$actionName}", $permissions);
    }


}
