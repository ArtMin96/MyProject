<?php

namespace App\Role;

use App\User;


class RoleChecker
{
    /**
     * @param User $user
     * @param $role
     * @return bool|mixed
     */
    public function check(User $user,$role)
    {
        if ($user->hasRole(UserRole::ROLE_SUPER_ADMIN)) return true;

        else if($user->hasRole(UserRole::ROLE_ADMIN)) {
            $AdminRoles = UserRole::getAllowedRoles(UserRole::ROLE_ADMIN);
            if (in_array($role, $AdminRoles)) return true;
        }
        else if($user->hasRole(UserRole::ROLE_SHOP_MANAGER)){
            $ShopRoles = UserRole::getAllowedRoles(UserRole::ROLE_SHOP_MANAGER);
            if (in_array($role, $ShopRoles)) return true;
        }
        return $user->hasRole($role);
    }
}
