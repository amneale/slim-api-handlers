<?php

namespace Amneale\Slim\ApiHandlers;

class NotFound extends AbstractHandler
{
    /**
     * @inheritdoc
     */
    protected function getCode()
    {
        return 404;
    }

    /**
     * @inheritdoc
     */
    protected function getData()
    {
        return [
            'id'      => 'not_found',
            'message' => 'Route not found'
        ];
    }
}