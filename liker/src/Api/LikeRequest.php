<?php
declare(strict_types=1);

namespace App\Api;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serialization;

class LikeRequest
{
    /**
     * @Serialization\Type("int")
     *
     * @Assert\NotNull()
     * @Assert\Type("int")
     * @Assert\GreaterThan("0")
     *
     * @var int
     */
    private $userId;

    /**
     * @param int $user_id
     */
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }
}
