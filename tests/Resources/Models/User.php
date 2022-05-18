<?php

namespace Railroad\MusoraApi\Tests\Resources\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * User
 *
 * @property integer $id
 * @property string $email
 */
class User extends Authenticatable
{
    protected $table = 'users';

    public function getJWTIdentifier()
    {
        return $this->id;
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function resolveChildRouteBinding($childType, $value, $field)
    {
        // TODO: Implement resolveChildRouteBinding() method.
    }
}
