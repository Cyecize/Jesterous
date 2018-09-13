<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/12/2018
 * Time: 10:31 PM
 */

namespace AppBundle\Service;


use AppBundle\Contracts\IGlobalSubscriberDbManager;
use AppBundle\Entity\GlobalSubscriber;
use AppBundle\Exception\IllegalArgumentException;
use Doctrine\ORM\EntityManagerInterface;

class GlobalSubscriberDbManager implements IGlobalSubscriberDbManager
{
    private $entityManager;

    private $subscriberRepo;

    private $lang;

    public function __construct(EntityManagerInterface $em, LocalLanguage $localLanguage)
    {
        $this->entityManager = $em;
        $this->subscriberRepo = $em->getRepository(GlobalSubscriber::class);
        $this->lang = $localLanguage;
    }

    /**
     * @param GlobalSubscriber $subscriber
     */
    public function unsubscribe(GlobalSubscriber $subscriber): void
    {
        $subscriber->setIsSubscribed(false);
        $this->entityManager->merge($subscriber);
        $this->entityManager->flush();
    }

    /**
     * @param string $email
     * @return GlobalSubscriber
     * @throws IllegalArgumentException
     */
    public function createSubscriber(string $email): GlobalSubscriber
    {
        //TODO if sub unsubbed, resub him, not throw exception
        if ($this->findOneByEmail($email) != null)
            throw new IllegalArgumentException($this->lang->emailAlreadyInUse());
        $sub = new GlobalSubscriber();
        $sub->setEmail($email);
        $this->entityManager->persist($sub);
        $this->entityManager->flush();
        return $sub;
    }

    /**
     * @param string $email
     * @return GlobalSubscriber
     * @throws IllegalArgumentException
     */
    public function createSubscriberOnRegister(string $email): GlobalSubscriber
    {
        $sub = $this->findOneByEmail($email);
        if ($sub != null) {
            $sub->setIsSubscribed(true);
            $this->entityManager->merge($sub);
            $this->entityManager->flush();
        } else {
            $sub = $this->createSubscriber($email);
        }
        return $sub;
    }

    /**
     * @param int $id
     * @return GlobalSubscriber
     */
    public function findOneById(int $id): ?GlobalSubscriber
    {
        return $this->subscriberRepo->findOneBy(array('id' => $id, 'isSubscribed' => true));
    }

    /**
     * @param string $email
     * @return GlobalSubscriber
     */
    public function findOneByEmail(string $email): ?GlobalSubscriber
    {
        return $this->subscriberRepo->findOneBy(array('email' => $email, 'isSubscribed' => true));
    }

    /**
     * @return GlobalSubscriber[]
     */
    public function findAll(): array
    {
        return $this->subscriberRepo->findBy(array('isSubscribed' => true));
    }
}