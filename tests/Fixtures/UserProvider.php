<?php
namespace Railroad\MusoraApi\Tests\Fixtures;

use Railroad\MusoraApi\Contracts\ChatProviderInterface;
use Railroad\MusoraApi\Contracts\UserProviderInterface;
use Railroad\MusoraApi\Entities\User;

class UserProvider implements UserProviderInterface
{
    public function getCurrentUserMembershipData()
    : array
    {
        // TODO: Implement getCurrentUserMembershipData() method.
    }

    public function getCurrentUserProfileData()
    : array
    {
        // TODO: Implement getCurrentUserProfileData() method.
    }

    public function getCurrentUserExperienceData()
    : array
    {
        // TODO: Implement getCurrentUserExperienceData() method.
    }

    public function setCurrentUserProfilePictureUrl(string $profilePictureUrl)
    : User {
        // TODO: Implement setCurrentUserProfilePictureUrl() method.
    }

    public function setCurrentUserPhoneNumber(string $phoneNumber)
    : User {
        // TODO: Implement setCurrentUserPhoneNumber() method.
    }

    public function setCurrentUserDisplayName(string $displayName)
    : ?User {
        // TODO: Implement setCurrentUserDisplayName() method.
    }

    public function setCurrentUserFirebaseTokens(?string $iosToken, ?string $androidToken)
    {
        // TODO: Implement setCurrentUserFirebaseTokens() method.
    }

    public function getCurrentUser()
    : ?User
    {
        // TODO: Implement getCurrentUser() method.
    }

    public function setReviewDataForCurrentUser(string $deviceType, int $reviewCount)
    {
        // TODO: Implement setReviewDataForCurrentUser() method.
    }

    public function getUsoraCurrentUser()
    {
        // TODO: Implement getUsoraCurrentUser() method.
    }

    public function setAndGetUserTimezone()
    : string
    {
        return '';
    }
}