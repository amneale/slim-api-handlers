<?php

namespace Amneale\Slim\ApiHandlers\Tests;

use Amneale\Slim\ApiHandlers\NotAllowed;
use RKA\ContentTypeRenderer\Renderer;
use Slim\Http\Response;

class NotAllowedTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider methods
     * @param $method
     */
    public function testResponseStatusCode($method, $expectedCode)
    {
        $handler = new NotAllowed(new Renderer(), true);

        $request = $this->getMockBuilder('Slim\Http\Request')
            ->disableOriginalConstructor()
            ->setMethods(['getMethod', 'getHeaderLine'])
            ->getMock();

        $request->expects($this->once())
            ->method('getMethod')
            ->willReturn($method);

        $response = $handler->__invoke($request, new Response(), []);

        $this->assertSame($expectedCode, $response->getStatusCode());
    }

    /**
     * @return array
     */
    public function methods()
    {
        return [
            ['GET', 405],
            ['POST', 405],
            ['OPTIONS', 200],
        ];
    }
}
