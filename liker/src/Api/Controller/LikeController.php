<?php
declare(strict_types=1);

namespace App\Api\Controller;

use App\Api\LikeRequest;
use App\Api\LikeResponse;
use App\Entity\Like;
use App\Repository\LikesRepository;
use App\Service\MailerService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class LikeController extends AbstractFOSRestController
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
     * @param LikesRepository $likesRepository
     * @param MailerService   $mailerService
     */
    public function __construct(LikesRepository $likesRepository, MailerService $mailerService)
    {
        $this->likesRepository = $likesRepository;
        $this->mailerService   = $mailerService;
    }

    /**
     * @Rest\Post("/like")
     * @ParamConverter("likeRequest", converter="fos_rest.request_body")
     *
     * @param LikeRequest                      $likeRequest
     * @param ConstraintViolationListInterface $validationErrors
     *
     * @return Response
     * @throws \Exception
     */
    public function like(
        LikeRequest $likeRequest,
        ConstraintViolationListInterface $validationErrors
    ): Response {
        if (count($validationErrors) > 0) {
            return $this->sendBadResponse($validationErrors);
        }

        $like = new Like($likeRequest->getUserId());
        $this->likesRepository->save($like);

        $userLikes = $this->likesRepository->getTotalLikesForUserId($likeRequest->getUserId());

        $isEmailSent = $this->mailerService->sendEmail($likeRequest->getUserId());

        $response = new LikeResponse($likeRequest->getUserId(),$userLikes,$isEmailSent);

        $view     = $this->view($response, Response::HTTP_OK);
        return $this->handleView($view);
    }

    /**
     * @param ConstraintViolationListInterface $validationErrors
     *
     * @return Response
     */
    private function sendBadResponse(ConstraintViolationListInterface $validationErrors)
    {
        $view = $this->view($validationErrors, Response::HTTP_BAD_REQUEST);

        return $this->handleView($view);
    }
}
