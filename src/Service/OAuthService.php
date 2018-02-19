<?php
/**
 * Created by PhpStorm.
 * User: dns
 * Date: 16.02.18
 * Time: 16:33
 */

namespace App\Service;



use App\Entity\User;
use App\OAuth\AuthenticatedData;
use App\OAuth\AuthenticatedUser;
use App\OAuth\FacebookProvider;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Model\UserManager;
use FOS\UserBundle\Model\UserManagerInterface;

class OAuthService
{
    /**
     * @var FacebookProvider
     */
    private $provider;
    /**
     * @var UserManager
     */
    private $userManager;
    /**
     * @var AuthService
     */
    private $authService;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(
        FacebookProvider $provider, UserRepository $userRepository,
        UserManagerInterface $userManager,
        AuthService $authService, EntityManagerInterface $entityManager
    )
    {
        $this->provider = $provider;
        $this->userManager = $userManager;
        $this->authService = $authService;
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }


    public function getFacebookLink(): string
    {
        return $this->provider->getUrl();
    }

    public function authenticate(string $state, string $code) : User
    {
        $user = $this->updateUserWithFriends($state, $code);
        $this->authService->authenticate($user);
        return $user;
    }

    private function updateUserWithFriends(string $state, string $code) : User
    {
        $authData = $this->provider->authenticate($code, $state);
        $user = $this->getOrCreateUserByAuthenticateUser($authData);

        $this->addFriends($user, $authData->getFriends());
        return $user;
    }


    /**
     * @param User $user
     * @param AuthenticatedData[] $friends
     */
    private function addFriends(User $user, array $friends)
    {
        foreach ($friends as $friend) {
            $myFriend = $this->getOrCreateUserByAuthenticateUser($friend);
            $user->addMyFriend($myFriend);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }


    private function getOrCreateUserByAuthenticateUser(AuthenticatedData $friend)
    {
        $user = $this->userRepository->findByFacebookLinkOrEmail(
            $friend->getEmail(),$friend->getFacebookLink()
        );
        if (!isset($user)) {
            $user = $this->createUser($friend);
        } else {
            $this->updateUser($user, $friend);
        }
        return $user;
    }

    private function updateUser(User &$user, AuthenticatedData $friend)
    {
        $user->setEmail($friend->getEmail());
        $user->setFacebookLink($friend->getFacebookLink());
    }

    private function createUser(AuthenticatedData $authData)
    {
        $user = $this->userManager->createUser();
        /**
         * @var User $user
         */
        $user->setUsername($authData->getUsername());
        $user->setEmail($authData->getEmail());
        $user->setFacebookLink($authData->getFacebookLink());
        $user->setEnabled(true);
        $user->setPlainPassword($this->generatePassword($authData->getUsername()));
        $this->userManager->updateUser($user);

        return $user;
    }

    private function generatePassword($username)
    {
        return md5(rand(10000, 100000) . time() . $username);
    }


}