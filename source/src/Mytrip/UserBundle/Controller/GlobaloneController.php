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

class GlobaloneController extends Controller
{
    
    public function indexAction(Request $request)
    {
       
        $session = $request->getSession();		
	$lan=$session->get('language');			
	$request->setLocale($lan);
        
        if ($session->get('bookingId')) {
			$bookingid=$session->get('bookingId');
			
			$em = $this->container->get('doctrine')->getManager();	
			$booking=$em->createQuery("SELECT d,IDENTITY(d.hostal) AS hostal FROM MytripAdminBundle:Booking d WHERE d.bookingId=".$bookingid)->getArrayResult();	
			$hostal_content=$em->createQuery("SELECT d FROM MytripAdminBundle:HostalContent d WHERE d.hostal=".$booking[0]['hostal']." AND d.lan='".$lan."'")->getArrayResult();		
			$bookingprice=$em->createQuery("SELECT p FROM MytripAdminBundle:BookingPrice p WHERE p.booking='".$booking[0][0]['bookingId']."'")->getArrayResult();
			$bookinginfo=$em->createQuery("SELECT p FROM MytripAdminBundle:BookingInfo p WHERE p.booking='".$booking[0][0]['bookingId']."'")->getArrayResult();	
			if($bookinginfo[0]['province']!=''){
				$province=$em->createQuery("SELECT p FROM MytripAdminBundle:States p WHERE p.sid='".$bookinginfo[0]['province']."'")->getArrayResult();	
			}
			if($bookinginfo[0]['country']!=''){
				$country=$em->createQuery("SELECT p FROM MytripAdminBundle:Country p WHERE p.cid='".$bookinginfo[0]['country']."'")->getArrayResult();	
			}
			
            /** @var $paymentDetails PaymentDetails */  
			if($request->getMethod()=="POST"){          
				$currency = strtolower($bookingprice[0]['conversionCurrency']);	
									
				$ownername = urlencode($request->request->get('cardowner'));
				$cardnumber = urlencode(str_replace(" ","",$request->request->get('cardnumber')));
				$cardtype = urlencode(str_replace(" ","",$request->request->get('cardtype'))); 
				$exmonth =  urlencode($request->request->get('exmonth'));
				$exyear =  urlencode($request->request->get('exyear'));
				$cvv =  urlencode($request->request->get('cvv'));
				
				$name =  urlencode($bookinginfo[0]['firstname']." ".$bookinginfo[0]['lastname']);
				$email =  urlencode($bookinginfo[0]['email']);
				$phone =  urlencode($bookinginfo[0]['mobile']);
				$orderno= urlencode($booking[0][0]['bookingId']*1024);
				$amount =  urlencode($bookingprice[0]['reservationTotalPrice']*$bookingprice[0]['conversionRate']);
				
				$terminalid  = $this->container->get('mytrip_admin.helper.globalone')->getOption('terminalid');	
				$secret  = $this->container->get('mytrip_admin.helper.globalone')->getOption('secret');	
				$cardExpiry=$exmonth.substr($exyear, -2);
				
				$multicur = $this->container->get('mytrip_admin.helper.globalone')->getOption('multicurrency');
				$testAccount = $this->container->get('mytrip_admin.helper.globalone')->getOption('testaccount');
				
				$gateway='globalone';
				
				$auth = new \XmlAuthRequest($terminalid,$orderno,$bookingprice[0]['conversionCurrency'],$amount,$cardnumber,$cardtype);
				if($cardtype != "SECURECARD") $auth->SetNonSecureCardCardInfo($cardExpiry,$ownername);
				if($cvv != "") $auth->SetCvv($cvv);
				if($multicur) $auth->SetMultiCur();
				
				$response = $auth->ProcessRequestToGateway($secret,$testAccount, $gateway);	
						
				$expectedResponseHash = md5($terminalid . $response->UniqueRef() . ($multicur == true ? $currency : '') . $amount . $response->DateTime() . $response->ResponseCode() . $response->ResponseText() . $secret);
				if($response->IsError()){
					$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Sorry, Payment failed try once again'));
					return $this->redirect($this->generateUrl('mytrip_user_makepayment',array('bookingId'=>$booking[0][0]['bookingId']*1024)));
				}
				elseif($expectedResponseHash == $response->Hash()) {
					switch($response->ResponseCode()) {
						case "A" :	# -- If using local database, update order as Authorised.
								//echo 'Payment Processed successfully. Thanks you for your order.';
								$uniqueRef = $response->UniqueRef();
								$responseText = $response->ResponseText();
								$approvalCode = $response->ApprovalCode();
								$avsResponse = $response->AvsResponse();
								$cvvResponse = $response->CvvResponse();
													
								$booking_transaction = new \Mytrip\AdminBundle\Entity\BookingTransaction();
								$booking_transaction->setBooking($this->getDoctrine()->getRepository('MytripAdminBundle:Booking')->find($bookingid));						
								$booking_transaction->setPaymentType('Globalone');			
								$booking_transaction->setTransactionId($uniqueRef);
								$booking_transaction->setTransactionDate(date('Y-m-d H:i:s'));
								$booking_transaction->setTransactionAmount($amount);
								$booking_transaction->setTransactionCurrency($bookingprice[0]['conversionCurrency']);	
								if(empty($btransaction)){			
									$em->persist($booking_transaction);
								}
								$em->flush();
								
								$em->createQuery("UPDATE MytripAdminBundle:Booking p SET p.status='Confirmed' WHERE p.bookingId='".$bookingid."'")->execute();
								
								$booking=$em->createQuery("SELECT d,IDENTITY(d.hostal) AS hostal FROM MytripAdminBundle:Booking d WHERE d.bookingId=".$bookingid)->getArrayResult();				
								$booking_info=$em->createQuery("SELECT d FROM MytripAdminBundle:BookingInfo d WHERE d.booking=".$bookingid)->getArrayResult();
											
								/*******Contact mail send to admin***********/								
								$this->mailsend("Mytrip Cuba","info@mytriptocuba.com",$booking_info[0]['email'],$this->get('translator')->trans('Booking Details'),'','',0,'','ticket');
								$login = $this->container->get('mytrip_admin.helper.sms')->getOption('smsusername');
								$password = $this->container->get('mytrip_admin.helper.sms')->getOption('smspassword');
								$prefix = $booking_info[0]['cmcode'];	
								$number = $booking_info[0]['mobile'];
								$msg = urlencode($this->get('translator')->trans('Dear Customer, You are successfully booked the hotel rooms in our site. Your reference no is').' '."venacuba-".$bookingid*1024);			
								$URL="http://api.smsacuba.com/api10allcountries.php?";
								$URL.="login=".$login."&password=".$password."&prefix=".$prefix."&number=".$number."&sender=Mytriptocuba"."&msg=".$msg;							
								$r=@file($URL);
								$succmsg = $r[0];
								if($succmsg=="SMS ENVIADO"){
									$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Rooms booking successfull. Booking details sent to your mail id and SMS.'));
								}else{
									$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Rooms booking successfull. Booking details sent to your mail id.'));
								}
								
								
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
                                /*
                                if($hostal_content[0]['province']!=''){
                                    $province=$em->createQuery("SELECT d FROM MytripAdminBundle:States d WHERE d.sid=".$hostal_content[0]['province'])->getArrayResult();
                                }
                                if($hostal_content[0]['country']!=''){
                                    $country=$em->createQuery("SELECT d FROM MytripAdminBundle:Country d WHERE d.cid=".$hostal_content[0]['country'])->getArrayResult();
                                } */
								$address = $hostal_content[0]['address'].', '.$hostal_content[0]['city'].', '.($hostal_content[0]['province']!=''?$province[0]['state'].', ':'').($hostal_content[0]['country']!=''?$country[0]['country']:'');

								// booking rooms data.
								$rooms_data = '| ';
								foreach ($booking[0][0]['rooms'] as $room) {
									$rooms_data .= $room['roomtype'] . ' | ';
								}
								//$hostal_rooms = $em->createQuery("SELECT d FROM MytripAdminBundle:HostalRooms d WHERE d.hostal=".$hostals[0]['hostalId'])->getArrayResult();	
								
								$bookingprice=$em->createQuery("SELECT d FROM MytripAdminBundle:BookingPrice d WHERE d.booking=".$bookingid)->getArrayResult();
								$booking_transaction=$em->createQuery("SELECT d FROM MytripAdminBundle:BookingTransaction d WHERE d.booking=".$bookingid)->getArrayResult();
					
								$buser=$session->get('user');
								$uid=$buser['userId'];
								
								/**Booking send to the hostal owner email id***/						
								$setting=$em->createQuery("SELECT p FROM MytripAdminBundle:Settings p")->getArrayResult();	
								$busers=$em->createQuery("SELECT p FROM MytripAdminBundle:User p  WHERE  p.userId='".$uid."'")->getArrayResult();
								//if($busers[0]['province']!=''){
								//	$province=$em->createQuery("SELECT d FROM MytripAdminBundle:States d WHERE d.sid=".$busers[0]['province'])->getArrayResult();
								//}
								//if($busers[0]['country']!=''){
								//	$country=$em->createQuery("SELECT d FROM MytripAdminBundle:Country d WHERE d.cid=".$busers[0]['country'])->getArrayResult();
								//}
								$user_name=$busers[0]['firstname'].' '.$busers[0]['lastname'];
								//$address=$busers[0]['address'].', '.$busers[0]['city'].', '.($busers[0]['province']!=''?$province[0]['state'].', ':'').($busers[0]['country']!=''?$country[0]['country']:'');
								
								$emaillist=$em->getRepository('MytripAdminBundle:EmailList')->findOneBy(array('emailListId'=>'10'));							
								$emailcontent=$em->getRepository('MytripAdminBundle:EmailContent')->findOneBy(array('emailList'=>'10','lan'=>$lan));
								if(empty($emailcontent)){
									$emailcontent=$em->getRepository('MytripAdminBundle:EmailContent')->findOneBy(array('emailList'=>'10','lan'=>'en'));
								}
									
								$from_date=$booking[0][0]['fromDate'];
								$to_date=$booking[0][0]['toDate'];
								
								if($hostals[0]['ownerEmail']!=''){
									$message=str_replace(array('{owner_name}','{hostal_name}','{check_in}','{check_out}','{rooms}','{nights}','{username}','{address}','{accommodation_cost}','{reservation_charge}','{total_cost}','{ref_no}','{paid_amount}','{balance_amount}'),
										array($hostal_content[0]['ownerName'],$hostal_content[0]['name'],$from_date->format('Y-m-d H:i:s'),$to_date->format('Y-m-d H:i:s'),$rooms_data,$booking[0][0]['noOfDays'],$user_name,$address,number_format($bookingprice[0]['totalPrice']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format($bookingprice[0]['reservationCharge']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format(($bookingprice[0]['totalPrice']+$bookingprice[0]['reservationCharge'])*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],"venacuba-".$bookingid*1024,number_format($bookingprice[0]['reservationTotalPrice']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format((($bookingprice[0]['totalPrice']+$bookingprice[0]['reservationCharge'])-$bookingprice[0]['reservationTotalPrice'])*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency']),$emailcontent->getEmailContent());				
									$subject=str_replace(array('{owner_name}','{hostal_name}','{check_in}','{check_out}','{rooms}','{nights}','{username}','{address}','{accommodation_cost}','{reservation_charge}','{total_cost}','{ref_no}','{paid_amount}','{balance_amount}'),
										array($hostal_content[0]['ownerName'],$hostal_content[0]['name'],$from_date->format('Y-m-d H:i:s'),$to_date->format('Y-m-d H:i:s'),$rooms_data,$booking[0][0]['noOfDays'],$user_name,$address,number_format($bookingprice[0]['totalPrice']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format($bookingprice[0]['reservationCharge']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format(($bookingprice[0]['totalPrice']+$bookingprice[0]['reservationCharge'])*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],"venacuba-".$bookingid*1024,number_format($bookingprice[0]['reservationTotalPrice']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format((($bookingprice[0]['totalPrice']+$bookingprice[0]['reservationCharge'])-$bookingprice[0]['reservationTotalPrice'])*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency']),$emailcontent->getSubject());
																
									$this->mailsend($emaillist->getFromname(),$emaillist->getFromemail(),$hostals[0]['ownerEmail'],$subject,$message,'',0,'','email');
								}
								
								if($hostals[0]['cmcode']!='' && $hostals[0]['mobile']!=''){
									/**Booking send to the hostal owner mobile***/
									$prefix = $hostals[0]['cmcode'];	
									$number = $hostals[0]['mobile'];
									$msg = urlencode($this->get('translator')->trans('Dear '.$hostal_content[0]['ownerName'].', '.$user_name.' has booked room in the '.$hostal_content[0]['name'].'. Reference no is').' '."venacuba-".$bookingid*1024);					
									$URL="http://api.smsacuba.com/api10allcountries.php?";
									$URL.="login=".$login."&password=".$password."&prefix=".$prefix."&number=".$number."&sender=Mytriptocuba"."&msg=".$msg;						
									$r=@file($URL);
									$succmsg = $r[0];
								}
								
								/**Booking send to the Site Admin email id***/
								$emaillist=$em->getRepository('MytripAdminBundle:EmailList')->findOneBy(array('emailListId'=>'11'));							
								$emailcontent=$em->getRepository('MytripAdminBundle:EmailContent')->findOneBy(array('emailList'=>'11','lan'=>$lan));
								if(empty($emailcontent)){
									$emailcontent=$em->getRepository('MytripAdminBundle:EmailContent')->findOneBy(array('emailList'=>'11','lan'=>'en'));
								}	
								$admin=$em->createQuery("SELECT p FROM MytripAdminBundle:Admin p WHERE p.adminId='1'")->getArrayResult();	
								
								$message=str_replace(array('{admin_name}','{owner_name}','{hostal_name}','{check_in}','{check_out}','{rooms}','{nights}','{username}','{address}','{accommodation_cost}','{reservation_charge}','{total_cost}','{ref_no}','{paid_amount}','{balance_amount}'),
									array($admin[0]['name'],$hostal_content[0]['ownerName'],$hostal_content[0]['name'],$from_date->format('Y-m-d H:i:s'),$to_date->format('Y-m-d H:i:s'),$rooms_data,$booking[0][0]['noOfDays'],$user_name,$address,number_format($bookingprice[0]['totalPrice']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format($bookingprice[0]['reservationCharge']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format(($bookingprice[0]['totalPrice']+$bookingprice[0]['reservationCharge'])*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],"venacuba-".$bookingid*1024,number_format($bookingprice[0]['reservationTotalPrice']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format((($bookingprice[0]['totalPrice']+$bookingprice[0]['reservationCharge'])-$bookingprice[0]['reservationTotalPrice'])*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency']),$emailcontent->getEmailContent());
												
								$subject=str_replace(array('{admin_name}','{owner_name}','{hostal_name}','{check_in}','{check_out}','{rooms}','{nights}','{username}','{address}','{accommodation_cost}','{reservation_charge}','{total_cost}','{ref_no}','{paid_amount}','{balance_amount}'),
									array($admin[0]['name'],$hostal_content[0]['ownerName'],$hostal_content[0]['name'],$from_date->format('Y-m-d H:i:s'),$to_date->format('Y-m-d H:i:s'),$rooms_data,$booking[0][0]['noOfDays'],$user_name,$address,number_format($bookingprice[0]['totalPrice']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format($bookingprice[0]['reservationCharge']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format(($bookingprice[0]['totalPrice']+$bookingprice[0]['reservationCharge'])*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],"venacuba-".$bookingid*1024,number_format($bookingprice[0]['reservationTotalPrice']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format((($bookingprice[0]['totalPrice']+$bookingprice[0]['reservationCharge'])-$bookingprice[0]['reservationTotalPrice'])*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency']),$emailcontent->getSubject());
																
								$this->mailsend($emaillist->getFromname(),$emaillist->getFromemail(),$admin[0]['email'],$subject,$message,'',0,'','email');
								
								
								if($admin[0]['cmcode']!='' && $admin[0]['mobile']!=''){
									/**Booking send to the site admin mobile***/
									$prefix = $admin[0]['cmcode'];	
									$number = $admin[0]['mobile'];
									$msg = urlencode($this->get('translator')->trans('Dear '.$admin[0]['name'].', '.$user_name.' has booked room in the '.$hostal_content[0]['name'].'. Reference no is').' '."venacuba-".$bookingid*1024);					
									$URL="http://api.smsacuba.com/api10allcountries.php?";
									$URL.="login=".$login."&password=".$password."&prefix=".$prefix."&number=".$number."&sender=Mytriptocuba"."&msg=".$msg;						
									$r=@file($URL);
									$succmsg = $r[0];
								}	
								
								$session->remove('payment');
								$session->remove('bookingId');
								//$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Rooms booking successfully. Booking details send to your mail id.'));
								return $this->redirect($this->generateUrl('mytrip_user_bookinghistory'));	
				
								break;
						case "R" :
						case "D" :
						case "C" :
						case "S" :
						default  :	# -- If using local database, update order as declined/failed --
								$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Sorry, Payment failed try once again'));
								return $this->redirect($this->generateUrl('mytrip_user_makepayment',array('bookingId'=>$booking[0][0]['bookingId']*1024)));
					}
				} else {
					$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Sorry, Payment failed try once again'));
					return $this->redirect($this->generateUrl('mytrip_user_makepayment',array('bookingId'=>$booking[0][0]['bookingId']*1024)));
				}	
				
			}else{
				return $this->redirect($this->generateUrl('mytrip_user_homepage'));
			}
        }else{
			return $this->redirect($this->generateUrl('mytrip_user_homepage'));
		}
		
    }
	
	
	
	 /**Globalone Refund function
	  * This function will take NVPString and convert it to an Associative Array and it will decode the response.
      * It is usefull to search for a particular key and displaying arrays.
      */
	
	public function globalonerefundAction(Request $request){
		$session = $request->getSession();
		if ($session->get('refund')=='') {
			return $this->redirect($this->generateUrl('mytrip_user_homepage'));
		}
			
		$username=$session->get('user');
		$refunds=$session->get('refund');
		
		if(empty($username)){			
			return $this->redirect($this->generateUrl('mytrip_user_homepage'));
		}	
		$lan=$session->get('language');
		$request->setLocale($lan);
                
		$em = $this->container->get('doctrine')->getManager();		
		$bookingtransaction=$em->createQuery("SELECT p FROM MytripAdminBundle:BookingTransaction p WHERE p.booking='".$refunds['refund_booking_id']."'")->getArrayResult();		
		$booking=$em->createQuery("SELECT p FROM MytripAdminBundle:Booking p WHERE p.bookingId='".$refunds['refund_booking_id']."'")->getArrayResult();
		if($booking[0]['status']!="Confirmed"){
			$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Your booking already cancelled or payment not proceed. Please check it.'));
			return $this->redirect($this->generateUrl('mytrip_user_bookinghistory'));
		}
		$paymentArray['amount']=$refunds['cancelamount'];		
		$paymentArray['transactionID']=$bookingtransaction[0]['transactionId'];
		$paymentArray['orderno']="venacuba-".$refunds['refund_booking_id']*1024;
		$paymentArray['currency']=$bookingtransaction[0]['transactionCurrency'];
		
		$terminalid  = $this->container->get('mytrip_admin.helper.globalone')->getOption('terminalid');	
		$secret  = $this->container->get('mytrip_admin.helper.globalone')->getOption('secret');	
		$multicur=$this->container->get('mytrip_admin.helper.globalone')->getOption('multicurrency');	
		$testAccount = $this->container->get('mytrip_admin.helper.globalone')->getOption('testaccount');
		$gateway='globalone';
		$autoready='';
		$amount=$paymentArray['amount'];
		# Set up the refund object
		$refund = new \XmlRefundRequest($terminalid,$paymentArray['orderno'],$paymentArray['amount'],'User','Cancel the ticket');		
		$refund->SetUniqueRef($paymentArray['transactionID']);
		if($autoready) $refund->SetAutoReady($autoready);
		
		# Perform the refund and read in the result
		$response = $refund->ProcessRequestToGateway($secret,$testAccount, $gateway);
		
		//$expectedResponseHash = md5($terminalId . $response->UniqueRef() . ($multicur == true ? $currency : '') . $amount . $response->DateTime() . $response->ResponseCode() . $response->ResponseText() . $secret);
		
		if($response->IsError()){ 
			$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Sorry, Transaction has some problem. Please try again later or contact admin'));
			return $this->redirect($this->generateUrl('mytrip_user_bookinghistory'));
		}else{
		//elseif($expectedResponseHash == $response->Hash()) {
			switch($response->ResponseCode()) {
				case "A" :	# -- If using local database, update order as (partially) Refunded.		
						$responseText = $response->ResponseText();
						$UniqueRef = $response->UniqueRef();						
						$cancel = new \Mytrip\AdminBundle\Entity\BookingCancel();					
						$cancel->setBooking($this->getDoctrine()->getRepository('MytripAdminBundle:Booking')->find($refunds['refund_booking_id']));
						$cancel->setCancelPercentage($refunds['cancel_percentage']);
						$cancel->setCancelDate(new \DateTime(date('Y-m-d')));
						$cancel->setRefundReferenceno($UniqueRef);
						$cancel->setRefundAmount($refunds['cancelamount']);
						$cancel->setRefundDate(new \DateTime(date('Y-m-d')));
                                                $cancel->setCreatedDate(new \DateTime(date('Y-m-d')));
						$cancel->setRefundCurrency($bookingtransaction[0]['transactionCurrency']);
						$cancel->setStatus('Refund');
						$em->persist($cancel);
						$em->flush();
						
						$em->createQuery("UPDATE MytripAdminBundle:Booking p SET p.status='Cancelled' WHERE p.bookingId='".$refunds['refund_booking_id']."'")->execute();		
							
						$bookingid=$refunds['refund_booking_id']; //,IDENTITY(d.rooms) AS 
						$booking=$em->createQuery("SELECT d,IDENTITY(d.hostal) AS hostal FROM MytripAdminBundle:Booking d WHERE d.bookingId=".$bookingid)->getArrayResult();			
						$booking_info=$em->createQuery("SELECT d FROM MytripAdminBundle:BookingInfo d WHERE d.booking=".$bookingid)->getArrayResult();
						$this->mailsend("Mytrip Cuba","info@mytriptocuba.com",$booking_info[0]['email'],$this->get('translator')->trans('Reservation Cancel Details'),'');
						
						$login = $this->container->get('mytrip_admin.helper.sms')->getOption('smsusername');
						$password = $this->container->get('mytrip_admin.helper.sms')->getOption('smspassword');
						$prefix = $booking_info[0]['cmcode'];	
						$number = $booking_info[0]['mobile'];
						$msg = urlencode($this->get('translator')->trans('Dear Customer, Your booking cancelled successfully for the reference no is').' '.("venacuba-".$bookingid*1024).$this->get('translator')->trans('Refund amount is').' '.$refunds['cancelamount'].$bookingtransaction[0]['transactionCurrency'].' '.$this->get('translator')->trans('Refund transaction id is').' '.$result['trnId']);			
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
                        /*
                         if($hostal_content[0]['province']!=''){
                         
                            $province=$em->createQuery("SELECT d FROM MytripAdminBundle:States d WHERE d.sid=".$hostal_content[0]['province'])->getArrayResult();
                        }
                        if($hostal_content[0]['country']!=''){
                            $country=$em->createQuery("SELECT d FROM MytripAdminBundle:Country d WHERE d.cid=".$hostal_content[0]['country'])->getArrayResult();
                        }
                        */
                        $address = $hostal_content[0]['address'].', '.$hostal_content[0]['city'].', '.($hostal_content[0]['province']!=''?$province[0]['state'].', ':'').($hostal_content[0]['country']!=''?$country[0]['country']:'');

						// booking rooms data.
						$rooms_data = '| ';
						foreach ($booking[0][0]['rooms'] as $room) {
							$rooms_data .= $room['roomtype'] . ' | ';
						}
						//$hostal_room = $em->createQuery("SELECT d FROM MytripAdminBundle:HostalRooms d WHERE d.hostal=".$hostals[0]['hostalId'])->getArrayResult();	
						
						$bookingprice=$em->createQuery("SELECT d FROM MytripAdminBundle:BookingPrice d WHERE d.booking=".$bookingid)->getArrayResult();
						$booking_transaction=$em->createQuery("SELECT d FROM MytripAdminBundle:BookingTransaction d WHERE d.booking=".$bookingid)->getArrayResult();
			
						$buser=$session->get('user');
						$uid=$buser['userId'];
						
						/**Booking send to the hostal owner email id***/						
						$setting=$em->createQuery("SELECT p FROM MytripAdminBundle:Settings p")->getArrayResult();	
						$busers=$em->createQuery("SELECT p FROM MytripAdminBundle:User p  WHERE  p.userId='".$uid."'")->getArrayResult();
						//if($busers[0]['province']!=''){
						//	$province=$em->createQuery("SELECT d FROM MytripAdminBundle:States d WHERE d.sid=".$busers[0]['province'])->getArrayResult();
						//}
						//if($busers[0]['country']!=''){
						//	$country=$em->createQuery("SELECT d FROM MytripAdminBundle:Country d WHERE d.cid=".$busers[0]['country'])->getArrayResult();
						//}
						$user_name=$busers[0]['firstname'].' '.$busers[0]['lastname'];
						//$address=$busers[0]['address']." ".$busers[0]['address1'].', '.$busers[0]['city'].', '.($busers[0]['province']!=''?$province[0]['state'].', ':'').($busers[0]['country']!=''?$country[0]['country']:'');
						
						$emaillist=$em->getRepository('MytripAdminBundle:EmailList')->findOneBy(array('emailListId'=>'12'));							
						$emailcontent=$em->getRepository('MytripAdminBundle:EmailContent')->findOneBy(array('emailList'=>'12','lan'=>$lan));
						if(empty($emailcontent)){
							$emailcontent=$em->getRepository('MytripAdminBundle:EmailContent')->findOneBy(array('emailList'=>'12','lan'=>'en'));
						}
						$from_date=$booking[0][0]['fromDate'];
						$to_date=$booking[0][0]['toDate'];	
						
						if($hostals[0]['ownerEmail']!=''){
							$message=str_replace(array('{owner_name}','{hostal_name}','{check_in}','{check_out}','{rooms}','{nights}','{username}','{address}','{accommodation_cost}','{reservation_charge}','{total_cost}','{ref_no}','{paid_amount}','{balance_amount}','{refund_amount}'),
								array($hostal_content[0]['ownerName'],$hostal_content[0]['name'],$from_date->format('Y-m-d H:i:s'),$to_date->format('Y-m-d H:i:s'),$rooms_data,$booking[0][0]['noOfDays'],$user_name,$address,number_format($bookingprice[0]['totalPrice']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format($bookingprice[0]['reservationCharge']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format(($bookingprice[0]['totalPrice']+$bookingprice[0]['reservationCharge'])*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],"venacuba-".$bookingid*1024,number_format($bookingprice[0]['reservationTotalPrice']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format((($bookingprice[0]['totalPrice']+$bookingprice[0]['reservationCharge'])-$bookingprice[0]['reservationTotalPrice'])*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],$bookingcancel[0]['refundAmount'].' '.$bookingcancel[0]['refundCurrency']),$emailcontent->getEmailContent());	
										
							$subject=str_replace(array('{owner_name}','{hostal_name}','{check_in}','{check_out}','{rooms}','{nights}','{username}','{address}','{accommodation_cost}','{reservation_charge}','{total_cost}','{ref_no}','{paid_amount}','{balance_amount}','{refund_amount}'),
								array($hostal_content[0]['ownerName'],$hostal_content[0]['name'],$from_date->format('Y-m-d H:i:s'),$to_date->format('Y-m-d H:i:s'),$rooms_data,$booking[0][0]['noOfDays'],$user_name,$address,number_format($bookingprice[0]['totalPrice']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format($bookingprice[0]['reservationCharge']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format(($bookingprice[0]['totalPrice']+$bookingprice[0]['reservationCharge'])*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],"venacuba-".$bookingid*1024,number_format($bookingprice[0]['reservationTotalPrice']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format((($bookingprice[0]['totalPrice']+$bookingprice[0]['reservationCharge'])-$bookingprice[0]['reservationTotalPrice'])*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],$bookingcancel[0]['refundAmount'].' '.$bookingcancel[0]['refundCurrency']),$emailcontent->getSubject());
														
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
						if(empty($emailcontent)){
							$emailcontent=$em->getRepository('MytripAdminBundle:EmailContent')->findOneBy(array('emailList'=>'13','lan'=>'en'));
						}	
						$admin=$em->createQuery("SELECT p FROM MytripAdminBundle:Admin p WHERE p.adminId='1'")->getArrayResult();	
						
						$message=str_replace(array('{admin_name}','{owner_name}','{hostal_name}','{check_in}','{check_out}','{rooms}','{nights}','{username}','{address}','{accommodation_cost}','{reservation_charge}','{total_cost}','{ref_no}','{paid_amount}','{balance_amount}','{refund_amount}'),
							array($admin[0]['name'],$hostal_content[0]['ownerName'],$hostal_content[0]['name'],$from_date->format('Y-m-d H:i:s'),$to_date->format('Y-m-d H:i:s'),$rooms_data,$booking[0][0]['noOfDays'],$user_name,$address,number_format($bookingprice[0]['totalPrice']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format($bookingprice[0]['reservationCharge']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format(($bookingprice[0]['totalPrice']+$bookingprice[0]['reservationCharge'])*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],"venacuba-".$bookingid*1024,number_format($bookingprice[0]['reservationTotalPrice']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format((($bookingprice[0]['totalPrice']+$bookingprice[0]['reservationCharge'])-$bookingprice[0]['reservationTotalPrice'])*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],$bookingcancel[0]['refundAmount'].' '.$bookingcancel[0]['refundCurrency']),$emailcontent->getEmailContent());
										
						$subject=str_replace(array('{admin_name}','{owner_name}','{hostal_name}','{check_in}','{check_out}','{rooms}','{nights}','{username}','{address}','{accommodation_cost}','{reservation_charge}','{total_cost}','{ref_no}','{paid_amount}','{balance_amount}','{refund_amount}'),
							array($admin[0]['name'],$hostal_content[0]['ownerName'],$hostal_content[0]['name'],$from_date->format('Y-m-d H:i:s'),$to_date->format('Y-m-d H:i:s'),$rooms_data,$booking[0][0]['noOfDays'],$user_name,$address,number_format($bookingprice[0]['totalPrice']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format($bookingprice[0]['reservationCharge']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format(($bookingprice[0]['totalPrice']+$bookingprice[0]['reservationCharge'])*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],"venacuba-".$bookingid*1024,number_format($bookingprice[0]['reservationTotalPrice']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format((($bookingprice[0]['totalPrice']+$bookingprice[0]['reservationCharge'])-$bookingprice[0]['reservationTotalPrice'])*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],$bookingcancel[0]['refundAmount'].' '.$bookingcancel[0]['refundCurrency']),$emailcontent->getSubject());
														
							$this->mailsend($emaillist->getFromname(),$emaillist->getFromemail(),$hostals[0]['ownerEmail'],$subject,$message,'',0,'','email');
						
						
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
						$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Your booking was cancelled successfully'));
						return $this->redirect($this->generateUrl('mytrip_user_bookinghistory'));
						
						
						break;
				case "R" :
				case "D" :
				case "C" :
				case "S" :
				default  :	# -- If using local database, update order as declined/failed --
						$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Sorry, Transaction has some problem. Please try again later or contact admin'));
						return $this->redirect($this->generateUrl('mytrip_user_bookinghistory'));
			}
		}
		/*} else {
			$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Sorry, Transaction has some problem. Please try again later or contact admin'));
			return $this->redirect($this->generateUrl('mytrip_user_bookinghistory'));
		}*/
		
		
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