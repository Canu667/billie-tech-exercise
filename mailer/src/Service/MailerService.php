<?php
declare(strict_types=1);

namespace App\Service;

use Psr\Log\LoggerInterface;

class MailerService
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param int $userId
     *
     * @return mixed
     */
    public function sendEmail(int $userId)
    {
        $this->logger->debug('Trying to send email for userId: ' . $userId);

        $filename = './mylock.lock';

        $fp = fopen( $filename,"w");
        if (flock($fp, LOCK_EX | LOCK_NB)) {
            $this->logger->debug('Sending the email...');

            sleep(10);

            flock($fp, LOCK_UN);
            return true;
        } else {
            $this->logger->debug('Server busy - rejecting');
            return false;
        }
    }
}
