<?php
/**
 * Created by PhpStorm.
 * User: dns
 * Date: 15.02.18
 * Time: 15:33
 */

namespace App\Service;


use App\Entity\User;
use App\Exception\AuthException;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    /**
     * @var UserRepository
     */
    private $repo;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(UserRepository $repo, EntityManagerInterface $entityManager)
    {
        $this->repo = $repo;
        $this->entityManager = $entityManager;
    }

    private function isAllowEdit(User $user, User $authUser)
    {
        return ($user->getId() == $authUser->getId());
    }


    public function saveUser(User $user, User $authUser)
    {
        if (!$this->isAllowEdit($user, $authUser)) {
            throw new AuthException('Не разрешено редактирование данного объекта');
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }


}