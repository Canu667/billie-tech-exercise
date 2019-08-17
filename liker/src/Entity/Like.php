<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\Table;

/**
 * @Entity(repositoryClass="App\Repository\LikesRepository")
 * @Table(name="likes")
 * @HasLifecycleCallbacks()
 */
class Like
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     * @var int
     */
    private $id;

    /**
     * @Column(name="user_id", type="integer", nullable=false)
     * @var int
     */
    private $userId;

    /**
     * @var int
     * @Column(type="datetime")
     */
    private $createdAt;

    /**
     * @param int $userId
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @PrePersist
     * @throws \Exception
     */
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTime();
    }
}
