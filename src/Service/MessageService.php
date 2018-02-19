<?php
/**
 * Created by PhpStorm.
 * User: dns
 * Date: 14.02.18
 * Time: 15:36
 */

namespace App\Service;


use App\Entity\Message;
use App\Entity\User;
use App\Exception\AuthException;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;

class MessageService
{
    const DEFAULT_LIMIT = 50;
    /**
     * @var MessageRepository
     */
    private $repo;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * MessageService constructor.
     */
    public function __construct(MessageRepository $repo, EntityManagerInterface $entityManager)
    {
        $this->repo = $repo;
        $this->entityManager = $entityManager;
    }


    private function isAllowEditMessage(Message $message, User $user)
    {
        return (is_null($message->getUser()) or $message->getUser()->getId() == $user->getId());
    }

    public function saveMessage(Message $message,User $authUser)
    {

        if (!$this->isAllowEditMessage($message, $authUser)) {
            throw new AuthException('Не разрешено редактирование данного объекта');
        }

        $message->setUser($authUser);
        $this->entityManager->persist($message);
        $this->entityManager->flush();
    }


    public function getMessages() : array
    {
        return $this->repo->findItems(self::DEFAULT_LIMIT);
    }

}