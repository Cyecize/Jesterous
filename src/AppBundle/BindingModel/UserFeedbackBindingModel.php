<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/15/2018
 * Time: 12:56 PM
 */

namespace AppBundle\BindingModel;


use Symfony\Component\Validator\Constraints as Assert;

class UserFeedbackBindingModel
{
    /**
     * @Assert\NotNull(message="fieldCannotBeEmpty")
     * @Assert\NotBlank(message="fieldCannotBeEmpty")
     * @Assert\Length(max="50")
     */
    private $name;

    /**
     * @Assert\NotNull(message="fieldCannotBeEmpty"))
     * @Assert\Email(message="invalidEmailAddress")
     * @Assert\Length(max="50")
     */
    private $email;

    /**
     * @Assert\NotNull(message="fieldCannotBeEmpty")
     * @Assert\NotBlank(message="fieldCannotBeEmpty")
     * @Assert\Length(max="1000")
     */
    private $message;

    public function __construct()
    {

    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
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
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message): void
    {
        $this->message = $message;
    }


}