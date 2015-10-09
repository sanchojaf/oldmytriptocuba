<?php
namespace Mytrip\UserBundle\Controller;

use Mytrip\PaymentBundle\Model\PaymentDetails;
use Payum\Core\Security\GenericTokenFactoryInterface;
use Payum\Paypal\ExpressCheckout\Nvp\Api;
use Payum\Core\Registry\RegistryInterface;
use Payum\Stripe\Keys;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Extra;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Range;

class StripeController extends Controller
{
   
    public function indexAction(Request $request)
    {
        $paymentName = 'stripe_checkout';

       		
            
        $session = $request->getSession();		
		$lan=$session->get('language');			
		
        if ($session->get('bookingId')) {
			$bookingid=$session->get('bookingId');
			$em = $this->container->get('doctrine')->getManager();	
			$booking=$em->createQuery("SELECT d,IDENTITY(d.hostal) AS hostal,IDENTITY(d.room) AS room FROM MytripAdminBundle:Booking d WHERE d.bookingId=".$bookingid)->getArrayResult();	
			$hostal_content=$em->createQuery("SELECT d FROM MytripAdminBundle:HostalContent d WHERE d.hostal=".$booking[0]['hostal']." AND d.lan='".$lan."'")->getArrayResult();		
			$bookingprice=$em->createQuery("SELECT p FROM MytripAdminBundle:BookingPrice p WHERE p.booking='".$booking[0][0]['bookingId']."'")->getArrayResult();	

            $storage = $this->getPayum()->getStorage('Mytrip\PaymentBundle\Model\PaymentDetails');

            /** @var $paymentDetails PaymentDetails */
            $paymentDetails = $storage->createModel();
            $paymentDetails['currency'] = $bookingprice[0]['conversionCurrency'];        
			$paymentDetails['amount'] = $bookingprice[0]['reservationTotalPrice']*$bookingprice[0]['conversionRate']*100;           
            $paymentDetails['description'] = $hostal_content['0']['name'];
           
			//if ($request->isMethod('POST') && $request->request->get('stripeToken')) {
	
				$paymentDetails["card"] = $request->request->get('stripeToken');
				$storage->updateModel($paymentDetails);
	
				$captureToken = $this->getTokenFactory()->createCaptureToken(
					$paymentName,
					$paymentDetails,
					'mytrip_user_stripe_view'
				);
	
				return $this->redirect($captureToken->getTargetUrl());
			//}            
        }else{
			return $this->redirect($this->generateUrl('mytrip_user_homepage'));
		}
    }
	
	public function striperefundAction(Request $request){
		$session = $request->getSession();
		if ($session->get('refund')=='') {
			return $this->redirect($this->generateUrl('mytrip_user_homepage'));
		}
			
		$username=$session->get('user');
		$refund=$session->get('refund');
		if(empty($username)){			
			return $this->redirect($this->generateUrl('mytrip_user_homepage'));
		}	
		$lan=$session->get('language');
		
		$em = $this->container->get('doctrine')->getManager();		
		$bookingtransaction=$em->createQuery("SELECT p FROM MytripAdminBundle:BookingTransaction p WHERE p.booking='".$refund['refund_booking_id']."'")->getArrayResult();
		
		$booking=$em->createQuery("SELECT d,IDENTITY(d.hostal) AS hostal,IDENTITY(d.room) AS room FROM MytripAdminBundle:Booking d WHERE d.bookingId=".$refund['refund_booking_id'])->getArrayResult();
		if($booking[0][0]['status']!="Confirmed"){
			$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Your booking already cancelled or payment not proceed. Please check it.'));
			return $this->redirect($this->generateUrl('mytrip_user_bookinghistory'));
		}
		$paymentArray['amount']=$refund['cancelamount'];
		//$paymentArray['refundType']='Partial';
		$paymentArray['transactionID']=$bookingtransaction[0]['transactionId'];
		//$paymentArray['currency']=$bookingtransaction[0]['transactionCurrency'];
		$result=$this->StripeRefund($paymentArray);
		$ack = strtoupper($result["ACK"]);
		if($ack=="SUCCESS"){			
			$cancel = new \Mytrip\AdminBundle\Entity\BookingCancel();
			$cancel->setBooking($this->getDoctrine()->getRepository('MytripAdminBundle:Booking')->find($refund['refund_booking_id']));
			$cancel->setCancelPercentage($refund['cancel_percentage']);
			$cancel->setCancelDate(new \DateTime(date('Y-m-d')));
			$cancel->setRefundReferenceno($result['REFUNDTRANSACTIONID']);
			$cancel->setRefundAmount($refund['cancelamount']);
			$cancel->setRefundDate(new \DateTime(date('Y-m-d')));
			$cancel->setRefundCurrency($bookingtransaction[0]['transactionCurrency']);
			$cancel->setStatus('Refund');
			$em->persist($cancel);
			$em->flush();
			$em->createQuery("UPDATE MytripAdminBundle:Booking p SET p.status='Cancelled' WHERE p.bookingId='".$refund['refund_booking_id']."'")->execute();		
				
			$bookingid=$refund['refund_booking_id'];
			$booking=$em->createQuery("SELECT d,IDENTITY(d.hostal) AS hostal,IDENTITY(d.room) AS room FROM MytripAdminBundle:Booking d WHERE d.bookingId=".$bookingid)->getArrayResult();			
			$booking_info=$em->createQuery("SELECT d FROM MytripAdminBundle:BookingInfo d WHERE d.booking=".$bookingid)->getArrayResult();
			$this->mailsend("Mytrip Cuba","info@mytriptocuba.com",$booking_info[0]['email'],$this->get('translator')->trans('Reservation Cancel Details'),'','');
			$session->remove('refund');
			
			$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Your booking cancelled successfully'));
			return $this->redirect($this->generateUrl('mytrip_user_bookinghistory'));
		}else{
			$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans($result['message']));
			return $this->redirect($this->generateUrl('mytrip_user_bookinghistory'));
		}
		
	}
	
	
	public function StripeRefund($paymentInfo=array()) {		  		
		$amount=$paymentInfo['amount']*100;		
		$transactionID=urlencode($paymentInfo['transactionID']);
		$keys = $this->container->get('payum.context.stripe_checkout.keys');			
        try {
			\Stripe::setApiKey($keys->getSecretKey());
			$ch = \Stripe_Charge::retrieve($transactionID); 
			$re = $ch->refunds->create(array('amount'=>$amount));
			$result=array('ACK'=>'Success','REFUNDTRANSACTIONID'=>$re->id);			
		}catch (Stripe_InvalidRequestError $e) { 
			// Invalid parameters were supplied to Stripe's API 
			$body = $e->getJsonBody();
			$err = $body['error'];
			$result=array('ACK'=>'Failure','Message'=>$err['message']);
		}		
		return $result;
	} 
	
    /**
     * @return RegistryInterface
     */
    protected function getPayum()
    {
        return $this->get('payum');
    }

    /**
     * @return GenericTokenFactoryInterface
     */
    protected function getTokenFactory()
    {
        return $this->get('payum.security.token_factory');
    }
	
	/*********Mail sending function**************/
	private function mailsend($fromname,$from,$to,$subject,$content,$cc = NULL,$attachment=0,$attachmentsfiles='',$template='refund'){
		/****Mail******/
		$message = \Swift_Message::newInstance()
					->setSubject($subject)
					->setFrom($from,$fromname)
					->setTo($to)
					->setBody(
						$this->renderView(
							'MytripAdminBundle:Email:'.$template.'.html.php',array('content' => $content,'url'=>array('url'=>$this->getRequest()->getSchemeAndHttpHost(),'rootfolder'=>$this->container->get('router')->getContext()->getBaseUrl()))
						)
					)
					->setContentType("text/html");;
		
		/*****Mail CC********/			
		if(!empty($cc)){
			$ccmail=explode(",",$cc);
			$message->setCc($ccmail);
		}
		
		/********Mail Attachment***********/
		if($attachment>0){
			foreach($attachmentsfiles as $attachmentsfile){
            	$message->attach(\Swift_Attachment::fromPath($attachmentsfile));
			}
        }
		/*******Sending Mail**********/
		$mailmess=$this->get('mailer')->send($message);
		
	}
}