<?php
namespace Mytrip\PaymentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Payum\Core\Model\Token;

/**
 * @ORM\Table(name="payment_security_token")
 * @ORM\Entity
 */
class PaymentSecurityToken extends Token
{
	/**
* @ORM\Column(name="id", type="integer")
* @ORM\Id
* @ORM\GeneratedValue(strategy="IDENTITY")
*
* @var integer $id
*/
   // protected $id;


}