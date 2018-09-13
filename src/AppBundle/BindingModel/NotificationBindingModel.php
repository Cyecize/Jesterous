<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/13/2018
 * Time: 8:56 PM
 */

namespace AppBundle\BindingModel;


use Symfony\Component\Validator\Constraints as Assert;

class NotificationBindingModel
{
    /**
     * @Assert\NotNull(message="field cannot be null")
     * @Assert\NotBlank()
     */
    private $message;

    /**
     * @Assert\NotNull()
     * @Assert\NotBlank()
     */
    private $href;

    public function __construct()
    {

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

    /**
     * @return mixed
     */
    public function getHref()
    {
        return $this->href;
    }

    /**
     * @param mixed $href
     */
    public function setHref($href): void
    {
        $this->href = $href;
    }


}