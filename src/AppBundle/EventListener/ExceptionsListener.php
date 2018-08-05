<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 8/5/2018
 * Time: 12:30 AM
 */

namespace AppBundle\EventListener;


use AppBundle\Exception\CategoryNotFoundException;
use AppBundle\Exception\InternalServerException;
use AppBundle\Exception\NotFoundException;
use AppBundle\Exception\RestFriendlyException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class ExceptionsListener
{

    private $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        if (!$exception instanceof InternalServerException) {
            return;
        }

        $response = new Response();
        $response->setContent($this->twig->render("exceptions/internal-server-error.html.twig", [
            'exception' => $exception,
        ]));
        $event->setResponse($response);
    }

    public function onNotFoundException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        if (!$exception instanceof NotFoundException) {
            return;
        }

        $response = new Response();
        $response->setContent($this->twig->render("exceptions/not-found-exception.html.twig", [
            'exception' => $event->getException(),
        ]));
        $event->setResponse($response);
    }

    public function onRestException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        if (!$exception instanceof RestFriendlyException) {
            return;
        }

        $code = $exception->getCode();
        $responseData = [
            'code' => $code,
            'message' => $exception->getMessage()
        ];
        $event->setResponse(new JsonResponse($responseData, $code));
    }
}