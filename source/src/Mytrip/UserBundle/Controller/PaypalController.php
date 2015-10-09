<?php
namespace Mytrip\UserBundle\Controller;

use Mytrip\PaymentBundle\Model\PaymentDetails;
use Payum\Core\Security\GenericTokenFactoryInterface;
use Payum\Paypal\ExpressCheckout\Nvp\Api;
use Payum\Core\Registry\RegistryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Extra;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Range;

class PaypalController extends Controller
{
    
    public function indexAction(Request $request)
    {
        $paymentName = 'paypal_express_checkout_and_doctrine_orm';        
        $session = $request->getSession();		
		$lan=$session->get('language');			
		
        if ($session->get('bookingId')) {
			$bookingid=$session->get('bookingId');
			$em = $this->container->get('doctrine')->getManager();	
			$booking=$em->createQuery("SELECT d,IDENTITY(d.hostal) AS hostal FROM MytripAdminBundle:Booking d WHERE d.bookingId=".$bookingid)->getArrayResult();	
			$hostal_content=$em->createQuery("SELECT d FROM MytripAdminBundle:HostalContent d WHERE d.hostal=".$booking[0]['hostal']." AND d.lan='".$lan."'")->getArrayResult();		
			$bookingprice=$em->createQuery("SELECT p FROM MytripAdminBundle:BookingPrice p WHERE p.booking='".$booking[0][0]['bookingId']."'")->getArrayResult();	

            $storage = $this->getPayum()->getStorage('Mytrip\PaymentBundle\Entity\PaymentDetails');

            /** @var $paymentDetails PaymentDetails */
            $paymentDetails = $storage->createModel();
            $paymentDetails['PAYMENTREQUEST_0_CURRENCYCODE'] = $bookingprice[0]['conversionCurrency'];
            $paymentDetails['PAYMENTREQUEST_0_AMT'] = $bookingprice[0]['reservationTotalPrice']*$bookingprice[0]['conversionRate'];
			$paymentDetails['L_PAYMENTREQUEST_0_AMT0'] = $bookingprice[0]['reservationTotalPrice']*$bookingprice[0]['conversionRate'];
            $paymentDetails['L_PAYMENTREQUEST_0_QTY0'] = 1;
            $paymentDetails['L_PAYMENTREQUEST_0_NAME0'] = $hostal_content['0']['name'];
            $paymentDetails['L_PAYMENTREQUEST_0_DESC0'] = $hostal_content['0']['smallDesc'];
            $storage->updateModel($paymentDetails);

            $captureToken = $this->getTokenFactory()->createCaptureToken(
                $paymentName,
                $paymentDetails,
                'mytrip_user_details_view'
            );

            $paymentDetails['RETURNURL'] = $captureToken->getTargetUrl();
            $paymentDetails['CANCELURL'] = $captureToken->getTargetUrl();
            $paymentDetails['INVNUM'] = $paymentDetails->getId();
            $storage->updateModel($paymentDetails);

            return $this->redirect($captureToken->getTargetUrl());
        }else{
			return $this->redirect($this->generateUrl('mytrip_user_homepage'));
		}
		
    }
	
	public function paypalrefundAction(Request $request){
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
		$booking=$em->createQuery("SELECT p FROM MytripAdminBundle:Booking p WHERE p.bookingId='".$refund['refund_booking_id']."'")->getArrayResult();
		if($booking[0]['status']!="Confirmed"){
			$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Your booking already cancelled or payment not proceed. Please check it.'));
			return $this->redirect($this->generateUrl('mytrip_user_bookinghistory'));
		}
		$paymentArray['amount']=$refund['cancelamount'];
		$paymentArray['refundType']='Partial';
		$paymentArray['transactionID']=$bookingtransaction[0]['transactionId'];
		$paymentArray['currency']=$bookingtransaction[0]['transactionCurrency'];
		$result=$this->PaypalRefund($paymentArray);
		
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
			$this->mailsend("Mytrip Cuba","info@mytriptocuba.com",$booking_info[0]['email'],$this->get('translator')->trans('Reservation Cancel Details'),'');
			
			$login = $this->container->get('mytrip_admin.helper.sms')->getOption('smsusername');
			$password = $this->container->get('mytrip_admin.helper.sms')->getOption('smspassword');
			$prefix = $booking_info[0]['cmcode'];	
			$number = $booking_info[0]['mobile'];
			$msg = urlencode($this->get('translator')->trans('Dear Customer, Your booking cancelled successfully for the reference no is').' '.("venacuba-".$bookingid*1024).$this->get('translator')->trans('Refund amount is').' '.$refund['cancelamount'].$bookingtransaction[0]['transactionCurrency'].' '.$this->get('translator')->trans('Refund transaction id is').' '.$result['REFUNDTRANSACTIONID']);			
			$URL="http://api.smsacuba.com/api10allcountries.php?";
			$URL.="login=".$login."&password=".$password."&prefix=".$prefix."&number=".$number."&sender=Mytriptocuba"."&msg=".$msg;			
			$r=@file($URL);
			$succmsg = $r[0];
			
			$session->remove('refund');
			
			$bookingcancel=$em->createQuery("SELECT p FROM MytripAdminBundle:BookingCancel p WHERE p.booking='".$booking[0][0]['bookingId']."'")->getArrayResult();
			
			$hostal_query = $em->createQuery("SELECT h FROM MytripAdminBundle:Hostal h WHERE h.status='Active' AND h.hostalId='".$booking[0]['hostal']."'");			
			$hostals = $hostal_query->getArrayResult();		
			if(empty($hostals)){
				return $this->redirect($this->generateUrl('mytrip_user_destination'));
			}
			
			$hostal_content_query = $em->createQuery("SELECT d FROM MytripAdminBundle:HostalContent d WHERE d.lan='$lan' AND d.hostal=".$hostals[0]['hostalId']);			
			$hostal_content = $hostal_content_query->getArrayResult();
			if(empty($hostal_content)){
				$hostal_content_query = $em->createQuery("SELECT d FROM MytripAdminBundle:HostalContent d WHERE d.lan='en' AND d.hostal=".$hostals[0]['hostalId']);			
				$hostal_content = $hostal_content_query->getArrayResult();
			}
			
			$hostal_room = $em->createQuery("SELECT d FROM MytripAdminBundle:HostalRooms d WHERE d.hostal=".$hostals[0]['hostalId'])->getArrayResult();	
			
			$bookingprice=$em->createQuery("SELECT d FROM MytripAdminBundle:BookingPrice d WHERE d.booking=".$bookingid)->getArrayResult();
			$booking_transaction=$em->createQuery("SELECT d FROM MytripAdminBundle:BookingTransaction d WHERE d.booking=".$bookingid)->getArrayResult();

			$buser=$session->get('user');
			$uid=$buser['userId'];
			
			/**Booking send to the hostal owner email id***/						
			$setting=$em->createQuery("SELECT p FROM MytripAdminBundle:Settings p")->getArrayResult();	
			$busers=$em->createQuery("SELECT p FROM MytripAdminBundle:User p  WHERE  p.userId='".$uid."'")->getArrayResult();
			if($busers[0]['province']!=''){
				$province=$em->createQuery("SELECT d FROM MytripAdminBundle:States d WHERE d.sid=".$busers[0]['province'])->getArrayResult();
			}
			if($busers[0]['country']!=''){
				$country=$em->createQuery("SELECT d FROM MytripAdminBundle:Country d WHERE d.cid=".$busers[0]['country'])->getArrayResult();
			}
			$user_name=$busers[0]['firstname'].' '.$busers[0]['lastname'];
			$address=$busers[0]['address'].', '.$busers[0]['city'].', '.($busers[0]['province']!=''?$province[0]['state'].', ':'').($busers[0]['country']!=''?$country[0]['country']:'');
			
			$emaillist=$em->getRepository('MytripAdminBundle:EmailList')->findOneBy(array('emailListId'=>'12'));							
			$emailcontent=$em->getRepository('MytripAdminBundle:EmailContent')->findOneBy(array('emailList'=>'12','lan'=>$lan));
			if(!empty($emailcontent)){
				$emailcontent=$em->getRepository('MytripAdminBundle:EmailContent')->findOneBy(array('emailList'=>'12','lan'=>'en'));
			}
			
			$from_date=$booking[0][0]['fromDate'];
			$to_date=$booking[0][0]['toDate'];		
			
			if($hostals[0]['ownerEmail']!=''){
				$message=str_replace(array('{owner_name}','{hostal_name}','{check_in}','{check_out}','{room_type}','{rooms}','{nights}','{room_details}','{username}','{address}','{room_price}','{accommodation_cost}','{reservation_charge}','{total_cost}','{ref_no}','{paid_amount}','{balance_amount}','{refund_amount}'),array($hostal_content[0]['ownerName'],$hostal_content[0]['name'],$from_date->format('Y-m-d H:i:s'),$to_date->format('Y-m-d H:i:s'),$hostal_room[0]['roomtype'],$booking[0][0]['noOfRooms'],$booking[0][0]['noOfDays'],'Guests:'.$hostal_room[0]['guests'].',Adults:'.$hostal_room[0]['adults'].',Child:'.$hostal_room[0]['child'],$user_name,$address,$hostal_room[0]['price'].' CAD',number_format($bookingprice[0]['totalPrice']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format($bookingprice[0]['reservationCharge']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format(($bookingprice[0]['totalPrice']+$bookingprice[0]['reservationCharge'])*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],"venacuba-".$bookingid*1024,number_format($bookingprice[0]['reservationTotalPrice']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format((($bookingprice[0]['totalPrice']+$bookingprice[0]['reservationCharge'])-$bookingprice[0]['reservationTotalPrice'])*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],$bookingcancel[0]['refundAmount'].' '.$bookingcancel[0]['refundCurrency']),$emailcontent->getEmailContent());	
							
				$subject=str_replace(array('{owner_name}','{hostal_name}','{check_in}','{check_out}','{room_type}','{rooms}','{nights}','{room_details}','{username}','{address}','{room_price}','{accommodation_cost}','{reservation_charge}','{total_cost}','{ref_no}','{paid_amount}','{balance_amount}','{refund_amount}'),array($hostal_content[0]['ownerName'],$hostal_content[0]['name'],$from_date->format('Y-m-d H:i:s'),$to_date->format('Y-m-d H:i:s'),$hostal_room[0]['roomtype'],$booking[0][0]['noOfRooms'],$booking[0][0]['noOfDays'],'Guests:'.$hostal_room[0]['guests'].',Adults:'.$hostal_room[0]['adults'].',Child:'.$hostal_room[0]['child'],$user_name,$address,$hostal_room[0]['price'].' CAD',number_format($bookingprice[0]['totalPrice']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format($bookingprice[0]['reservationCharge']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format(($bookingprice[0]['totalPrice']+$bookingprice[0]['reservationCharge'])*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],"venacuba-".$bookingid*1024,number_format($bookingprice[0]['reservationTotalPrice']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format((($bookingprice[0]['totalPrice']+$bookingprice[0]['reservationCharge'])-$bookingprice[0]['reservationTotalPrice'])*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],$bookingcancel[0]['refundAmount'].' '.$bookingcancel[0]['refundCurrency']),$emailcontent->getSubject());
											
				$this->mailsend($emaillist->getFromname(),$emaillist->getFromemail(),$hostals[0]['ownerEmail'],$subject,$message,'',0,'','email');
			}
			
			if($hostals[0]['cmcode']!='' && $hostals[0]['mobile']!=''){
				/**Booking send to the hostal owner mobile***/
				$prefix = $hostals[0]['cmcode'];	
				$number = $hostals[0]['mobile'];
				$msg = urlencode($this->get('translator')->trans('Dear '.$hostal_content[0]['ownerName'].', '.$user_name.' has cancelled room in the '.$hostal_content[0]['name'].'. Reference no is').' '."venacuba-".$bookingid*1024);					
				$URL="http://api.smsacuba.com/api10allcountries.php?";
				$URL.="login=".$login."&password=".$password."&prefix=".$prefix."&number=".$number."&sender=Mytriptocuba"."&msg=".$msg;						
				$r=@file($URL);
				$succmsg = $r[0];
			}
			
			/**Booking send to the Site Admin email id***/
			$emaillist=$em->getRepository('MytripAdminBundle:EmailList')->findOneBy(array('emailListId'=>'13'));							
			$emailcontent=$em->getRepository('MytripAdminBundle:EmailContent')->findOneBy(array('emailList'=>'13','lan'=>$lan));
			if(!empty($emailcontent)){
				$emailcontent=$em->getRepository('MytripAdminBundle:EmailContent')->findOneBy(array('emailList'=>'13','lan'=>'en'));
			}	
			$admin=$em->createQuery("SELECT p FROM MytripAdminBundle:Admin p WHERE p.adminId='1'")->getArrayResult();	
			
			$message=str_replace(array('{admin_name}','{owner_name}','{hostal_name}','{check_in}','{check_out}','{room_type}','{rooms}','{nights}','{room_details}','{username}','{address}','{room_price}','{accommodation_cost}','{reservation_charge}','{total_cost}','{ref_no}','{paid_amount}','{balance_amount}','{refund_amount}'),array($admin[0]['name'],$hostal_content[0]['ownerName'],$hostal_content[0]['name'],$from_date->format('Y-m-d H:i:s'),$to_date->format('Y-m-d H:i:s'),$hostal_room[0]['roomtype'],$booking[0][0]['noOfRooms'],$booking[0][0]['noOfDays'],'Guests:'.$hostal_room[0]['guests'].',Adults:'.$hostal_room[0]['adults'].',Child:'.$hostal_room[0]['child'],$user_name,$address,$hostal_room[0]['price'].' CAD',number_format($bookingprice[0]['totalPrice']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format($bookingprice[0]['reservationCharge']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format(($bookingprice[0]['totalPrice']+$bookingprice[0]['reservationCharge'])*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],"venacuba-".$bookingid*1024,number_format($bookingprice[0]['reservationTotalPrice']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format((($bookingprice[0]['totalPrice']+$bookingprice[0]['reservationCharge'])-$bookingprice[0]['reservationTotalPrice'])*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],$bookingcancel[0]['refundAmount'].' '.$bookingcancel[0]['refundCurrency']),$emailcontent->getEmailContent());
							
			$subject=str_replace(array('{admin_name}','{owner_name}','{hostal_name}','{check_in}','{check_out}','{room_type}','{rooms}','{nights}','{room_details}','{username}','{address}','{room_price}','{accommodation_cost}','{reservation_charge}','{total_cost}','{ref_no}','{paid_amount}','{balance_amount}','{refund_amount}'),array($admin[0]['name'],$hostal_content[0]['ownerName'],$hostal_content[0]['name'],$from_date->format('Y-m-d H:i:s'),$to_date->format('Y-m-d H:i:s'),$hostal_room[0]['roomtype'],$booking[0][0]['noOfRooms'],$booking[0][0]['noOfDays'],'Guests:'.$hostal_room[0]['guests'].',Adults:'.$hostal_room[0]['adults'].',Child:'.$hostal_room[0]['child'],$user_name,$address,$hostal_room[0]['price'].' CAD',number_format($bookingprice[0]['totalPrice']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format($bookingprice[0]['reservationCharge']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format(($bookingprice[0]['totalPrice']+$bookingprice[0]['reservationCharge'])*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],"venacuba-".$bookingid*1024,number_format($bookingprice[0]['reservationTotalPrice']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format((($bookingprice[0]['totalPrice']+$bookingprice[0]['reservationCharge'])-$bookingprice[0]['reservationTotalPrice'])*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],$bookingcancel[0]['refundAmount'].' '.$bookingcancel[0]['refundCurrency']),$emailcontent->getSubject());
											
				$this->mailsend($emaillist->getFromname(),$emaillist->getFromemail(),$admin[0]['email'],$subject,$message,'',0,'','email');
			
			
			if($admin[0]['cmcode']!='' && $admin[0]['mobile']!=''){
				/**Booking send to the site admin mobile***/
				$prefix = $admin[0]['cmcode'];	
				$number = $admin[0]['mobile'];
				$msg = urlencode($this->get('translator')->trans('Dear '.$admin[0]['name'].', '.$user_name.' has cancelled room in the '.$hostal_content[0]['name'].'. Reference no is').' '."venacuba-".$bookingid*1024);					
				$URL="http://api.smsacuba.com/api10allcountries.php?";
				$URL.="login=".$login."&password=".$password."&prefix=".$prefix."&number=".$number."&sender=Mytriptocuba"."&msg=".$msg;						
				$r=@file($URL);
				$succmsg = $r[0];
			}	
			
			$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Your booking cancelled successfully'));
			return $this->redirect($this->generateUrl('mytrip_user_bookinghistory'));
		}else{
			$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Sorry, Transaction has some problem. Please try again later or contact admin'));
			return $this->redirect($this->generateUrl('mytrip_user_bookinghistory'));
		}
		
	}
	
	public function hash_call($methodName,$nvpStr)
    {     
       
        $API_UserName= $this->container->get('payum.context.paypal_express_checkout_and_doctrine_orm.api')->getOption('username');
        $API_Password= $this->container->get('payum.context.paypal_express_checkout_and_doctrine_orm.api')->getOption('password');
        $API_Signature= $this->container->get('payum.context.paypal_express_checkout_and_doctrine_orm.api')->getOption('signature');
        $API_Endpoint =$this->container->get('payum.context.paypal_express_checkout_and_doctrine_orm.api')->getApiEndpoint();
        $version='84.0';
        //setting the curl parameters.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$API_Endpoint);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
    
        //turning off the server and peer verification(TrustManager Concept).
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POST, 1);
        //if USE_PROXY constant set to TRUE in Constants.php, then only proxy will be enabled.
        //Set proxy name to PROXY_HOST and port number to PROXY_PORT in constants.php 
        define('USE_PROXY',FALSE);
		define('PROXY_HOST', '127.0.0.1');
		define('PROXY_PORT', '808');
		
        if(USE_PROXY)
            curl_setopt ($ch, CURLOPT_PROXY, PROXY_HOST.":".PROXY_PORT); 
    
        //NVPRequest for submitting to server
        $nvpreq="METHOD=".urlencode($methodName)."&VERSION=".urlencode($version)."&PWD=".urlencode($API_Password)."&USER=".urlencode($API_UserName)."&SIGNATURE=".urlencode($API_Signature).$nvpStr;
		
        //setting the nvpreq as POST FIELD to curl		
        curl_setopt($ch,CURLOPT_POSTFIELDS,$nvpreq);
    
        //getting response from server
        $response = curl_exec($ch);
        //convrting NVPResponse to an Associative Array
        $nvpResArray=$this->deformatNVP($response);
        $nvpReqArray=$this->deformatNVP($nvpreq);
    
        if (curl_errno($ch))
            $nvpResArray = $this->APIError(curl_errno($ch),curl_error($ch),$nvpResArray);
        else 
            curl_close($ch);
        return $nvpResArray;
    }
    
    /** This function will take NVPString and convert it to an Associative Array and it will decode the response.
      * It is usefull to search for a particular key and displaying arrays.
      * @nvpstr is NVPString.
      * @nvpArray is Associative Array.
      */
    
    private function deformatNVP($nvpstr)
    {
    
        $intial=0;
         $nvpArray = array();
    
    
        while(strlen($nvpstr)){
            //postion of Key
            $keypos= strpos($nvpstr,'=');
            //position of value
            $valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr);
    
            /*getting the Key and Value values and storing in a Associative Array*/
            $keyval=substr($nvpstr,$intial,$keypos);
            $valval=substr($nvpstr,$keypos+1,$valuepos-$keypos-1);
            //decoding the respose
            $nvpArray[urldecode($keyval)] =urldecode( $valval);
            $nvpstr=substr($nvpstr,$valuepos+1,strlen($nvpstr));
         }
        return $nvpArray;
    }
	
	 /** Paypal Refund function
	  * This function will take NVPString and convert it to an Associative Array and it will decode the response.
      * It is usefull to search for a particular key and displaying arrays.
      * @nvpstr is NVPString.
      * @nvpArray is Associative Array.
      */
	  
	 public function PaypalRefund($paymentInfo=array()) {		  		
		$amount=urlencode($paymentInfo['amount']);
		$refundType=urlencode($paymentInfo['refundType']);
		$transactionID=urlencode($paymentInfo['transactionID']);		
        $currencyCode=urlencode($paymentInfo['currency']);
		$nvpStr = "&TRANSACTIONID=$transactionID&REFUNDTYPE=$refundType&CURRENCYCODE=$currencyCode&AMT=$amount";		
		$resArray=$this->hash_call("RefundTransaction",$nvpStr);
		return $resArray;
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
					->setContentType("text/html");
		
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