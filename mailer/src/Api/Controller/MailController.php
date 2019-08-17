<?php
declare(strict_types=1);

namespace App\Api\Controller;

use App\Service\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MailController extends AbstractController
{
    /**
     * @var MailerService
     */
    private $mailerService;

    /**
     * @param MailerService $mailerService
     */
    public function __construct(MailerService $mailerService)
    {
        $this->mailerService = $mailerService;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function sendEmail(Request $request)
    {
        $content = json_decode($request->getContent(), true);

        $response = $this->mailerService->sendEmail((int) $content['user_id']);

        return $response ? Response::create(null, Response::HTTP_OK) :
            Response::create(null, Response::HTTP_TOO_MANY_REQUESTS);
    }
}
