<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/18/2018
 * Time: 9:49 PM
 */

namespace AppBundle\ViewModel;


use AppBundle\Entity\Image;
use AppBundle\Util\Page;

class BrowseImagesBindingModel
{
    /**
     * @var Image[]
     */
    private $images;

    /**
     * @var Page
     */
    private $page;

    public function __construct(Page $page)
    {
        $this->page = $page;
        $this->images = $page->getElements();
    }

    /**
     * @return Image[]
     */
    public function getImages(): array
    {
        return $this->images;
    }

    /**
     * @return Page
     */
    public function getPage(): Page
    {
        return $this->page;
    }


}