<?php

namespace Amneale\Slim\ApiHandlers\Tests;

use Amneale\Slim\ApiHandlers\NotFound;
use RKA\ContentTypeRenderer\Renderer;
use Slim\Http\Response;

class NotFoundTest extends \PHPUnit_Framework_TestCase
{
    public function testResponseStatusCode()
    {
        $handler = new NotFound(new Renderer());

        $request = $this->getMockBuilder('Slim\Http\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $response = $handler->__invoke($request, new Response());

        $this->assertSame(404, $response->getStatusCode());
    }
}
