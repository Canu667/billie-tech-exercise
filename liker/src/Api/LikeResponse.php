<?php
declare(strict_types=1);

namespace App\Api;

class LikeResponse
{
    /**
     * @var int
     */
    private $userId;

    /**
     * @var int
     */
    private $totalLikes;

    /**
     * @var bool
     */
    private $isEmailSent;

    /**
     * @param int  $userId
     * @param int  $totalLikes
     * @param bool $isEmailSent
     */
    public function __construct($userId, $totalLikes, $isEmailSent)
    {
        $this->userId      = $userId;
        $this->totalLikes  = $totalLikes;
        $this->isEmailSent = $isEmailSent;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return int
     */
    public function getTotalLikes(): int
    {
        return $this->totalLikes;
    }

    /**
     * @return bool
     */
    public function isEmailSent(): bool
    {
        return $this->isEmailSent;
    }
}
