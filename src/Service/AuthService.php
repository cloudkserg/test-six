<?php
/**
 * Created by PhpStorm.
 * User: dns
 * Date: 14.02.18
 * Time: 15:05
 */

namespace App\Service;


use App\Entity\User;
use App\Exception\AuthException;
use App\Repository\UserRepository;
use FOS\UserBundle\Security\LoginManager;
use FOS\UserBundle\Security\LoginManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AuthService
{
    const FIREWALL='main';

    /**
     * @var UserRepository
     */
    private $repo;
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;
    /**
     * @var LoginManager
     */
    private $loginManager;

    public function __construct(UserRepository $repo, TokenStorageInterface $tokenStorage, LoginManagerInterface $loginManager)
    {
        $this->repo = $repo;
        $this->tokenStorage = $tokenStorage;
        $this->loginManager = $loginManager;
    }


    public function getAuthUser() : User
    {
        $token = $this->tokenStorage->getToken();
        if (!isset($token)) {
            throw new AuthException('Not found auth user');
        }
        $user = $token->getUser();
        if (!isset($user)) {
            throw new AuthException('Not found auth user');
        }
        return $user;
    }

    public function authenticate(User $user)
    {
        $this->loginManager->logInUser(self::FIREWALL, $user);
    }

}