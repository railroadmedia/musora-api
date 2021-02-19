<?php
namespace Railroad\MusoraApi\Tests\Fixtures;

use Railroad\MusoraApi\Contracts\ChatProviderInterface;
use Railroad\MusoraApi\Contracts\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    public function getCurrentUser(){
        return [];
    }

    /**
     * @param $xp
     * @return mixed
     */
    public  function getExperienceRank($xp){
        return '';
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function getUserXp($userId){
        return 0;
    }
}