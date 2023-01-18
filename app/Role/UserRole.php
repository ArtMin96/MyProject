<?php

namespace App\Role;


class UserRole {

    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_SHOP_MANAGER = 'ROLE_SHOP_MANAGER';
    const ROLE_SUPPORT = 'ROLE_SUPPORT';
    const ROLE_WAREHOUSE  = 'ROLE_WAREHOUSE';
    const ROLE_BLOG_MODERATOR = 'ROLE_BLOG_MANAGER';
    const ROLE_USER = 'ROLE_USER';

    /**
     * @var array
     */

    protected static $hierarchy = [
        self::ROLE_SUPER_ADMIN => ['*'],
        self::ROLE_ADMIN => [
            self::ROLE_SHOP_MANAGER,
            self::ROLE_BLOG_MODERATOR,
        ],
        self::ROLE_SHOP_MANAGER => [
            self::ROLE_SUPPORT,
            self::ROLE_WAREHOUSE,
        ],
        self::ROLE_BLOG_MODERATOR => [],
        self::ROLE_USER => [],
    ];

    /**
     * @param $role
     * @return array|mixed
     */


    public static function getAllowedRoles(string $role) :array
    {
        if (isset(self::$hierarchy[$role])){
            $roles = self::$hierarchy[$role];
            foreach (self::$hierarchy[$role] as $item){
                if(isset(self::$hierarchy[$item])) {
                    $roles = array_merge($roles,self::$hierarchy[$item]);
                }
            }
            return $roles;
        }

        return [];
    }

    /**
     * @param null $except
     * @return object
     */

    public  static function getAllRoles(array $except = null): object
    {
        $roles = (object)[
            'super_admin' => static::ROLE_SUPER_ADMIN,
            'admin' => static::ROLE_ADMIN,
            'shop_manager' => static::ROLE_SHOP_MANAGER,
            'blog_moderator' => static::ROLE_BLOG_MODERATOR,
            'user' => static::ROLE_USER,
            'support' => static::ROLE_SUPPORT,
            'warehouse' => static::ROLE_WAREHOUSE,
        ];

        if($except) foreach ($except as $item) unset($roles->$item);

        return $roles;
    }
}
