<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //
    public function hasPermission($permission) : bool
    {
        return $this->permission[$permission] ?? false;
    }
    
    public function hasAccess(array $permissions) : bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }
    
    public function hasRole($roleSlug) : bool
    {
        return $this->roles()->where(column: 'slug', $roleSlug)->count() == 1;
    }
}
