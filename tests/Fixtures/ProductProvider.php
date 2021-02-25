<?php
namespace Railroad\MusoraApi\Tests\Fixtures;

use Railroad\MusoraApi\Contracts\ProductProviderInterface;

class ProductProvider implements ProductProviderInterface
{

    public function getPackPrice($slug)
    {
        // TODO: Implement getPackPrice() method.
    }

    public function getAppleProductId($slug)
    {
        // TODO: Implement getAppleProductId() method.
    }

    public function getGoogleProductId($slug)
    {
        // TODO: Implement getGoogleProductId() method.
    }

    public function currentUserOwnsPack($id)
    {
        // TODO: Implement currentUserOwnsPack() method.
    }

    public function getMembershipProductIds()
    {
        // TODO: Implement getMembershipProductIds() method.
    }
}