<?php

namespace Railroad\MusoraApi\Contracts;

interface RailTrackerProviderInterface
{
    public function trackMediaType($type, $category)
    : string;

    public function trackMediaPlaybackStart(
        $mediaId,
        $mediaLengthSeconds,
        $userId,
        $typeId,
        $currentSecond = 0,
        $secondsPlayed = 0,
        $startedOn = null
    )
    : array;

    public function trackMediaPlaybackProgress(
        $sessionId,
        $secondsPlayed,
        $currentSecond,
        $lastUpdatedOn = null
    )
    : bool|array|null;

}
