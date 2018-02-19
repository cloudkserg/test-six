<?php
/**
 * Created by PhpStorm.
 * User: dns
 * Date: 16.02.18
 * Time: 16:39
 */

namespace App\OAuth;


use League\OAuth2\Client\Provider\Facebook;
use League\OAuth2\Client\Provider\FacebookUser;
use League\OAuth2\Client\Token\AccessToken;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class FacebookProvider
{

    const STATE_NAME = 'facebook2state';
    /**
     * @var Facebook
     */
    private $core;
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var \Facebook\Facebook
     */
    private $facebook;

    public function __construct(Facebook $core, \Facebook\Facebook $facebook, SessionInterface $session)
    {
        $this->core = $core;

        $this->session = $session;
        $this->facebook = $facebook;
    }


    public function getUrl() : string
    {
        // If we don't have an authorization code then get one
        $authUrl = $this->core->getAuthorizationUrl([
            'scope' => ['email', 'user_friends'],
        ]);
        $this->session->set(self::STATE_NAME, $this->core->getState());
        return $authUrl;
    }


    public function authenticate(string $code, string $state) : AuthenticatedData
    {
        $token = $this->core->getAccessToken('authorization_code', [
            'code' => $code
        ]);

        // We got an access token, let's now get the user's details
        $user = $this->core->getResourceOwner($token);
        /**
         * @var FacebookUser $user
         */
        $friendLists  = $this->getFriendsLists($token);
        $friends = $this->getAuthenticatedDataForFriends($friendLists);
        return new AuthenticatedData($user->getName(), $user->getId(), $user->getEmail(), $friends);
    }

    private function getFriendsLists(AccessToken $token) :  array
    {
        $response = $this->facebook->get('/me/taggable_friends?fields=name&limit=100', $token->getToken());
        $b = $response->getDecodedBody();
        return $b['data'];
    }

    private function getAuthenticatedDataForFriends(array $friendsLists) : array
    {
        $myFriends = [];

        foreach ($friendsLists as $friend) {
            $myFriends[] = new AuthenticatedData(
                $friend['name'], $friend['id']
            );
        }

        return $myFriends;
    }

}