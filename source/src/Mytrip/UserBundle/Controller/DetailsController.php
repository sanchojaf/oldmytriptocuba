<?php
namespace Mytrip\UserBundle\Controller;

use Payum\Bundle\PayumBundle\Controller\PayumController;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\BinaryMaskStatusRequest;
use Payum\Core\Request\SyncRequest;
use Symfony\Component\HttpFoundation\Request;

class DetailsController extends PayumController
{
    public function viewAction(Request $request)
    {
        $token = $this->getHttpRequestVerifier()->verify($request);
        
        $payment = $this->getPayum()->getPayment($token->getPaymentName());
		
		$session = $request->getSession();
		$lan=$session->get('language');		
		$bookingid=$session->get('bookingId');
		
        try {
            $payment->execute(new SyncRequest($token));
        } catch (RequestNotSupportedException $e) {}        
        	
		$status = new BinaryMaskStatusRequest($token);
        $payment->execute($status);
		$model=$status->getModel();		
		$em = $this->container->get('doctrine')->getManager();
		
		$session = $request->getSession();		
		$lan=$session->get('language');	
		$bookingid=$session->get('bookingId'); 
		
		if(!empty($model['PAYMENTINFO_0_ACK']) && strtolower($model['PAYMENTINFO_0_ACK'])=="success"){			
			$btransaction=$em->createQuery("SELECT d FROM MytripAdminBundle:BookingTransaction d WHERE d.booking=".$bookingid)->getArrayResult();
			if(empty($btransaction)){
				$booking_transaction = new \Mytrip\AdminBundle\Entity\BookingTransaction();
				$booking_transaction->setBooking($this->getDoctrine()->getRepository('MytripAdminBundle:Booking')->find($bookingid));	
				
			}else{
				$repository_content = $em->getRepository('MytripAdminBundle:BookingTransaction');		 
				$booking_transaction=$repository_content->findOneByBookingTransactionId($btransaction['0']['bookingTransactionId']);
			}
			$booking_transaction->setPaymentType('Paypal');			
			$booking_transaction->setTransactionId($model['PAYMENTINFO_0_TRANSACTIONID']);
			$booking_transaction->setTransactionDate(date('Y-m-d H:i:s'));
			$booking_transaction->setTransactionAmount($model['PAYMENTINFO_0_AMT']);
			$booking_transaction->setTransactionCurrency($model['PAYMENTINFO_0_CURRENCYCODE']);	
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
			$msg = urlencode($this->get('translator')->trans('Dear Customer, You have successfully booked the hotel rooms in our site. Your reference no is').' '."venacuba-".$bookingid*1024);			
			$URL="http://api.smsacuba.com/api10allcountries.php?";
			$URL.="login=".$login."&password=".$password."&prefix=".$prefix."&number=".$number."&sender=Mytriptocuba"."&msg=".$msg;						
			$r=@file($URL);
			$succmsg = $r[0];
			if($succmsg=="SMS ENVIADO"){
				$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Rooms booking successfully. Booking details send to your mail id and SMS.'));
			}else{
				$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Rooms booking successfully. Booking details send to your mail id.'));
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
			$booking_transaction=$em->createQuery("SELECT d FROM MytripAdminBundle:BookingTransaction d WHERE d.booking='".$bookingid."'")->getArrayResult();

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
											
				$this->mailsend($emaillist->getFromname(),$emaillist->getFromemail(),$hostals[0]['ownerEmail'],$subject,$message,'','0','','email');
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
											
				$this->mailsend($emaillist->getFromname(),$emaillist->getFromemail(),$admin[0]['email'],$subject,$message,'','0','','email');
			
			
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
			
			//return $this->redirect($this->generateUrl('mytrip_user_homepage'));	
			return $this->redirect($this->generateUrl('mytrip_user_bookinghistory'));		
		}else{
			$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Sorry, Payment failed try once again'));
			return $this->redirect($this->generateUrl('mytrip_user_makepayment',array('bookingId'=>$bookingid*1024)));
		}
		
    }
	
	
	public function stripeAction(Request $request)
    {
        $token = $this->getHttpRequestVerifier()->verify($request);
        
        $payment = $this->getPayum()->getPayment($token->getPaymentName());

        try {
            $payment->execute(new SyncRequest($token));
        } catch (RequestNotSupportedException $e) {}        
        	
		$status = new BinaryMaskStatusRequest($token);
        $payment->execute($status);
		$model=$status->getModel();		
		$suc_status=$status->isSuccess();		
		$session = $request->getSession();
		$bookingid=$session->get('bookingId');
		$em = $this->container->get('doctrine')->getManager();
		if($suc_status=="1"){
			$btransaction=$em->createQuery("SELECT d FROM MytripAdminBundle:BookingTransaction d WHERE d.booking=".$bookingid)->getArrayResult();
			if(empty($btransaction)){
				$booking_transaction = new \Mytrip\AdminBundle\Entity\BookingTransaction();
				$booking_transaction->setBooking($this->getDoctrine()->getRepository('MytripAdminBundle:Booking')->find($bookingid));	
				
			}else{
				$repository_content = $em->getRepository('MytripAdminBundle:BookingTransaction');		 
				$booking_transaction=$repository_content->findOneByBookingTransactionId($btransaction['0']['bookingTransactionId']);
			}
			$booking_transaction->setPaymentType('Stripe');			
			$booking_transaction->setTransactionId($model['id']);
			$booking_transaction->setTransactionDate(date('Y-m-d H:i:s'));
			$booking_transaction->setTransactionAmount(number_format($model['amount']/100),2);
			$booking_transaction->setTransactionCurrency(strtoupper($model['currency']));	
			if(empty($btransaction)){			
				$em->persist($booking_transaction);
			}
			$em->flush();
			
			$em->createQuery("UPDATE MytripAdminBundle:Booking p SET p.status='Confirmed' WHERE p.bookingId='".$bookingid."'")->execute();
			
			$booking=$em->createQuery("SELECT d,IDENTITY(d.hostal) AS hostal,IDENTITY(d.room) AS room FROM MytripAdminBundle:Booking d WHERE d.bookingId=".$bookingid)->getArrayResult();				
			$booking_info=$em->createQuery("SELECT d FROM MytripAdminBundle:BookingInfo d WHERE d.booking=".$bookingid)->getArrayResult();
						
			/*******Contact mail send to admin***********/								
			$this->mailsend("Mytrip Cuba","info@mytriptocuba.com",$booking_info[0]['email'],$this->get('translator')->trans('Booking Details'),'','');
			$session->remove('payment');
			$session->remove('booking');
			
			$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Rooms booking successfully. Booking details send to your mail id.'));
			return $this->redirect($this->generateUrl('mytrip_user_homepage'));			
		}
		
    }
	
	public function skrillAction(Request $request)
    {
        if(isset($_POST['status']) && $_POST['status']=="2"){		
		$session = $request->getSession();
		$bookingid=$session->get('bookingId');
		$em = $this->container->get('doctrine')->getManager();		
			$btransaction=$em->createQuery("SELECT d FROM MytripAdminBundle:BookingTransaction d WHERE d.booking=".$bookingid)->getArrayResult();
			if(empty($btransaction)){
				$booking_transaction = new \Mytrip\AdminBundle\Entity\BookingTransaction();
				$booking_transaction->setBooking($this->getDoctrine()->getRepository('MytripAdminBundle:Booking')->find($bookingid));	
				
			}else{
				$repository_content = $em->getRepository('MytripAdminBundle:BookingTransaction');		 
				$booking_transaction=$repository_content->findOneByBookingTransactionId($btransaction['0']['bookingTransactionId']);
			}
			if(isset($_POST['mb_transaction_id'])){
				$transactionid=$_POST['mb_transaction_id'];
			}else{
				$transactionid=$_POST['transaction_id'];
			}
			$booking_transaction->setPaymentType('Skrill');			
			$booking_transaction->setTransactionId($transactionid);
			$booking_transaction->setTransactionDate(date('Y-m-d H:i:s'));
			$booking_transaction->setTransactionAmount($_POST['amount']);
			$booking_transaction->setTransactionCurrency(strtoupper($_POST['mb_currency']));	
			if(empty($btransaction)){			
				$em->persist($booking_transaction);
			}
			$em->flush();
			
			$em->createQuery("UPDATE MytripAdminBundle:Booking p SET p.status='Confirmed' WHERE p.bookingId='".$bookingid."'")->execute();
			
			$booking=$em->createQuery("SELECT d,IDENTITY(d.hostal) AS hostal,IDENTITY(d.room) AS room FROM MytripAdminBundle:Booking d WHERE d.bookingId=".$bookingid)->getArrayResult();				
			$booking_info=$em->createQuery("SELECT d FROM MytripAdminBundle:BookingInfo d WHERE d.booking=".$bookingid)->getArrayResult();
						
			/*******Contact mail send to admin***********/								
			$this->mailsend("Mytrip Cuba","info@mytriptocuba.com",$booking_info[0]['email'],$this->get('translator')->trans('Booking Details'),'','');
			$session->remove('payment');
			$session->remove('booking');
			
			$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Rooms booking successfully. Booking details send to your mail id.'));
			return $this->redirect($this->generateUrl('mytrip_user_homepage'));			
		}else{
			$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Sorry, Payment failed try once again'));
			return $this->redirect($this->generateUrl('mytrip_user_homepage'));
		}
		
    }
	
	/*********Mail sending function**************/
	private function mailsend($fromname,$from,$to,$subject,$content,$cc = NULL,$attachment=0,$attachmentsfiles='',$template='ticket'){
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