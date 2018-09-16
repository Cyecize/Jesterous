<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/16/2018
 * Time: 3:54 PM
 */

namespace AppBundle\Util;


use Symfony\Component\HttpFoundation\Request;

class PageRequest extends Pageable
{
    public function __construct(int $page = 1, int $size = self::DEFAULT_SIZE)
    {
        parent::__construct(null);
        $this->setSize($size);
        $this->setPage($page);
    }
}