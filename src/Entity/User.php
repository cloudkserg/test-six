<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\JoinColumn;
use Gedmo\Mapping\Annotation as Gedmo;

use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("email")
 * @UniqueEntity("facebookLink")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;



    /**
     * @var string
     *
     * @ORM\Column(type="string",nullable=true)
     */
    private $facebookLink;


    /**
     * @var ArrayCollection
     *
     * Many Users have Many Users.
     * @ManyToMany(targetEntity="User", mappedBy="myFriends")
     */
    private $friendsWithMe;

    /**
     * @var ArrayCollection
     *
     * Many Users have many Users.
     * @ORM\ManyToMany(targetEntity="User", inversedBy="friendsWithMe")
     * @JoinTable(name="friends",
     *      joinColumns={@JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="friend_user_id", referencedColumnName="id")}
     *      )
     */
    private $myFriends;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated;

    public function __construct() {
        $this->friendsWithMe = new ArrayCollection();
        $this->myFriends = new ArrayCollection();
        parent::__construct();
        $this->setUsername('username');

    }


    /**
     * @return mixed
     */
    public function getFacebookLink() : string
    {
        return $this->facebookLink;
    }

    /**
     * @param mixed $facebookLink
     */
    public function setFacebookLink($facebookLink): void
    {
        $this->facebookLink = $facebookLink;
    }

    /**
     * @return ArrayCollection
     */
    public function getFriendsWithMe()
    {
        return $this->friendsWithMe;
    }


    public function addMyFriend(User $friend)
    {
        $this->myFriends[] = $friend;
    }

    /**
     * @return ArrayCollection
     */
    public function getMyFriends()
    {
        return $this->myFriends;
    }

    /**
     * @param ArrayCollection $myFriends
     */
    public function setMyFriends(ArrayCollection $myFriends): void
    {
        $this->myFriends = $myFriends;
    }




    /**
     * @return mixed
     */
    public function getCreated() : \DateTime
    {
        return $this->created;
    }

    /**
     * @param mixed $created
     */
    public function setCreated(\DateTime $created): void
    {
        $this->created = $created;
    }

    /**
     * @return mixed
     */
    public function getUpdated() : \DateTime
    {
        return $this->updated;
    }

    /**
     * @param mixed $updated
     */
    public function setUpdated(\DateTime $updated): void
    {
        $this->updated = $updated;
    }


    /**
     * Sets the email.
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->username = $email;

        return parent::setEmail($email);
    }

    /**
     * Set the canonical email.
     *
     * @param string $emailCanonical
     * @return User
     */
    public function setEmailCanonical($emailCanonical)
    {
        $this->usernameCanonical = $emailCanonical;

        return parent::setEmailCanonical($emailCanonical);
    }

    public function setUsername($username)
    {
        return $this;
    }

    public function setUsernameCanonical($usernameCanonical)
    {
        return $this;
    }


}
