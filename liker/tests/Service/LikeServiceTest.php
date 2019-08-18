<?php
declare(strict_types=1);

namespace App\Tests\Service;

use App\Api\LikeRequest;
use App\Api\LikeResponse;
use App\Entity\Like;
use App\Repository\LikesRepository;
use App\Service\LikeService;
use App\Service\MailerService;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class LikeServiceTest extends TestCase
{
    /**
     * @var LikeService
     */
    private $sut;

    /**
     * @var LikesRepository
     */
    private $likeRepositoryMock;

    /**
     * @var MailerService
     */
    private $mailServiceMock;

    /**
     * @var LoggerInterface
     */
    private $loggerMock;

    public function setUp()
    {
        $this->loggerMock         = $this->prophesize(LoggerInterface::class);
        $this->likeRepositoryMock = $this->prophesize(LikesRepository::class);
        $this->mailServiceMock    = $this->prophesize(MailerService::class);

        $this->sut = new LikeService(
            $this->likeRepositoryMock->reveal(),
            $this->mailServiceMock->reveal(),
            $this->loggerMock->reveal()
        );
    }

    public function testAddLikeToUserSuccess()
    {
        $likeRequest = new LikeRequest(1);
        $like = new Like($likeRequest->getUserId());
        $expectedLikeResponse = new LikeResponse(1, 1, true);

        $this->mockTheRepositoryReponse($like, $likeRequest);

        $this->mailServiceMock->sendEmail($likeRequest->getUserId())->willReturn(true);

        $response = $this->sut->addLikeToUser($likeRequest);

        $this->assertEquals($expectedLikeResponse, $response);
    }

    public function testAddLikeToUserCouldNotSendEmail()
    {
        $likeRequest = new LikeRequest(1);
        $like = new Like($likeRequest->getUserId());
        $expectedLikeResponse = new LikeResponse(1, 1, false);

        $this->mockTheRepositoryReponse($like, $likeRequest);

        $this->mailServiceMock->sendEmail($likeRequest->getUserId())->willReturn(false);

        $response = $this->sut->addLikeToUser($likeRequest);

        $this->assertEquals($expectedLikeResponse, $response);
    }

    private function mockTheRepositoryReponse(Like $like, LikeRequest $likeRequest): void
    {
        $this->likeRepositoryMock->save($like)->shouldBeCalled();

        $this->likeRepositoryMock->getTotalLikesForUserId($likeRequest->getUserId())
            ->shouldBeCalled()
            ->willReturn(1);
    }
}
