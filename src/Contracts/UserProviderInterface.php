<?php

namespace Railroad\MusoraApi\Contracts;

use Railroad\MusoraApi\Entities\User;

interface UserProviderInterface
{
    /**
     * @return User|null
     */
    public function getCurrentUser()
    : ?User;

    /**
     * @param string|null $brand
     * @return array
     */
    public function getCurrentUserMembershipData(?string $brand)
    : array;

    /**
     * @return array
     */
    public function getCurrentUserProfileData()
    : array;

    /**
     * @return array
     */
    public function getCurrentUserExperienceData()
    : array;

    /**
     * @param string $profilePictureUrl
     * @return User
     */
    public function setCurrentUserProfilePictureUrl(?string $profilePictureUrl)
    : User;

    /**
     * @param string $phoneNumber
     * @return User
     */
    public function setCurrentUserPhoneNumber(string $phoneNumber)
    : User;

    /**
     * @param string $displayName
     * @return User|null
     */
    public function setCurrentUserDisplayName(string $displayName)
    : ?User;

    /**
     * @param string|null $iosToken
     * @param string|null $androidToken
     * @return mixed
     */
    public function setCurrentUserFirebaseTokens(?string $iosToken, ?string $androidToken);

    /**
     * @param string $deviceType
     * @param int $reviewCount
     * @return mixed
     */
    public function setReviewDataForCurrentUser(string $deviceType, int $reviewCount);

    /**
     * @return mixed
     */
    public function getUsoraCurrentUser();

    /**
     * @return string
     */
    public function setAndGetUserTimezone()
    : string;

    public function deleteAccount();

    public function getAuthKey();

    public function getUserAfterRevenuecatPurchase($email, $password, $revenuecatOriginalAppUserId);

    public function getAllBranchInformation();

    public function getAccessibleFeatures();
}
