<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('u')
            ->where('u.something = :value')->setParameter('value', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function findById($id)
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function findByFacebookLinkOrEmail(string $email, string $link)
    {
        $user = $this->findOneBy(['email' => $email]);
        if (null !== $user) {
            return $user;
        }

        return $this->findOneBy(['facebookLink' => $link]);
    }
}
