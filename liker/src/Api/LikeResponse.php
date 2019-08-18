<?php
declare(strict_types=1);

namespace App\Api;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema()
 */
class LikeResponse
{
    /**
     * @var int
     *
     * @OA\Property(type="int", nullable=false)
     */
    private $userId;

    /**
     * @var int
     *
     * @OA\Property(type="int", nullable=false)
     */
    private $totalLikes;

    /**
     * @var bool
     *
     * @OA\Property(type="boolean", nullable=false)
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
