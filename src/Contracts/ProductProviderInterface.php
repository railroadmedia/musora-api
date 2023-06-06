<?php

namespace Railroad\MusoraApi\Contracts;


interface ProductProviderInterface
{
    /**
     * @param $slug
     * @return array
     */
    public function getPackPrice($slug) :array;

    /**
     * @param $slug
     * @return string
     */
    public function getAppleProductId($slug):string;

    /**
     * @param $slug
     * @return string
     */
    public function getGoogleProductId($slug):string;

    /**
     * @param $id
     * @return bool
     */
    public function currentUserOwnsPack($id):bool;

    /**
     * @return array
     */
    public function getMembershipProductIds():array;

    public function carousel();

    public function getCohortTemplate($slug) : array;

    public function getActiveCohort();

    public function userOwnProduct($productId);
}
