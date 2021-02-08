<?php

namespace Railroad\MusoraApi\Contracts;


interface UserProviderInterface
{
    public function getCurrentUser();

    /**
     * @param $xp
     * @return mixed
     */
    public  function getExperienceRank($xp);

    /**
     * @param $userId
     * @return mixed
     */
    public function getUserXp($userId);
}
