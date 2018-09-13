<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/13/2018
 * Time: 11:06 PM
 */

namespace AppBundle\Contracts;


interface IMailSenderManager
{
    /**
     * @param string $subject
     * @param string $message
     * @param string $receiver
     */
    public function sendText(string $subject, string $message, string $receiver) : void ;

    /**
     * @param string $subject
     * @param $content
     * @param string $receiver
     */
    public function sendHtml(string $subject, $content, string $receiver) : void ;
}