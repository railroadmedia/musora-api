<?php

namespace Railroad\MusoraApi\Contracts;


interface ProductProviderInterface
{
    /**
     * @param $slug
     * @return mixed
     */
    public function getPackPrice($slug);

    /**
     * @param $slug
     * @return mixed
     */
    public function getAppleProductId($slug);

    /**
     * @param $slug
     * @return mixed
     */
    public function getGoogleProductId($slug);

    /**
     * @param $id
     * @return mixed
     */
    public function currentUserOwnsPack($id);
}
