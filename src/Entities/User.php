<?php

namespace Railroad\MusoraApi\Entities;

class User
{
    private $id;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $displayName;

    /**
     * @var string
     */
    private $profilePictureUrl;

    private $phoneNumber;

    /**
     * User constructor.
     *
     * @param $id
     * @param string $email
     * @param string $displayName
     */
    public function __construct($id, string $email, string $displayName, string $profilePictureUrl='', $phoneNumber = null)
    {
        $this->id = $id;
        $this->email = $email;
        $this->displayName = $displayName;
        $this->profilePictureUrl = $profilePictureUrl;
        $this->phoneNumber = $phoneNumber;

    }

    /**
     * @return int
     */
    public function getId()
    : int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getEmail()
    : string
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getDisplayName()
    : string
    {
        return $this->displayName;
    }

    /**
     * @param $displayName
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }

    /**
     * @return string
     */
    public function getProfilePictureUrl()
    : string
    {
        return $this->profilePictureUrl;
    }

    /**
     * @param $profilePictureUrl
     */
    public function setProfilePictureUrl($profilePictureUrl)
    {
        $this->profilePictureUrl = $profilePictureUrl;
    }

    /**
     * @return int
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @param int $phoneNumber
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = !empty($phoneNumber) ? preg_replace('/[^0-9]/', '', $phoneNumber) : $phoneNumber;
    }


}