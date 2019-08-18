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
     * @var int
     */
    private $duration;

    /**
     * @var string
     */
    private $filePath;

    /**
     * @param LoggerInterface $logger
     * @param int             $duration
     * @param string          $filePath
     */
    public function __construct(LoggerInterface $logger, int $duration, string $filePath)
    {
        $this->logger   = $logger;
        $this->duration = $duration;
        $this->filePath = $filePath;
    }

    /**
     * @param int $userId
     *
     * @return mixed
     */
    public function sendEmail(int $userId)
    {
        $this->logger->debug('Trying to send email for userId: ' . $userId);;

        $fp = fopen( $this->filePath,"w");
        if (flock($fp, LOCK_EX | LOCK_NB)) {
            $this->logger->debug('Sending the email...');

            sleep($this->duration);

            flock($fp, LOCK_UN);
            return true;
        } else {
            $this->logger->debug('Server busy - rejecting');
            return false;
        }
    }
}
