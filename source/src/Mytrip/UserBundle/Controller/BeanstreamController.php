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

class BeanstreamController extends Controller
{
    
    public function indexAction(Request $request)
    {
            
        $session = $request->getSession();		
		$lan=$session->get('language');			
		
        if ($session->get('bookingId')) {
			$bookingid=$session->get('bookingId');
			
			$em = $this->container->get('doctrine')->getManager();	
			$booking=$em->createQuery("SELECT d,IDENTITY(d.hostal) AS hostal,IDENTITY(d.room) AS room FROM MytripAdminBundle:Booking d WHERE d.bookingId=".$bookingid)->getArrayResult();	
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
				$exmonth =  urlencode($request->request->get('exmonth'));
				$exyear =  urlencode($request->request->get('exyear'));
				$cvv =  urlencode($request->request->get('cvv'));
				
				$name =  urlencode($bookinginfo[0]['firstname']." ".$bookinginfo[0]['lastname']);
				$email =  urlencode($bookinginfo[0]['email']);
				$phone =  urlencode($bookinginfo[0]['mobile']);
				$orderno= urlencode("venacuba-".$booking[0][0]['bookingId']*1024);
				$amount =  urlencode($bookingprice[0]['reservationTotalPrice']*$bookingprice[0]['conversionRate']);
				/*$address =  urlencode($bookinginfo[0]['address']);
				$address1 =  urlencode($bookinginfo[0]['address1']);
				$city =  urlencode($bookinginfo[0]['city']);
				$province =  (($bookinginfo[0]['province']!='' || $bookinginfo[0]['province'] > '0')?urlencode(str_replace($country[0]['isocountry'],'',$province[0]['code'])):'');
				$postalcode =  urlencode($bookinginfo[0]['zip']);
				$country=  (($bookinginfo[0]['country']!='' || $bookinginfo[0]['country'] > '0')?urlencode($country[0]['isocountry']):'');*/
				$address=$address1=$city=$province=$country='';
            
				$nvpStr =  "&trnCardOwner=".$ownername."&trnCardNumber=".$cardnumber."&trnExpMonth=".$exmonth."&trnExpYear=".$exyear."&trnCardCvd=".$cvv."&trnOrderNumber=".$orderno."&trnAmount=".$amount."&ordEmailAddress=".$email."&ordName=".$name;		
				//."&ordPhoneNumber=".$phone."&ordAddress1=".$address."&ordAddress2=".$address1."&ordCity=".$city."&ordProvince=".$province."&ordPostalCode=".$postalcode."&ordCountry=".$country
				
				$resArray=$this->hash_call('Payment',$nvpStr,$currency);
				
				//print_r($resArray);exit;
				
				if(!empty($resArray['trnApproved']) && $resArray['trnApproved']=="1"){					
					$booking_transaction = new \Mytrip\AdminBundle\Entity\BookingTransaction();
					$booking_transaction->setBooking($this->getDoctrine()->getRepository('MytripAdminBundle:Booking')->find($bookingid));						
					$booking_transaction->setPaymentType('Beanstream');			
					$booking_transaction->setTransactionId($resArray['trnId']);
					$booking_transaction->setTransactionDate(date('Y-m-d H:i:s'));
					$booking_transaction->setTransactionAmount($amount);
					$booking_transaction->setTransactionCurrency($bookingprice[0]['conversionCurrency']);	
					if(empty($btransaction)){			
						$em->persist($booking_transaction);
					}
					$em->flush();
					
					$em->createQuery("UPDATE MytripAdminBundle:Booking p SET p.status='Confirmed' WHERE p.bookingId='".$bookingid."'")->execute();
					
					$booking=$em->createQuery("SELECT d,IDENTITY(d.hostal) AS hostal,IDENTITY(d.room) AS room FROM MytripAdminBundle:Booking d WHERE d.bookingId=".$bookingid)->getArrayResult();				
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
						$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Rooms booked successfully. Booking details send to your mail id and SMS.'));
					}else{
						$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Rooms booked successfully. Booking details send to your mail id.'));
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
					$address=$busers[0]['address']." ".$busers[0]['city'].', '.($busers[0]['province']!=''?$province[0]['state'].', ':'').($busers[0]['country']!=''?$country[0]['country']:'');
					
					$emaillist=$em->getRepository('MytripAdminBundle:EmailList')->findOneBy(array('emailListId'=>'10'));							
					$emailcontent=$em->getRepository('MytripAdminBundle:EmailContent')->findOneBy(array('emailList'=>'10','lan'=>$lan));
					if(!empty($emailcontent)){
						$emailcontent=$em->getRepository('MytripAdminBundle:EmailContent')->findOneBy(array('emailList'=>'10','lan'=>'en'));
					}
					
					$from_date=$booking[0][0]['fromDate'];
					$to_date=$booking[0][0]['toDate'];	
					
					if($hostals[0]['ownerEmail']!=''){
						$message=str_replace(array('{owner_name}','{hostal_name}','{check_in}','{check_out}','{room_type}','{rooms}','{nights}','{room_details}','{username}','{address}','{room_price}','{accommodation_cost}','{reservation_charge}','{total_cost}','{ref_no}','{paid_amount}','{balance_amount}'),array($hostal_content[0]['ownerName'],$hostal_content[0]['name'],$from_date->format('Y-m-d H:i:s'),$to_date->format('Y-m-d H:i:s'),$hostal_room[0]['roomtype'],$booking[0][0]['noOfRooms'],$booking[0][0]['noOfDays'],'Guests:'.$hostal_room[0]['guests'].',Adults:'.$hostal_room[0]['adults'].',Child:'.$hostal_room[0]['child'],$user_name,$address,$hostal_room[0]['price'].' CAD',number_format($bookingprice[0]['totalPrice']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format($bookingprice[0]['reservationCharge']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format(($bookingprice[0]['totalPrice']+$bookingprice[0]['reservationCharge'])*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],"venacuba-".$bookingid*1024,number_format($bookingprice[0]['reservationTotalPrice']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format((($bookingprice[0]['totalPrice']+$bookingprice[0]['reservationCharge'])-$bookingprice[0]['reservationTotalPrice'])*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency']),$emailcontent->getEmailContent());				
						$subject=str_replace(array('{owner_name}','{hostal_name}','{check_in}','{check_out}','{room_type}','{rooms}','{nights}','{room_details}','{username}','{address}','{room_price}','{accommodation_cost}','{reservation_charge}','{total_cost}','{ref_no}','{paid_amount}','{balance_amount}'),array($hostal_content[0]['ownerName'],$hostal_content[0]['name'],$from_date->format('Y-m-d H:i:s'),$to_date->format('Y-m-d H:i:s'),$hostal_room[0]['roomtype'],$booking[0][0]['noOfRooms'],$booking[0][0]['noOfDays'],'Guests:'.$hostal_room[0]['guests'].',Adults:'.$hostal_room[0]['adults'].',Child:'.$hostal_room[0]['child'],$user_name,$address,$hostal_room[0]['price'].' CAD',number_format($bookingprice[0]['totalPrice']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format($bookingprice[0]['reservationCharge']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format(($bookingprice[0]['totalPrice']+$bookingprice[0]['reservationCharge'])*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],"venacuba-".$bookingid*1024,number_format($bookingprice[0]['reservationTotalPrice']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format((($bookingprice[0]['totalPrice']+$bookingprice[0]['reservationCharge'])-$bookingprice[0]['reservationTotalPrice'])*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency']),$emailcontent->getSubject());
													
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
					if(!empty($emailcontent)){
						$emailcontent=$em->getRepository('MytripAdminBundle:EmailContent')->findOneBy(array('emailList'=>'11','lan'=>'en'));
					}	
					$admin=$em->createQuery("SELECT p FROM MytripAdminBundle:Admin p WHERE p.adminId='1'")->getArrayResult();	
					
					$message=str_replace(array('{admin_name}','{owner_name}','{hostal_name}','{check_in}','{check_out}','{room_type}','{rooms}','{nights}','{room_details}','{username}','{address}','{room_price}','{accommodation_cost}','{reservation_charge}','{total_cost}','{ref_no}','{paid_amount}','{balance_amount}'),array($admin[0]['name'],$hostal_content[0]['ownerName'],$hostal_content[0]['name'],$from_date->format('Y-m-d H:i:s'),$to_date->format('Y-m-d H:i:s'),$hostal_room[0]['roomtype'],$booking[0][0]['noOfRooms'],$booking[0][0]['noOfDays'],'Guests:'.$hostal_room[0]['guests'].',Adults:'.$hostal_room[0]['adults'].',Child:'.$hostal_room[0]['child'],$user_name,$address,$hostal_room[0]['price'].' CAD',number_format($bookingprice[0]['totalPrice']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format($bookingprice[0]['reservationCharge']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format(($bookingprice[0]['totalPrice']+$bookingprice[0]['reservationCharge'])*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],"venacuba-".$bookingid*1024,number_format($bookingprice[0]['reservationTotalPrice']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format((($bookingprice[0]['totalPrice']+$bookingprice[0]['reservationCharge'])-$bookingprice[0]['reservationTotalPrice'])*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency']),$emailcontent->getEmailContent());
									
					$subject=str_replace(array('{admin_name}','{owner_name}','{hostal_name}','{check_in}','{check_out}','{room_type}','{rooms}','{nights}','{room_details}','{username}','{address}','{room_price}','{accommodation_cost}','{reservation_charge}','{total_cost}','{ref_no}','{paid_amount}','{balance_amount}'),array($admin[0]['name'],$hostal_content[0]['ownerName'],$hostal_content[0]['name'],$from_date->format('Y-m-d H:i:s'),$to_date->format('Y-m-d H:i:s'),$hostal_room[0]['roomtype'],$booking[0][0]['noOfRooms'],$booking[0][0]['noOfDays'],'Guests:'.$hostal_room[0]['guests'].',Adults:'.$hostal_room[0]['adults'].',Child:'.$hostal_room[0]['child'],$user_name,$address,$hostal_room[0]['price'].' CAD',number_format($bookingprice[0]['totalPrice']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format($bookingprice[0]['reservationCharge']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format(($bookingprice[0]['totalPrice']+$bookingprice[0]['reservationCharge'])*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],"venacuba-".$bookingid*1024,number_format($bookingprice[0]['reservationTotalPrice']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format((($bookingprice[0]['totalPrice']+$bookingprice[0]['reservationCharge'])-$bookingprice[0]['reservationTotalPrice'])*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency']),$emailcontent->getSubject());
													
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
				}else{
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
	
	public function hash_call($methodName,$nvpStr,$currency){  
	
		$merchant_id  = $this->container->get('mytrip_admin.helper.beanstream')->getOption('beanstream_merchant_id_'.$currency); 
		$username  = $this->container->get('mytrip_admin.helper.beanstream')->getOption('beanstream_username_'.$currency);	
		$password  = $this->container->get('mytrip_admin.helper.beanstream')->getOption('beanstream_password_'.$currency);
		
        //setting the curl parameters.
        $ch = curl_init();
		curl_setopt( $ch, CURLOPT_POST, 1 );
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		// Instruct curl to suppress the output from the system , and to directly
		// return the transfer instead.(Output will be stored in $txResult.)
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		// This is the location of the system
		curl_setopt( $ch, CURLOPT_URL, "https://www.beanstream.com/scripts/process_transaction.asp" );
    
	    
        //NVPRequest for submitting to server
        $nvpreq="requestType=BACKEND&merchant_id=".urlencode($merchant_id)."&username=".urlencode($username)."&password=".urlencode($password).$nvpStr; 
		
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
	
	 private function APIError($errorNo,$errorMsg,$resArray){
        $resArray['Error']['Number']=$errorNo;
        $resArray['Error']['Number']=$errorMsg;
        return $resArray;
    }
    
    /** This function will take NVPString and convert it to an Associative Array and it will decode the response.
      * It is usefull to search for a particular key and displaying arrays.
      * @nvpstr is NVPString.
      * @nvpArray is Associative Array.
      */
    
    private function deformatNVP($nvpstr){
    
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
	
	 /** bean stream Refund function
	  * This function will take NVPString and convert it to an Associative Array and it will decode the response.
      * It is usefull to search for a particular key and displaying arrays.
      * @nvpstr is NVPString.
      * @nvpArray is Associative Array.
      */
	  
	 public function BeanstreamRefund($paymentInfo=array()) {	
		$amount=urlencode($paymentInfo['amount']);		
		$transactionID=urlencode($paymentInfo['transactionID']);
		$orderno=urlencode($paymentInfo['orderno']);			
        $currencyCode=strtolower($paymentInfo['currency']);			
		$nvpStr = "&trnType=R&trnOrderNumber=$orderno&adjId=$transactionID&trnAmount=$amount";		
		$resArray=$this->hash_call('ReturnType',$nvpStr,$currencyCode);
		return $resArray;
		
	}
	
	public function beanstreamrefundAction(Request $request){
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
		$paymentArray['transactionID']=$bookingtransaction[0]['transactionId'];
		$paymentArray['orderno']="venacuba-".$refund['refund_booking_id']*1024;
		$paymentArray['currency']=$bookingtransaction[0]['transactionCurrency'];
		$result=$this->BeanstreamRefund($paymentArray);		
		if($result['trnApproved']=="1"){			
			$cancel = new \Mytrip\AdminBundle\Entity\BookingCancel();
			$cancel->setBooking($this->getDoctrine()->getRepository('MytripAdminBundle:Booking')->find($refund['refund_booking_id']));
			$cancel->setCancelPercentage($refund['cancel_percentage']);
			$cancel->setCancelDate(new \DateTime(date('Y-m-d')));
			$cancel->setRefundReferenceno($result['trnId']);
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
			
			$login = $this->container->get('mytrip_admin.helper.sms')->getOption('smsusername');
			$password = $this->container->get('mytrip_admin.helper.sms')->getOption('smspassword');
			$prefix = $booking_info[0]['cmcode'];	
			$number = $booking_info[0]['mobile'];
			$msg = urlencode($this->get('translator')->trans('Dear Customer, Your booking cancelled successfully for the reference no is').' '.("venacuba-".$bookingid*1024).$this->get('translator')->trans('Refund amount is').' '.$refund['cancelamount'].$bookingtransaction[0]['transactionCurrency'].' '.$this->get('translator')->trans('Refund transaction id is').' '.$result['trnId']);			
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
			$address=$busers[0]['address']." , ".$busers[0]['city'].', '.($busers[0]['province']!=''?$province[0]['state'].', ':'').($busers[0]['country']!=''?$country[0]['country']:'');
			
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