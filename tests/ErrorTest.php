<?php

namespace Amneale\Slim\ApiHandlers\Tests;

use Amneale\Slim\ApiHandlers\Error;
use RKA\ContentTypeRenderer\Renderer;
use Slim\Http\Response;

class ErrorTest extends \PHPUnit_Framework_TestCase
{
    public function testResponseStatusCode()
    {
        $handler = new Error(new Renderer(), true);

        $request = $this->getMockBuilder('Slim\Http\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $response = $handler->__invoke($request, new Response(), new \Exception('test'));

        $this->assertSame(500, $response->getStatusCode());
    }
}
