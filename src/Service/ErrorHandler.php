<?php
// src/Service/ErrorHandler.php
namespace App\Service;

use Twig\Environment;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ErrorHandler
{
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        $statusCode = $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR;

        if ($statusCode === Response::HTTP_NOT_FOUND) {
            $response = new Response(
                $this->twig->render('bundles/TwigBundle/Exception/error404.html.twig'),
                Response::HTTP_NOT_FOUND
            );
        } else {
            $response = new Response(
                $this->twig->render('bundles/TwigBundle/Exception/error.html.twig'),
                $statusCode
            );
        }

        $event->setResponse($response);
    }
}
