<?php
namespace Payum\Core\Tests\Exception\Http;

use Buzz\Message\Request;
use Buzz\Message\Response;

use Payum\Core\Exception\Http\HttpException;

class HttpExceptionInterfaceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldImplementPayumExceptionInterface()
    {
        $rc = new \ReflectionClass('Payum\Core\Exception\Http\HttpExceptionInterface');
        
        $this->assertTrue($rc->implementsInterface('Payum\Core\Exception\ExceptionInterface'));
    }
}
