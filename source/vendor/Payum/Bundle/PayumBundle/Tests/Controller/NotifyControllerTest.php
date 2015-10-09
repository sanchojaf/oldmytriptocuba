<?php
namespace Payum\Bundle\PayumBundle\Tests\Controller;

use Payum\Bundle\PayumBundle\Controller\NotifyController;
use Payum\Core\Registry\RegistryInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class NotifyControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldBeSubClassOfController()
    {
        $rc = new \ReflectionClass('Payum\Bundle\PayumBundle\Controller\NotifyController');

        $this->assertTrue($rc->isSubclassOf('Symfony\Bundle\FrameworkBundle\Controller\Controller'));
    }

    /**
     * @test
     */
    public function shouldExecuteNotifyRequestOnDoUnsafe()
    {
        $request = Request::create('/');
        $request->query->set('payment_name', 'thePaymentName');

        $paymentMock = $this->getMock('Payum\Core\PaymentInterface');
        $paymentMock
            ->expects($this->once())
            ->method('execute')
            ->with($this->isInstanceOf('Payum\Core\Request\NotifyRequest'))
        ;

        $registryMock = $this->getMock('Payum\Core\Registry\RegistryInterface');
        $registryMock
            ->expects($this->once())
            ->method('getPayment')
            ->with('thePaymentName')
            ->will($this->returnValue($paymentMock))
        ;

        $container = new Container;
        $container->set('payum', $registryMock);

        $controller = new NotifyController;
        $controller->setContainer($container);

        $response = $controller->doUnsafeAction($request);

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
        $this->assertEquals(204, $response->getStatusCode());
        $this->assertEquals('', $response->getContent());
    }
}
