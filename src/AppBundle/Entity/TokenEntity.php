<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Exception\InvalidArgumentException;

/**
 * TokenEntity
 *
 * @ORM\Table(name="tokens")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TokenRepository")
 */
class TokenEntity
{
    public static $ACTION_REGISTER = 'register';
    public static $ACTION_RESTORE = 'restore';

    private $salt = '6a3ee06ea92a5b3bf6c8fefbcaa82f8125255b79';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="user_id", type="integer")
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="action", type="string", length=255)
     */
    private $action;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255)
     */
    private $value;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
    * Counstructor of the class
    */
    public function __construct(UserEntity $user, $action) {
        $availableActions = [self::$ACTION_REGISTER, self::$ACTION_RESTORE];
        
        if (!in_array($action, $availableActions)) {
            throw new InvalidArgumentException("An invalid '".$action."' action for token creation!");
        }

        $this->userId = $user->getId();
        $this->action = $action;
        $this->user = $user;
        $this->value = crypt(time(), $this->salt);
        $this->created = new \DateTime();
    }

    /* ===== Public getters and setters ===== */

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return TokenEntity
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Checks whether or not token is for 'register' action
     *
     * @return bool
     */
    public function isForRegister()
    {
        return $this->action === 'register';
    }

    /**
     * Checks whether or not token is for 'restore' action
     *
     * @return bool
     */
    public function isForRestore()
    {
        return $this->action === 'restore';
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return TokenEntity
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return TokenEntity
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /* ===== Entity Relationships ===== */

    /**
    * @ORM\ManyToOne(targetEntity="UserEntity", inversedBy="tokens")
    * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
    */
    private $user;

    /**
     * Get user
     *
     * @return UserEntity
     */
    public function getUser()
    {
        return $this->user;
    }
}

