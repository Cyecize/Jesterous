<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/12/2018
 * Time: 10:25 PM
 */

namespace AppBundle\Contracts;


use AppBundle\Entity\GlobalSubscriber;

interface IGlobalSubscriberDbManager
{
    /**
     * @param GlobalSubscriber $subscriber
     */
    public function unsubscribe(GlobalSubscriber $subscriber): void;

    /**
     * @param string $email
     * @return GlobalSubscriber
     */
    public function createSubscriber(string $email): GlobalSubscriber;

    /**
     * @param string $email
     * @return GlobalSubscriber
     */
    public function createSubscriberOnRegister(string $email): GlobalSubscriber;

    /**
     * @param int $id
     * @return GlobalSubscriber
     */
    public function findOneById(int $id): ?GlobalSubscriber;

    /**
     * @param string $email
     * @return GlobalSubscriber
     */
    public function findOneByEmail(string $email) : ?GlobalSubscriber;

    /**
     * @return GlobalSubscriber[]
     */
    public function findAll(): array;
}