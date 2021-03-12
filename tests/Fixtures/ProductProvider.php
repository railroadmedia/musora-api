<?php
namespace Railroad\MusoraApi\Tests\Fixtures;

use Railroad\MusoraApi\Contracts\ProductProviderInterface;

class ProductProvider implements ProductProviderInterface
{

    public function getPackPrice($slug)
    : array {
        return [];
    }

    public function getAppleProductId($slug)
    : string {
        return '';
    }

    public function getGoogleProductId($slug)
    : string {
        return '';
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