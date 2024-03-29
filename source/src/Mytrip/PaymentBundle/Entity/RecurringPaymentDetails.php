<?php
namespace Mytrip\PaymentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mytrip\PaymentBundle\Model\RecurringPaymentDetails as BaseRecurringPaymentDetails;

/**
 * @ORM\Table(name="payum_recurring_payment_details")
 * @ORM\Entity
 */
class RecurringPaymentDetails extends BaseRecurringPaymentDetails
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;
}