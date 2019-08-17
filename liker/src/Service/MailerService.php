<?php
declare(strict_types=1);

namespace App\Service;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\HttpFoundation\Response;

class MailerService
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @param ClientInterface $guzzleClient
     */
    public function __construct(ClientInterface $guzzleClient)
    {
        $this->client = $guzzleClient;
    }

    /**
     * @param int $userId
     *
     * @return bool
     */
    public function sendEmail(int $userId)
    {
        $options = [
            'max_retry_attempts' => 1,
            'json' => [
                "user_id" => $userId
            ]
        ];

        try {
            $this->client->post("http://mailer/send-email", $options);

            return true;
        } catch (RequestException $exception) {
            if ($exception->getCode() === Response::HTTP_TOO_MANY_REQUESTS) {
                return false;
            }

            throw $exception;
        }
    }
}
