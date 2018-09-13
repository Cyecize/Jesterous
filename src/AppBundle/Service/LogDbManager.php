<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/13/2018
 * Time: 11:48 PM
 */

namespace AppBundle\Service;


use AppBundle\Contracts\ILogDbManager;
use AppBundle\Entity\Log;
use Doctrine\ORM\EntityManagerInterface;

class LogDbManager implements ILogDbManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManger;

    /**
     * @var \AppBundle\Repository\LogRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    private $logRepo;

    /**
     * LogDbManager constructor.
     * @param $entityManger
     * @param $logRepo
     */
    public function __construct(EntityManagerInterface $entityManger)
    {
        $this->entityManger = $entityManger;
        $this->logRepo = $entityManger->getRepository(Log::class);
    }


    /**
     * @param string $location
     * @param string $message
     */
    public function log(string $location, string $message): void
    {
        $log = new Log();
        $log->setLocation($location);
        $log->setMessage($message);
        $this->entityManger->persist($log);
        $this->entityManger->flush();
    }
}