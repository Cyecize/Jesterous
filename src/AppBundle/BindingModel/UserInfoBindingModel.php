<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 10/16/2018
 * Time: 9:13 PM
 */

namespace AppBundle\BindingModel;


use Symfony\Component\Validator\Constraints as Assert;

class UserInfoBindingModel
{
    /**
     * @Assert\Length(max="1000", maxMessage="fieldTooLong")
     */
    private $userDescription;

    /**
     * @Assert\Length(max="50", maxMessage="fieldTooLong")
     */
    private $nickname;

    public function __construct()
    {

    }

    /**
     * @return mixed
     */
    public function getUserDescription()
    {
        return $this->userDescription;
    }

    /**
     * @param mixed $userDescription
     */
    public function setUserDescription($userDescription): void
    {
        $this->userDescription = $userDescription;
    }

    /**
     * @return mixed
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * @param mixed $nickname
     */
    public function setNickname($nickname): void
    {
        $this->nickname = $nickname;
    }
}