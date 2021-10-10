<?php
declare(strict_types=1);

namespace Horde\Core\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;
use \Horde_Registry;
use \Horde_Application;

/**
 * ErrorFilter middleware
 *
 * Purpose: 
 * 
 * Prevent ugly stack traces from showing up to users or APIs.
 * Give meaningful feedback and logging.
 * Can handle errors early in setup
 * Can give more meaningful feedback on a fully setup environment
 * 
 * Intended to run close to top of stack
 * 
 * Requires Attributes:
 * 
 * Sets Attributes:
 * 
 * 
 */
class ErrorFilter implements MiddlewareInterface
{
    private ResponseFactoryInterface $responseFactory;

    public function __construct(
        ResponseFactoryInterface $responseFactory
    ) {
        $this->responseFactory = $responseFactory;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (\Exception $e){
            // TODO Logging
            if ($request->getAttribute('HORDE_GLOBAL_ADMIN')) {
                throw $e;
            } else {
                // TODO output actual error page
                return $this->responseFactory
                    ->createResponse()
                    ->withStatus(501, 'Internal Server Error');
            }
        }
    }
}
