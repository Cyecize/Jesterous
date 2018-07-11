<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Language
 *
 * @ORM\Table(name="languages")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LanguageRepository")
 */
class Language
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="locale_name", type="string", length=10, unique=true)
     */
    private $localeName;

    public function __construct()
    {

    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set localeName
     *
     * @param string $localeName
     *
     * @return Language
     */
    public function setLocaleName($localeName)
    {
        $this->localeName = $localeName;

        return $this;
    }

    /**
     * Get localeName
     *
     * @return string
     */
    public function getLocaleName()
    {
        return $this->localeName;
    }
}

