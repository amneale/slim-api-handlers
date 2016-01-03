<?php

namespace Amneale\Slim\ApiHandlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class NotAllowed extends AbstractHandler
{
    /** @var bool */
    protected $isOptions = false;

    /** @var array */
    protected $allowed = array();

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $methods
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $methods)
    {
        $this->isOptions = $request->getMethod() === 'OPTIONS';
        $this->allowed   = $methods;

        $response = $response->withHeader(
            'Allow',
            implode(',', $this->allowed)
        );

        return parent::__invoke($request, $response);
    }

    /**
     * @inheritdoc
     */
    protected function getCode()
    {
        return $this->isOptions
            ? 200
            : 405;
    }

    /**
     * @inheritdoc
     */
    protected function getData()
    {
        return $this->isOptions
            ? [ 'allowed_methods' => $this->allowed ]
            : [
                'id'      => 'not_allowed',
                'message' => 'Method not allowed'
            ];
    }
}