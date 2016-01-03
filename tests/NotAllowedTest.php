<?php

namespace Amneale\Slim\ApiHandlers\Tests;

use Amneale\Slim\ApiHandlers\NotAllowed;
use Slim\Http\Response;

class NotAllowedTest extends \PHPUnit_Framework_TestCase
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
    public function testInvalidMethod($contentType, $startOfBody)
    {
        $handler = new NotAllowed();
        $allowed = ['GET', 'POST'];

        $response = $handler->__invoke(
            $this->getRequest($contentType, 'PUT'),
            new Response(),
            $allowed
        );

        $this->assertSame(405, $response->getStatusCode());
        $this->assertSame($contentType, $response->getHeaderLine('Content-Type'));;
        $this->assertTrue($response->hasHeader('Allow'));
        $this->assertEquals(implode(', ', $allowed), $response->getHeaderLine('Allow'));
        $this->assertSame(0, strpos((string) $response->getBody(), $startOfBody));
    }
    /**
     * @dataProvider contentTypeProvider
     *
     * @param $contentType
     * @param $startOfBody
     */
    public function testOptionsMethod($contentType, $startOfBody)
    {
        $handler = new NotAllowed();
        $allowed = ['GET', 'POST'];

        $response = $handler->__invoke(
            $this->getRequest($contentType, 'OPTIONS'),
            new Response(),
            $allowed
        );

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($contentType, $response->getHeaderLine('Content-Type'));
        $this->assertTrue($response->hasHeader('Allow'));
        $this->assertEquals(implode(', ', $allowed), $response->getHeaderLine('Allow'));
        $this->assertSame(0, strpos((string) $response->getBody(), $startOfBody));
    }

    /**
     * @param string $contentType
     * @return \PHPUnit_Framework_MockObject_MockObject|\Slim\Http\Request
     */
    protected function getRequest($contentType, $method)
    {
        $request = $this->getMockBuilder('Slim\Http\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $request->expects($this->once())
            ->method('getHeaderLine')
            ->willReturn($contentType);

        $request->expects($this->once())
            ->method('getMethod')
            ->willReturn($method);

        return $request;
    }
}
