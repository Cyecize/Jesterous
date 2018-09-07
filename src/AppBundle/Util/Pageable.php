<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/7/2018
 * Time: 6:56 PM
 */

namespace AppBundle\Util;


use Symfony\Component\HttpFoundation\Request;

class Pageable
{
    private $size;

    private $page;

    public function __construct(Request $request)
    {
       $this->init($request);
    }

    private function init(Request $request) : void {
        $pageReq = intval($request->get('page'));
        $sizeReq = intval($request->get('size'));
        if ($pageReq < 1) $pageReq = 1;
        if ($sizeReq < 1) $sizeReq = 6;
        $this->setPage($pageReq);
        $this->setSize($sizeReq);
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param mixed $size
     */
    public function setSize($size): void
    {
        $this->size = $size;
    }

    /**
     * @return mixed
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param mixed $page
     */
    public function setPage($page): void
    {
        $this->page = $page;
    }


}