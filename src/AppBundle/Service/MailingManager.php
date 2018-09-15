<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/14/2018
 * Time: 9:46 PM
 */

namespace AppBundle\Service;


use AppBundle\BindingModel\UserFeedbackBindingModel;
use AppBundle\Constants\Roles;
use AppBundle\Contracts\IGlobalSubscriberDbManager;
use AppBundle\Contracts\ILogDbManager;
use AppBundle\Contracts\IMailingManager;
use AppBundle\Contracts\IMailSenderManager;
use AppBundle\Contracts\IUserDbManager;
use AppBundle\Entity\Article;
use AppBundle\Entity\User;

class MailingManager implements IMailingManager
{
    private const LOGGER_LOCATION = "Mailing Manager";
    private const ADMIN_SENT_MESSAGE_FORMAT = "Admin %s sent message to %s subs";
    private const AUTHOR_SENT_MESSAGE_FORMAT = "Author %s sent message to %s subs";
    private const FEEDBACK_SENT_FORMAT = "Person %s with email %s sent feedback to %d admins";
    private const FEEDBACK_SUBJECT = "Someone is asking something";

    /**
     * @var IMailSenderManager
     */
    private $mailerService;

    /**
     * @var IGlobalSubscriberDbManager
     */
    private $subscriberService;

    /**
     * @var ILogDbManager
     */
    private $logger;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var LocalLanguage
     */
    private $language;

    /**
     * @var IUserDbManager
     */
    private $userService;

    /**
     * MailingManager constructor.
     * @param IMailSenderManager $mailerService
     * @param IGlobalSubscriberDbManager $subscriberService
     * @param ILogDbManager $logDb
     * @param \Twig_Environment $twig_Environment
     * @param LocalLanguage $localLanguage
     * @param IUserDbManager $userDbManager
     */
    public function __construct(
        IMailSenderManager $mailerService, IGlobalSubscriberDbManager $subscriberService, ILogDbManager $logDb,
        \Twig_Environment $twig_Environment, LocalLanguage $localLanguage, IUserDbManager $userDbManager)
    {
        $this->mailerService = $mailerService;
        $this->subscriberService = $subscriberService;
        $this->logger = $logDb;
        $this->twig = $twig_Environment;
        $this->language = $localLanguage;
        $this->userService = $userDbManager;
    }

    public function sendMessageToSubscribers(User $admin, string $subject, string $message): void
    {
        $subs = $this->subscriberService->findAll();
        $this->logger->log(self::LOGGER_LOCATION, sprintf(self::ADMIN_SENT_MESSAGE_FORMAT, $admin->getUsername(), count($subs)));
        foreach ($subs as $sub) {
            $html = $this->twig->render('mail/from-admin.html.twig', [
                'message' => $message,
                'admin' => $admin,
                'receiver' => $sub
            ]);
            $this->mailerService->sendHtml($subject, $html, $sub->getEmail());
        }
    }

    public function sendMessageForNewArticle(Article $article): void
    {
        if (!$article->getAuthor()->hasRole(Roles::ROLE_MAILER))
            return;
        $subs = $this->subscriberService->findAll();
        $this->logger->log(self::LOGGER_LOCATION, sprintf(self::AUTHOR_SENT_MESSAGE_FORMAT, $article->getAuthor()->getUsername(), count($subs)));

        $artLang = $article->getCategory()->getLanguage()->getLocaleName();
        $subject = sprintf($this->language->newArticleFormat(), $article->getAuthor()->getUsername(), $article->getTitle());
        foreach ($subs as $sub) {
            $html = $this->twig->render('mail/partials/lang/' . $artLang . '/new-article.html.twig', [
                'article' => $article,
                'receiver' => $sub
            ]);
            $this->mailerService->sendHtml($subject, $html, $sub->getEmail());
        }
    }

    public function sendFeedback(UserFeedbackBindingModel $bindingModel): void
    {
        $admins = $this->userService->findByRole(Roles::ROLE_ADMIN);
        $this->logger->log(self::LOGGER_LOCATION, sprintf(self::FEEDBACK_SENT_FORMAT, $bindingModel->getName(), $bindingModel->getEmail(), count($admins)));
        $html = $this->twig->render('mail/feedback-admin-form.html.twig',[
           'sender'=>$bindingModel
        ]);
        foreach ($admins as $admin) {
            $this->mailerService->sendHtml(self::FEEDBACK_SUBJECT, $html, $admin->getEmail());
        }
    }
}