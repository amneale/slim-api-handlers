<?php

namespace Amneale\Slim\ApiHandlers\Tests;

use Amneale\Slim\ApiHandlers\Error;
use Slim\Http\Response;

class ErrorTest extends \PHPUnit_Framework_TestCase
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
    public function testError($contentType, $startOfBody)
    {
        $handler = new Error();

        $response = $handler->__invoke(
            $this->getRequest($contentType),
            new Response(),
            new \Exception('exception')
        );

        $this->assertSame(500, $response->getStatusCode());
        $this->assertSame($contentType, $response->getHeaderLine('Content-Type'));
        $this->assertSame(0, strpos((string) $response->getBody(), $startOfBody));
    }

    /**
     * @return array
     */
    public function displayErrorDetailsProvider()
    {
        return [[false], [true]];
    }

    /**
     * @dataProvider displayErrorDetailsProvider
     *
     * @param bool $displayErrorDetails
     */
    public function testDisplayErrorDetails($displayErrorDetails)
    {
        $contentType = 'application/json';
        $handler = new Error($displayErrorDetails);

        $response = $handler->__invoke(
            $this->getRequest($contentType),
            new Response(),
            new \Exception('exception')
        );

        $this->assertSame(500, $response->getStatusCode());
        $this->assertSame($contentType, $response->getHeaderLine('Content-Type'));

        $body = (string) $response->getBody();
        $this->assertEquals(
            $displayErrorDetails,
            array_key_exists('exception', json_decode($body, true))
        );
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
