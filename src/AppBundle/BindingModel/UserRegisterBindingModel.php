<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 7/11/2018
 * Time: 9:51 PM
 */

namespace AppBundle\BindingModel;

use AppBundle\Constants\Config;
use AppBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class UserRegisterBindingModel
{
    /**
     * @Assert\NotBlank(message="invalidUsername")
     * @Assert\Regex(pattern="/^\w+$/", message="invalidUsername")
     */
    private $username;

    /**
     * @Assert\Email(message="invalidEmailAddress")
     */
    private $email;

    /**
     * @Assert\NotBlank(message="passwordIsIncorrect")
     * @Assert\Length(min=6, minMessage="passwordIsLessThanLength")
     */
    private $password;


    /**
     * @Assert\EqualTo(propertyPath="password", message="passwordsDoNotMatch")
     */
    private $confPassword;

    /**
     * UserRegisterBindingModel constructor.
     */
    public function __construct()
    {

    }


    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
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

    public function forgeUser() : User{
        $user = new User();
        $user->setUsername($this->getUsername());
        $user->setEmail($this->getEmail());
        $user->setPassword($this->getPassword());
        return $user;
    }


}