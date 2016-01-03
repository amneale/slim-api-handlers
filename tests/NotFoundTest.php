<?php

namespace Amneale\Slim\ApiHandlers\Tests;

use Amneale\Slim\ApiHandlers\NotFound;
use RKA\ContentTypeRenderer\Renderer;
use Slim\Http\Response;

class NotFoundTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function contentTypeProvider()
    {
        return [
            ['application/json', '{'],
            ['application/xml', '<?xml version="1.0"?>'],
            ['text/xml', '<?xml version="1.0"?>'],
            ['text/html', '<!DOCTYPE html>'],
        ];
    }

    /**
     * @dataProvider contentTypeProvider
     *
     * @param $contentType
     * @param $startOfBody
     */
    public function testNotFound($contentType, $startOfBody)
    {
        $handler = new NotFound(new Renderer());

        $response = $handler->__invoke(
            $this->getRequest($contentType),
            new Response()
        );

        $this->assertSame(404, $response->getStatusCode());
        $this->assertSame($contentType, $response->getHeaderLine('Content-Type'));
        $this->assertSame(0, strpos((string) $response->getBody(), $startOfBody));
    }

    /**
     * @param string $contentType
     * @return \PHPUnit_Framework_MockObject_MockObject|\Slim\Http\Request
     */
    protected function getRequest($contentType)
    {
        $request = $this->getMockBuilder('Slim\Http\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $request->expects($this->once())
            ->method('getHeaderLine')
            ->willReturn($contentType);

        return $request;
    }
}
