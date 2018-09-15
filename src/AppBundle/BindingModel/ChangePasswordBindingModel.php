<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/15/2018
 * Time: 9:30 PM
 */

namespace AppBundle\BindingModel;


use Symfony\Component\Validator\Constraints as Assert;

class ChangePasswordBindingModel
{
    /**
     * @Assert\NotNull(message="fieldCannotBeEmpty")
     */
    private $oldPassword;

    /**
     * @Assert\NotNull(message="fieldCannotBeEmpty")
     * @Assert\Length(min=6, minMessage="passwordIsLessThanLength")
     */
    private $newPassword;

    /**
     * @Assert\NotNull(message="fieldCannotBeEmpty")
     * @Assert\EqualTo(propertyPath="newPassword", message="passwordsDoNotMatch")
     */
    private $confPassword;

    public function __construct()
    {

    }

    /**
     * @return mixed
     */
    public function getOldPassword()
    {
        return $this->oldPassword;
    }

    /**
     * @param mixed $oldPassword
     */
    public function setOldPassword($oldPassword): void
    {
        $this->oldPassword = $oldPassword;
    }

    /**
     * @return mixed
     */
    public function getNewPassword()
    {
        return $this->newPassword;
    }

    /**
     * @param mixed $newPassword
     */
    public function setNewPassword($newPassword): void
    {
        $this->newPassword = $newPassword;
    }

    /**
     * @return mixed
     */
    public function getConfPassword()
    {
        return $this->confPassword;
    }

    /**
     * @param mixed $confPassword
     */
    public function setConfPassword($confPassword): void
    {
        $this->confPassword = $confPassword;
    }

}