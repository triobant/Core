<?php

declare(strict_types=1);

namespace Horde\Core\Middleware;

use Exception;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Horde_Registry;
use Horde_Application;
use Horde_Controller;
use Horde_Routes_Mapper as Router;
use Horde_String;
use Horde;
use Horde\Core\UrlStore;
use Horde\Core\UserPassport;
use Psr\Http\Message\ResponseFactoryInterface;

/**
 * RedirectToLogin middleware
 *
 * Purpose: Redirect to login if not authenticated
 *
 * Reads attribute:
 * - HORDE_AUTHENTICATED_USER the uid, if authenticated
 *
 */
class RedirectToLogin implements MiddlewareInterface
{
    public UrlStore $UrlStore; //added this
    private Horde_Registry $registry;
    private ResponseFactoryInterface $responseFactory;
    public function __construct(Horde_Registry $registry, ResponseFactoryInterface $responseFactory)
    {
        $this->registry = $registry;
        $this->responseFactory = $responseFactory;
        //$this->UrlStore; //added this; is it necessary?
    }
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request->getAttribute('HORDE_AUTHENTICATED_USER')) {
            return $handler->handle($request);
        }
        
        $redirect = (string)$this->registry->getInitialPage(); //changed this; before it was $this->UrlStore->getInitialPage();
        return $this->responseFactory->createResponse(302)->withHeader('Location', $redirect);
    }
}
