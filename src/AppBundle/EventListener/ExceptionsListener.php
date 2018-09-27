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
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionsListener
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if($exception instanceof AccessDeniedHttpException){
            $response = new Response();
            $response->setContent($this->twig->render("exceptions/error401.html.twig", [
                'exception'=>$exception
            ]));
            $event->setResponse($response);
        }

//        if($exception instanceof FatalThrowableError){
//            $response = new Response();
//            $response->setContent($this->twig->render("exceptions/error500.html.twig", [
//                'exception'=>$exception
//            ]));
//            //TODO comment this when developing
//            $event->setResponse($response);
//        }


        if ($exception instanceof InternalServerException) {
            $response = new Response();
            $response->setContent($this->twig->render("exceptions/internal-server-error.html.twig", [
                'exception' => $exception,
            ]));
            $event->setResponse($response);
        }

    }

    public function onNotFoundException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        if ($exception instanceof NotFoundException) {
            $response = new Response();
            $response->setContent($this->twig->render("exceptions/not-found-exception.html.twig", [
                'exception' => $event->getException(),
            ]));
            $event->setResponse($response);
        }

        if($exception instanceof NotFoundHttpException ){
            $response = new Response();
            $response->setContent($this->twig->render("exceptions/error404.html.twig", []));
            $event->setResponse($response);
        }

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