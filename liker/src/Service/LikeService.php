<?php
declare(strict_types=1);

namespace App\Service;

use App\Api\LikeRequest;
use App\Api\LikeResponse;
use App\Entity\Like;
use App\Repository\LikesRepository;
use Psr\Log\LoggerInterface;

class LikeService
{
    /**
     * @var LikesRepository
     */
    private $likesRepository;

    /**
     * @var MailerService
     */
    private $mailerService;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LikesRepository $likesRepository, MailerService $mailerService, LoggerInterface $logger)
    {
        $this->likesRepository = $likesRepository;
        $this->mailerService   = $mailerService;
        $this->logger          = $logger;
    }

    public function addLikeToUser(LikeRequest $likeRequest): LikeResponse
    {
        $like = new Like($likeRequest->getUserId());
        $this->likesRepository->save($like);

        $userLikes = $this->likesRepository->getTotalLikesForUserId($likeRequest->getUserId());
        $isEmailSent = $this->mailerService->sendEmail($likeRequest->getUserId());

        return new LikeResponse($likeRequest->getUserId(),$userLikes,$isEmailSent);
    }
}
