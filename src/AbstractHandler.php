<?php

namespace Amneale\Slim\ApiHandlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use RKA\ContentTypeRenderer\Renderer;

abstract class AbstractHandler
{
    /** @var Renderer */
    protected $renderer;

    public function __construct()
    {
        $this->renderer = new Renderer();
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        return $this->renderer->render(
            $request,
            $response->withStatus($this->getCode()),
            $this->getData()
        );
    }

    /**
     * Get the HTTP status code
     *
     * @return int
     */
    abstract protected function getCode();

    /**
     * Get the data to be written to the response
     *
     * @return array
     */
    abstract protected function getData();
}