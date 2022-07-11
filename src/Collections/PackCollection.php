<?php

namespace Railroad\MusoraApi\Collections;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Railroad\Railcontent\Entities\ContentEntity;

class PackCollection extends Collection
{
    public function sortByUserActivity($userId, $direction = 'desc')
    {
        return $this->sort(
            function (ContentEntity $a, ContentEntity $b) use ($direction, $userId) {
                if (!empty($a['user_progress'][$userId]['updated_on'])
                    && !empty($b['user_progress'][$userId]['updated_on'])
                ) {
                    $aUpdatedOn = Carbon::parse($a['user_progress'][$userId]['updated_on']);
                    $bUpdatedOn = Carbon::parse($b['user_progress'][$userId]['updated_on']);

                    if ($direction == 'desc') {
                        return $aUpdatedOn < $bUpdatedOn ? 1 : -1;
                    } else {
                        return $aUpdatedOn > $bUpdatedOn ? 1 : -1;
                    }
                }

                if (!empty($a['user_progress'][$userId]['updated_on'])
                    && empty($b['user_progress'][$userId]['updated_on'])
                ) {
                    return -1;
                }

                if (!empty($b['user_progress'][$userId]['updated_on'])
                    && empty($a['user_progress'][$userId]['updated_on'])
                ) {
                    return 1;
                }

                return 0;
            }
        );
    }
}