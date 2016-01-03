<?php

namespace Amneale\Slim\ApiHandlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use RKA\ContentTypeRenderer\Renderer;

class Error extends AbstractHandler
{
    /** @var bool */
    protected $displayErrorDetails = false;

    /** @var Exception */
    protected $exception;

    /**
     * @param Renderer $renderer
     * @param $displayErrorDetails
     */
    public function __construct(Renderer $renderer, $displayErrorDetails)
    {
        $this->displayErrorDetails = $displayErrorDetails;

        parent::__construct($renderer);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param \Exception $exception
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, \Exception $exception)
    {
        $this->exception = $exception;

        return parent::__invoke($request, $response);
    }

    /**
     * @inheritdoc
     */
    protected function getCode()
    {
        return 500;
    }

    /**
     * @inheritdoc
     */
    protected function getData()
    {
        $data = [
            'id'      => 'api_error',
            'message' => 'Application error'
        ];

        if ($this->displayErrorDetails) {
            $data['exception'] = [];
            $exception = $this->exception;

            do {
                $data['exception'][] = [
                    'type' => get_class($exception),
                    'code' => $exception->getCode(),
                    'message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'trace' => explode("\n", $exception->getTraceAsString()),
                ];
            } while ($exception = $exception->getPrevious());
        }

        return $data;
    }
}