<?php
declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\MailerService;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Log\Logger;

class MailerServiceTest extends TestCase
{
    /**
     * @var MailerService
     */
    private $sut;

    /**
     * @var Logger
     */
    private $loggerMock;

    /**
     * @var ClientInterface
     */
    private $guzzleClientMock;

    public function setUp()
    {
        $this->loggerMock         = $this->prophesize(LoggerInterface::class);
        $this->guzzleClientMock = $this->prophesize(Client::class);

        $this->sut = new MailerService($this->guzzleClientMock->reveal(), $this->loggerMock->reveal());
    }

    public function testSendEmailTooBusy()
    {

        $request = $this->prophesize(Request::class);
        $reponse = new Response(\Symfony\Component\HttpFoundation\Response::HTTP_TOO_MANY_REQUESTS);
        $exception = new RequestException("Uppss too budy", $request->reveal(), $reponse);

        $this->guzzleClientMock->post(
            "http://mailer/send-email",
            Argument::any()
        )->shouldBeCalled()->willThrow($exception);

        $this->assertFalse($this->sut->sendEmail(1));
    }

    public function testSendEmailRequestExceptionOtherThanTooBusy()
    {
        $this->expectException(RequestException::class);

        $this->guzzleClientMock->post(
            "http://mailer/send-email",
            Argument::any()
        )->shouldBeCalled()->willThrow(RequestException::class);

        $this->sut->sendEmail(1);
    }
}
