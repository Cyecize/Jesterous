<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Banner
 *
 * @ORM\Table(name="banners")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BannerRepository")
 */
class Banner
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
     * @ORM\Column(name="image_link", type="string", length=255)
     */
    private $imageLink;

    /**
     * @var int
     *
     * @ORM\Column(name="order_index", type="integer")
     */
    private $orderIndex;

    public function __construct()
    {
        $this->orderIndex = 0;
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
     * Set imageLink
     *
     * @param string $imageLink
     *
     * @return Banner
     */
    public function setImageLink($imageLink)
    {
        $this->imageLink = $imageLink;

        return $this;
    }

    /**
     * Get imageLink
     *
     * @return string
     */
    public function getImageLink()
    {
        return $this->imageLink;
    }

    /**
     * Set orderIndex
     *
     * @param integer $orderIndex
     *
     * @return Banner
     */
    public function setOrderIndex($orderIndex)
    {
        $this->orderIndex = $orderIndex;

        return $this;
    }

    /**
     * Get orderIndex
     *
     * @return int
     */
    public function getOrderIndex()
    {
        return $this->orderIndex;
    }
}

