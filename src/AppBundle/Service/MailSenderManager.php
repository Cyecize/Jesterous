<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/13/2018
 * Time: 11:27 PM
 */

namespace AppBundle\Service;


use AppBundle\Constants\Config;
use AppBundle\Contracts\ILogDbManager;
use AppBundle\Contracts\IMailSenderManager;
use AppBundle\Util\YamlParser;
use Swift_TransportException;

class MailSenderManager implements IMailSenderManager
{
    private const LOG_LOCATION = "MailSender";
    private const MAIL_FAIL_FORMAT = "Mail failed!, subject: %s  receiver: %s";

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var ILogDbManager
     */
    private $logger;

    /**
     * @var string
     */
    private $senderEmail;

    public function __construct(\Swift_Mailer $mailer, ILogDbManager $logDb)
    {
        $this->mailer = $mailer;
        $this->logger = $logDb;
        $this->senderEmail = YamlParser::getMailerUsername();
    }

    /**
     * @param string $subject
     * @param string $message
     * @param string $receiver
     */
    public function sendText(string $subject, string $message, string $receiver): void
    {
        $swiftMailerMsg = (new \Swift_Message($subject))
            ->setFrom([$this->senderEmail => Config::MAILER_SENDER_NAME])
            ->setTo($receiver)
            ->setBody($message);
        try {
            $this->mailer->send($swiftMailerMsg);
        } catch (Swift_TransportException $e) {
            $this->logger->log(self::LOG_LOCATION, sprintf(self::MAIL_FAIL_FORMAT, $subject, $receiver));
        }
    }

    /**
     * @param string $subject
     * @param $content
     * @param string $receiver
     */
    public function sendHtml(string $subject, $content, string $receiver): void
    {
        $swiftMailerMsg = (new \Swift_Message($subject))
            ->setFrom([$this->senderEmail=> Config::MAILER_SENDER_NAME])
            ->setTo($receiver)
            ->setBody($content, 'text/html');
        try {
            $this->mailer->send($swiftMailerMsg);
        } catch (Swift_TransportException $e) {
            $this->logger->log(self::LOG_LOCATION, sprintf(self::MAIL_FAIL_FORMAT, $subject, $receiver));
        }
    }

}