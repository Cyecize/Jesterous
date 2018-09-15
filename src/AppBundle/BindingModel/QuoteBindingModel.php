<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/15/2018
 * Time: 2:50 PM
 */

namespace AppBundle\BindingModel;


use Symfony\Component\Validator\Constraints as Assert;

class QuoteBindingModel
{
    /**
     * @Assert\NotNull(message="fieldCannotBeEmpty")
     * @Assert\NotBlank(message="fieldCannotBeEmpty")
     * @Assert\Length(max="255")
     */
    private $bgAuthorName;

    /**
     * @Assert\NotNull(message="fieldCannotBeEmpty")
     * @Assert\NotBlank(message="fieldCannotBeEmpty")
     * @Assert\Length(max="255")
     */
    private $enAuthorName;

    /**
     * @Assert\NotNull(message="fieldCannotBeEmpty")
     * @Assert\NotBlank(message="fieldCannotBeEmpty")
     */
    private $bgQuote;

    /**
     * @Assert\NotNull(message="fieldCannotBeEmpty")
     * @Assert\NotBlank(message="fieldCannotBeEmpty")
     */
    private $enQuote;

    public function __construct()
    {

    }

    /**
     * @return mixed
     */
    public function getBgAuthorName()
    {
        return $this->bgAuthorName;
    }

    /**
     * @param mixed $bgAuthorName
     */
    public function setBgAuthorName($bgAuthorName): void
    {
        $this->bgAuthorName = $bgAuthorName;
    }

    /**
     * @return mixed
     */
    public function getEnAuthorName()
    {
        return $this->enAuthorName;
    }

    /**
     * @param mixed $enAuthorName
     */
    public function setEnAuthorName($enAuthorName): void
    {
        $this->enAuthorName = $enAuthorName;
    }

    /**
     * @return mixed
     */
    public function getBgQuote()
    {
        return $this->bgQuote;
    }

    /**
     * @param mixed $bgQuote
     */
    public function setBgQuote($bgQuote): void
    {
        $this->bgQuote = $bgQuote;
    }

    /**
     * @return mixed
     */
    public function getEnQuote()
    {
        return $this->enQuote;
    }

    /**
     * @param mixed $enQuote
     */
    public function setEnQuote($enQuote): void
    {
        $this->enQuote = $enQuote;
    }


}