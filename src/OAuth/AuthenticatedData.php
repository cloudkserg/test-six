<?php
/**
 * Created by PhpStorm.
 * User: dns
 * Date: 16.02.18
 * Time: 16:55
 */

namespace App\OAuth;


class AuthenticatedData
{

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $facebookLink;

    /**
     * @var AuthenticatedData[]
     */
    private $friends;

    /**
     * AuthenticatedData constructor.
     * @param string $username
     * @param AuthenticatedData[] $friends
     */
    public function __construct(string $username,  string $facebookLink, string $email = '',  array $friends = [])
    {
        $this->username = $username;
        if (empty($email)) {
            $email = $this->generateEmail($this->username);
        }
        $this->email = $email;
        $this->friends = $friends;
        $this->facebookLink = $facebookLink;
    }

    private function generateEmail(string $username) : string
    {
        return $username . '@facebook.com';
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }



    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return AuthenticatedData[]
     */
    public function getFriends(): array
    {
        return $this->friends;
    }

    /**
     * @return string
     */
    public function getFacebookLink(): string
    {
        return $this->facebookLink;
    }






}