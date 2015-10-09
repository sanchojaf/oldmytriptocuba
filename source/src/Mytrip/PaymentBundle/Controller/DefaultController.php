<?php

namespace Mytrip\PaymentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MytripPaymentBundle:Default:index.html.twig');
    }
}