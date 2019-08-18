<?php
declare(strict_types=1);

namespace App\Api\Controller;

use OpenApi\Annotations as OA;
use App\Api\LikeRequest;
use App\Service\LikeService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class LikeController extends AbstractFOSRestController
{
    /**
     * @var LikeService
     */
    private $likeService;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LikeService $likeService, LoggerInterface $logger)
    {
        $this->likeService = $likeService;
        $this->logger      = $logger;
    }

    /**
     * @OA\Post(
     *     operationId="likeUser",
     *     description="Adds a like to the user and sends an email",
     *     tags={"Like"},
     *     path="/like",
     *     @OA\RequestBody(
     *         description="Adds a like to a given user and sends an email",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/LikeRequest"
     *         )
     *     ),
     *     @OA\Response(
     *      response="200",
     *      description="Returns the response",
     *      @OA\JsonContent(ref="#/components/schemas/LikeResponse")
     *     ),
     *   )
     * )
     *
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

        $response = $this->likeService->addLikeToUser($likeRequest);

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
