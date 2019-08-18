<?php
declare(strict_types=1);

use App\Service\MailerService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class MailerServiceTest extends TestCase
{
    /**
     * @var MailerService
     */
    private $sut;

    protected function setUp()
    {
        $logger = $this->prophesize(LoggerInterface::class);
        $this->sut = new MailerService($logger->reveal(), 0, './myLock');
    }

    public function testAdd()
    {
        $this->assertTrue($this->sut->sendEmail(1));
    }
}
