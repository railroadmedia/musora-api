<?php
namespace Railroad\MusoraApi\Tests\Fixtures;

use Railroad\MusoraApi\Contracts\ProductProviderInterface;

class ProductProvider implements ProductProviderInterface
{

    public function getPackPrice($slug)
    : array {
        // TODO: Implement getPackPrice() method.
    }

    public function getAppleProductId($slug)
    : string {
        // TODO: Implement getAppleProductId() method.
    }

    public function getGoogleProductId($slug)
    : string {
        // TODO: Implement getGoogleProductId() method.
    }

    public function currentUserOwnsPack($id)
    : bool {
        // TODO: Implement currentUserOwnsPack() method.
    }

    public function getMembershipProductIds()
    : array
    {
        // TODO: Implement getMembershipProductIds() method.
    }
}