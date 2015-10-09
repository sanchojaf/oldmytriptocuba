<?php
namespace Mytrip\PaymentBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class MytripPaymentBundle extends Bundle
{
    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        return 'PayumBundle';
    }
}