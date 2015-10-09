<?php

namespace Mytrip\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Mytrip\AdminBundle\Entity\Admin;
use Mytrip\AdminBundle\Entity\Staticpage;
use Mytrip\AdminBundle\Entity\StaticpageContent;
use Mytrip\AdminBundle\Entity\Contact;
use Mytrip\AdminBundle\Entity\ApiGateway;
use Mytrip\AdminBundle\Entity\ApiInfo;
use Mytrip\AdminBundle\Entity\Feature;
use Mytrip\AdminBundle\Entity\FeatureContent;
use Mytrip\AdminBundle\Entity\SocialLink;
use Mytrip\AdminBundle\Entity\EmailList;
use Mytrip\AdminBundle\Entity\EmailContent;
use Mytrip\AdminBundle\Entity\Destination;
use Mytrip\AdminBundle\Entity\DestinationContent;
use Mytrip\AdminBundle\Entity\DestinationFeature;
use Mytrip\AdminBundle\Entity\DestinationImage;
use Mytrip\AdminBundle\Entity\Hostal;
use Mytrip\AdminBundle\Entity\HostalContent;
use Mytrip\AdminBundle\Entity\HostalFeature;
use Mytrip\AdminBundle\Entity\HostalImage;
use Mytrip\AdminBundle\Entity\HostalRooms;
use Mytrip\AdminBundle\Entity\Story;
use Mytrip\AdminBundle\Entity\StoryContent;
use Mytrip\AdminBundle\Entity\StoryImage;
use Mytrip\AdminBundle\Entity\States;
use Mytrip\AdminBundle\Entity\Country;
use Mytrip\AdminBundle\Entity\City;
use Mytrip\AdminBundle\Entity\Banner;
use Mytrip\AdminBundle\Entity\Visits;
use Mytrip\AdminBundle\Entity\CancelDefault;
use Mytrip\AdminBundle\Entity\HostalCancelDetails;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Mytrip\AdminBundle\Helper\Date;
use Mytrip\AdminBundle\Helper\Amazon;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Finder\Finder;

class DefaultController extends Controller {
    
	/*******Login**********/
	public function indexAction(Request $request){
		$session=$request->getSession();		
		$username=$session->get('username');
		if(!empty($username)){						
			return $this->redirect($this->generateUrl('mytrip_admin_dashboard'));
		}
		
        $admin = new Admin();
		$result=""; 
		
		if($request->getMethod()=="POST"){
			$form=$request->get('form');			
			$em = $this->container->get('doctrine')->getManager();
			$repository = $em->getRepository('MytripAdminBundle:Admin');
			
			/*******Forgot Password***********/
			if(!empty($form['email'])){				
				$check=$repository->findOneBy(array('email'=>$form['email'],'status'=>'Active'));										
				if(!empty($check)){
					$pass=$this->str_rand();
					$password=sha1($pass);
					$emaillist=$em->getRepository('MytripAdminBundle:EmailList')->findOneBy(array('emailListId'=>'1'));							
					$emailcontent=$em->getRepository('MytripAdminBundle:EmailContent')->findOneBy(array('emailList'=>'1','lan'=>'en'));					
					$link=$this->getRequest()->getSchemeAndHttpHost()."/".$this->container->get('router')->getContext()->getBaseUrl()."/".$this->generateUrl('mytrip_admin_homepage');
					$message=str_replace(array('{name}','{username}','{password}','{link}'),array($check->getName(),$check->getUsername(),$pass,$link),$emailcontent->getEmailContent());
					
					/*******Forgot Password mail send admin***********/												
					$this->mailsend($emaillist->getFromname(),$emaillist->getFromemail(),$check->getEmail(),$emailcontent->getSubject(),$message,$emaillist->getCcmail());
					$check->setPassword($password);
					$check->setModifyDate(new \DateTime(date('Y-m-d H:i:s')));
					$em->flush();	
					
					$this->get('session')->getFlashBag()->add('error','<div class="success msg">Your password details sent to your email address</div>');
					return $this->redirect($this->generateUrl('mytrip_admin_homepage'));
				}else{
					$this->get('session')->getFlashBag()->add('error','<div class="error msg">Invalid email address</div>');				
					$result="forgot";
				}
			}else{
				/*******Login***********/
				$check=$repository->findOneBy(array('username'=>$form['username'],'status'=>'Active'));				
				if(!empty($check)){					
					if($check->getPassword()==sha1($form['password'])){									
						 $session = $request->getSession();
						 $session->set('username', $check->getUsername());
						 $session->set('adminlogin', "True");
						 $session->set('adminid', $check->getAdminId());						 
						return $this->redirect($this->generateUrl('mytrip_admin_dashboard'));
					}
				}else{
					$this->get('session')->getFlashBag()->add('error','<div class="error msg">Sorry, Username and password mismatch</div>');				
					return $this->redirect($this->generateUrl('mytrip_admin_homepage'));
				}
			}
		}
		
		/*******Login Form***********/
        $login = $this->createFormBuilder($admin,array('attr'=>array('id'=>'myForm')))
            ->add('username', 'text',array('label'=>'Username','label_attr'=>array('class'=>''),'attr'=>array('class'=>'validate[required]'),'required'=>false))
            ->add('password', 'password',array('label'=>'Password','label_attr'=>array('class'=>''),'attr'=>array('class'=>'validate[required]'),'required'=>false))           
			->add('save', 'submit', array('attr' => array('class' => 'button gray'),'label' => "Login"))
            ->getForm();
					
		/*******Forgot Password Form***********/	
		$forgot =$this->createFormBuilder($admin,array('attr'=>array('id'=>'myForms')))
            ->add('email', 'text',array('label'=>'Email','label_attr'=>array('class'=>''),'attr'=>array('class'=>'validate[required,custom[email]]'),'required'=>false))
			->add('save', 'submit', array('attr' => array('class' => 'button gray','name'=>'forgot'),'label' => "Forgot Password"))
            ->getForm();

        return $this->render('MytripAdminBundle:Default:index.html.php', array(
            'login' => $login->createView(),'forgot' => $forgot->createView(),'result'=>$result
        ));
		
    }
	
	/********Admin Dashboard************/
	public function dashboardAction(Request $request){
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}	
		$em =  $this->getDoctrine()->getManager();
		$confirmbooking=$em->createQuery("SELECT COUNT(b) AS counts, MONTH(b.createdDate) AS months FROM MytripAdminBundle:Booking b WHERE b.status = 'Confirmed' GROUP BY months ORDER BY b.bookingId DESC ")->getArrayResult();
		$cancelbooking=$em->createQuery("SELECT COUNT(b) AS counts, MONTH(b.createdDate) AS months FROM MytripAdminBundle:Booking b WHERE b.status = 'Cancelled' GROUP BY months ORDER BY b.bookingId DESC ")->getArrayResult();
		//print_r($this->getHelper('date')->format('2014-07-20'));
		return $this->render('MytripAdminBundle:Default:dashboard.html.php');
	}
	
	/*******Admin Profile Edit***********/
	public function profileAction(Request $request){
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		 $admin = new Admin();
		 
		 $session = $request->getSession();
		 $id=$session->get('adminid');
		 
		 $em =  $this->getDoctrine()->getManager();
		 $repository = $em->getRepository('MytripAdminBundle:Admin');
		 
		 $check=$repository->findOneByAdminId($id);
		 
		 if($request->getMethod()=="POST"){
			$form=$request->get('form');
			/*******Duplicate Username Checking***********/		
			$query = $em->createQuery("SELECT p FROM MytripAdminBundle:Admin p WHERE p.adminId NOT IN ( ".$id.") AND p.username = '".$form['username']."' AND p.status = 'Active'");			
			$checkusername = $query->getResult();			
			if(empty($checkusername)){
				/*******Duplicate Email id checking***********/
				$emailquery = $em->createQuery("SELECT p FROM MytripAdminBundle:Admin p WHERE p.adminId NOT IN ( ".$id.") AND p.email = '".$form['email']."' AND p.status = 'Active'");			
				$checkemail = $emailquery->getResult();				
				if(empty($checkemail)){					
					$check->setName($form['name']);
					$check->setUsername($form['username']);
					$check->setEmail($form['email']);
					$check->setCmcode($form['cmcode']);
					$check->setMobile($form['mobile']);
					$check->setModifyDate(new \DateTime(date('Y-m-d H:i:s')));
					$em->flush();
					$this->get('session')->getFlashBag()->add('error','<div class="success msg">Your profile has been successfully updated</div>');
					return $this->redirect($this->generateUrl('mytrip_admin_profile'));
				}else{
					$this->get('session')->getFlashBag()->add('error','<div class="error msg">Email id already exists</div>');
					return $this->redirect($this->generateUrl('mytrip_admin_profile'));
				}
			}else{
				$this->get('session')->getFlashBag()->add('error','<div class="error msg">Username already exists</div>');
				return $this->redirect($this->generateUrl('mytrip_admin_profile'));
			}
			
		 }	 
		 
		 /*******Edit Profile Form***********/
		 $profile = $this->createFormBuilder($admin,array('attr'=>array('id'=>'myForm')))
            ->add('username', 'text',array('label'=>'Username','attr'=>array('class'=>'validate[required,minSize[5]]','value'=>$check->getUsername(),'size'=>'50'),'required'=>false))
            ->add('name', 'text',array('label'=>'Name','attr'=>array('class'=>'validate[required]','value'=>$check->getName(),'size'=>'50'),'required'=>false)) 
			->add('email', 'text',array('label'=>'Email','attr'=>array('class'=>'validate[required,custom[email]]','value'=>$check->getEmail(),'size'=>'50'),'required'=>false)) 			 
			->add('cmcode', 'text',array('label'=>'Cmcode','attr'=>array('class'=>'validate[required,custom[integer]]','value'=>$check->getCmcode(),'size'=>'5'),'required'=>false))			 
			->add('mobile', 'text',array('label'=>'Mobile','attr'=>array('class'=>'validate[required,custom[integer]]','value'=>$check->getMobile(),'size'=>'39'),'required'=>false))           
			->add('save', 'submit', array('attr' => array('class' => 'button gray'),'label' => "Save"))
            ->getForm();
			
		 return $this->render('MytripAdminBundle:Default:profile.html.php', array(
            'profile' => $profile->createView()
        ));
	}
	
	/*******Change Password Form***********/
	public function changepasswordAction(Request $request){
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		 $admin = new Admin();
		 
		 $session = $request->getSession();
		 $id=$session->get('adminid');
		 
		 $em =  $this->getDoctrine()->getManager();
		 $repository = $em->getRepository('MytripAdminBundle:Admin');
		 
		 $check=$repository->findOneByAdminId($id);
		 
		 if($request->getMethod()=="POST"){	
		 	/*******Checking Old password***********/		
			if($check->getPassword()==sha1($request->get('oldpassword'))){	
				/*******checking new and confirm password***********/	
				if($request->get('newpassword')==$request->get('confirmpassword')){											
					$check->setPassword(sha1($request->get('newpassword'))); 					
					$check->setModifyDate(new \DateTime(date('Y-m-d H:i:s')));
					$em->flush();
					$this->get('session')->getFlashBag()->add('error','<div class="success msg">Your password has been successfully updated</div>');
					return $this->redirect($this->generateUrl('mytrip_admin_changepassword'));
				}else{
					$this->get('session')->getFlashBag()->add('error','<div class="error msg">Password and Confirm password mismatch</div>');
					return $this->redirect($this->generateUrl('mytrip_admin_changepassword'));
				}
			}else{
				$this->get('session')->getFlashBag()->add('error','<div class="error msg">Old password incorrect</div>');
				return $this->redirect($this->generateUrl('mytrip_admin_changepassword'));
			}			
		 }			 
		 return $this->render('MytripAdminBundle:Default:changepassword.html.php',array());
	}
	
	/*******Booking settings***********/
	public function settingsAction(Request $request){
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		
		 $session = $request->getSession();
		 $id=$session->get('adminid');
		 
		 $em =  $this->getDoctrine()->getManager();
		 $repository = $em->getRepository('MytripAdminBundle:Settings');
		 
		 $settings=$em->createQuery("SELECT p FROM MytripAdminBundle:Settings p WHERE p.settingId=1")->getArrayResult();;
		 	
		 $check=$repository->findOneBySettingId(1);
		 
		 if($request->getMethod()=="POST"){			 												
			$check->setReservationCharge($request->get('reservationcharge')); 
			$check->setBookingPercentage($request->get('bookingpercentage')); 	
			$check->setBookingConfirmationDays($request->get('bookingconfirmationdays'));
			$em->flush();
			$this->get('session')->getFlashBag()->add('error','<div class="success msg">Booking settings has been successfully updated</div>');
			return $this->redirect($this->generateUrl('mytrip_admin_settings'));						
		 }	
		  
		 return $this->render('MytripAdminBundle:Default:settings.html.php',array('settings'=>$settings));
	}
	
	/*******Checking Admin session***********/
	private function checkAdmin($session){
		$username=$session->get('username');
		if(empty($username)){			
			return $this->redirect($this->generateUrl('mytrip_admin_homepage'));
		}
	}
	
	/*******Checking superAdmin session***********/
	private function supercheckAdmin($session){
		$id=$session->get('adminid');
		if($id > "1"){			
			return $this->redirect($this->generateUrl('mytrip_admin_homepage'));
		}
	}
	
	/*******Logout***********/
	public function logoutAction(Request $request){
		$session=$request->getSession();
		$session->remove('username');
		$session->remove('adminlogin');
		return $this->redirect($this->generateUrl('mytrip_admin_homepage'));			
	}
	
	/*******Generate Random alphanumeric string***********/
	private function str_rand($length = 8, $output = 'alphanum'){
		// Possible seeds
		$outputs['alpha'] = 'abcdefghijklmnopqrstuvwqyz';
		$outputs['numeric'] = '0123456789';
		$outputs['alphanum'] = 'abcdefghijklmnopqrstuvwqyz0123456789';
		$outputs['hexadec'] = '0123456789abcdef';
		
		// Choose seed
		if (isset($outputs[$output])) {
			$output = $outputs[$output];
		}
		
		// Seed generator
		list($usec, $sec) = explode(' ', microtime());
		$seed = (float) $sec + ((float) $usec * 100000);
		mt_srand($seed);
		
		// Generate
		$str = '';
		$output_count = strlen($output);
		for ($i = 0; $length > $i; $i++) {
			$str .= $output{mt_rand(0, $output_count - 1)};
		}
		
		return $str;
	}
	
	/*******Get the all request***********/
	private function geturlrequest($request){
		if(!empty($request->query)){
			$url=array();
			foreach($request->query as $key=>$value){
				if($key!="page"){
					$url[]=$key."=".$value;
				}
			}
			if(!empty($url)){
				return implode("&",$url);
			}else{
				return "";
			}
		}else{
			return "";
		}
	}
	
	/*******Get the all request for sorting***********/
	private function getsortrequest($request){
		if(!empty($request->query)){
			$url=array();
			foreach($request->query as $key=>$value){
				if($key!="page" && $key!="sort" && $key!='direction'){
					$url[]=$key."=".$value;
				}
			}
			if(!empty($url)){
				return implode("&",$url);
			}else{
				return "";
			}
		}else{
			return "";
		}
	}
	
	/********Get all request in array***********/
	private function getrequestarray($request){
		if(!empty($request->query)){
			$url=array();
			foreach($request->query as $key=>$value){
				if($key!="page"){
					$url[$key]=$value;
				}
			}
			if(!empty($url)){
				return $url;
			}else{
				return "";
			}
		}else{
			return "";
		}
	}
	
	/***Fetch the state corresponding country****/
	public function getstateAction(Request $request){	
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		$em =  $this->getDoctrine()->getManager();		 
		$id=$request->get('sid');
		$query = $em->createQuery("SELECT s FROM MytripAdminBundle:States s  where s.cid=$id" );		
		$state=$query->getArrayResult();				 
		return $this->render('MytripAdminBundle:Default:getstate.html.php',array('state'=>$state));		
	}
	
	/*********Mail sending function**************/
	private function mailsend($fromname,$from,$to,$subject,$content,$cc = NULL,$attachment=0,$attachmentsfiles='',$template='email'){		
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
				    ->setContentType('text/html');;
		
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
	
	/********Find the latitude and longitude from google api*****/
	private function getBoundLatitude_longitude($location){		
		$position = array();
		$search_map = $location;
		if(isset($search_map)){
			$myaddress = urlencode($search_map);			
			$url = "http://maps.googleapis.com/maps/api/geocode/json?address=$myaddress&sensor=false";
			$getmap = file_get_contents($url);
			$googlemap = json_decode($getmap);
	
			foreach($googlemap->results as $res){
				$address = $res->geometry;
				$latlng = $address->location;
				$formattedaddress = $res->formatted_address;
		 
				$position['latitude'] = $latlng->lat ;
			    $position['longitude'] = $latlng->lng;
			    
				//$position['ne_latitude'] = $res->geometry->bounds->northeast->lat;
			    //$position['ne_longitude'] = $res->geometry->bounds->northeast->lng;

				//$position['sw_latitude'] = $res->geometry->bounds->southwest->lat;
			    //$position['sw_longitude'] = $res->geometry->bounds->southwest->lng;
			
			}
		}
		return  $position;
	} 
	
	/*******Change All status**********/
	public function changestatusAction(Request $request){
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
						
		 $em =  $this->getDoctrine()->getManager();
		 /********Admin status change***********/
		 if($request->query->get('page')=="admin"){
			 $repository = $em->getRepository('MytripAdminBundle:Admin');
			 $check=$repository->findOneByAdminId($request->query->get('id'));	
			 if(!empty($check)){
				 if($request->query->get('status')=="active"){
					 $check->setStatus("Active");
				 }elseif($request->query->get('status')=="inactive"){
					  $check->setStatus("Inactive");
				 }
				 $em->flush();
				 $this->get('session')->getFlashBag()->add('error','<div class="success msg">Admin status successfully changed</div>');
				 return $this->redirect($this->generateUrl('mytrip_admin_adminusers'));
			 }else{
				 $this->get('session')->getFlashBag()->add('error','<div class="error msg">Sorry, Admin record not found in our database</div>');
				 return $this->redirect($this->generateUrl('mytrip_admin_adminusers'));
			 }
		 }elseif($request->query->get('page')=="api"){
			 /********API status change***********/
			 $repository = $em->getRepository('MytripAdminBundle:Apigateway');
			 $check=$repository->findOneByApiId($request->query->get('id'));	
			 if(!empty($check)){
				 if($request->query->get('status')=="active"){
					 $check->setStatus("Active");
				 }elseif($request->query->get('status')=="inactive"){
					  $check->setStatus("Inactive");
				 }
				 $em->flush();
				 $this->get('session')->getFlashBag()->add('error','<div class="success msg">Api status successfully changed</div>');
				 return $this->redirect($this->generateUrl('mytrip_admin_api'));
			 }else{
				 $this->get('session')->getFlashBag()->add('error','<div class="error msg">Sorry, Api record not found in our database</div>');
				 return $this->redirect($this->generateUrl('mytrip_admin_api'));
			 }
		 }elseif($request->query->get('page')=="destination"){
			 /********destination status change***********/
			 $repository = $em->getRepository('MytripAdminBundle:Destination');
			 $check=$repository->findOneByDestinationId($request->query->get('id'));	
			 if(!empty($check)){
				 if($request->query->get('status')=="active"){
					 $check->setStatus("Active");
				 }elseif($request->query->get('status')=="inactive"){
					  $check->setStatus("Inactive");
				 }
				 $em->flush();
				 $this->get('session')->getFlashBag()->add('error','<div class="success msg">Destination status successfully changed</div>');
				 return $this->redirect($this->generateUrl('mytrip_admin_destination'));
			 }else{
				 $this->get('session')->getFlashBag()->add('error','<div class="error msg">Sorry, Destination record not found in our database</div>');
				 return $this->redirect($this->generateUrl('mytrip_admin_destination'));
			 }
		 }elseif($request->query->get('page')=="hostal"){
			 /********destination status change***********/
			 $repository = $em->getRepository('MytripAdminBundle:Hostal');
			 $check=$repository->findOneByHostalId($request->query->get('id'));	
			 if(!empty($check)){
				 if($request->query->get('status')=="active"){
					 $check->setStatus("Active");
				 }elseif($request->query->get('status')=="inactive"){
					  $check->setStatus("Inactive");
				 }
				 $em->flush();
				 $this->get('session')->getFlashBag()->add('error','<div class="success msg">Hostal status successfully changed</div>');
				 if($request->server->get('HTTP_REFERER')!=''){		
					return $this->redirect($request->server->get('HTTP_REFERER'));
				 }else{
					return $this->redirect($this->generateUrl('mytrip_admin_hostal'));
				 }
			 }else{
				 $this->get('session')->getFlashBag()->add('error','<div class="error msg">Sorry, Hostal record not found in our database</div>');
				 return $this->redirect($this->generateUrl('mytrip_admin_hostal'));
			 }
		 }elseif($request->query->get('page')=="story"){
			 /********story status change***********/
			 $repository = $em->getRepository('MytripAdminBundle:Story');
			 $check=$repository->findOneByStoryId($request->query->get('id'));	
			 if(!empty($check)){
				 if($request->query->get('status')=="active"){
					 $check->setStatus("Active");
				 }elseif($request->query->get('status')=="inactive"){
					  $check->setStatus("Inactive");
				 }
				 $em->flush();
				 $this->get('session')->getFlashBag()->add('error','<div class="success msg">Story status successfully changed</div>');
				 return $this->redirect($this->generateUrl('mytrip_admin_story'));
			 }else{
				 $this->get('session')->getFlashBag()->add('error','<div class="error msg">Sorry, Story record not found in our database</div>');
				 return $this->redirect($this->generateUrl('mytrip_admin_story'));
			 }
		 }elseif($request->query->get('page')=="comments"){
			 /********destination status change***********/
			 $repository = $em->getRepository('MytripAdminBundle:Review');
			 $check=$repository->findOneByReviewId($request->query->get('id'));	
			 if(!empty($check)){
				 if($request->query->get('status')=="active"){
					 $check->setStatus("Active");
				 }elseif($request->query->get('status')=="inactive"){
					  $check->setStatus("Inactive");
				 }
				 $em->flush();
				 $this->get('session')->getFlashBag()->add('error','<div class="success msg">Review status successfully changed</div>');
				 if($request->server->get('HTTP_REFERER')!=''){		
					return $this->redirect($request->server->get('HTTP_REFERER'));
				 }else{
					return $this->redirect($this->generateUrl('mytrip_admin_comments'));
				 }
			 }else{
				 $this->get('session')->getFlashBag()->add('error','<div class="error msg">Sorry, Review record not found in our database</div>');
				 return $this->redirect($this->generateUrl('mytrip_admin_comments'));
			 }
		 }elseif($request->query->get('page')=="users"){
			 /********destination status change***********/
			 $repository = $em->getRepository('MytripAdminBundle:User');
			 $check=$repository->findOneByUserId($request->query->get('id'));	
			 if(!empty($check)){
				 if($request->query->get('status')=="active"){
					 $check->setStatus("Active");
				 }elseif($request->query->get('status')=="inactive"){
					  $check->setStatus("Inactive");
				 }
				 $em->flush();
				 $this->get('session')->getFlashBag()->add('error','<div class="success msg">User status successfully changed</div>');
				 if($request->server->get('HTTP_REFERER')!=''){		
					return $this->redirect($request->server->get('HTTP_REFERER'));
				 }else{
					return $this->redirect($this->generateUrl('mytrip_admin_users'));
				 }
			 }else{
				 $this->get('session')->getFlashBag()->add('error','<div class="error msg">Sorry, Users record not found in our database</div>');
				 return $this->redirect($this->generateUrl('mytrip_admin_users'));
			 }
		 }
	 }
	
	/*******Admin Users***********/
	public function adminusersAction(Request $request){	
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		$session=$request->getSession();
		/****** Super admin session checking**********/
		/*$superadmin=$this->supercheckAdmin($request->getSession());		
		if($superadmin){
			return $superadmin;
		}*/
		
		/****** Search redirect same page **********/
		$em =  $this->getDoctrine()->getManager();	
		 if($request->getMethod()=="POST"){
			 if($request->request->get('searchname')!=''){
				 $searchcontent['name']=$request->request->get('searchname');
			 }
			 if($request->request->get('searchusername')!=''){
				 $searchcontent['username']=$request->request->get('searchusername');
			 }
			 if($request->request->get('searchemail')!=''){
				 $searchcontent['email']=$request->request->get('searchemail');
			 }			
			if(!empty($searchcontent)){	
				$searchcontent['search']=1;			
				return $this->redirect($this->generateUrl('mytrip_admin_adminusers',$searchcontent));			
			} 
		 }
		 
		 if($session->get('adminid')!='1'){
			$notid='1,'.$session->get('adminid');
		}else{
			$notid=1;
		}
		 
		 /****** Search Query params checking**********/		
		 if($request->query->get('search')!=''){
			 $where=array();
			if($request->query->get('name')!=''){
				$where[]="p.name LIKE '%".$request->query->get('name')."%'"; 
			}elseif($request->query->get('username')!=''){
				$where[]="p.username LIKE '%".$request->query->get('username')."%'"; 
			}elseif($request->query->get('email')!=''){
				$where[]="p.email LIKE '%".$request->query->get('email')."%'"; 
			}			
			
			
			if(!empty($where)){
				$wherequery=implode(" AND ",$where);
				$sql="SELECT p FROM MytripAdminBundle:Admin p WHERE p.adminId NOT IN ( ".$notid." ) AND ".$wherequery;
			}else{
				 $sql="SELECT p FROM MytripAdminBundle:Admin p WHERE p.adminId NOT IN ( ".$notid." ) ";
			}
		 }else{
			  $sql="SELECT p FROM MytripAdminBundle:Admin p WHERE p.adminId NOT IN ( ".$notid." ) ";
		 }
		
		/*****Pagnation****/
		$query = $em->createQuery($sql);		
		$paginator  = $this->get('knp_paginator');
		$pagination = $paginator->paginate(
			$query,
			$this->get('request')->query->get('page', 1)/*page number*/,
			10/*limit per page*/
		);			
		return $this->render('MytripAdminBundle:Default:adminusers.html.php', array('pagination' => $pagination,'urlrequest'=>$this->geturlrequest($request),'pagerequest'=>$this->getrequestarray($request),'sortingrequest'=>$this->getsortrequest($request)));			
	}
	
	/*******Cancel setting***********/
	public function cancelsettingsAction(Request $request){	
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		/****** Super admin session checking**********/
		$superadmin=$this->supercheckAdmin($request->getSession());		
		if($superadmin){
			return $superadmin;
		}
		
		/****** Search redirect same page **********/
		$em =  $this->getDoctrine()->getManager();	
		$sql="SELECT p FROM MytripAdminBundle:CancelDefault p ";
		/*****Pagnation****/
		$query = $em->createQuery($sql);		
		$paginator  = $this->get('knp_paginator');
		$pagination = $paginator->paginate(
			$query,
			$this->get('request')->query->get('page', 1)/*page number*/,
			10/*limit per page*/
		);			
		return $this->render('MytripAdminBundle:Default:cancelsettings.html.php', array('pagination' => $pagination,'urlrequest'=>$this->geturlrequest($request),'pagerequest'=>$this->getrequestarray($request),'sortingrequest'=>$this->getsortrequest($request)));			
	}
	
	/*******Add cancel settings***********/
	public function addcancelsettingsAction(Request $request){
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		
		 $session = $request->getSession();
		 $id=$session->get('adminid');
		 
		 $canceldefault = new CancelDefault();
		 
		 $em =  $this->getDoctrine()->getManager();
		 
		 $checkdays = $em->createQuery("SELECT p FROM MytripAdminBundle:CancelDefault p WHERE  p.days = '".$request->get('days')."'")->getArrayResult();	
		 
		 if($request->getMethod()=="POST"){	
			 if(empty($checkdays)){		 												
				$canceldefault->setDays($request->get('days')); 
				$canceldefault->setPercentage($request->get('percentage')); 
				$em->persist($canceldefault);
				$em->flush();
				$this->get('session')->getFlashBag()->add('error','<div class="success msg">Cancel settings has been successfully inserted</div>');
				return $this->redirect($this->generateUrl('mytrip_admin_add_cancel_setting'));						
			 }else{
				$this->get('session')->getFlashBag()->add('error','<div class="error msg">Days already inserted</div>'); 
			 }
		 }	
		  
		 return $this->render('MytripAdminBundle:Default:addcancelsettings.html.php');
	}
	
	/*******Edit cancel settings***********/
	public function editcancelsettingsAction(Request $request){
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		
		 $session = $request->getSession();
		 $id=$request->get('id');
		 
		 $em =  $this->getDoctrine()->getManager();
		 /***Fetch admin users******/
		 $canceldefault = $em->getRepository('MytripAdminBundle:CancelDefault')->findOneByCancelId($id);	
		 
		 $checkdays = $em->createQuery("SELECT p FROM MytripAdminBundle:CancelDefault p WHERE  p.days = '".$request->get('days')."' AND p.cancelId NOT IN ('".$id."')")->getArrayResult();	
		 
		 if($request->getMethod()=="POST"){	
			 if(empty($checkdays)){		 												
				$canceldefault->setDays($request->get('days')); 
				$canceldefault->setPercentage($request->get('percentage')); 				
				$em->flush();
				$this->get('session')->getFlashBag()->add('error','<div class="success msg">Cancel settings has been successfully updated</div>');
				return $this->redirect($this->generateUrl('mytrip_admin_edit_cancel_settings',array('id'=>$id)));						
			 }else{
				$this->get('session')->getFlashBag()->add('error','<div class="error msg">Days already inserted</div>'); 
			 }
		 }
		 
		 $setting=$em->createQuery("SELECT p FROM MytripAdminBundle:CancelDefault p WHERE p.cancelId =".$id)->getArrayResult();	
		 
		 return $this->render('MytripAdminBundle:Default:editcancelsettings.html.php',array('setting'=>$setting));
	}
	
	/*******Delete cancel settings***********/
	public function deletecancelsettingsAction(Request $request){	
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
			 
		 $em =  $this->getDoctrine()->getManager();	
		 
		 /*****Single record delete*******/
		 $id=$request->query->get('id');		 
		 if($id!='' && $id>1){
			 $em->createQuery("DELETE FROM MytripAdminBundle:CancelDefault u WHERE u.cancelId =$id")->execute();
			 $this->get('session')->getFlashBag()->add('error','<div class="success msg">Record deleted successfully</div>');
			 return $this->redirect($this->generateUrl('mytrip_admin_cancel_settings'));
		 }
		 
		 /********Multiple Admin delete**********/
		 if($request->getMethod()=="POST"){			
			 $delid=array_diff($request->request->get('action'),array('1'));
			 $delid=implode(",",$delid);
			 $em->createQuery("DELETE FROM MytripAdminBundle:CancelDefault u WHERE u.cancelId IN (".$delid.")")->execute();
			 $this->get('session')->getFlashBag()->add('error','<div class="success msg">Records deleted successfully</div>');
			 return $this->redirect($this->generateUrl('mytrip_admin_cancel_settings'));			 
		 }
		 
		 if($id=='' && $request->getMethod()!="POST"){
			 $this->get('session')->getFlashBag()->add('error','<div class="error msg">Sorry, Record not available</div>');
			 return $this->redirect($this->generateUrl('mytrip_admin_cancel_settings'));	
		 }
		 
	}
	
	/******Add admin********/
	public function addadminAction(Request $request){
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		/****** Super admin session checking**********/
		$superadmin=$this->supercheckAdmin($request->getSession());		
		if($superadmin){
			return $superadmin;
		}
		
		$admin = new Admin();
		$em =  $this->getDoctrine()->getManager();
		/******Submit form**********/
		if($request->getMethod()=="POST"){
			$form=$request->get('form');
			
			/********Checking duplicate username***********/
			$query = $em->createQuery("SELECT p FROM MytripAdminBundle:Admin p WHERE  p.username = '".$form['username']."' ");			
			$checkusername = $query->getResult();			
					
			if(empty($checkusername)){
				
				/********Checking duplicate email id***********/
				$emailquery = $em->createQuery("SELECT p FROM MytripAdminBundle:Admin p WHERE  p.email = '".$form['email']."'");			
				$checkemail = $emailquery->getResult();	
				
				/********Save admin***********/			
				if(empty($checkemail)){	
					$password=sha1($form['password']);
					$admin->setName($form['name']);
					$admin->setPassword($password);
					$admin->setUsername($form['username']);
					$admin->setEmail($form['email']);
					$admin->setCmcode($form['cmcode']);
					$admin->setMobile($form['mobile']);
					$admin->setStatus("Active");
					$admin->setModifyDate(new \DateTime(date('Y-m-d H:i:s')));
					$em->persist($admin);
					$em->flush();
					$emaillist=$em->getRepository('MytripAdminBundle:EmailList')->findOneBy(array('emailListId'=>'2'));							
					$emailcontent=$em->getRepository('MytripAdminBundle:EmailContent')->findOneBy(array('emailList'=>'2','lan'=>'en'));					
					$link=$this->getRequest()->getSchemeAndHttpHost()."/".$this->container->get('router')->getContext()->getBaseUrl()."/".$this->generateUrl('mytrip_admin_homepage');
					$message=str_replace(array('{name}','{username}','{password}','{link}'),array($form['name'],$form['username'],$form['password'],$link),$emailcontent->getEmailContent());
					
					/*******Admin Credentials send to new admin***********/								
					$this->mailsend($emaillist->getFromname(),$emaillist->getFromemail(),$form['email'],$emailcontent->getSubject(),$message,$emaillist->getCcmail());
					
					$this->get('session')->getFlashBag()->add('error','<div class="success msg">Admin user successfully created</div>');
					return $this->redirect($this->generateUrl('mytrip_admin_adminusers'));					
				}else{
					$this->get('session')->getFlashBag()->add('error','<div class="error msg">Email id already exists</div>');
					//return $this->redirect($this->generateUrl('mytrip_admin_adminusers'));
				}
			}else{
				$this->get('session')->getFlashBag()->add('error','<div class="error msg">Username already exists</div>');
				//return $this->redirect($this->generateUrl('mytrip_admin_adminusers'));
		 	}			
		}
		
		/********Add admin form**********/
		$addadmin = $this->createFormBuilder($admin,array('attr'=>array('id'=>'myForm')))
            ->add('username', 'text',array('label'=>'Username','attr'=>array('class'=>'validate[required,minSize[5]]','value'=>(isset($_REQUEST['form']['username'])?$_REQUEST['form']['username']:''),'size'=>'50'),'required'=>false))
            ->add('name', 'text',array('label'=>'Name','attr'=>array('class'=>'validate[required]','value'=>(isset($_REQUEST['form']['name'])?$_REQUEST['form']['name']:''),'size'=>'50'),'required'=>false)) 
			->add('password', 'password',array('label'=>'Password','attr'=>array('class'=>'validate[required]','value'=>"",'size'=>'50'),'required'=>false)) 
			->add('email', 'text',array('label'=>'Email','attr'=>array('class'=>'validate[required,custom[email]]','value'=>(isset($_REQUEST['form']['email'])?$_REQUEST['form']['email']:''),'size'=>'50'),'required'=>false)) 
			->add('cmcode', 'text',array('label'=>'Code','attr'=>array('class'=>'validate[required,custom[integer]]','value'=>(isset($_REQUEST['form']['cmcode'])?$_REQUEST['form']['cmcode']:''),'size'=>'5'),'required'=>false))    
			->add('mobile', 'text',array('label'=>'Mobile','attr'=>array('class'=>'validate[required,custom[integer]]','value'=>(isset($_REQUEST['form']['mobile'])?$_REQUEST['form']['mobile']:''),'size'=>'39'),'required'=>false))               
			->add('save', 'submit', array('attr' => array('class' => 'button gray'),'label' => "Save"))
            ->getForm();
			
		 return $this->render('MytripAdminBundle:Default:addadmin.html.php', array('addadmin' => $addadmin->createView()));
	}	
	
	/******Edit admin********/
	public function editadminAction(Request $request){	
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		/****** Super admin session checking**********/
		$superadmin=$this->supercheckAdmin($request->getSession());		
		if($superadmin){
			return $superadmin;
		}	
		
		$admin = new Admin();	
		$id=$request->query->get('id');		 
		 
		 if($id!=''){		 
			 $em =  $this->getDoctrine()->getManager();
			 
			 /***Fetch admin users******/
			 $repository = $em->getRepository('MytripAdminBundle:Admin');		 
			 $check=$repository->findOneByAdminId($id);	
		 
			 if($request->getMethod()=="POST"){
				$form=$request->get('form');
				
				/********Checking duplicate username***********/		
				$query = $em->createQuery("SELECT p FROM MytripAdminBundle:Admin p WHERE p.adminId NOT IN ( ".$id.") AND p.username = '".$form['username']."'");			
				$checkusername = $query->getResult();			
				if(empty($checkusername)){
					
					/********Checking duplicate email id***********/
					$emailquery = $em->createQuery("SELECT p FROM MytripAdminBundle:Admin p WHERE p.adminId NOT IN ( ".$id.") AND p.email = '".$form['email']."' ");			
					$checkemail = $emailquery->getResult();				
					if(empty($checkemail)){					
						$check->setName($form['name']);
						$check->setUsername($form['username']);
						$check->setEmail($form['email']);
						$check->setCmcode($form['cmcode']);
						$check->setMobile($form['mobile']);						
						$check->setModifyDate(new \DateTime(date('Y-m-d H:i:s')));
						$em->flush();
						$this->get('session')->getFlashBag()->add('error','<div class="success msg">Your profile has been successfully updated</div>');
						return $this->redirect($this->generateUrl('mytrip_admin_adminusers'));
					}else{
						$this->get('session')->getFlashBag()->add('error','<div class="error msg">Email id already exists</div>');
						//return $this->redirect($this->generateUrl('mytrip_admin_adminusers'));
					}
				}else{
					$this->get('session')->getFlashBag()->add('error','<div class="error msg">Username already exists</div>');
					//return $this->redirect($this->generateUrl('mytrip_admin_adminusers'));
				}
			 }	 			
		 
		 	/*****Edit admin********/
			 $editadmin = $this->createFormBuilder($admin,array('attr'=>array('id'=>'myForm')))
					->add('username', 'text',array('label'=>'Username','attr'=>array('class'=>'validate[required,minSize[5]]','value'=>$check->getUsername(),'size'=>'50'),'required'=>false))
					->add('name', 'text',array('label'=>'Name','attr'=>array('class'=>'validate[required]','value'=>$check->getName(),'size'=>'50'),'required'=>false)) 					
					->add('email', 'text',array('label'=>'Email','attr'=>array('class'=>'validate[required,custom[email]]','value'=>$check->getEmail(),'size'=>'50'),'required'=>false))											
					->add('cmcode', 'text',array('label'=>'Cmcode','attr'=>array('class'=>'validate[required,custom[integer]]','value'=>$check->getCmcode(),'size'=>'5'),'required'=>false))						
					->add('mobile', 'text',array('label'=>'Mobile','attr'=>array('class'=>'validate[required,custom[integer]]','value'=>$check->getMobile(),'size'=>'39'),'required'=>false))
					->add('save', 'submit', array('attr' => array('class' => 'button gray'),'label' => "Update"))
					->getForm();			
			return $this->render('MytripAdminBundle:Default:editadmin.html.php', array('editadmin' => $editadmin->createView()));
			
		 }else{
			 $this->get('session')->getFlashBag()->add('error','<div class="error msg">Sorry, Admin record not available in our database</div>');
			 return $this->redirect($this->generateUrl('mytrip_admin_adminusers'));
		 }
	}
	
	/******Admin Password change*********/
	public function adminpasswordAction(Request $request){
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		/****** Super admin session checking**********/
		$superadmin=$this->supercheckAdmin($request->getSession());		
		if($superadmin){
			return $superadmin;
		}
		
		$admin = new Admin();	
		$id=$request->query->get('id');		 
		 
		 if($id!=''){		 
			 $em =  $this->getDoctrine()->getManager();
			 
			 /***Fetch admin users******/
			 $repository = $em->getRepository('MytripAdminBundle:Admin');		 
			 $check=$repository->findOneByAdminId($id);	
		 
			 if($request->getMethod()=="POST"){
				$password=$request->request->get('newpassword');
				$cpassword=$request->request->get('confirmpassword');				
				if($password==$cpassword){
					$passwords=sha1($password);
					$check->setPassword($passwords);					
					$check->setModifyDate(new \DateTime(date('Y-m-d H:i:s')));
					$em->flush();
					$emaillist=$em->getRepository('MytripAdminBundle:EmailList')->findOneBy(array('emailListId'=>'3'));							
					$emailcontent=$em->getRepository('MytripAdminBundle:EmailContent')->findOneBy(array('emailList'=>'3','lan'=>'en'));					
					$link=$this->getRequest()->getSchemeAndHttpHost()."/".$this->container->get('router')->getContext()->getBaseUrl()."/".$this->generateUrl('mytrip_admin_homepage');
					$message=str_replace(array('{name}','{username}','{password}','{link}'),array($check->getName(),$check->getUsername(),$password,$link),$emailcontent->getEmailContent());
					
					/*******Admin password Credentials send to admin***********/								
					$this->mailsend($emaillist->getFromname(),$emaillist->getFromemail(),$check->getEmail(),$emailcontent->getSubject(),$message,$emaillist->getCcmail());
					
					$this->get('session')->getFlashBag()->add('error','<div class="success msg">Admin user password successfully updated</div>');
					return $this->redirect($this->generateUrl('mytrip_admin_adminusers'));
				}else{
					$this->get('session')->getFlashBag()->add('error','<div class="error msg">Password and confirm password mismatch.</div>');
					return $this->redirect($this->generateUrl('mytrip_admin_adminusers'));
				}
			}
			
			return $this->render('MytripAdminBundle:Default:adminpassword.html.php');			
		 }else{
			 $this->get('session')->getFlashBag()->add('error','<div class="error msg">Sorry, Admin record not available in our database</div>');
			 return $this->redirect($this->generateUrl('mytrip_admin_adminusers'));
		 }
	}
	
	/*******Delete Admin users***********/
	public function deleteadminAction(Request $request){	
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		/****** Super admin session checking**********/
		$superadmin=$this->supercheckAdmin($request->getSession());		
		if($superadmin){
			return $superadmin;
		}	
		
		 $admin = new Admin();		 
		 $em =  $this->getDoctrine()->getManager();	
		 
		 /*****Single Admin delete*******/
		 $id=$request->query->get('id');		 
		 if($id!='' && $id>1){
			 $delete = $em->createQuery("DELETE FROM MytripAdminBundle:Admin u WHERE u.adminId =$id")->execute();
			 $this->get('session')->getFlashBag()->add('error','<div class="success msg">Admin user deleted successfully</div>');
			 return $this->redirect($this->generateUrl('mytrip_admin_adminusers'));
		 }
		 
		 /********Multiple Admin delete**********/
		 if($request->getMethod()=="POST"){			
			 $adminid=array_diff($request->request->get('action'),array('1'));
			 $adminid=implode(",",$adminid);
			 $delete = $em->createQuery("DELETE FROM MytripAdminBundle:Admin u WHERE u.adminId IN (".$adminid.")")->execute();
			 $this->get('session')->getFlashBag()->add('error','<div class="success msg">Admin users deleted successfully</div>');
			 return $this->redirect($this->generateUrl('mytrip_admin_adminusers'));			 
		 }
		 
		 if($id=='' && $request->getMethod()!="POST"){
			 $this->get('session')->getFlashBag()->add('error','<div class="error msg">Sorry, Admin record not available</div>');
			 return $this->redirect($this->generateUrl('mytrip_admin_adminusers'));	
		 }
		 
	}
	
	/*******API lists***********/
	public function apiAction(Request $request){
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		$em =  $this->getDoctrine()->getManager();
		
		if($request->getMethod()=="POST"){
			if($request->request->get('searchname')!=''){
				$searchcontent['name']=$request->request->get('searchname');
			}			 	
			if(!empty($searchcontent)){	
				$searchcontent['search']=1;			
				return $this->redirect($this->generateUrl('mytrip_admin_api',$searchcontent));			
			} 
		 }
		 
		  /****** Search Query params checking**********/		
		 if($request->query->get('search')!=''){
			 $where=array();
			if($request->query->get('name')!=''){
				$where[]="p.gateway LIKE '%".$request->query->get('name')."%'"; 
			}
			
			if(!empty($where)){
				$wherequery=implode(" AND ",$where);
				$sql="SELECT p FROM MytripAdminBundle:ApiGateway p WHERE ".$wherequery;
			}else{
				 $sql="SELECT p FROM MytripAdminBundle:ApiGateway p  ";
			}
		 }else{
			  $sql="SELECT p FROM MytripAdminBundle:ApiGateway p ";
		 }
		
		/*****Pagnation****/
		$query = $em->createQuery($sql);		
		$paginator  = $this->get('knp_paginator');
		$pagination = $paginator->paginate(
			$query,
			$this->get('request')->query->get('page', 1)/*page number*/,
			10/*limit per page*/
		);			
		return $this->render('MytripAdminBundle:Default:api.html.php', array('pagination' => $pagination,'urlrequest'=>$this->geturlrequest($request),'pagerequest'=>$this->getrequestarray($request),'sortingrequest'=>$this->getsortrequest($request)));		
	}
	
	/********Add API******/
	public function addapiAction(Request $request){
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		$api = new ApiInfo();
		$em =  $this->getDoctrine()->getManager();
		
		/******Fetch Api Gateway********/
		$query = $em->createQuery("SELECT p FROM MytripAdminBundle:ApiGateway  p ");			
		$getapi = $query->getArrayResult();
		
		/*******Save API**********/
		if($request->getMethod()=="POST"){			
			$api->setApi($this->getDoctrine()->getRepository('MytripAdminBundle:ApiGateway')->find($request->request->get('apikey')));
			$api->setMetaKey($request->request->get('metakey'));
			$api->setMetaValue($request->request->get('metavalue'));			
			$em->persist($api);
			$em->flush();
			$this->get('session')->getFlashBag()->add('error','<div class="success msg">API successfully added</div>');
			return $this->redirect($this->generateUrl('mytrip_admin_addapi'));
		}
		return $this->render('MytripAdminBundle:Default:addapi.html.php', array('api' => $getapi));	
		
	}
	
	/****Edit API******/
	public function editapiAction(Request $request){
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		
		$api = new ApiInfo();
		$em =  $this->getDoctrine()->getManager();
		$id=$this->container->get('request')->get('id');
		
		/******Fetch Api Gateway info********/
		$query = $em->createQuery("SELECT p FROM MytripAdminBundle:ApiGateway p WHERE p.apiId= $id");			
		$getapi = $query->getArrayResult();
		
		/******Fetch Api Gateway info********/
		$queryinfo = $em->createQuery("SELECT p FROM MytripAdminBundle:ApiInfo p WHERE p.api= $id");			
		$getapiinfo = $queryinfo->getArrayResult();
		
		/*******Save API**********/
		if($request->getMethod()=="POST"){
			$i=0;
			foreach($request->request as $apis=>$value){
				if($apis!='save'){
					$em =  $this->getDoctrine()->getManager();
					$repository = $em->getRepository('MytripAdminBundle:ApiInfo');		 
					$api_info=$repository->findOneByMetaKey(trim($apis));								
					$api_info->setMetaKey($apis);
					$api_info->setMetaValue($value);
					$em->flush();
				}
			}			
			$this->get('session')->getFlashBag()->add('error','<div class="success msg">API successfully updated</div>');
			return $this->redirect($this->generateUrl('mytrip_admin_api'));			
		}
		return $this->render('MytripAdminBundle:Default:editapi.html.php', array('apiinfo' => $getapiinfo,'api'=>$getapi));			
	}
	
	/****Edit API******/
	public function paymentsettingsAction(Request $request){
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		
		$em =  $this->getDoctrine()->getManager();
		
		/*******Save API**********/
		if($request->getMethod()=="POST"){
			$em->createQuery("UPDATE MytripAdminBundle:ApiGateway p SET p.status='Inactive'")->execute();
			$em->createQuery("UPDATE MytripAdminBundle:ApiGateway p SET p.status='Active' WHERE p.apiId='".$request->request->get('apikey')."'")->execute();						
			$this->get('session')->getFlashBag()->add('error','<div class="success msg">Payment settings successfully updated</div>');
			return $this->redirect($this->generateUrl('mytrip_admin_payment_settings'));			
		}
		$getapi = $em->createQuery("SELECT p FROM MytripAdminBundle:ApiGateway p WHERE p.gateway IN ( 'Global One','Bean Stream')")->getArrayResult();
		return $this->render('MytripAdminBundle:Default:paymentsettings.html.php', array('api'=>$getapi));			
	}
	
	public function staticpageAction(Request $request){
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		$em =  $this->getDoctrine()->getManager();
		
		if($request->getMethod()=="POST"){
			if($request->request->get('searchname')!=''){
				$searchcontent['name']=$request->request->get('searchname');
			}			 	
			if(!empty($searchcontent)){	
				$searchcontent['search']=1;			
				return $this->redirect($this->generateUrl('mytrip_admin_staticpage',$searchcontent));			
			} 
		 }
		 
		  /****** Search Query params checking**********/		
		 if($request->query->get('search')!=''){
			 $where=array();
			if($request->query->get('name')!=''){
				$where[]="p.pagename LIKE '%".$request->query->get('name')."%'"; 
			}
			
			if(!empty($where)){
				$wherequery=implode(" AND ",$where);
				$sql="SELECT p FROM MytripAdminBundle:Staticpage p WHERE  p.staticpageId NOT IN (22,23) AND ".$wherequery;
			}else{
				 $sql="SELECT p FROM MytripAdminBundle:Staticpage p  WHERE p.staticpageId NOT IN (22,23)";
			}
		 }else{
			  $sql="SELECT p FROM MytripAdminBundle:Staticpage p   WHERE p.staticpageId NOT IN (22,23)";
		 }
		
		/*****Pagnation****/
		$query = $em->createQuery($sql);		
		$paginator  = $this->get('knp_paginator');
		$pagination = $paginator->paginate(
			$query,
			$this->get('request')->query->get('page', 1)/*page number*/,
			10/*limit per page*/
		);			
		return $this->render('MytripAdminBundle:Default:staticpage.html.php', array('pagination' => $pagination,'urlrequest'=>$this->geturlrequest($request),'pagerequest'=>$this->getrequestarray($request),'sortingrequest'=>$this->getsortrequest($request)));	
		
	}
	
	/********Add Staticpage*********/
	public function addstaticpageAction(Request $request){
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		
		$staticpage = new Staticpage();
		$staticpage_content = new StaticpageContent();
		$em =  $this->getDoctrine()->getManager();
		if($request->getMethod()=="POST"){
			$url= strtolower(str_replace(' ','-',preg_replace('/[^a-zA-Z0-9_ -]/s', ' ',$request->request->get('pagename'))));	
			$check=$em->createQuery("SELECT p FROM MytripAdminBundle:Staticpage  p WHERE p.url !='' AND p.url='".$url."'")->getArrayResult();
			if(empty($check)){
				$staticpage->setUrl($url);	
				$staticpage->setMainMenu('No');	
				$staticpage->setMenuId($request->request->get('mainmenu'));			
				$staticpage->setPagename($request->request->get('pagename'));
				$staticpage->setSeo('Yes');
				$staticpage->setContent('Yes');	
				$staticpage->setStatus('Active');			
				$em->persist($staticpage);
				$em->flush();
				
				$em =  $this->getDoctrine()->getManager();
				$lastid=$staticpage->getStaticpageId();
				$staticpage_content->setStaticpage($this->getDoctrine()->getRepository('MytripAdminBundle:Staticpage')->find($lastid));
				$staticpage_content->setName($request->request->get('pagename'));
				$staticpage_content->setPageTitle($request->request->get('pagetitle'));
				$staticpage_content->setMetaDescription($request->request->get('metadescription'));
				$staticpage_content->setMetaKeyword($request->request->get('metakeyword'));
				$staticpage_content->setContent($request->request->get('pagecontent'));	
				$staticpage_content->setLan('en');	
				$em->persist($staticpage_content);
				$em->flush();
				
				$this->get('session')->getFlashBag()->add('error','<div class="success msg">Content page successfully added</div>');
				return $this->redirect($this->generateUrl('mytrip_admin_staticpage'));
			}else{
				$this->get('session')->getFlashBag()->add('error','<div class="error msg">Content page already exists</div>');
			}
		}
		
		return $this->render('MytripAdminBundle:Default:addstaticpage.html.php');	
		
	}
	
	/********Edit Staticpage**********/
	public function editstaticpageAction(Request $request){
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		
		$em =  $this->getDoctrine()->getManager();
		$lan=$this->container->get('request')->get('lan');
		$id=$this->container->get('request')->get('id');
		if(in_array($id,array(22,23))){
			return $this->redirect($this->generateUrl('mytrip_admin_staticpage'));
		}
		$checkquery = $em->createQuery("SELECT p FROM MytripAdminBundle:Language  p WHERE p.lanCode='".$lan."'");			
		$checklanguage = $checkquery->getArrayResult();
		if(empty($checklanguage)){
			return $this->redirect($this->generateUrl('mytrip_admin_editstaticpage',array('id'=>$id,'lan'=>'en')));
		}		
		
		if($request->getMethod()=="POST"){
			if($lan=="en"){
				$repository = $em->getRepository('MytripAdminBundle:Staticpage');		 
				$staticpage=$repository->findOneByStaticpageId($id);
				$url= strtolower(str_replace(' ','-',preg_replace('/[^a-zA-Z0-9_ -]/s', ' ',$request->request->get('pagename'))));	
				$check=$em->createQuery("SELECT p FROM MytripAdminBundle:Staticpage  p WHERE p.url !='' AND p.url='".$url."' AND p.staticpageId NOT IN ('".$id."')")->getArrayResult();
				if(!empty($check)){
					$this->get('session')->getFlashBag()->add('error','<div class="error msg">Content page name already exists</div>');
					return $this->redirect($this->generateUrl('mytrip_admin_editstaticpage',array('id'=>$id,'lan'=>$lan)));
				}
				$staticpage->setUrl($url);	
				if($id >23){					
					$staticpage->setMenuId($request->request->get('mainmenu'));	
				}
				$staticpage->setPagename($request->request->get('pagename'));	
				$staticpage->setStatus($request->request->get('status'));											
			}
			$em =  $this->getDoctrine()->getManager();
			$check=$em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p WHERE p.staticpage=".$id." AND p.lan='".$lan."'");			
			$check_content = $check->getArrayResult();			
			if(!empty($check_content)){				
				$repository_content = $em->getRepository('MytripAdminBundle:StaticpageContent');		 
				$check_contents=$repository_content->findOneByStaticpageContentId($check_content['0']['staticpageContentId']);				
				$check_contents->setName($request->request->get('pagename'));
				$check_contents->setPageTitle($request->request->get('pagetitle'));
				$check_contents->setMetaDescription($request->request->get('metadescription'));
				$check_contents->setMetaKeyword($request->request->get('metakeyword'));
				$check_contents->setContent($request->request->get('pagecontent'));						
			}else{
				$staticpage_content = new StaticpageContent();
				$staticpage_content->setStaticpage($this->getDoctrine()->getRepository('MytripAdminBundle:Staticpage')->find($id));
				$staticpage_content->setName($request->request->get('pagename'));
				$staticpage_content->setPageTitle($request->request->get('pagetitle'));
				$staticpage_content->setMetaDescription($request->request->get('metadescription'));
				$staticpage_content->setMetaKeyword($request->request->get('metakeyword'));
				$staticpage_content->setContent($request->request->get('pagecontent'));	
				$staticpage_content->setLan($lan);	
				$em->persist($staticpage_content);
			}
			$em->flush();
			$this->get('session')->getFlashBag()->add('error','<div class="success msg">Content page successfully updated</div>');
			return $this->redirect($this->generateUrl('mytrip_admin_editstaticpage',array('id'=>$id,'lan'=>$lan)));
		}
		
		/******Fetch language********/
		$query = $em->createQuery("SELECT p FROM MytripAdminBundle:Language  p ");			
		$language = $query->getArrayResult();
		
		/*******Fetch Static page details*****/
		$static_query = $em->createQuery("SELECT p FROM MytripAdminBundle:Staticpage  p WHERE p.staticpageId=".$id);			
		$staticpage = $static_query->getArrayResult();
		if(empty($staticpage)){
			return $this->redirect($this->generateUrl('mytrip_admin_staticpage'));
		}
		
		/*******Fetch Static page content details*****/
		$static_content_query = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p WHERE p.staticpage=".$id." AND p.lan='".$lan."'");			
		$static_content = $static_content_query->getArrayResult();		
		if(empty($static_content)){
			$static_content_query = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p WHERE p.staticpage=".$id." AND p.lan='en'");			
			$static_content = $static_content_query->getArrayResult();
		}
		
		return $this->render('MytripAdminBundle:Default:editstaticpage.html.php',array('language'=>$language,'staticpage'=>$staticpage,'static_content'=>$static_content));
	}
	
	/*******Delete Staticpage***********/
	public function deletestaticpageAction(Request $request){	
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		 
		 $em =  $this->getDoctrine()->getManager();	
		 
		 /*****Single feature delete*******/
		 $id=$request->query->get('id');		
		 if($id!='' && $id>23){
			 $delete = $em->createQuery("DELETE FROM MytripAdminBundle:Staticpage u WHERE u.staticpageId =$id")->execute();
			 $em->createQuery("DELETE FROM MytripAdminBundle:StaticpageContent u WHERE u.staticpage =$id")->execute();
			 $this->get('session')->getFlashBag()->add('error','<div class="success msg">Staticpage deleted successfully</div>');
			 return $this->redirect($this->generateUrl('mytrip_admin_staticpage'));
		 }
		 
		 if($id=='' && $id>23){
			 $this->get('session')->getFlashBag()->add('error','<div class="error msg">Sorry, Staticpage record not available</div>');
			 return $this->redirect($this->generateUrl('mytrip_admin_staticpage'));	
		 }		 
	}
	
	/*******Features List***********/
	public function featuresAction(Request $request){	
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}		
		
		/****** Search redirect same page **********/
		$em =  $this->getDoctrine()->getManager();	
		 if($request->getMethod()=="POST"){
			 if($request->request->get('searchname')!=''){
				 $searchcontent['feature']=$request->request->get('searchname');
			 }				
			if(!empty($searchcontent)){	
				$searchcontent['search']=1;			
				return $this->redirect($this->generateUrl('mytrip_admin_features',$searchcontent));			
			} 
		 }
		 
		 /****** Search Query params checking**********/		
		 if($request->query->get('search')!=''){
			 $where=array();
			if($request->query->get('feature')!=''){
				$where[]="p.feature LIKE '%".$request->query->get('feature')."%'"; 
			}
			
			if(!empty($where)){
				$wherequery=implode(" AND ",$where);
				$sql="SELECT p FROM MytripAdminBundle:Feature p WHERE ".$wherequery;
			}else{
				 $sql="SELECT p FROM MytripAdminBundle:Feature p ";
			}
		 }else{
			  $sql="SELECT p FROM MytripAdminBundle:Feature p ";
		 }
		
		/*****Pagnation****/
		$query = $em->createQuery($sql);		
		$paginator  = $this->get('knp_paginator');
		$pagination = $paginator->paginate(
			$query,
			$this->get('request')->query->get('page', 1)/*page number*/,
			10/*limit per page*/
		);			
		return $this->render('MytripAdminBundle:Default:features.html.php', array('pagination' => $pagination,'urlrequest'=>$this->geturlrequest($request),'pagerequest'=>$this->getrequestarray($request),'sortingrequest'=>$this->getsortrequest($request)));			
	}
	
	/*********Add Feature***********/
	public function addfeatureAction(Request $request){
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		
		$feature = new Feature();
		$feature_content = new FeatureContent();
		$em =  $this->getDoctrine()->getManager();
		/*******Feature add*****/
		if($request->getMethod()=="POST"){
			/********Checking duplicate username***********/		
			$query = $em->createQuery("SELECT p FROM MytripAdminBundle:Feature p WHERE p.feature = '".$request->request->get('feature')."'");			
			$checkfeature = $query->getArrayResult();			
			if(empty($checkfeature)){					
				if(($request->files->get('icon')!='')){
					$ext=$request->files->get('icon')->getClientOriginalExtension() ;
					$filename=$this->str_rand(8,"alphanum").".".$ext;
					$request->files->get('icon')->move("img/feature_icon",$filename);
					$feature->setIcon($filename);	
				}			
				$feature->setFeature($request->request->get('feature'));					
				$em->persist($feature);
				$em->flush();
				$em =  $this->getDoctrine()->getManager();
				$lastid=$feature->getFeatureId();
				$feature_content->setFeature2($this->getDoctrine()->getRepository('MytripAdminBundle:Feature')->find($lastid));
				$feature_content->setFeature($request->request->get('feature'));			
				$feature_content->setLan('en');	
				$em->persist($feature_content);
				$em->flush();
				$this->get('session')->getFlashBag()->add('error','<div class="success msg">Feature successfully added</div>');
				return $this->redirect($this->generateUrl('mytrip_admin_features'));
			}else{
				$this->get('session')->getFlashBag()->add('error','<div class="error msg">Feature already exists</div>');
				//return $this->redirect($this->generateUrl('mytrip_admin_features'));
			}
		}
		
		return $this->render('MytripAdminBundle:Default:addfeature.html.php');		
	
	}
	
	/***********Edit Feature*********/
	public function editfeatureAction(Request $request){	
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		
		$em =  $this->getDoctrine()->getManager();
		$lan=$this->container->get('request')->get('lan');
		$id=$this->container->get('request')->get('id');
		
		$checkquery = $em->createQuery("SELECT p FROM MytripAdminBundle:Language  p WHERE p.lanCode='".$lan."'");			
		$checklanguage = $checkquery->getArrayResult();
		if(empty($checklanguage)){
			return $this->redirect($this->generateUrl('mytrip_admin_editfeature',array('id'=>$id,'lan'=>'en')));
		}		
		/*****Feature Edit*******/
		if($request->getMethod()=="POST"){
			if($lan=="en"){
				$repository = $em->getRepository('MytripAdminBundle:Feature');		 
				$feature=$repository->findOneByFeatureId($id);								
				$feature->setFeature($request->request->get('feature'));
				//$request->files->get('icon')->getClientOriginalName()
				if(($request->files->get('icon')!='')){
					$file_path="img/feature_icon".$feature->getIcon();
					if($feature->getIcon()!='' && file_exists($file_path)){
						unlink($file_path);
					}
					$ext=$request->files->get('icon')->getClientOriginalExtension() ;
					$filename=$this->str_rand(8,"alphanum").".".$ext;
					$request->files->get('icon')->move("img/feature_icon",$filename);
					$feature->setIcon($filename);	
				}													
			}
			$em =  $this->getDoctrine()->getManager();
			$check=$em->createQuery("SELECT p FROM MytripAdminBundle:FeatureContent p WHERE p.feature2=".$id." AND p.lan='".$lan."'");			
			$check_content = $check->getArrayResult();	
			//print_r($check_content);exit;		
			if(!empty($check_content)){				
				$repository_content = $em->getRepository('MytripAdminBundle:FeatureContent');		 
				$check_contents=$repository_content->findOneByFeatureContentId($check_content['0']['featureContentId']);			
				$check_contents->setFeature($request->request->get('feature'));				
			}else{
				$feature_content = new FeatureContent();
				$feature_content->setFeature2($this->getDoctrine()->getRepository('MytripAdminBundle:Feature')->find($id));
				$feature_content->setFeature($request->request->get('feature'));					
				$feature_content->setLan($lan);	
				$em->persist($feature_content);
			}
			$em->flush();
			$this->get('session')->getFlashBag()->add('error','<div class="success msg">Feature successfully updated</div>');
			return $this->redirect($this->generateUrl('mytrip_admin_editfeature',array('id'=>$id,'lan'=>$lan)));
		}
		
		/******Fetch language********/
		$query = $em->createQuery("SELECT p FROM MytripAdminBundle:Language  p ");			
		$language = $query->getArrayResult();
		
		/*******Fetch feature details*****/
		$feature_query = $em->createQuery("SELECT p FROM MytripAdminBundle:Feature  p WHERE p.featureId=".$id);			
		$feature = $feature_query->getArrayResult();
		if(empty($feature)){
			return $this->redirect($this->generateUrl('mytrip_admin_features'));
		}
		
		/*******Fetch feature content details*****/
		$feature_content_query = $em->createQuery("SELECT p FROM MytripAdminBundle:FeatureContent p WHERE p.feature2=".$id." AND p.lan='".$lan."'");			
		$feature_content = $feature_content_query->getArrayResult();		
		if(empty($feature_content)){
			$feature_content_query = $em->createQuery("SELECT p FROM MytripAdminBundle:FeatureContent p WHERE p.feature2=".$id." AND p.lan='en'");			
			$feature_content = $feature_content_query->getArrayResult();
		}		
		return $this->render('MytripAdminBundle:Default:editfeature.html.php',array('language'=>$language,'feature'=>$feature,'feature_content'=>$feature_content));
	
	}
	
	/*******Delete feature***********/
	public function deletefeatureAction(Request $request){	
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		 
		 $em =  $this->getDoctrine()->getManager();	
		 
		 /*****Single feature delete*******/
		 $id=$request->query->get('id');		
		 if($id!='' && $id>1){
			 $delete = $em->createQuery("DELETE FROM MytripAdminBundle:Feature u WHERE u.featureId =$id")->execute();
			 $this->get('session')->getFlashBag()->add('error','<div class="success msg">Feature deleted successfully</div>');
			 return $this->redirect($this->generateUrl('mytrip_admin_features'));
		 }
		 
		 /********Multiple Admin delete**********/
		 if($request->getMethod()=="POST"){	
			 $featureid=implode(",",$request->request->get('action'));
			 $delete = $em->createQuery("DELETE FROM MytripAdminBundle:Feature u WHERE u.featureId IN (".$featureid.")")->execute();
			 $this->get('session')->getFlashBag()->add('error','<div class="success msg">Features deleted successfully</div>');
			 return $this->redirect($this->generateUrl('mytrip_admin_features'));			 
		 }
		 
		 if($id=='' && $request->getMethod()!="POST"){
			 $this->get('session')->getFlashBag()->add('error','<div class="error msg">Sorry, Features record not available</div>');
			 return $this->redirect($this->generateUrl('mytrip_admin_features'));	
		 }		 
	}
	
	/*******Sociallink List***********/
	public function sociallinksAction(Request $request){	
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}		
		
		/****** Search redirect same page **********/
		$em =  $this->getDoctrine()->getManager();	
		 if($request->getMethod()=="POST"){
			 if($request->request->get('searchname')!=''){
				 $searchcontent['link']=$request->request->get('searchname');
			 }				
			if(!empty($searchcontent)){	
				$searchcontent['search']=1;			
				return $this->redirect($this->generateUrl('mytrip_admin_sociallinks',$searchcontent));			
			} 
		 }
		 
		 /****** Search Query params checking**********/		
		 if($request->query->get('search')!=''){
			 $where=array();
			if($request->query->get('link')!=''){
				$where[]="p.site LIKE '%".$request->query->get('site')."%'"; 
			}
			
			if(!empty($where)){
				$wherequery=implode(" AND ",$where);
				$sql="SELECT p FROM MytripAdminBundle:SocialLink p WHERE ".$wherequery;
			}else{
				 $sql="SELECT p FROM MytripAdminBundle:SocialLink p ";
			}
		 }else{
			  $sql="SELECT p FROM MytripAdminBundle:SocialLink p ";
		 }
		
		/*****Pagnation****/
		$query = $em->createQuery($sql);		
		$paginator  = $this->get('knp_paginator');
		$pagination = $paginator->paginate(
			$query,
			$this->get('request')->query->get('page', 1)/*page number*/,
			10/*limit per page*/
		);			
		return $this->render('MytripAdminBundle:Default:sociallinks.html.php', array('pagination' => $pagination,'urlrequest'=>$this->geturlrequest($request),'pagerequest'=>$this->getrequestarray($request),'sortingrequest'=>$this->getsortrequest($request)));			
	}
	
	/*********Add Social link***********/
	public function addsociallinkAction(Request $request){
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		
		$sociallink = new Sociallink();		
		$em =  $this->getDoctrine()->getManager();
		/*******Feature add*****/
		if($request->getMethod()=="POST"){
			/********Checking duplicate username***********/		
			$query = $em->createQuery("SELECT p FROM MytripAdminBundle:SocialLink p WHERE p.site = '".$request->request->get('site')."'");			
			$checksite = $query->getArrayResult();			
			if(empty($checksite)){
				$sociallink->setSite($request->request->get('site'));
				$sociallink->setLink($request->request->get('link'));					
				$em->persist($sociallink);
				$em->flush();				
				$this->get('session')->getFlashBag()->add('error','<div class="success msg">Social link successfully added</div>');
				return $this->redirect($this->generateUrl('mytrip_admin_sociallinks'));
			}else{
				$this->get('session')->getFlashBag()->add('error','<div class="error msg">Social link already exists</div>');
				//return $this->redirect($this->generateUrl('mytrip_admin_features'));
			}
		}		
		return $this->render('MytripAdminBundle:Default:addsociallink.html.php');		
	
	}
	
	/***********Edit Sociallink*********/
	public function editsociallinkAction(Request $request){	
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		
		$em =  $this->getDoctrine()->getManager();		
		$id=$this->container->get('request')->get('id');		
		/*****Social link Edit*******/
		if($request->getMethod()=="POST"){			
			$repository = $em->getRepository('MytripAdminBundle:SocialLink');		 
			$social=$repository->findOneBySocialLinkId($id);								
			$social->setLink($request->request->get('link'));
			$em->flush();
			$this->get('session')->getFlashBag()->add('error','<div class="success msg">Social link successfully updated</div>');
			return $this->redirect($this->generateUrl('mytrip_admin_editsociallink',array('id'=>$id)));
		}
		
		
		/*******Fetch Social link details*****/
		$social_query = $em->createQuery("SELECT p FROM MytripAdminBundle:SocialLink  p WHERE p.socialLinkId=".$id);			
		$social = $social_query->getArrayResult();
		if(empty($social)){
			return $this->redirect($this->generateUrl('mytrip_admin_sociallinks'));
		}		
			
		return $this->render('MytripAdminBundle:Default:editsociallink.html.php',array('social'=>$social));
	
	}
	
	/*******Email content List***********/
	public function emailcontentAction(Request $request){	
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}		
		
		/****** Search redirect same page **********/
		$em =  $this->getDoctrine()->getManager();	
		 if($request->getMethod()=="POST"){
			 if($request->request->get('searchname')!=''){
				 $searchcontent['title']=$request->request->get('searchname');
			 }				
			if(!empty($searchcontent)){	
				$searchcontent['search']=1;			
				return $this->redirect($this->generateUrl('mytrip_admin_emailcontent',$searchcontent));			
			} 
		 }
		 
		 /****** Search Query params checking**********/		
		 if($request->query->get('search')!=''){
			 $where=array();
			if($request->query->get('title')!=''){
				$where[]="p.title LIKE '%".$request->query->get('title')."%'"; 
			}
			
			if(!empty($where)){
				$wherequery=implode(" AND ",$where);
				$sql="SELECT p FROM MytripAdminBundle:EmailList p WHERE ".$wherequery;
			}else{
				 $sql="SELECT p FROM MytripAdminBundle:EmailList p ";
			}
		 }else{
			  $sql="SELECT p FROM MytripAdminBundle:EmailList p ";
		 }
		
		/*****Pagnation****/
		$query = $em->createQuery($sql);		
		$paginator  = $this->get('knp_paginator');
		$pagination = $paginator->paginate(
			$query,
			$this->get('request')->query->get('page', 1)/*page number*/,
			10/*limit per page*/
		);			
		return $this->render('MytripAdminBundle:Default:emailcontent.html.php', array('pagination' => $pagination,'urlrequest'=>$this->geturlrequest($request),'pagerequest'=>$this->getrequestarray($request),'sortingrequest'=>$this->getsortrequest($request)));			
	}
	
	/*******Add Email content List***********/
	public function addemailcontentAction(Request $request){
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		
		$emaillist = new EmailList();
		$email_content = new EmailContent();
		$em =  $this->getDoctrine()->getManager();
		if($request->getMethod()=="POST"){			
			$emaillist->setTitle($request->request->get('title'));
			$emaillist->setLabel($request->request->get('label'));
			$emaillist->setFromname($request->request->get('fromname'));
			$emaillist->setFromemail($request->request->get('fromemail'));
			$emaillist->setTomail($request->request->get('tomail'));
			$emaillist->setCcmail($request->request->get('ccmail'));			
			$em->persist($emaillist);
			$em->flush();
			$em =  $this->getDoctrine()->getManager();
			$lastid=$emaillist->getEmailListId();
			$email_content->setEmailList($this->getDoctrine()->getRepository('MytripAdminBundle:EmailList')->find($lastid));
			$email_content->setSubject($request->request->get('subject'));				
			$email_content->setEmailcontent($request->request->get('emailcontent'));	
			$email_content->setLan('en');	
			$em->persist($email_content);
			$em->flush();
			$this->get('session')->getFlashBag()->add('error','<div class="success msg">Email Content successfully added</div>');
			return $this->redirect($this->generateUrl('mytrip_admin_emailcontent'));
		}
		
		return $this->render('MytripAdminBundle:Default:addemailcontent.html.php');
		
	}
	
	/*******Edit Email content List***********/
	public function editemailcontentAction(Request $request){
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		
		$em =  $this->getDoctrine()->getManager();
		$lan=$this->container->get('request')->get('lan');
		$id=$this->container->get('request')->get('id');
		
		$checkquery = $em->createQuery("SELECT p FROM MytripAdminBundle:Language  p WHERE p.lanCode='".$lan."'");			
		$checklanguage = $checkquery->getArrayResult();
		if(empty($checklanguage)){
			return $this->redirect($this->generateUrl('mytrip_admin_editemailcontent',array('id'=>$id,'lan'=>'en')));
		}		
		
		if($request->getMethod()=="POST"){
			if($lan=="en"){
				$repository = $em->getRepository('MytripAdminBundle:EmailList');		 
				$emaillist=$repository->findOneByEmailListId($id);								
				$emaillist->setTitle($request->request->get('title'));				
				$emaillist->setFromname($request->request->get('fromname'));
				$emaillist->setFromemail($request->request->get('fromemail'));
				$emaillist->setTomail($request->request->get('tomail'));
				$emaillist->setCcmail($request->request->get('ccmail'));												
			}
			$em =  $this->getDoctrine()->getManager();
			$check=$em->createQuery("SELECT p FROM MytripAdminBundle:EmailContent p WHERE p.emailList=".$id." AND p.lan='".$lan."'");			
			$check_content = $check->getArrayResult();			
			if(!empty($check_content)){				
				$repository_content = $em->getRepository('MytripAdminBundle:EmailContent');		 
				$check_contents=$repository_content->findOneByEmailContentId($check_content['0']['emailContentId']);
				$check_contents->setSubject($request->request->get('subject'));				
			    $check_contents->setEmailcontent($request->request->get('emailcontent'));
			}else{
				$check_contents = new EmailContent();
				$check_contents->setEmailList($this->getDoctrine()->getRepository('MytripAdminBundle:EmailList')->find($id));				
				$check_contents->setSubject($request->request->get('subject'));				
			    $check_contents->setEmailcontent($request->request->get('emailcontent'));				
				$check_contents->setLan($lan);	
				$em->persist($check_contents);
			}
			$em->flush();
			$this->get('session')->getFlashBag()->add('error','<div class="success msg">Email Content successfully updated</div>');
			return $this->redirect($this->generateUrl('mytrip_admin_editemailcontent',array('id'=>$id,'lan'=>$lan)));
		}
		
		/******Fetch language********/
		$query = $em->createQuery("SELECT p FROM MytripAdminBundle:Language  p ");			
		$language = $query->getArrayResult();
		
		/*******Fetch Email content details*****/
		$email_query = $em->createQuery("SELECT p FROM MytripAdminBundle:EmailList  p WHERE p.emailListId=".$id);			
		$emaillist = $email_query->getArrayResult();
		if(empty($emaillist)){
			return $this->redirect($this->generateUrl('mytrip_admin_emailcontent'));
		}
		
		/*******Fetch Email content content details*****/
		$email_content_query = $em->createQuery("SELECT p FROM MytripAdminBundle:EmailContent p WHERE p.emailList=".$id." AND p.lan='".$lan."'");			
		$email_content = $email_content_query->getArrayResult();		
		if(empty($email_content)){
			$email_content_query = $em->createQuery("SELECT p FROM MytripAdminBundle:EmailContent p WHERE p.emailList=".$id." AND p.lan='en'");			
			$email_content = $email_content_query->getArrayResult();
		}
		
		return $this->render('MytripAdminBundle:Default:editemailcontent.html.php',array('language'=>$language,'emaillist'=>$emaillist,'email_content'=>$email_content));
		
	}
	
	/**********Destination List********/
	public function destinationAction(Request $request){
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		$em =  $this->getDoctrine()->getManager();
		
		if($request->getMethod()=="POST"){
			if($request->request->get('searchname')!=''){
				$searchcontent['name']=$request->request->get('searchname');
			}			 	
			if(!empty($searchcontent)){	
				$searchcontent['search']=1;			
				return $this->redirect($this->generateUrl('mytrip_admin_destination',$searchcontent));			
			} 
		 }
		 
		  /****** Search Query params checking**********/		
		 if($request->query->get('search')!=''){
			 $where=array();
			if($request->query->get('name')!=''){
				$where[]="d.name LIKE '%".$request->query->get('name')."%'"; 
			}
			
			if(!empty($where)){
				$wherequery=implode(" AND ",$where);
				$sql="SELECT d,(SELECT COUNT(h) FROM MytripAdminBundle:Hostal h WHERE d.destinationId=h.destination) FROM MytripAdminBundle:Destination d WHERE ".$wherequery." AND d.status NOT IN ('Trash')  ORDER BY d.destinationId DESC";
			}else{
				 $sql="SELECT d,(SELECT COUNT(h) FROM MytripAdminBundle:Hostal h WHERE d.destinationId=h.destination) FROM MytripAdminBundle:Destination d WHERE d.status NOT IN ('Trash')  ORDER BY d.destinationId DESC";
			}
		 }else{			 
			  $sql="SELECT d,(SELECT COUNT(h) FROM MytripAdminBundle:Hostal h WHERE d.destinationId=h.destination) FROM MytripAdminBundle:Destination d WHERE d.status NOT IN ('Trash')  ORDER BY d.destinationId DESC";
		 }
		 
		
		/*****Pagnation****/
		$query = $em->createQuery($sql);		
		$paginator  = $this->get('knp_paginator');
		$pagination = $paginator->paginate(
			$query,
			$this->get('request')->query->get('page', 1)/*page number*/,
			10/*limit per page*/
		);				
		return $this->render('MytripAdminBundle:Default:destination.html.php', array('pagination' => $pagination,'urlrequest'=>$this->geturlrequest($request),'pagerequest'=>$this->getrequestarray($request),'sortingrequest'=>$this->getsortrequest($request)));	
		
	}
	
	/********Add Destination*********/
	public function adddestinationAction(Request $request){
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		
		$destination = new Destination();
		$destination_content = new DestinationContent();
		$destination_image = new DestinationImage();
		$destination_feature = new DestinationFeature();
		$feature = new Feature();
		
		if($request->getMethod()=="POST"){	
			$page_link = strtolower(str_replace(' ','_',preg_replace('/[^a-zA-Z0-9_ -]/s', ' ', $request->request->get('name'))));
			if($page_link ==''){
				$page_link=strtolower(str_replace(array(' ','\''),array('_','-'), $request->request->get('name')));
			}
			
			$em =  $this->getDoctrine()->getManager();
			
			/********Checking duplicate username***********/
			$check = $em->createQuery("SELECT d FROM MytripAdminBundle:Destination d WHERE  d.url='".$page_link."' AND d.status NOT IN ('Trash')  ");			
	        $check_destination = $check->getArrayResult();				
					
			if(empty($check_destination)){			
				$country_query = $em->createQuery("SELECT c FROM MytripAdminBundle:Country c WHERE c.cid='".$request->request->get('country')."'");			
				$country = $country_query->getArrayResult();
				
				$state_query = $em->createQuery("SELECT s FROM MytripAdminBundle:States s WHERE s.sid='".$request->request->get('province')."'");			
				$state = $state_query->getArrayResult();
				
				$address=$request->request->get('name').",".$state[0]['state'].",".$country[0]['country'];
				$position=$this->getBoundLatitude_longitude($address);	
				
				$destination->setName($request->request->get('name'));
				$destination->setUrl($page_link);				
				$destination->setVideo($request->request->get('svdesc'));
				$destination->setTripadvisor($request->request->get('tripadvisor'));
				$destination->setCountry($this->getDoctrine()->getRepository('MytripAdminBundle:Country')->find($request->request->get('country')));
				$destination->setProvince($this->getDoctrine()->getRepository('MytripAdminBundle:States')->find($request->request->get('province')));
				$destination->setLongitude($position['longitude']!=''?$position['longitude']:0);
				$destination->setLatitude($position['latitude']!=''?$position['latitude']:0);
				$destination->setStatus('Active');
				$destination->setCreatedDate(new \DateTime(date('Y-m-d H:i:s')));
				$destination->setModifyDate(new \DateTime(date('Y-m-d H:i:s')));				
				$em->persist($destination);
				$em->flush();
				
				$em =  $this->getDoctrine()->getManager();
				$lastid=$destination->getDestinationId();
				
				$destination_content->setName($request->request->get('name'));
				$destination_content->setDestination($this->getDoctrine()->getRepository('MytripAdminBundle:Destination')->find($lastid));
				$destination_content->setDescription($request->request->get('description'));
				$destination_content->setLocationDesc($request->request->get('location_desc'));	
				$destination_content->setAddress($address);	
				$destination_content->setCity($request->request->get('name'));	
				$destination_content->setProvince($state[0]['state']);	
				$destination_content->setCountry($country[0]['country']);		
				$destination_content->setMetaTitle($request->request->get('metatitle'));
				$destination_content->setMetaDescription($request->request->get('metadescription'));
				$destination_content->setMetaKeyword($request->request->get('metakeyword'));
				$destination_content->setLan('en');	
				$em->persist($destination_content);
				$em->flush();
				
				$d_feature=$request->request->get('feature');
				foreach($d_feature as $features){
					$em =  $this->getDoctrine()->getManager();
					$destination_feature = new DestinationFeature();
					$destination_feature->setDestination($this->getDoctrine()->getRepository('MytripAdminBundle:Destination')->find($lastid));
					$destination_feature->setFeature($this->getDoctrine()->getRepository('MytripAdminBundle:Feature')->find($features));
					$destination_feature->setCreatedDate(new \DateTime(date('Y-m-d H:i:s')));				
					$em->persist($destination_feature);
					$em->flush();
					unset($destination_feature);
				}
				
				if(($request->files->get('image')!='')){
					$em =  $this->getDoctrine()->getManager();
					$destination_image->setDestination($this->getDoctrine()->getRepository('MytripAdminBundle:Destination')->find($lastid));
					$ext=$request->files->get('image')->getClientOriginalExtension() ;
					$filename=$this->str_rand(8,"alphanum").".".$ext;
					//$request->files->get('image')->move("img/destination",$filename);
					$awsAccessKey = $this->container->get('mytrip_admin.helper.amazon')->getOption('awsAccessKey');
					$awsSecretKey = $this->container->get('mytrip_admin.helper.amazon')->getOption('awsSecretKey');
					$bucket = $this->container->get('mytrip_admin.helper.amazon')->getOption('bucket');
					\S3::setAuth($awsAccessKey, $awsSecretKey);	
					$tmpfile=$request->files->get('image')->getPathName() ;
					$putobject=\S3::putObjectFile($tmpfile, $bucket, $filename, \S3::ACL_PUBLIC_READ);
					if($putobject){	
						$destination_image->setImage($filename);
						$destination_image->setCreatedDate(new \DateTime(date('Y-m-d H:i:s')));					
						$em->persist($destination_image);
						$em->flush();
					}
				}			
				
				$this->get('session')->getFlashBag()->add('error','<div class="success msg">Destination successfully added</div>');
				return $this->redirect($this->generateUrl('mytrip_admin_destination'));
			}else{
				$this->get('session')->getFlashBag()->add('error','<div class="error msg">Destination already exists</div>');
			}
		}
		
		$em =  $this->getDoctrine()->getManager();
		
		$feature_query = $em->createQuery("SELECT f FROM MytripAdminBundle:Feature f" );		
		$feature=$feature_query->getArrayResult();
		
		$country_query = $em->createQuery("SELECT c FROM MytripAdminBundle:Country c");			
	    $country = $country_query->getArrayResult();	
		
		return $this->render('MytripAdminBundle:Default:adddestination.html.php',array('feature'=>$feature,'country'=>$country));	
		
	}
	
	/********Edit Destination*********/
	public function editdestinationAction(Request $request){
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		$em =  $this->getDoctrine()->getManager();
		$lan=$this->container->get('request')->get('lan');
		$id=$this->container->get('request')->get('id');
		
		$checkquery = $em->createQuery("SELECT p FROM MytripAdminBundle:Language  p WHERE p.lanCode='".$lan."'");			
		$checklanguage = $checkquery->getArrayResult();
		if(empty($checklanguage)){
			return $this->redirect($this->generateUrl('mytrip_admin_editdestination',array('id'=>$id,'lan'=>'en')));
		}
		
		if($request->getMethod()=="POST"){
			if($lan=="en"){
				$page_link = strtolower(str_replace(' ','_',preg_replace('/[^a-zA-Z0-9_ -]/s', ' ', $request->request->get('name'))));
				if($page_link ==''){
					$page_link=strtolower(str_replace(array(' ','\''),array('_','-'), $request->request->get('name')));
				}
				
				$em =  $this->getDoctrine()->getManager();
				
				/********Checking duplicate destination***********/
				$check = $em->createQuery("SELECT d FROM MytripAdminBundle:Destination d WHERE  d.url='".$page_link."' AND d.destinationId NOT IN ($id) AND  d.status NOT IN ('Trash')");			
				$check_destination = $check->getArrayResult();				
						
				if(empty($check_destination)){
					/***Destination english language update***/	
					$repository = $em->getRepository('MytripAdminBundle:Destination');		 
					$destination=$repository->findOneByDestinationId($id);
					$country_query = $em->createQuery("SELECT c FROM MytripAdminBundle:Country c WHERE c.cid='".$request->request->get('country')."'");			
					$country = $country_query->getArrayResult();
					
					$state_query = $em->createQuery("SELECT s FROM MytripAdminBundle:States s WHERE s.sid='".$request->request->get('province')."'");			
					$state = $state_query->getArrayResult();
					
					$address=$request->request->get('name').",".$state[0]['state'].",".$country[0]['country'];
					$position=$this->getBoundLatitude_longitude($address);	
					
					$destination->setName($request->request->get('name'));
					$destination->setUrl($page_link);
					$destination->setVideo($request->request->get('svdesc'));
					$destination->setTripadvisor($request->request->get('tripadvisor'));
					$destination->setCountry($this->getDoctrine()->getRepository('MytripAdminBundle:Country')->find($request->request->get('country')));
					$destination->setProvince($this->getDoctrine()->getRepository('MytripAdminBundle:States')->find($request->request->get('province')));
					$destination->setLongitude($position['longitude']);
					$destination->setLatitude($position['latitude']);
					$destination->setStatus('Active');					
					$destination->setModifyDate(new \DateTime(date('Y-m-d H:i:s')));
				}else{
					$this->get('session')->getFlashBag()->add('error','<div class="success msg">Destination already exists</div>');
					return $this->redirect($this->generateUrl('mytrip_admin_destination',array('id'=>$id,'lan'=>$lan)));
				}
			}
			
			/******Destination content update with corresponding lanugage******/
			$em =  $this->getDoctrine()->getManager();
			$check=$em->createQuery("SELECT p FROM MytripAdminBundle:DestinationContent p WHERE p.destination=".$id." AND p.lan='".$lan."'");			
			$check_content = $check->getArrayResult();
			//print_r($check_content); echo $check_content['0']['destinationContentId'];exit;	
			if($lan=="en"){	
				$address=$request->request->get('name').",".$state[0]['state'].",".$country[0]['country'];	
			}else{
				$address=$request->request->get('name').",".$request->request->get('province').",".$request->request->get('country');	
			}
			if(!empty($check_content)){				
				$repository_content = $em->getRepository('MytripAdminBundle:DestinationContent');		 
				$check_contents=$repository_content->findOneByDestinationContentId($check_content['0']['destinationContentId']);
				$check_contents->setName($request->request->get('name'));
				$check_contents->setDestination($this->getDoctrine()->getRepository('MytripAdminBundle:Destination')->find($id));
				$check_contents->setDescription($request->request->get('description'));
				$check_contents->setLocationDesc($request->request->get('location_desc'));	
				$check_contents->setAddress($address);	
				$check_contents->setCity($request->request->get('name'));
				if($lan=="en"){	
					$check_contents->setProvince($state[0]['state']);	
					$check_contents->setCountry($country[0]['country']);
				}else{
					$check_contents->setProvince($request->request->get('province'));	
					$check_contents->setCountry($request->request->get('country'));
				}
				$check_contents->setMetaTitle($request->request->get('metatitle'));
				$check_contents->setMetaDescription($request->request->get('metadescription'));
				$check_contents->setMetaKeyword($request->request->get('metakeyword'));				
			}else{
				$destination_content = new DestinationContent();		
				$destination_content->setName($request->request->get('name'));
				$destination_content->setDestination($this->getDoctrine()->getRepository('MytripAdminBundle:Destination')->find($id));
				$destination_content->setDescription($request->request->get('description'));
				$destination_content->setLocationDesc($request->request->get('location_desc'));	
				$destination_content->setAddress($address);	
				$destination_content->setCity($request->request->get('name'));	
				$destination_content->setProvince($request->request->get('province'));	
				$destination_content->setCountry($request->request->get('country'));		
				$destination_content->setMetaTitle($request->request->get('metatitle'));
				$destination_content->setMetaDescription($request->request->get('metadescription'));
				$destination_content->setMetaKeyword($request->request->get('metakeyword'));
				$destination_content->setLan($lan);	
				$em->persist($destination_content);				
			}
			$em->flush();
			
			$em =  $this->getDoctrine()->getManager();
			/******Destination feature update******/
			if($request->request->get('feature')!=''){				
				$em->createQuery("DELETE FROM MytripAdminBundle:DestinationFeature b WHERE b.destination =$id")->execute();
				$d_feature=$request->request->get('feature');
				foreach($d_feature as $features){
					$em =  $this->getDoctrine()->getManager();
					$destination_feature = new DestinationFeature();
					$destination_feature->setDestination($this->getDoctrine()->getRepository('MytripAdminBundle:Destination')->find($id));
					$destination_feature->setFeature($this->getDoctrine()->getRepository('MytripAdminBundle:Feature')->find($features));
					$destination_feature->setCreatedDate(new \DateTime(date('Y-m-d H:i:s')));				
					$em->persist($destination_feature);
					$em->flush();
					unset($destination_feature);
				}
			}
			
			/**********Destination Image update********/
			if(($request->files->get('image')!='')){
				$destination_image = new DestinationImage();
				$em =  $this->getDoctrine()->getManager();
				$checkimage_query=$em->createQuery("SELECT p FROM MytripAdminBundle:DestinationImage p WHERE p.destination=".$id);			
				$checkimage = $checkimage_query->getArrayResult();
				
				$awsAccessKey = $this->container->get('mytrip_admin.helper.amazon')->getOption('awsAccessKey');
				$awsSecretKey = $this->container->get('mytrip_admin.helper.amazon')->getOption('awsSecretKey');
				$bucket = $this->container->get('mytrip_admin.helper.amazon')->getOption('bucket');
				\S3::setAuth($awsAccessKey, $awsSecretKey);
				
				if(!empty($checkimage)){
					$deleteobject=\S3::deleteObject($bucket, $checkimage[0]['image']);					
					/*$file_path="img/destination/".$checkimage[0]['image'];
					if($checkimage[0]['image']!='' && file_exists($file_path)){
						unlink($file_path);
					}*/
					 $em->createQuery("DELETE FROM MytripAdminBundle:DestinationImage b WHERE b.destination =$id")->execute();
				}
				$destination_image->setDestination($this->getDoctrine()->getRepository('MytripAdminBundle:Destination')->find($id));
				$ext=$request->files->get('image')->getClientOriginalExtension() ;
				$filename=$this->str_rand(8,"alphanum").".".$ext;
				//$request->files->get('image')->move("img/destination",$filename);
				$tmpfile=$request->files->get('image')->getPathName() ;
				$putobject=\S3::putObjectFile($tmpfile, $bucket, $filename, \S3::ACL_PUBLIC_READ);
				if($putobject){	
					$destination_image->setImage($filename);
					$destination_image->setCreatedDate(new \DateTime(date('Y-m-d H:i:s')));					
					$em->persist($destination_image);
					$em->flush();	
				}
			}
			
			$this->get('session')->getFlashBag()->add('error','<div class="success msg">Destination successfully updated</div>');
			return $this->redirect($this->generateUrl('mytrip_admin_editdestination',array('id'=>$id,'lan'=>$lan)));
		}
		
		/******Fetch language********/
		$query = $em->createQuery("SELECT p FROM MytripAdminBundle:Language  p ");			
		$language = $query->getArrayResult();
		
		/*******Fetch destination details*****/
		$destination_query = $em->createQuery("SELECT p,IDENTITY(p.country) AS country,IDENTITY(p.province) AS province FROM MytripAdminBundle:Destination  p WHERE p.destinationId=".$id);			
		$destination = $destination_query->getArrayResult();
		if(empty($destination)){
			return $this->redirect($this->generateUrl('mytrip_admin_destination'));
		}
		
		/*******Fetch Destination content details*****/		
		$destination_content_query = $em->createQuery("SELECT d FROM MytripAdminBundle:DestinationContent d WHERE d.destination=".$id." AND d.lan='".$lan."'");			
		$destination_content = $destination_content_query->getArrayResult();
			
		if(empty($destination_content)){
			$destination_content_query = $em->createQuery("SELECT d FROM MytripAdminBundle:DestinationContent d WHERE d.destination=".$id." AND d.lan='en'");			
			$destination_content = $destination_content_query->getArrayResult();
		}
		
		$feature_query = $em->createQuery("SELECT f FROM MytripAdminBundle:Feature f" );		
		$feature=$feature_query->getArrayResult();
		
		$country_query = $em->createQuery("SELECT c FROM MytripAdminBundle:Country c");			
	    $country = $country_query->getArrayResult();
		
		$state_query = $em->createQuery("SELECT c FROM MytripAdminBundle:States c WHERE c.cid=".$destination['0']['country']);			
	    $state = $state_query->getArrayResult();
		
		$destination_image_query = $em->createQuery("SELECT d FROM MytripAdminBundle:DestinationImage d WHERE d.destination='".$destination[0][0]['destinationId']."'" );		
		$destination_image=$destination_image_query->getArrayResult();
		
		$destination_feature_query = $em->createQuery("SELECT IDENTITY(f.feature) AS feature FROM MytripAdminBundle:DestinationFeature f WHERE f.destination=$id");			
	    $destination_feature = $destination_feature_query->getArrayResult();
		
		return $this->render('MytripAdminBundle:Default:editdestination.html.php',array('language'=>$language,'destination'=>$destination,'destination_content'=>$destination_content,'feature'=>$feature,'country'=>$country,'destination_image'=>$destination_image,'destination_feature'=>$destination_feature,'state'=>$state));
		
	}
	
	/**********Destination Hostal List********/
	public function destinationhostalsAction(Request $request){
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		$em =  $this->getDoctrine()->getManager();
		
		if($request->getMethod()=="POST"){
			if($request->request->get('searchname')!=''){
				$searchcontent['name']=$request->request->get('searchname');
			}			 	
			if(!empty($searchcontent)){	
				$searchcontent['search']=1;			
				return $this->redirect($this->generateUrl('mytrip_admin_hostal',$searchcontent));			
			} 
		 }
		 
		  /****** Search Query params checking**********/		
		 if($request->query->get('search')!=''){
			 $where=array();
			if($request->query->get('name')!=''){
				$where[]="h.name LIKE '%".$request->query->get('name')."%'"; 
			}
			
			if(!empty($where)){
				$wherequery=implode(" AND ",$where);
				$sql="SELECT h FROM MytripAdminBundle:Hostal h WHERE ".$wherequery." AND h.destination='".$_REQUEST['id']."' AND h.status NOT IN ('Trash') ORDER BY h.hostalId DESC";
			}else{
				 $sql="SELECT h FROM MytripAdminBundle:Hostal h WHERE h.destination='".$_REQUEST['id']."' AND h.status NOT IN ('Trash')    ORDER BY h.hostalId DESC";
			}
		 }else{			 
			  $sql="SELECT h FROM MytripAdminBundle:Hostal h WHERE h.destination='".$_REQUEST['id']."' AND h.status NOT IN ('Trash')  ORDER BY h.hostalId DESC";
		 }
		 
		//echo $sql;exit;
		/*****Pagnation****/
		$query = $em->createQuery($sql);		
		$paginator  = $this->get('knp_paginator');
		$pagination = $paginator->paginate(
			$query,
			$this->get('request')->query->get('page', 1)/*page number*/,
			10/*limit per page*/
		);					
		return $this->render('MytripAdminBundle:Default:destinationhostals.html.php', array('pagination' => $pagination,'urlrequest'=>$this->geturlrequest($request),'pagerequest'=>$this->getrequestarray($request),'sortingrequest'=>$this->getsortrequest($request)));	
		
	}
	
	/**********comments List********/
	public function commentsAction(Request $request){		
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		$em =  $this->getDoctrine()->getManager();
		
		if($request->getMethod()=="POST"){
			if($request->request->get('searchname')!=''){
				$searchcontent['name']=$request->request->get('searchname');
			}
			if($request->request->get('searchtype')!=''){
				$searchcontent['type']=$request->request->get('searchtype');
			}
			if($request->request->get('searchtypename')!=''){
				$searchcontent['typename']=$request->request->get('searchtypename');
			}			 	
			if(!empty($searchcontent)){	
				$searchcontent['search']=1;							
				return $this->redirect($this->generateUrl('mytrip_admin_comments',$searchcontent));			
			} 
		 }
		 
		  /****** Search Query params checking**********/		
		 if($request->query->get('search')!=''){
			 $where=array();
			if($request->query->get('name')!=''){
				$user=$em->createQuery("SELECT d.userId FROM MytripAdminBundle:User d WHERE d.email='".$request->query->get('name')."'" )->getArrayResult();
				$tarray= $this->container->get('mytrip_admin.helper.date')->array_column($user, 'userId');		
				if(!empty($tarray)){		
					$where[]=" h.user IN(".implode(",",$tarray).") ";
				}else{
					$where[]=" h.user IN(0) ";
				}				
			}
			if($request->query->get('type')!=''){
				if($request->query->get('typename')!=''){
					if($request->query->get('type')=="Destination"){
						$destination=$em->createQuery("SELECT d.destinationId FROM MytripAdminBundle:Destination d WHERE d.name LIKE '%".$request->query->get('typename')."%'" )->getArrayResult();
						$tarray= $this->container->get('mytrip_admin.helper.date')->array_column($destination, 'destinationId');						
					}else{
						$hostal=$em->createQuery("SELECT d.hostalId FROM MytripAdminBundle:Hostal d WHERE d.name LIKE '%".$request->query->get('typename')."%'" )->getArrayResult();
						$tarray= $this->container->get('mytrip_admin.helper.date')->array_column($hostal, 'destinationId');	
					}
					if(!empty($tarray)){		
						$where[]=" h.typeId IN(".implode(",",$tarray).") ";
					}else{
						$where[]=" h.typeId IN(0) ";
					}
				}
				$where[]=" h.reviewType LIKE '".$request->query->get('type')."' "; 
			}
			
			if(!empty($where)){
				$wherequery=implode(" AND ",$where);
				$sql="SELECT h,IDENTITY(h.user) AS user  FROM MytripAdminBundle:Review h WHERE ".$wherequery." AND h.status NOT IN ('Trash') ORDER BY h.reviewId DESC";
			}else{
				  $sql="SELECT h,IDENTITY(h.user) AS user FROM MytripAdminBundle:Review h WHERE h.status NOT IN ('Trash') ORDER BY h.reviewId DESC";
			}
		 }else{			 
			  $sql="SELECT h,IDENTITY(h.user) AS user FROM MytripAdminBundle:Review h WHERE h.status NOT IN ('Trash') ORDER BY h.reviewId DESC";
		 }
		 
		//echo $sql;exit;
		/*****Pagnation****/
		$query = $em->createQuery($sql);		
		$paginator  = $this->get('knp_paginator');
		$pagination = $paginator->paginate(
			$query,
			$this->get('request')->query->get('page', 1)/*page number*/,
			10/*limit per page*/
		);					
		return $this->render('MytripAdminBundle:Default:comments.html.php', array('pagination' => $pagination,'urlrequest'=>$this->geturlrequest($request),'pagerequest'=>$this->getrequestarray($request),'sortingrequest'=>$this->getsortrequest($request)));
	}
	
	/*****View Comments********/
	public function viewcommentsAction(Request $request){
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		
		 $session = $request->getSession();
		 $id=$request->query->get('id');
		 
		 $em =  $this->getDoctrine()->getManager();
		 $repository = $em->getRepository('MytripAdminBundle:Review');
		 
		 $comments=$em->createQuery("SELECT p,IDENTITY(p.user) AS user FROM MytripAdminBundle:Review p WHERE p.reviewId='".$id."'")->getArrayResult();;
		 	
		 $check=$repository->findOneByReviewId($id);		
		 if($request->getMethod()=="POST"){			  												
			$check->setStatus($request->get('status')); 			
			$em->flush();
			$this->get('session')->getFlashBag()->add('error','<div class="success msg">Review has been successfully updated</div>');
			return $this->redirect($this->generateUrl('mytrip_admin_viewcomments',array('id'=>$id)));						
		 }	
		  
		 return $this->render('MytripAdminBundle:Default:viewcomments.html.php',array('comment'=>$comments));
	}
	
	/*******Delete comments***********/
	public function deletecommentsAction(Request $request){	
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		 
		 $em =  $this->getDoctrine()->getManager();	
		 
		 /*****Single comments delete*******/
		 $id=$request->query->get('id');		
		 if($id!='' && $id>0){
			 $delete = $em->createQuery("DELETE FROM MytripAdminBundle:Review u WHERE u.reviewId =$id")->execute();
			 $this->get('session')->getFlashBag()->add('error','<div class="success msg">Review deleted successfully</div>');
			 if($request->server->get('HTTP_REFERER')!=''){		
				return $this->redirect($request->server->get('HTTP_REFERER'));
			 }else{
				return $this->redirect($this->generateUrl('mytrip_admin_comments'));
			 }
		 }
		 
		 /********Multiple comments delete**********/
		 if($request->getMethod()=="POST"){	
			 $reviewid=implode(",",$request->request->get('action'));
			 $delete = $em->createQuery("DELETE FROM MytripAdminBundle:Review u WHERE u.reviewId IN (".$reviewid.")")->execute();
			 $this->get('session')->getFlashBag()->add('error','<div class="success msg">Reviews deleted successfully</div>');
			 if($request->server->get('HTTP_REFERER')!=''){		
				return $this->redirect($request->server->get('HTTP_REFERER'));
			 }else{
				return $this->redirect($this->generateUrl('mytrip_admin_comments'));
			 }		 
		 }
		 
		 if($id=='' && $request->getMethod()!="POST"){
			 $this->get('session')->getFlashBag()->add('error','<div class="error msg">Sorry, Reviews record not available</div>');
			 if($request->server->get('HTTP_REFERER')!=''){		
				return $this->redirect($request->server->get('HTTP_REFERER'));
			 }else{
				return $this->redirect($this->generateUrl('mytrip_admin_comments'));
			 }	
		 }		 
	}
	
	/**********Users List********/
	public function usersAction(Request $request){		
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		$em =  $this->getDoctrine()->getManager();
		
		if($request->getMethod()=="POST"){
			if($request->request->get('searchname')!=''){
				$searchcontent['name']=$request->request->get('searchname');
			}
			if($request->request->get('searchemail')!=''){
				$searchcontent['email']=$request->request->get('searchemail');
			}
			if($request->request->get('searchmobile')!=''){
				$searchcontent['mobile']=$request->request->get('searchmobile');
			}			 	
			if(!empty($searchcontent)){	
				$searchcontent['search']=1;							
				return $this->redirect($this->generateUrl('mytrip_admin_users',$searchcontent));			
			} 
		 }
		 
		  /****** Search Query params checking**********/		
		 if($request->query->get('search')!=''){
			 $where=array();			
			if($request->query->get('name')!=''){
				$where[]=" CONCAT(h.firstname,' ',h.lastname) LIKE '%".$request->query->get('name')."%'";				
			}
			if($request->query->get('email')!=''){
				$where[]="h.email LIKE '%".$request->query->get('email')."%'";				
			}
			if($request->query->get('mobile')!=''){
				$where[]="h.mobile ='".$request->query->get('mobile')."'";				
			}			
			
			if(!empty($where)){
				$wherequery=implode(" AND ",$where);
				$sql="SELECT h FROM MytripAdminBundle:User h WHERE ".$wherequery." AND h.status NOT IN ('Trash') ORDER BY h.userId DESC";
			}else{
				  $sql="SELECT h FROM MytripAdminBundle:User h WHERE h.status NOT IN ('Trash') ORDER BY h.userId DESC";
			}
		 }else{			 
			  $sql="SELECT h FROM MytripAdminBundle:User h WHERE h.status NOT IN ('Trash') ORDER BY h.userId DESC";
		 }
		 
		//echo $sql;exit;
		/*****Pagnation****/
		$query = $em->createQuery($sql);		
		$paginator  = $this->get('knp_paginator');
		$pagination = $paginator->paginate(
			$query,
			$this->get('request')->query->get('page', 1)/*page number*/,
			10/*limit per page*/
		);					
		return $this->render('MytripAdminBundle:Default:users.html.php', array('pagination' => $pagination,'urlrequest'=>$this->geturlrequest($request),'pagerequest'=>$this->getrequestarray($request),'sortingrequest'=>$this->getsortrequest($request)));
	}
	
	/*****View User********/
	public function viewusersAction(Request $request){
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		
		 $session = $request->getSession();
		 $id=$request->query->get('id');
		 
		 $em =  $this->getDoctrine()->getManager();
		 $repository = $em->getRepository('MytripAdminBundle:User');
		 
		 $user=$em->createQuery("SELECT p FROM MytripAdminBundle:User p WHERE p.userId='".$id."'")->getArrayResult();;
		 	
		 $check=$repository->findOneByUserId($id);		
		 if($request->getMethod()=="POST"){			  												
			$check->setStatus($request->get('status')); 			
			$em->flush();
			$this->get('session')->getFlashBag()->add('error','<div class="success msg">User has been successfully updated</div>');
			return $this->redirect($this->generateUrl('mytrip_admin_viewusers',array('id'=>$id)));						
		 }	
		  
		 return $this->render('MytripAdminBundle:Default:viewusers.html.php',array('user'=>$user));
	}
	
	/*******Delete comments***********/
	public function deleteusersAction(Request $request){	
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		 $id=$request->query->get('id');	
		 $em =  $this->getDoctrine()->getManager();	 
		 $user=$em->createQuery("SELECT p FROM MytripAdminBundle:User p WHERE p.userId='".$id."' AND p.status NOT IN ('Trash')")->getArrayResult();
		 if(empty($user)){
			return $this->redirect($this->generateUrl('mytrip_admin_users')); 
		 }
		 $comments=$em->createQuery("SELECT p FROM MytripAdminBundle:Review p WHERE p.user='".$id."'")->getArrayResult();
		 foreach($comments as $comment){
			 /*****Single comments delete*******/
			 $ids=$comment['reviewId'];					
			 if($ids!='' && $ids>0){
				 $delete = $em->createQuery("DELETE FROM MytripAdminBundle:Review u WHERE u.reviewId =$ids")->execute();				 
			 }
		 }
		 $em->createQuery("UPDATE MytripAdminBundle:User p SET p.status='Trash' WHERE p.userId='".$id."'")->execute();	
		
		 $this->get('session')->getFlashBag()->add('error','<div class="success msg">User deleted successfully</div>');
		 if($request->server->get('HTTP_REFERER')!=''){		
			return $this->redirect($request->server->get('HTTP_REFERER'));
		 }else{
			return $this->redirect($this->generateUrl('mytrip_admin_users'));
		 }
	}
	
	/**********confirm booking List********/
	public function confirmbookingAction(Request $request){		
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		$em =  $this->getDoctrine()->getManager();
		
		if($request->getMethod()=="POST"){
			if($request->request->get('searchref')!=''){
				$searchcontent['ref']=$request->request->get('searchref');
			}
			if($request->request->get('searchemail')!=''){
				$searchcontent['email']=$request->request->get('searchemail');
			}
			if($request->request->get('cdate')!=''){
				$searchcontent['cdate']=$request->request->get('cdate');
			}
			if($request->request->get('edate')!=''){
				$searchcontent['edate']=$request->request->get('edate');
			}
						 	
			if(!empty($searchcontent)){	
				$searchcontent['search']=1;							
				return $this->redirect($this->generateUrl('mytrip_admin_confirm_booking',$searchcontent));			
			} 
		 }
		 
		  /****** Search Query params checking**********/		
		 if($request->query->get('search')!=''){
			 $where=array();
			if($request->query->get('ref')!=''){
				$ref=explode("-",$request->query->get('ref'));
				$refno=$ref[1]/1024;						
				$where[]=" b.bookingId=".$refno;						
			}
			if($request->query->get('email')!=''){
				$user=$em->createQuery("SELECT d.userId FROM MytripAdminBundle:User d WHERE d.email LIKE '%".$request->query->get('email')."%'" )->getArrayResult();
				$tarray= $this->container->get('mytrip_admin.helper.date')->array_column($user, 'userId');	
					
				if(!empty($tarray)){		
					$where[]=" b.user IN(".implode(",",$tarray).") ";
				}else{
					$where[]=" b.user IN(0) ";
				}
			}
			
			if($request->query->get('edate')!='' && $request->query->get('cdate')!=''){
				$where[]="b.fromDate >= '".$this->container->get('mytrip_admin.helper.date')->format($request->query->get('cdate'))."' AND b.toDate <= '".$this->container->get('mytrip_admin.helper.date')->format($request->query->get('edate'))."'";				
			}else{
				if($request->query->get('cdate')!=''){
					$where[]="b.fromDate= '".$this->container->get('mytrip_admin.helper.date')->format($request->query->get('cdate'))."'";
				}
				if($request->query->get('edate')!=''){
					$where[]="b.toDate= '".$this->container->get('mytrip_admin.helper.date')->format($request->query->get('edate'))."'";
				}
			}
			
			if(!empty($where)){
				$wherequery=implode(" AND ",$where);
				$sql="SELECT b,IDENTITY(b.user) AS users,IDENTITY(b.hostal) AS hostals FROM MytripAdminBundle:Booking b WHERE ".$wherequery." AND b.status = 'Confirmed' ORDER BY b.bookingId DESC";
			}else{
				  $sql="SELECT b,IDENTITY(b.user) AS users,IDENTITY(b.hostal) AS hostals FROM MytripAdminBundle:Booking b WHERE b.status = 'Confirmed' ORDER BY b.bookingId DESC";
			}
		 }else{			 
			  $sql="SELECT b,IDENTITY(b.user) AS users,IDENTITY(b.hostal) AS hostals FROM MytripAdminBundle:Booking b WHERE b.status = 'Confirmed' ORDER BY b.bookingId DESC";
		 }
		 
		//echo $sql;exit;
		/*****Pagnation****/
		$query = $em->createQuery($sql);	
		
		$paginator  = $this->get('knp_paginator');
		$pagination = $paginator->paginate(
			$query,
			$this->get('request')->query->get('page', 1)/*page number*/,
			10/*limit per page*/
		);					
		return $this->render('MytripAdminBundle:Default:confirmbooking.html.php', array('pagination' => $pagination,'urlrequest'=>$this->geturlrequest($request),'pagerequest'=>$this->getrequestarray($request),'sortingrequest'=>$this->getsortrequest($request)));
	}
	
	/**********cancel booking List********/
	public function cancelbookingAction(Request $request){		
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		$em =  $this->getDoctrine()->getManager();
		
		if($request->getMethod()=="POST"){
			if($request->request->get('searchref')!=''){
				$searchcontent['ref']=$request->request->get('searchref');
			}
			if($request->request->get('searchemail')!=''){
				$searchcontent['email']=$request->request->get('searchemail');
			}
			if($request->request->get('cdate')!=''){
				$searchcontent['cdate']=$request->request->get('cdate');
			}
			if($request->request->get('edate')!=''){
				$searchcontent['edate']=$request->request->get('edate');
			}
						 	
			if(!empty($searchcontent)){	
				$searchcontent['search']=1;							
				return $this->redirect($this->generateUrl('mytrip_admin_cancel_booking',$searchcontent));			
			} 
		 }
		 
		  /****** Search Query params checking**********/		
		 if($request->query->get('search')!=''){
			 $where=array();
			if($request->query->get('ref')!=''){
				$ref=explode("-",$request->query->get('ref'));
				$refno=$ref[1]/1024;						
				$where[]=" b.bookingId=".$refno;						
			}
			if($request->query->get('email')!=''){
				$user=$em->createQuery("SELECT d.userId FROM MytripAdminBundle:User d WHERE d.email LIKE '%".$request->query->get('email')."%'" )->getArrayResult();
				$tarray= $this->container->get('mytrip_admin.helper.date')->array_column($user, 'userId');	
					
				if(!empty($tarray)){		
					$where[]=" b.user IN(".implode(",",$tarray).") ";
				}else{
					$where[]=" b.user IN(0) ";
				}
			}
			
			if($request->query->get('edate')!='' && $request->query->get('cdate')!=''){
				$where[]="b.fromDate >= '".$this->container->get('mytrip_admin.helper.date')->format($request->query->get('cdate'))."' AND b.toDate <= '".$this->container->get('mytrip_admin.helper.date')->format($request->query->get('edate'))."'";				
			}else{
				if($request->query->get('cdate')!=''){
					$where[]="b.fromDate= '".$this->container->get('mytrip_admin.helper.date')->format($request->query->get('cdate'))."'";
				}
				if($request->query->get('edate')!=''){
					$where[]="b.toDate= '".$this->container->get('mytrip_admin.helper.date')->format($request->query->get('edate'))."'";
				}
			}
			
			if(!empty($where)){
				$wherequery=implode(" AND ",$where);
				$sql="SELECT b,IDENTITY(b.user) AS users,IDENTITY(b.hostal) AS hostals FROM MytripAdminBundle:Booking b WHERE ".$wherequery." AND b.status = 'Cancelled' ORDER BY b.bookingId DESC";
			}else{
				  $sql="SELECT b,IDENTITY(b.user) AS users,IDENTITY(b.hostal) AS hostals FROM MytripAdminBundle:Booking b WHERE b.status = 'Cancelled' ORDER BY b.bookingId DESC";
			}
		 }else{			 
			  $sql="SELECT b,IDENTITY(b.user) AS users,IDENTITY(b.hostal) AS hostals FROM MytripAdminBundle:Booking b WHERE b.status = 'Cancelled' ORDER BY b.bookingId DESC";
		 }
		 
		//echo $sql;exit;
		/*****Pagnation****/
		$query = $em->createQuery($sql);	
		
		$paginator  = $this->get('knp_paginator');
		$pagination = $paginator->paginate(
			$query,
			$this->get('request')->query->get('page', 1)/*page number*/,
			10/*limit per page*/
		);					
		return $this->render('MytripAdminBundle:Default:cancelbooking.html.php', array('pagination' => $pagination,'urlrequest'=>$this->geturlrequest($request),'pagerequest'=>$this->getrequestarray($request),'sortingrequest'=>$this->getsortrequest($request)));
	}
	
	/*****View Booking********/
	public function viewbookingAction(Request $request){
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		
		 $session = $request->getSession();
		 $id=$request->query->get('id');
		 
		 $em =  $this->getDoctrine()->getManager();		 
		 $booking=$em->createQuery("SELECT p,IDENTITY(p.hostal) AS hostal  FROM MytripAdminBundle:Booking p WHERE p.bookingId='".$id."'")->getArrayResult();
		  
		 return $this->render('MytripAdminBundle:Default:viewbooking.html.php',array('booking'=>$booking));
	}
	
	
	/*******Delete Destination**********/
	public function deletedestinationAction(Request $request){	
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		 
		 $em =  $this->getDoctrine()->getManager();	
		 
		 /*****Single feature delete*******/
		 $id=$request->query->get('id');
		 if($id!='' && $id>0){
			 $em->createQuery("UPDATE MytripAdminBundle:Destination d SET d.status='Trash' WHERE d.destinationId =$id")->execute();
			 $em->createQuery("UPDATE MytripAdminBundle:Hostal h SET h.status='Trash' WHERE h.destination =$id")->execute();
			 $this->get('session')->getFlashBag()->add('error','<div class="success msg">Destination deleted successfully</div>');
			  if($request->server->get('HTTP_REFERER')!=''){		
				return $this->redirect($request->server->get('HTTP_REFERER'));
			 }else{
				return $this->redirect($this->generateUrl('mytrip_admin_destination'));
			 }
		 }
		 
		 /********Multiple Admin delete**********/
		 if($request->getMethod()=="POST"){	
			 $desid=implode(",",$request->request->get('action'));
			 $em->createQuery("UPDATE MytripAdminBundle:Destination d SET d.status='Trash' WHERE d.destinationId IN (".$desid.")")->execute();
			 $em->createQuery("UPDATE MytripAdminBundle:Hostal h SET h.status='Trash' WHERE h.destination IN (".$desid.")")->execute();
			 $this->get('session')->getFlashBag()->add('error','<div class="success msg">Destination deleted successfully</div>');
			  if($request->server->get('HTTP_REFERER')!=''){		
				return $this->redirect($request->server->get('HTTP_REFERER'));
			 }else{
				return $this->redirect($this->generateUrl('mytrip_admin_destination'));
			 }			 
		 }
		 
		 if($id=='' && $request->getMethod()!="POST"){
			 $this->get('session')->getFlashBag()->add('error','<div class="error msg">Sorry, Destination record not available</div>');
			return $this->redirect($this->generateUrl('mytrip_admin_destination'));
		 }	
	
		 $this->get('session')->getFlashBag()->add('error','<div class="error msg">Sorry, This feature work going on.</div>');
		 return $this->redirect($this->generateUrl('mytrip_admin_destination'));
	}
	
	/********Add Banner for destination, hostal and story*********/
	public function bannerAction(Request $request){
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		$type=$this->container->get('request')->get('type');
		$id=$this->container->get('request')->get('id');
		
		if(empty($type) || empty($id)){
			return $this->redirect($this->generateUrl('mytrip_admin_homepage'));
		}
		
		$em =  $this->getDoctrine()->getManager();		
		$render_array=array();
		if($type=="destination"){
			$destination_query = $em->createQuery("SELECT d FROM MytripAdminBundle:Destination d WHERE d.destinationId=$id" );		
			$destination=$destination_query->getArrayResult();
			$render_array=$destination;
		}elseif($type=="hostal"){
			$hostal_query = $em->createQuery("SELECT h FROM MytripAdminBundle:Hostal h WHERE h.hostalId=$id" );		
			$hostal=$hostal_query->getArrayResult();
			$render_array=$hostal;
		}elseif($type=="story"){
			$story_query = $em->createQuery("SELECT h FROM MytripAdminBundle:Story h WHERE h.storyId=$id" );		
			$story=$story_query->getArrayResult();
			$render_array=$story;
		}
		
		if($request->getMethod()=="POST"){
			$banner = new Banner();
			if(($request->files->get('image')!='')){				
				$awsAccessKey = $this->container->get('mytrip_admin.helper.amazon')->getOption('awsAccessKey');
				$awsSecretKey = $this->container->get('mytrip_admin.helper.amazon')->getOption('awsSecretKey');
				$bucket = $this->container->get('mytrip_admin.helper.amazon')->getOption('bucket');
				\S3::setAuth($awsAccessKey, $awsSecretKey);				
				$em =  $this->getDoctrine()->getManager();
				$banner->setBannerType(ucfirst($type));
				$banner->setTypeId($id);
				$ext=$request->files->get('image')->getClientOriginalExtension() ;
				$filename=$this->str_rand(8,"alphanum").".".$ext;
				$tmpfile=$request->files->get('image')->getPathName() ;
				$putobject=\S3::putObjectFile($tmpfile, $bucket, $filename, \S3::ACL_PUBLIC_READ);
				if($putobject){				
					//$request->files->get('image')->move("img/banner/".$type,$filename);
					$banner->setImage($filename);
					$banner->setStatus('Active');					
					$em->persist($banner);
					$em->flush();	
				}else{
					$this->get('session')->getFlashBag()->add('error','<div class="success msg">Something problem to upload image to server</div>');
				}
			}
			
			$this->get('session')->getFlashBag()->add('error','<div class="success msg">Banner image successfully added</div>');
			return $this->redirect($this->generateUrl('mytrip_admin_banner',array('type'=>$type,'id'=>$id)));	
			
		}
		
		if(empty($render_array)){
			return $this->redirect($this->generateUrl('mytrip_admin_homepage'));
		}
		$em =  $this->getDoctrine()->getManager();
		$banner_query = $em->createQuery("SELECT b FROM MytripAdminBundle:Banner b WHERE b.typeId=$id AND b.bannerType='".ucfirst($type)."'" );		
		$banner=$banner_query->getArrayResult();		
		
		$render_array=array('banner'=>$banner,$type=>$render_array);
		
		return $this->render('MytripAdminBundle:Default:banner.html.php',$render_array);
	}
	
	
	
	/********Delete Banner for destination, hostal and story*********/
	public function deletebannerAction(Request $request){
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		$id=$this->container->get('request')->get('id');
		$type=$this->container->get('request')->get('type');
		if(empty($id)){
			return $this->redirect($this->generateUrl('mytrip_admin_homepage'));
		}
		$em =  $this->getDoctrine()->getManager();
		$banner_query = $em->createQuery("SELECT b FROM MytripAdminBundle:Banner b WHERE b.bannerId=$id" );		
		$banner=$banner_query->getArrayResult();
		
		if(!empty($banner)){
			$file_path="img/banner/".$type."/".$banner[0]['image'];
			if($banner[0]['image']!='' && file_exists($file_path)){
				unlink($file_path);
			}
			 $em->createQuery("DELETE FROM MytripAdminBundle:Banner b WHERE b.bannerId =$id")->execute();
			 $this->get('session')->getFlashBag()->add('error','<div class="success msg">Banner deleted successfully</div>');
			 return $this->redirect($this->generateUrl('mytrip_admin_banner',array('type'=>$type,'id'=>$banner['0']['typeId'])));
		}else{
			return $this->redirect($this->generateUrl('mytrip_admin_homepage'));
		}
		
	}
	
	/**********Hostal List********/
	public function hostalAction(Request $request){
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		$em =  $this->getDoctrine()->getManager();
		
		if($request->getMethod()=="POST"){
			if($request->request->get('searchname')!=''){
				$searchcontent['name']=$request->request->get('searchname');
			}			 	
			if(!empty($searchcontent)){	
				$searchcontent['search']=1;			
				return $this->redirect($this->generateUrl('mytrip_admin_hostal',$searchcontent));			
			} 
		 }
		 
		  /****** Search Query params checking**********/		
		 if($request->query->get('search')!=''){
			 $where=array();
			if($request->query->get('name')!=''){
				$where[]="h.name LIKE '%".$request->query->get('name')."%'"; 
			}
			
			if(!empty($where)){
				$wherequery=implode(" AND ",$where);
				$sql="SELECT h,(SELECT d.name FROM MytripAdminBundle:Destination d WHERE d.destinationId=h.destination ) AS destination FROM MytripAdminBundle:Hostal h WHERE ".$wherequery." AND h.status NOT IN ('Trash') AND  h.destination IN (SELECT ds.destinationId FROM MytripAdminBundle:Destination ds WHERE  ds.destinationId=h.destination AND ds.status NOT IN ('Trash')) ORDER BY h.hostalId DESC";
			}else{
				 $sql="SELECT h,(SELECT d.name FROM MytripAdminBundle:Destination d WHERE d.destinationId=h.destination ) AS destination FROM MytripAdminBundle:Hostal h  WHERE  h.status NOT IN ('Trash') AND  h.destination IN (SELECT ds.destinationId FROM MytripAdminBundle:Destination ds WHERE  ds.destinationId=h.destination AND ds.status NOT IN ('Trash'))   ORDER BY h.hostalId DESC";
			}
		 }else{			 
			$sql="SELECT h,(SELECT d.name FROM MytripAdminBundle:Destination d WHERE d.destinationId=h.destination ) AS destination FROM MytripAdminBundle:Hostal h WHERE h.status NOT IN ('Trash') AND h.destination IN (SELECT ds.destinationId FROM MytripAdminBundle:Destination ds WHERE  ds.destinationId=h.destination AND ds.status NOT IN ('Trash'))  ORDER BY h.hostalId DESC";
		 }
		 
		
		/*****Pagnation****/
		$query = $em->createQuery($sql);		
		$paginator  = $this->get('knp_paginator');
		$pagination = $paginator->paginate(
			$query,
			$this->get('request')->query->get('page', 1)/*page number*/,
			10/*limit per page*/
		);				
		return $this->render('MytripAdminBundle:Default:hostal.html.php', array('pagination' => $pagination,'urlrequest'=>$this->geturlrequest($request),'pagerequest'=>$this->getrequestarray($request),'sortingrequest'=>$this->getsortrequest($request)));	
		
	}
	
	/********Add Hostal*********/
	public function addhostalAction(Request $request){
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		$em =  $this->getDoctrine()->getManager();
		
		$hostal = new Hostal();
		$hostal_content = new HostalContent();
		$hostal_image = new HostalImage();
		//$hostal_rooms = new HostalRooms();
		$hostal_feature = new HostalFeature();
		$feature = new Feature();		
		$cancel = new CancelDefault();
		
		if($request->getMethod()=="POST"){	
			$page_link = strtolower(str_replace(' ','_',preg_replace('/[^a-zA-Z0-9_ -]/s', ' ', $request->request->get('name'))));
			if($page_link ==''){
				$page_link=strtolower(str_replace(array(' ','\''),array('_','-'), $request->request->get('name')));
			}
			
			
			
			// /********Checking duplicate hostal***********/
			// $check = $em->createQuery("SELECT h FROM MytripAdminBundle:Hostal h WHERE  h.url='".$page_link."' AND h.destination ='".$request->request->get('destination')."' AND  h.status NOT IN ('Trash') ");			
	  //       $check_hostal = $check->getArrayResult();				
					
			// if(empty($check_hostal)){
				$destination_query=$em->createQuery("SELECT d,IDENTITY(d.country) AS country, IDENTITY(d.province) AS province FROM MytripAdminBundle:Destination d WHERE d.destinationId='".$request->request->get('destination')."' AND d.status NOT IN ('Trash')");
				$destination = $destination_query->getArrayResult();				
							
				$country_query = $em->createQuery("SELECT c FROM MytripAdminBundle:Country c WHERE c.cid='".$destination['0']['country']."'");			
				$country = $country_query->getArrayResult();
				
				$state_query = $em->createQuery("SELECT s FROM MytripAdminBundle:States s WHERE s.sid='".$destination['0']['province']."'");			
				$state = $state_query->getArrayResult();
				
				$destination[0]=$destination[0][0];
				
				$address=$request->request->get('address').",".$country[0]['country'];
				$position=$this->getBoundLatitude_longitude($address);
				
				if(empty($position)){
					$longitude=$destination['0']['longitude'];
					$latitude=$destination['0']['latitude'];					
				}else{					
					$longitude=$position['longitude'];
					$latitude=$position['latitude'];
				}
				
				$hostal->setName($request->request->get('name'));
				$hostal->setDestination($this->getDoctrine()->getRepository('MytripAdminBundle:Destination')->find($request->request->get('destination')));
				$hostal->setUrl($page_link);
				$hostal->setOwnerEmail($request->request->get('owneremail'));
				$hostal->setCccode($request->request->get('cccode'));
				$hostal->setPhone($request->request->get('phone'));
				$hostal->setCmcode($request->request->get('cmcode'));
				$hostal->setMobile($request->request->get('mobile'));
				$hostal->setVideo($request->request->get('svdesc'));
				$hostal->setTripadvisor($request->request->get('tripadvisor'));				
				$hostal->setLongitude($longitude);
				$hostal->setLatitude($latitude);
				$hostal->setStatus('Active');
				$hostal->setCreatedDate(new \DateTime(date('Y-m-d H:i:s')));
				$hostal->setModifyDate(new \DateTime(date('Y-m-d H:i:s')));				
				$em->persist($hostal);
				$em->flush();
				
				$em =  $this->getDoctrine()->getManager();
				$lastid=$hostal->getHostalId();
				
				$hostal_content->setName($request->request->get('name'));
				$hostal_content->setHostal($this->getDoctrine()->getRepository('MytripAdminBundle:Hostal')->find($lastid));
				$hostal_content->setSmallDesc($request->request->get('smalldescription'));
				$hostal_content->setDescription($request->request->get('description'));
				$hostal_content->setLocationDesc($request->request->get('location_desc'));	
				$hostal_content->setAddress($address);	
				$hostal_content->setOwnerName($request->request->get('ownername'));	
				$hostal_content->setCity($destination[0]['name']);	
				$hostal_content->setProvince($state[0]['state']);	
				$hostal_content->setCountry($country[0]['country']);		
				$hostal_content->setMetaTitle($request->request->get('metatitle'));
				$hostal_content->setMetaDescription($request->request->get('metadescription'));
				$hostal_content->setMetaKeyword($request->request->get('metakeyword'));
				$hostal_content->setLan('en');	
				$em->persist($hostal_content);
				$em->flush();
				
				// $em =  $this->getDoctrine()->getManager();				
				// $hostal_rooms->setHostal($this->getDoctrine()->getRepository('MytripAdminBundle:Hostal')->find($lastid));
				// $hostal_rooms->setRooms($request->request->get('rooms'));
				// $hostal_rooms->setRoomtype($request->request->get('roomtype'));
				// $hostal_rooms->setGuests($request->request->get('guests'));
				// $hostal_rooms->setAdults($request->request->get('adults'));	
				// $hostal_rooms->setChild($request->request->get('child'));	
				// $hostal_rooms->setPrice($request->request->get('price'));				
				// $em->persist($hostal_rooms);
				// $em->flush();
				
				
				$h_feature=$request->request->get('feature');
				foreach($h_feature as $features){
					$em =  $this->getDoctrine()->getManager();
					$hostal_feature = new HostalFeature();
					$hostal_feature->setHostal($this->getDoctrine()->getRepository('MytripAdminBundle:Hostal')->find($lastid));
					$hostal_feature->setFeature($this->getDoctrine()->getRepository('MytripAdminBundle:Feature')->find($features));
					$hostal_feature->setCreatedDate(new \DateTime(date('Y-m-d H:i:s')));				
					$em->persist($hostal_feature);
					$em->flush();
					unset($hostal_feature);
				}
				
				if(($request->files->get('image')!='')){
					$em =  $this->getDoctrine()->getManager();
					$hostal_image->setHostal($this->getDoctrine()->getRepository('MytripAdminBundle:Hostal')->find($lastid));
					$ext=$request->files->get('image')->getClientOriginalExtension() ;
					$filename=$this->str_rand(8,"alphanum").".".$ext;
					//$request->files->get('image')->move("img/destination",$filename);
					$awsAccessKey = $this->container->get('mytrip_admin.helper.amazon')->getOption('awsAccessKey');
					$awsSecretKey = $this->container->get('mytrip_admin.helper.amazon')->getOption('awsSecretKey');
					$bucket = $this->container->get('mytrip_admin.helper.amazon')->getOption('bucket');
					\S3::setAuth($awsAccessKey, $awsSecretKey);	
					$tmpfile=$request->files->get('image')->getPathName() ;
					$putobject=\S3::putObjectFile($tmpfile, $bucket, $filename, \S3::ACL_PUBLIC_READ);
					if($putobject){	
					//$request->files->get('image')->move("img/hostal",$filename);
						$hostal_image->setImage($filename);
						$hostal_image->setCreatedDate(new \DateTime(date('Y-m-d H:i:s')));					
						$em->persist($hostal_image);
						$em->flush();
					}
				}
				
				$canceldetails=$em->createQuery("SELECT d FROM MytripAdminBundle:CancelDefault d")->getArrayResult();
				foreach($canceldetails as $canceldetails){
					$em =  $this->getDoctrine()->getManager();
					$hostal_cancel = new HostalCancelDetails();
					$hostal_cancel->setHostal($this->getDoctrine()->getRepository('MytripAdminBundle:Hostal')->find($lastid));
					$hostal_cancel->setDays($canceldetails['days']);
					$hostal_cancel->setPercentage($canceldetails['percentage']);					
					$em->persist($hostal_cancel);
					$em->flush();
					unset($hostal_cancel);
				}
				
				$this->get('session')->getFlashBag()->add('error','<div class="success msg">Hostal successfully added</div>');
				return $this->redirect($this->generateUrl('mytrip_admin_hostal'));
			// }
			// else{
			// 	$this->get('session')->getFlashBag()->add('error','<div class="error msg">Hostal already exists</div>');
			// }
		}
		
		$em =  $this->getDoctrine()->getManager();
		
		$feature_query = $em->createQuery("SELECT f FROM MytripAdminBundle:Feature f" );		
		$feature=$feature_query->getArrayResult();	
		
		$destination_query = $em->createQuery("SELECT f FROM MytripAdminBundle:Destination f WHERE f.status NOT IN ('Trash')" );		
		$destination=$destination_query->getArrayResult();			
		
		return $this->render('MytripAdminBundle:Default:addhostal.html.php',array('feature'=>$feature,'destination'=>$destination));	
		
	}
	
	/********Edit Hostal*********/
	public function edithostalAction(Request $request){
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		$em =  $this->getDoctrine()->getManager();
		$lan=$this->container->get('request')->get('lan');
		$id=$this->container->get('request')->get('id');
		
		$checkquery = $em->createQuery("SELECT p FROM MytripAdminBundle:Language  p WHERE p.lanCode='".$lan."'");			
		$checklanguage = $checkquery->getArrayResult();
		if(empty($checklanguage)){
			return $this->redirect($this->generateUrl('mytrip_admin_edithostal',array('id'=>$id,'lan'=>'en')));
		}
		
		if($request->getMethod()=="POST"){
			if($lan=="en"){
				$page_link = strtolower(str_replace(' ','_',preg_replace('/[^a-zA-Z0-9_ -]/s', ' ', $request->request->get('name'))));
				if($page_link ==''){
					$page_link=strtolower(str_replace(array(' ','\''),array('_','-'), $request->request->get('name')));
				}
				
				$em =  $this->getDoctrine()->getManager();
				
				/********Checking duplicate hostal***********/
				$check = $em->createQuery("SELECT d FROM MytripAdminBundle:Hostal d WHERE  d.url='".$page_link."' AND d.status NOT IN ('Trash') AND d.hostalId NOT IN ($id)  AND d.destination NOT IN (".$request->request->get('destination').")");			
				$check_hostal = $check->getArrayResult();				
						
				if(empty($check_hostal)){
					/***Hostal english language update***/	
					$repository = $em->getRepository('MytripAdminBundle:Hostal');		 
					$hostal=$repository->findOneByHostalId($id);
					$destination_query=$em->createQuery("SELECT d,IDENTITY(d.country) AS country, IDENTITY(d.province) AS province FROM MytripAdminBundle:Destination d WHERE d.destinationId='".$request->request->get('destination')."'");
					$destination = $destination_query->getArrayResult();				
								
					$country_query = $em->createQuery("SELECT c FROM MytripAdminBundle:Country c WHERE c.cid='".$destination['0']['country']."'");			
					$country = $country_query->getArrayResult();
					
					$state_query = $em->createQuery("SELECT s FROM MytripAdminBundle:States s WHERE s.sid='".$destination['0']['province']."'");			
					$state = $state_query->getArrayResult();
					
					$destination[0]=$destination[0][0];
					
					$address=$request->request->get('address').",".$country[0]['country'];
					$position=$this->getBoundLatitude_longitude($address);
					
					if(empty($position)){
						$longitude=$destination['0']['longitude'];
						$latitude=$destination['0']['latitude'];					
					}else{
						$longitude=$position['longitude'];
						$latitude=$position['latitude'];
					}
					
					$hostal->setName($request->request->get('name'));
					$hostal->setDestination($this->getDoctrine()->getRepository('MytripAdminBundle:Destination')->find($request->request->get('destination')));
					$hostal->setUrl($page_link);
					$hostal->setOwnerEmail($request->request->get('owneremail'));
					$hostal->setCccode($request->request->get('cccode'));
					$hostal->setPhone($request->request->get('phone'));
					$hostal->setCmcode($request->request->get('cmcode'));
					$hostal->setMobile($request->request->get('mobile'));
					$hostal->setVideo($request->request->get('svdesc'));
					$hostal->setTripadvisor($request->request->get('tripadvisor'));					
					$hostal->setLongitude($longitude);
					$hostal->setLatitude($latitude);
					$hostal->setStatus('Active');					
					$hostal->setModifyDate(new \DateTime(date('Y-m-d H:i:s')));	
				}else{
					$this->get('session')->getFlashBag()->add('error','<div class="success msg">Hostal already exists</div>');
					return $this->redirect($this->generateUrl('mytrip_admin_edithostal',array('id'=>$id,'lan'=>$lan)));
				}
			}
			
			/******Hostal content update with corresponding lanugage******/
			$em =  $this->getDoctrine()->getManager();
			$check=$em->createQuery("SELECT p FROM MytripAdminBundle:HostalContent p WHERE p.hostal=".$id." AND p.lan='".$lan."'");			
			$check_content = $check->getArrayResult();
			
			$hostalinfos=$em->createQuery("SELECT p,IDENTITY(p.destination) AS destination FROM MytripAdminBundle:Hostal p WHERE p.hostalId=".$id);			
			$hostalinfo = $hostalinfos->getArrayResult();
			
			$destination_content_query=$em->createQuery("SELECT p FROM MytripAdminBundle:DestinationContent p WHERE p.destination=".$hostalinfo['0']['destination']." AND p.lan='".$lan."'");			
			$destination_content = $destination_content_query->getArrayResult();
			
			if(empty($destination_content)){
				$destination_content_query=$em->createQuery("SELECT p FROM MytripAdminBundle:DestinationContent p WHERE p.destination=".$hostalinfo['0']['destination']." AND p.lan='en'");			
				$destination_content = $destination_content_query->getArrayResult();
			}
						
			if(!empty($check_content)){				
				$repository_content = $em->getRepository('MytripAdminBundle:HostalContent');		 
				$hostal_content=$repository_content->findOneByHostalContentId($check_content['0']['hostalContentId']);
				$hostal_content->setName($request->request->get('name'));
				$hostal_content->setHostal($this->getDoctrine()->getRepository('MytripAdminBundle:Hostal')->find($id));
				$hostal_content->setSmallDesc($request->request->get('smalldescription'));
				$hostal_content->setDescription($request->request->get('description'));
				$hostal_content->setLocationDesc($request->request->get('location_desc'));	
				$hostal_content->setAddress($request->request->get('address'));	
				$hostal_content->setOwnerName($request->request->get('ownername'));	
				if($lan=="en"){	
					$hostal_content->setCity($destination[0]['name']);
					$hostal_content->setProvince($state[0]['state']);	
					$hostal_content->setCountry($country[0]['country']);
				}else{
					$hostal_content->setCity($destination_content[0]['city']);
					$hostal_content->setProvince($destination_content[0]['province']);	
					$hostal_content->setCountry($destination_content[0]['country']);
				}						
				$hostal_content->setMetaTitle($request->request->get('metatitle'));
				$hostal_content->setMetaDescription($request->request->get('metadescription'));
				$hostal_content->setMetaKeyword($request->request->get('metakeyword'));
				$hostal_content->setLan($lan);								
			}else{
				$hostal_content = new HostalContent();		
				$hostal_content->setName($request->request->get('name'));
				$hostal_content->setHostal($this->getDoctrine()->getRepository('MytripAdminBundle:Hostal')->find($id));
				$hostal_content->setSmallDesc($request->request->get('smalldescription'));
				$hostal_content->setDescription($request->request->get('description'));
				$hostal_content->setLocationDesc($request->request->get('location_desc'));	
				$hostal_content->setAddress($request->request->get('address'));	
				$hostal_content->setOwnerName($request->request->get('ownername'));	
				$hostal_content->setCity($destination_content[0]['city']);
				$hostal_content->setProvince($destination_content[0]['province']);	
				$hostal_content->setCountry($destination_content[0]['country']);		
				$hostal_content->setMetaTitle($request->request->get('metatitle'));
				$hostal_content->setMetaDescription($request->request->get('metadescription'));
				$hostal_content->setMetaKeyword($request->request->get('metakeyword'));				
				$hostal_content->setLan($lan);	
				$em->persist($hostal_content);				
			}
			$em->flush();
			
			// if($lan=="en"){
			// 	$em =  $this->getDoctrine()->getManager();	
			// 	$checkroom_query=$em->createQuery("SELECT p FROM MytripAdminBundle:HostalRooms p WHERE p.hostal=".$id);			
			// 	$checkroom = $checkroom_query->getArrayResult();
			// 	if(!empty($checkroom)){
			// 		$repository_rooms = $em->getRepository('MytripAdminBundle:HostalRooms');		 
			// 		$hostal_rooms=$repository_rooms->findOneByRoomId($checkroom['0']['roomId']);
			// 	}else{
			// 		$hostal_rooms = new HostalRooms();
			// 	}
			// 	$hostal_rooms->setHostal($this->getDoctrine()->getRepository('MytripAdminBundle:Hostal')->find($id));
			// 	$hostal_rooms->setRooms($request->request->get('rooms'));
			// 	$hostal_rooms->setRoomtype($request->request->get('roomtype'));
			// 	$hostal_rooms->setGuests($request->request->get('guests'));
			// 	$hostal_rooms->setAdults($request->request->get('adults'));	
			// 	$hostal_rooms->setChild($request->request->get('child'));	
			// 	$hostal_rooms->setPrice($request->request->get('price'));
			// 	if(empty($checkimage)){				
			// 		$em->persist($hostal_rooms);
			// 	}
			// 	$em->flush();
			// }
			
			$em =  $this->getDoctrine()->getManager();
			/******Destination feature update******/
			if($request->request->get('feature')!=''){				
				$em->createQuery("DELETE FROM MytripAdminBundle:HostalFeature b WHERE b.hostal =$id")->execute();
				$d_feature=$request->request->get('feature');
				foreach($d_feature as $features){
					$em =  $this->getDoctrine()->getManager();
					$hostal_feature = new HostalFeature();
					$hostal_feature->setHostal($this->getDoctrine()->getRepository('MytripAdminBundle:Hostal')->find($id));
					$hostal_feature->setFeature($this->getDoctrine()->getRepository('MytripAdminBundle:Feature')->find($features));
					$hostal_feature->setCreatedDate(new \DateTime(date('Y-m-d H:i:s')));				
					$em->persist($hostal_feature);
					$em->flush();
					unset($hostal_feature);
				}
			}
			
			/**********Hostal Image update********/
			if(($request->files->get('image')!='')){
				$hostal_image = new HostalImage();
				$em =  $this->getDoctrine()->getManager();
				$checkimage_query=$em->createQuery("SELECT p FROM MytripAdminBundle:HostalImage p WHERE p.hostal=".$id);			
				$checkimage = $checkimage_query->getArrayResult();
				
				$awsAccessKey = $this->container->get('mytrip_admin.helper.amazon')->getOption('awsAccessKey');
				$awsSecretKey = $this->container->get('mytrip_admin.helper.amazon')->getOption('awsSecretKey');
				$bucket = $this->container->get('mytrip_admin.helper.amazon')->getOption('bucket');
				\S3::setAuth($awsAccessKey, $awsSecretKey);
				
				if(!empty($checkimage)){
					$deleteobject=\S3::deleteObject($bucket, $checkimage[0]['image']);	
					/*$file_path="img/hostal/".$checkimage[0]['image'];
					if($checkimage[0]['image']!='' && file_exists($file_path)){
						unlink($file_path);
					}*/
					 $em->createQuery("DELETE FROM MytripAdminBundle:HostalImage b WHERE b.hostal =$id")->execute();
				}
				$hostal_image->setHostal($this->getDoctrine()->getRepository('MytripAdminBundle:Hostal')->find($id));
				$ext=$request->files->get('image')->getClientOriginalExtension() ;
				$filename=$this->str_rand(8,"alphanum").".".$ext;
				//$request->files->get('image')->move("img/hostal",$filename);
				$tmpfile=$request->files->get('image')->getPathName() ;
				$putobject=\S3::putObjectFile($tmpfile, $bucket, $filename, \S3::ACL_PUBLIC_READ);
				if($putobject){	
					$hostal_image->setImage($filename);
					$hostal_image->setCreatedDate(new \DateTime(date('Y-m-d H:i:s')));					
					$em->persist($hostal_image);
					$em->flush();
				}
			}
			
			$this->get('session')->getFlashBag()->add('error','<div class="success msg">Hostal successfully updated</div>');
			return $this->redirect($this->generateUrl('mytrip_admin_edithostal',array('id'=>$id,'lan'=>$lan)));
		}
		
		/******Fetch language********/
		$query = $em->createQuery("SELECT p FROM MytripAdminBundle:Language  p ");			
		$language = $query->getArrayResult();
		
		/*******Fetch hostal details*****/
		$hostal_query = $em->createQuery("SELECT p,IDENTITY(p.destination) AS destination FROM MytripAdminBundle:Hostal  p WHERE p.hostalId=".$id);			
		$hostal = $hostal_query->getArrayResult();
		if(empty($hostal)){
			return $this->redirect($this->generateUrl('mytrip_admin_hostal'));
		}
		
		/*******Fetch Hostal content details*****/		
		$hostal_content_query = $em->createQuery("SELECT d FROM MytripAdminBundle:HostalContent d WHERE d.hostal=".$id." AND d.lan='".$lan."'");			
		$hostal_content = $hostal_content_query->getArrayResult();
			
		if(empty($hostal_content)){
			$hostal_content_query = $em->createQuery("SELECT d FROM MytripAdminBundle:HostalContent d WHERE d.hostal=".$id." AND d.lan='en'");			
			$hostal_content = $hostal_content_query->getArrayResult();
		}
		
		$feature_query = $em->createQuery("SELECT f FROM MytripAdminBundle:Feature f" );		
		$feature=$feature_query->getArrayResult();
				
		$hostal_image_query = $em->createQuery("SELECT d FROM MytripAdminBundle:HostalImage d WHERE d.hostal=".$id );		
		$hostal_image=$hostal_image_query->getArrayResult();
		
		$hostal_feature_query = $em->createQuery("SELECT IDENTITY(f.feature) AS feature FROM MytripAdminBundle:HostalFeature f WHERE f.hostal=".$id);			
	    $hostal_feature = $hostal_feature_query->getArrayResult();
		
		$hostal_rooms_query = $em->createQuery("SELECT d FROM MytripAdminBundle:HostalRooms d WHERE d.hostal=".$id);			
		$hostal_rooms = $hostal_rooms_query->getArrayResult();
		
		$destination_query = $em->createQuery("SELECT f FROM MytripAdminBundle:Destination f WHERE f.status NOT IN ('Trash')" );		
		$destination=$destination_query->getArrayResult();
		
		return $this->render('MytripAdminBundle:Default:edithostal.html.php',array('language'=>$language,'hostal'=>$hostal,'hostal_content'=>$hostal_content,'feature'=>$feature,'hostal_image'=>$hostal_image,'hostal_feature'=>$hostal_feature,'hostal_rooms'=>$hostal_rooms,'destination'=>$destination));
		
	}
	
	/*******Delete Hostal**********/
	public function deletehostalAction(Request $request){	
		 /****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		 
		 $em =  $this->getDoctrine()->getManager();	
		 
		 /*****Single feature delete*******/
		 $id=$request->query->get('id');
		 if($id!='' && $id>0){			 
			 $em->createQuery("UPDATE MytripAdminBundle:Hostal h SET h.status='Trash' WHERE h.hostalId =$id")->execute();
			 $this->get('session')->getFlashBag()->add('error','<div class="success msg">Hostal deleted successfully</div>');
			  if($request->server->get('HTTP_REFERER')!=''){		
				return $this->redirect($request->server->get('HTTP_REFERER'));
			 }else{
				return $this->redirect($this->generateUrl('mytrip_admin_hostal'));
			 }
		 }
		 
		 /********Multiple Admin delete**********/
		 if($request->getMethod()=="POST"){	
			 $hostalid=implode(",",$request->request->get('action'));			
			 $em->createQuery("UPDATE MytripAdminBundle:Hostal h SET h.status='Trash' WHERE h.hostalId IN (".$hostalid.")")->execute();
			 $this->get('session')->getFlashBag()->add('error','<div class="success msg">Hostal deleted successfully</div>');
			  if($request->server->get('HTTP_REFERER')!=''){		
				return $this->redirect($request->server->get('HTTP_REFERER'));
			 }else{
				return $this->redirect($this->generateUrl('mytrip_admin_hostal'));
			 }			 
		 }
		 
		 if($id=='' && $request->getMethod()!="POST"){
			 $this->get('session')->getFlashBag()->add('error','<div class="error msg">Sorry, Hostal record not available</div>');
			return $this->redirect($this->generateUrl('mytrip_admin_hostal'));
		 }	
	
		 $this->get('session')->getFlashBag()->add('error','<div class="error msg">Sorry, This feature work going on.</div>');
		 return $this->redirect($this->generateUrl('mytrip_admin_hostal'));
	}
	
	/*******Cancel setting***********/
	public function hostalcancelsettingsAction(Request $request){	
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		/****** Super admin session checking**********/
		$superadmin=$this->supercheckAdmin($request->getSession());		
		if($superadmin){
			return $superadmin;
		}
		
		$id=$request->query->get('id');
		
		/****** Search redirect same page **********/
		$em =  $this->getDoctrine()->getManager();
		$hostal = $em->createQuery("SELECT d FROM MytripAdminBundle:Hostal d WHERE d.hostalId=".$id)->getArrayResult();			
			
		$sql="SELECT p FROM MytripAdminBundle:HostalCancelDetails p WHERE p.hostal='".$id."' ORDER BY p.days DESC";
		/*****Pagnation****/
		$query = $em->createQuery($sql);		
		$paginator  = $this->get('knp_paginator');
		$pagination = $paginator->paginate(
			$query,
			$this->get('request')->query->get('page', 1)/*page number*/,
			10/*limit per page*/
		);			
		return $this->render('MytripAdminBundle:Default:hostalcancelsettings.html.php', array('hostal'=>$hostal,'pagination' => $pagination,'urlrequest'=>$this->geturlrequest($request),'pagerequest'=>$this->getrequestarray($request),'sortingrequest'=>$this->getsortrequest($request)));			
	}
	
	/*******Add cancel settings***********/
	public function hostaladdcancelsettingsAction(Request $request){
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		
		 $session = $request->getSession();
		 $id=$request->query->get('id');
		 
		 $canceldefault = new HostalCancelDetails();
		 
		 $em =  $this->getDoctrine()->getManager();
		 
		 $checkdays = $em->createQuery("SELECT p FROM MytripAdminBundle:HostalCancelDetails p WHERE  p.days = '".$request->get('days')."' AND p.hostal='".$id."'")->getArrayResult();	
		 
		 if($request->getMethod()=="POST"){	
			 if(empty($checkdays)){	
			 	$canceldefault->setHostal($this->getDoctrine()->getRepository('MytripAdminBundle:Hostal')->find($id)); 	 												
				$canceldefault->setDays($request->get('days')); 
				$canceldefault->setPercentage($request->get('percentage')); 
				$em->persist($canceldefault);
				$em->flush();
				$this->get('session')->getFlashBag()->add('error','<div class="success msg">Cancel settings has been successfully inserted</div>');
				return $this->redirect($this->generateUrl('mytrip_admin_hostal_add_cancel_setting',array('id'=>$id)));						
			 }else{
				$this->get('session')->getFlashBag()->add('error','<div class="error msg">Days already inserted</div>'); 
			 }
		 }	
		 $hostal = $em->createQuery("SELECT d FROM MytripAdminBundle:Hostal d WHERE d.hostalId=".$id)->getArrayResult();	 
		 return $this->render('MytripAdminBundle:Default:hostaladdcancelsettings.html.php', array('hostal'=>$hostal));
	}
	
	/*******Edit cancel settings***********/
	public function hostaleditcancelsettingsAction(Request $request){
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		
		 $session = $request->getSession();
		 $id=$request->get('id');
		 $hostalid=$request->get('hostalid');
		 
		 $em =  $this->getDoctrine()->getManager();
		 
		 /***Fetch admin users******/
		 $canceldefault = $em->getRepository('MytripAdminBundle:HostalCancelDetails')->findOneByHostalCancelId($id);	
		 
			
		 
		 if($request->getMethod()=="POST"){	
		  $checkdays = $em->createQuery("SELECT p FROM MytripAdminBundle:HostalCancelDetails p WHERE  p.days = '".$request->get('days')."' AND p.hostal =".$hostalid." AND p.hostalCancelId NOT IN ('".$id."') ")->getArrayResult();
			 if(empty($checkdays)){		 												
				$canceldefault->setDays($request->get('days')); 
				$canceldefault->setPercentage($request->get('percentage')); 				
				$em->flush();
				$this->get('session')->getFlashBag()->add('error','<div class="success msg">Cancel settings has been successfully updated</div>');
				return $this->redirect($this->generateUrl('mytrip_admin_hostal_edit_cancel_settings',array('id'=>$id,'hostalid'=>$hostalid)));						
			 }else{
				$this->get('session')->getFlashBag()->add('error','<div class="error msg">Days already inserted</div>'); 
			 }
		 }
		 
		 $setting=$em->createQuery("SELECT p FROM MytripAdminBundle:HostalCancelDetails p WHERE p.hostalCancelId =".$id)->getArrayResult();	
		 $hostal = $em->createQuery("SELECT d FROM MytripAdminBundle:Hostal d WHERE d.hostalId=".$hostalid)->getArrayResult();	
		 
		 return $this->render('MytripAdminBundle:Default:hostaleditcancelsettings.html.php',array('setting'=>$setting,'hostal'=>$hostal));
	}
	
	/*******Delete cancel settings***********/
	public function hostaldeletecancelsettingsAction(Request $request){	
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
			 
		 $em =  $this->getDoctrine()->getManager();	
		 
		 /*****Single record delete*******/
		 $id=$request->query->get('id');
		 $hostalid=$request->query->get('hostalid');	
		 		 
		 if($id!=''){
			 $hostal = $em->createQuery("SELECT COUNT(d) AS counts FROM MytripAdminBundle:HostalCancelDetails d WHERE d.hostal=".$hostalid)->getArrayResult();
			 if($hostal[0]['counts'] >1){
				 $em->createQuery("DELETE FROM MytripAdminBundle:HostalCancelDetails u WHERE u.hostalCancelId =$id")->execute();
				 $this->get('session')->getFlashBag()->add('error','<div class="success msg">Record deleted successfully</div>');
				 return $this->redirect($this->generateUrl('mytrip_admin_hostal_cancel',array('id'=>$hostalid)));
			 }else{
				 $this->get('session')->getFlashBag()->add('error','<div class="error msg">Sorry, Record deleted successfully</div>');
				 return $this->redirect($this->generateUrl('mytrip_admin_hostal_cancel',array('id'=>$hostalid)));
			 }
		 }
		 
		 /********Multiple Admin delete**********/
		 if($request->getMethod()=="POST"){			
			 $delid=$request->request->get('action');
				foreach($delid as $delid){
					 $hostal = $em->createQuery("SELECT COUNT(d) AS counts FROM MytripAdminBundle:HostalCancelDetails d WHERE d.hostal=".$hostalid)->getArrayResult();
					 if($hostal[0]['counts'] >1){
						 $em->createQuery("DELETE FROM MytripAdminBundle:HostalCancelDetails u WHERE u.hostalCancelId =$delid")->execute();						
					 }else{
						 $this->get('session')->getFlashBag()->add('error','<div class="error msg">Sorry, Record deleted successfully</div>');
						 return $this->redirect($this->generateUrl('mytrip_admin_hostal_cancel',array('id'=>$hostalid)));
					 }
				}
			 $this->get('session')->getFlashBag()->add('error','<div class="success msg">Records deleted successfully</div>');
			 return $this->redirect($this->generateUrl('mytrip_admin_hostal_cancel',array('id'=>$hostalid)));			 
		 }
		 
		 if($id=='' && $request->getMethod()!="POST"){
			 $this->get('session')->getFlashBag()->add('error','<div class="error msg">Sorry, Record not available</div>');
			 return $this->redirect($this->generateUrl('mytrip_admin_hostal_cancel',array('id'=>$hostalid)));	
		 }
		 
	}
	
	/*******Manage Enquires list***********/
	public function contactAction(Request $request){	
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}		
		
		/****** Search redirect same page **********/
		$em =  $this->getDoctrine()->getManager();	
		 if($request->getMethod()=="POST"){			
			if($request->request->get('cdate')!=''){
				$searchcontent['cdate']=$request->request->get('cdate');
			}
			if($request->request->get('edate')!=''){
				$searchcontent['edate']=$request->request->get('edate');
			}
			if($request->request->get('lan')!=''){
				$searchcontent['lan']=$request->request->get('lan');
			}
			if(!empty($searchcontent)){	
				$searchcontent['search']=1;			
				return $this->redirect($this->generateUrl('mytrip_admin_contact',$searchcontent));			
			} 
		 }
		 
		 /****** Search Query params checking**********/		
		 if($request->query->get('search')!=''){
			 $where=array();			
			 if($request->query->get('edate')!='' && $request->query->get('cdate')!=''){
				$where[]="p.createdDate BETWEEN '".$this->container->get('mytrip_admin.helper.date')->format($request->query->get('cdate'))."' AND '".$this->container->get('mytrip_admin.helper.date')->format($request->query->get('edate'))."'";				
			}else{
				if($request->query->get('cdate')!=''){
					$where[]="p.createdDate= '".$this->container->get('mytrip_admin.helper.date')->format($request->query->get('cdate'))."'";
				}
				if($request->query->get('edate')!=''){
					$where[]="p.createdDate= '".$this->container->get('mytrip_admin.helper.date')->format($request->query->get('edate'))."'";
				}
			}
			if($request->query->get('lan')!=''){				
				$where[]="p.lan LIKE '".$request->query->get('lan')."'";
			}			 
			
			if(!empty($where)){
				$wherequery=implode(" AND ",$where);
				$sql="SELECT p FROM MytripAdminBundle:Contact p WHERE ".$wherequery."  ORDER BY p.contactId DESC";
			}else{
				 $sql="SELECT p FROM MytripAdminBundle:Contact p  ORDER BY p.contactId DESC";
			}
		 }else{
			  $sql="SELECT p FROM MytripAdminBundle:Contact p  ORDER BY p.contactId DESC";
		 }
		 
		 /******Fetch language********/
		$query = $em->createQuery("SELECT p FROM MytripAdminBundle:Language  p ");			
		$language = $query->getArrayResult();
		
		/*****Pagnation****/
		$query = $em->createQuery($sql);		
		$paginator  = $this->get('knp_paginator');
		$pagination = $paginator->paginate(
			$query,
			$this->get('request')->query->get('page', 1)/*page number*/,
			10/*limit per page*/
		);			
		return $this->render('MytripAdminBundle:Default:contact.html.php', array('pagination' => $pagination,'urlrequest'=>$this->geturlrequest($request),'pagerequest'=>$this->getrequestarray($request),'sortingrequest'=>$this->getsortrequest($request),'language'=>$language));			
	}
	
	/*******View Enquires***********/
	public function viewcontactAction(Request $request){	
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		
		$id=$this->container->get('request')->get('id');
		if($id==''){
			return $this->redirect($this->generateUrl('mytrip_admin_contact'));
		}
		$em =  $this->getDoctrine()->getManager();
		
		if($request->getMethod()=="POST"){
			$em =  $this->getDoctrine()->getManager();
			$repository_content = $em->getRepository('MytripAdminBundle:Contact');		 
			$contactus=$repository_content->findOneByContactId($id);
			$contactus->setReply("Yes");
			$contactus->setReplysubject($request->request->get('replysubject'));
			$contactus->setReplyMessage($request->request->get('replymessage'));
			$contactus->setReplyDate(new \DateTime(date('Y-m-d H:i:s')));
			$em->flush();
			
			$emaillist=$em->getRepository('MytripAdminBundle:EmailList')->findOneBy(array('emailListId'=>'5'));							
			$emailcontent=$em->getRepository('MytripAdminBundle:EmailContent')->findOneBy(array('emailList'=>'5','lan'=>'en'));			
			
			/*******Contact mail send to admin***********/								
			$this->mailsend($emaillist->getFromname(),$emaillist->getFromemail(),$request->request->get('email'),$request->request->get('replysubject'),$request->request->get('replymessage'),$emaillist->getCcmail());
			
			$this->get('session')->getFlashBag()->add('error','<div class="success msg">Reply message send successfully</div>');
			return $this->redirect($this->generateUrl('mytrip_admin_viewcontact',array('id'=>$id)));
		}
				
		/*******Fetch contact details*****/
		$contact_query = $em->createQuery("SELECT p FROM MytripAdminBundle:Contact p WHERE p.contactId=".$id);			
		$contact = $contact_query->getArrayResult();
		if(empty($contact)){
			return $this->redirect($this->generateUrl('mytrip_admin_contact'));
		}
		
		if($contact[0]['view']=="No"){
			$repository_content = $em->getRepository('MytripAdminBundle:Contact');		 
			$contactus=$repository_content->findOneByContactId($contact['0']['contactId']);
			$contactus->setView("Yes");
			$em->flush();	
		}
		
		return $this->render('MytripAdminBundle:Default:viewcontact.html.php', array('contact'=>$contact));	
	}
	
	/*********Delete Enquiries*****/
	public function deletecontactAction(Request $request){	
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}		
		
		 $admin = new Admin();		 
		 $em =  $this->getDoctrine()->getManager();	
		 
		 /*****Single Admin delete*******/
		 $id=$request->query->get('id');		 
		 if($id!=''){
			 $delete = $em->createQuery("DELETE FROM MytripAdminBundle:Contact u WHERE u.contactId =$id")->execute();
			 $this->get('session')->getFlashBag()->add('error','<div class="success msg">Enquiry deleted successfully</div>');
			 return $this->redirect($this->generateUrl('mytrip_admin_contact'));
		 }
		 
		 /********Multiple Admin delete**********/
		 if($request->getMethod()=="POST"){			
			 $contactid=$request->request->get('action');
			 $contactid=implode(",",$contactid);
			 $delete = $em->createQuery("DELETE FROM MytripAdminBundle:Contact u WHERE u.contactId IN (".$contactid.")")->execute();
			 $this->get('session')->getFlashBag()->add('error','<div class="success msg">Enquiries deleted successfully</div>');
			 return $this->redirect($this->generateUrl('mytrip_admin_contact'));			 
		 }
		 
		 if($id=='' && $request->getMethod()!="POST"){
			 $this->get('session')->getFlashBag()->add('error','<div class="error msg">Sorry, Enquiry record not available</div>');
			 return $this->redirect($this->generateUrl('mytrip_admin_contact'));	
		 }
		 
	}
	
	/**********Stories List********/
	public function storiesAction(Request $request){
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		$em =  $this->getDoctrine()->getManager();
		
		if($request->getMethod()=="POST"){
			if($request->request->get('searchname')!=''){
				$searchcontent['name']=$request->request->get('searchname');
			}			 	
			if(!empty($searchcontent)){	
				$searchcontent['search']=1;			
				return $this->redirect($this->generateUrl('mytrip_admin_story',$searchcontent));			
			} 
		 }
		 
		  /****** Search Query params checking**********/		
		 if($request->query->get('search')!=''){
			 $where=array();
			if($request->query->get('name')!=''){
				$where[]="s.name LIKE '%".$request->query->get('name')."%'"; 
			}
			
			if(!empty($where)){
				$wherequery=implode(" AND ",$where);
				$sql="SELECT s,(SELECT h.name FROM MytripAdminBundle:Hostal h WHERE h.hostalId=s.hostal ) AS hostal FROM MytripAdminBundle:Story s WHERE ".$wherequery."  ORDER BY s.storyId DESC";
			}else{
				 $sql="SELECT s,(SELECT h.name FROM MytripAdminBundle:Hostal h WHERE h.hostalId=s.hostal ) AS hostal FROM MytripAdminBundle:Story s ORDER BY s.storyId DESC";
			}
		 }else{			 
			  $sql="SELECT s,(SELECT h.name FROM MytripAdminBundle:Hostal h WHERE h.hostalId=s.hostal ) AS hostal FROM MytripAdminBundle:Story s ORDER BY s.storyId DESC";
		 }
		 
		
		/*****Pagnation****/
		$query = $em->createQuery($sql);		
		$paginator  = $this->get('knp_paginator');
		$pagination = $paginator->paginate(
			$query,
			$this->get('request')->query->get('page', 1)/*page number*/,
			10/*limit per page*/
		);				
		return $this->render('MytripAdminBundle:Default:story.html.php', array('pagination' => $pagination,'urlrequest'=>$this->geturlrequest($request),'pagerequest'=>$this->getrequestarray($request),'sortingrequest'=>$this->getsortrequest($request)));	
		
	}
	
	/********Set month of the story using ajax***********/
	public function topstoryAction(Request $request){
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		$id= $this->container->get('request')->get('sid');
		$em =  $this->getDoctrine()->getManager();
		$check = $em->createQuery("SELECT s FROM MytripAdminBundle:Story s WHERE s.storyId='".$id."'")->getArrayResult();			
	    if(!empty($check)){
			$em->createQuery("UPDATE MytripAdminBundle:Story s SET s.topStory='No'")->execute();
			$em->createQuery("UPDATE MytripAdminBundle:Story s SET s.topStory='Yes' WHERE s.storyId='".$id."'")->execute();
		}
	}
	
	/********Add Story*********/
	public function addstoryAction(Request $request){
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		
		$story = new Story();
		$story_content = new StoryContent();
		$story_image = new StoryImage();		
		
		if($request->getMethod()=="POST"){	
			$page_link = strtolower(str_replace(' ','_',preg_replace('/[^a-zA-Z0-9_ -]/s', ' ', $request->request->get('name'))));
			if($page_link ==''){
				$page_link=strtolower(str_replace(array(' ','\''),array('_','-'), $request->request->get('name')));
			}
			
			$em =  $this->getDoctrine()->getManager();
			
			/********Checking duplicate stories***********/
			$check = $em->createQuery("SELECT s FROM MytripAdminBundle:Story s WHERE  s.url='".$page_link."' AND s.hostal ='".$request->request->get('hostal')."'");			
	        $check_story = $check->getArrayResult();				
					
			if(empty($check_story)){				
				$story->setName($request->request->get('name'));
				$story->setDestination($this->getDoctrine()->getRepository('MytripAdminBundle:Destination')->find($request->request->get('destination')));
				$story->setHostal($this->getDoctrine()->getRepository('MytripAdminBundle:Hostal')->find($request->request->get('hostal')));
				$story->setTopStory('No');	
				$story->setUrl($page_link);				
				$story->setStatus('Active');
				$story->setCreatedDate(new \DateTime(date('Y-m-d H:i:s')));							
				$em->persist($story);
				$em->flush();
				
				$em =  $this->getDoctrine()->getManager();
				$lastid=$story->getStoryId();
				
				$story_content->setName($request->request->get('name'));
				$story_content->setStory($this->getDoctrine()->getRepository('MytripAdminBundle:Story')->find($lastid));
				$story_content->setSubHead($request->request->get('subhead'));				
				$story_content->setContent($request->request->get('content'));						
				$story_content->setMetaTitle($request->request->get('metatitle'));
				$story_content->setMetaDescription($request->request->get('metadescription'));
				$story_content->setMetaKeyword($request->request->get('metakeyword'));
				$story_content->setLan('en');	
				$em->persist($story_content);
				$em->flush();				
				
				if(($request->files->get('image')!='')){
					$em =  $this->getDoctrine()->getManager();
					$story_image->setStory($this->getDoctrine()->getRepository('MytripAdminBundle:Story')->find($lastid));
					$ext=$request->files->get('image')->getClientOriginalExtension() ;
					$filename=$this->str_rand(8,"alphanum").".".$ext;
					//$request->files->get('image')->move("img/story",$filename);
					$awsAccessKey = $this->container->get('mytrip_admin.helper.amazon')->getOption('awsAccessKey');
					$awsSecretKey = $this->container->get('mytrip_admin.helper.amazon')->getOption('awsSecretKey');
					$bucket = $this->container->get('mytrip_admin.helper.amazon')->getOption('bucket');
					\S3::setAuth($awsAccessKey, $awsSecretKey);	
					
					$tmpfile=$request->files->get('image')->getPathName() ;
					$putobject=\S3::putObjectFile($tmpfile, $bucket, $filename, \S3::ACL_PUBLIC_READ);
					if($putobject){	
						$story_image->setImage($filename);
						$story_image->setCreatedDate(new \DateTime(date('Y-m-d H:i:s')));					
						$em->persist($story_image);
						$em->flush();	
					}
				}			
				
				$this->get('session')->getFlashBag()->add('error','<div class="success msg">Story successfully added</div>');
				return $this->redirect($this->generateUrl('mytrip_admin_story'));
			}else{
				$this->get('session')->getFlashBag()->add('error','<div class="error msg">Story already exists</div>');
			}
		}
		
		$em =  $this->getDoctrine()->getManager();	
		
		$destination_query = $em->createQuery("SELECT d FROM MytripAdminBundle:Destination d" );		
		$destination=$destination_query->getArrayResult();	
		
		return $this->render('MytripAdminBundle:Default:addstory.html.php',array('destination'=>$destination));	
		
	}
	
	/***Fetch the hostal corresponding country****/
	public function gethostalAction(Request $request){	
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		$em =  $this->getDoctrine()->getManager();		 
		$id=$request->get('did');
		$query = $em->createQuery("SELECT s FROM MytripAdminBundle:Hostal s  where s.destination=$id" );		
		$hostal=$query->getArrayResult();				 
		return $this->render('MytripAdminBundle:Default:gethostal.html.php',array('hostal'=>$hostal));		
	}
	
	/********Edit Story*********/
	public function editstoryAction(Request $request){
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		$em =  $this->getDoctrine()->getManager();
		$lan=$this->container->get('request')->get('lan');
		$id=$this->container->get('request')->get('id');
		
		$checkquery = $em->createQuery("SELECT p FROM MytripAdminBundle:Language  p WHERE p.lanCode='".$lan."'");			
		$checklanguage = $checkquery->getArrayResult();
		if(empty($checklanguage)){
			return $this->redirect($this->generateUrl('mytrip_admin_editstory',array('id'=>$id,'lan'=>'en')));
		}
		
		if($request->getMethod()=="POST"){
			if($lan=="en"){
				$page_link = strtolower(str_replace(' ','_',preg_replace('/[^a-zA-Z0-9_ -]/s', ' ', $request->request->get('name'))));
				if($page_link ==''){
					$page_link=strtolower(str_replace(array(' ','\''),array('_','-'), $request->request->get('name')));
				}
				
				$em =  $this->getDoctrine()->getManager();
				
				/********Checking duplicate hostal***********/
				$check = $em->createQuery("SELECT d FROM MytripAdminBundle:Story d WHERE  d.url='".$page_link."' AND d.storyId NOT IN ($id)  AND d.hostal NOT IN (".$request->request->get('hostal').")");			
				$check_hostal = $check->getArrayResult();				
						
				if(empty($check_hostal)){
					/***Story english language update***/	
					$repository = $em->getRepository('MytripAdminBundle:Story');		 
					$story=$repository->findOneByStoryId($id);				
					
					$story->setName($request->request->get('name'));
					$story->setDestination($this->getDoctrine()->getRepository('MytripAdminBundle:Destination')->find($request->request->get('destination')));
					$story->setHostal($this->getDoctrine()->getRepository('MytripAdminBundle:Hostal')->find($request->request->get('hostal')));
					$story->setUrl($page_link);
					$em->flush();	
				}else{
					$this->get('session')->getFlashBag()->add('error','<div class="success msg">Story already exists</div>');
					return $this->redirect($this->generateUrl('mytrip_admin_editstory',array('id'=>$id,'lan'=>$lan)));
				}
			}
			
			/******Story content update with corresponding lanugage******/
			$em =  $this->getDoctrine()->getManager();
			$check=$em->createQuery("SELECT p FROM MytripAdminBundle:StoryContent p WHERE p.story=".$id." AND p.lan='".$lan."'");			
			$check_content = $check->getArrayResult();
						
			if(!empty($check_content)){				
				$repository_content = $em->getRepository('MytripAdminBundle:StoryContent');		 
				$story_content=$repository_content->findOneByStoryContentId($check_content['0']['storyContentId']);
				$story_content->setName($request->request->get('name'));
				$story_content->setStory($this->getDoctrine()->getRepository('MytripAdminBundle:Story')->find($id));
				$story_content->setSubHead($request->request->get('subhead'));				
				$story_content->setContent($request->request->get('content'));						
				$story_content->setMetaTitle($request->request->get('metatitle'));
				$story_content->setMetaDescription($request->request->get('metadescription'));
				$story_content->setMetaKeyword($request->request->get('metakeyword'));
				$story_content->setLan($lan);								
			}else{
				$story_content = new StoryContent();		
				$story_content->setName($request->request->get('name'));
				$story_content->setStory($this->getDoctrine()->getRepository('MytripAdminBundle:Story')->find($id));
				$story_content->setSubHead($request->request->get('subhead'));				
				$story_content->setContent($request->request->get('content'));						
				$story_content->setMetaTitle($request->request->get('metatitle'));
				$story_content->setMetaDescription($request->request->get('metadescription'));
				$story_content->setMetaKeyword($request->request->get('metakeyword'));
				$story_content->setLan($lan);	
				$em->persist($story_content);				
			}
			$em->flush();
			
			/**********Story Image update********/
			if(($request->files->get('image')!='')){
				$story_image = new StoryImage();
				$em =  $this->getDoctrine()->getManager();
				$checkimage_query=$em->createQuery("SELECT p FROM MytripAdminBundle:StoryImage p WHERE p.story=".$id);			
				$checkimage = $checkimage_query->getArrayResult();
				
				$awsAccessKey = $this->container->get('mytrip_admin.helper.amazon')->getOption('awsAccessKey');
				$awsSecretKey = $this->container->get('mytrip_admin.helper.amazon')->getOption('awsSecretKey');
				$bucket = $this->container->get('mytrip_admin.helper.amazon')->getOption('bucket');
				\S3::setAuth($awsAccessKey, $awsSecretKey);
				
				if(!empty($checkimage)){
					$deleteobject=\S3::deleteObject($bucket, $checkimage[0]['image']);	
					/*$file_path="img/story/".$checkimage[0]['image'];
					if($checkimage[0]['image']!='' && file_exists($file_path)){
						unlink($file_path);
					}*/
					 $em->createQuery("DELETE FROM MytripAdminBundle:StoryImage b WHERE b.story =$id")->execute();
				}
				$story_image->setStory($this->getDoctrine()->getRepository('MytripAdminBundle:Story')->find($id));
				$ext=$request->files->get('image')->getClientOriginalExtension() ;
				$filename=$this->str_rand(8,"alphanum").".".$ext;
				//$request->files->get('image')->move("img/story",$filename);
				$tmpfile=$request->files->get('image')->getPathName() ;
				$putobject=\S3::putObjectFile($tmpfile, $bucket, $filename, \S3::ACL_PUBLIC_READ);
				if($putobject){	
					$story_image->setImage($filename);
					$story_image->setCreatedDate(new \DateTime(date('Y-m-d H:i:s')));					
					$em->persist($story_image);
					$em->flush();
				}
			}
			
			$this->get('session')->getFlashBag()->add('error','<div class="success msg">Story successfully updated</div>');
			return $this->redirect($this->generateUrl('mytrip_admin_editstory',array('id'=>$id,'lan'=>$lan)));
		}
		
		/******Fetch language********/
		$query = $em->createQuery("SELECT p FROM MytripAdminBundle:Language  p ");			
		$language = $query->getArrayResult();
		
		/*******Fetch story details*****/
		$story_query = $em->createQuery("SELECT p,IDENTITY(p.destination) AS destination,IDENTITY(p.hostal) AS hostal FROM MytripAdminBundle:Story p WHERE p.storyId=".$id);			
		$story = $story_query->getArrayResult();
		if(empty($story)){
			return $this->redirect($this->generateUrl('mytrip_admin_story'));
		}
		
		/*******Fetch Story content details*****/		
		$story_content_query = $em->createQuery("SELECT d FROM MytripAdminBundle:StoryContent d WHERE d.story=".$id." AND d.lan='".$lan."'");			
		$story_content = $story_content_query->getArrayResult();
			
		if(empty($story_content)){
			$story_content_query = $em->createQuery("SELECT d FROM MytripAdminBundle:StoryContent d WHERE d.story=".$id." AND d.lan='en'");			
			$story_content = $story_content_query->getArrayResult();
		}
				
		$story_image_query = $em->createQuery("SELECT d FROM MytripAdminBundle:StoryImage d WHERE d.story=".$id );		
		$story_image=$story_image_query->getArrayResult();		
				
		$destination_query = $em->createQuery("SELECT f FROM MytripAdminBundle:Destination f" );		
		$destination=$destination_query->getArrayResult();
		
		$hostal_query = $em->createQuery("SELECT f FROM MytripAdminBundle:Hostal f WHERE f.destination=".$story[0]['destination']);		
		$hostal=$hostal_query->getArrayResult();
		
		return $this->render('MytripAdminBundle:Default:editstory.html.php',array('language'=>$language,'story'=>$story,'story_content'=>$story_content,'story_image'=>$story_image,'destination'=>$destination,'hostal'=>$hostal));
		
	}
	
	/*******Delete Story users***********/
	public function deletestoryAction(Request $request){	
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}		
		
		 $story = new Story();		 
		 $em =  $this->getDoctrine()->getManager();	
		 
		 $awsAccessKey = $this->container->get('mytrip_admin.helper.amazon')->getOption('awsAccessKey');
		$awsSecretKey = $this->container->get('mytrip_admin.helper.amazon')->getOption('awsSecretKey');
		$bucket = $this->container->get('mytrip_admin.helper.amazon')->getOption('bucket');
		\S3::setAuth($awsAccessKey, $awsSecretKey);
		
		 /*****Single Story delete*******/
		 $id=$request->query->get('id');		 
		 if($id!=''){			 
			 $storyimage=$em->createQuery("SELECT u FROM MytripAdminBundle:StoryImage u WHERE u.story =$id")->getArrayResult();
			 if(!empty($storyimage)){
				 foreach($storyimage as $simage){					
					/*$file_path="img/story/".$simage['image'];
					if($simage['image']!='' && file_exists($file_path)){
						unlink($file_path);
					}*/
					$deleteobject=\S3::deleteObject($bucket, $simage['image']);	
					 $em->createQuery("DELETE FROM MytripAdminBundle:StoryImage b WHERE b.storyImageId =".$simage['storyImageId'])->execute(); 
				 }
			 }
			 $storybanner=$em->createQuery("SELECT u FROM MytripAdminBundle:Banner u WHERE u.bannerType='Story' AND u.typeId=$id")->getArrayResult();	
			 if(!empty($storybanner)){
				 foreach($storybanner as $bimage){					
					/*$file_path="img/story/".$bimage['image'];
					if($simage['image']!='' && file_exists($file_path)){
						unlink($file_path);
					}*/
					$deleteobject=\S3::deleteObject($bucket, $bimage['image']);	
					$em->createQuery("DELETE FROM MytripAdminBundle:Banner b WHERE b.bannerId=".$bimage['bannerId'])->execute(); 
				 }
			 }		
			 $em->createQuery("DELETE FROM MytripAdminBundle:Story u WHERE u.storyId =$id")->execute();
			 $this->get('session')->getFlashBag()->add('error','<div class="success msg">Story deleted successfully</div>');
			 return $this->redirect($this->generateUrl('mytrip_admin_story'));
		 }
		 
		 /********Multiple Story delete**********/
		 if($request->getMethod()=="POST"){			
			 $storyid=$request->request->get('action');
			 $storyid=implode(",",$storyid);
			 $storyimage=$em->createQuery("SELECT u FROM MytripAdminBundle:StoryImage u WHERE u.story IN ($storyid)")->getArrayResult();
			 if(!empty($storyimage)){
				 foreach($storyimage as $simage){					
					/*$file_path="img/story/".$simage['image'];
					if($simage['image']!='' && file_exists($file_path)){
						unlink($file_path);
					}*/
					$deleteobject=\S3::deleteObject($bucket, $simage['image']);	
					 $em->createQuery("DELETE FROM MytripAdminBundle:StoryImage b WHERE b.storyImageId =".$simage['storyImageId'])->execute(); 
				 }
			 }
			 $storybanner=$em->createQuery("SELECT u FROM MytripAdminBundle:Banner u WHERE u.bannerType='Story' AND u.typeId IN ($storyid)")->getArrayResult();	
			 if(!empty($storybanner)){
				 foreach($storybanner as $bimage){					
					/*$file_path="img/story/".$bimage['image'];
					if($simage['image']!='' && file_exists($file_path)){
						unlink($file_path);
					}*/
					$deleteobject=\S3::deleteObject($bucket, $bimage['image']);	
					 $em->createQuery("DELETE FROM MytripAdminBundle:Banner b WHERE b.bannerId=".$bimage['bannerId'])->execute(); 
				 }
			 }
			 $em->createQuery("DELETE FROM MytripAdminBundle:Story u WHERE u.storyId IN (".$storyid.")")->execute();			 
			 $this->get('session')->getFlashBag()->add('error','<div class="success msg">Stories deleted successfully</div>');
			 return $this->redirect($this->generateUrl('mytrip_admin_story'));			 
		 }
		 
		 if($id=='' && $request->getMethod()!="POST"){
			 $this->get('session')->getFlashBag()->add('error','<div class="error msg">Sorry, Story record not available</div>');
			 return $this->redirect($this->generateUrl('mytrip_admin_story'));	
		 }
		 
	}
	
	public function languagetextAction(Request $request){
		/****** Admin session checking**********/
		$response=$this->checkAdmin($request->getSession());		
		if($response){
			return $response;
		}
		
		$configDirectories = array(__DIR__.'/../../../../app/Resources/translations');

		$locator = new FileLocator($configDirectories);
		$yamlUserFiles = $locator->locate('messages.fr.php', null, false);
		//print_r($yamlUserFiles);exit;
		$finder = new Finder();
		$finder->files()->in($yamlUserFiles);
		//$finder->path('app/Resources/translations')->files()->in('messages.fr.php');
		//print_r($finder); exit;
		//$finder->path('app/Resources/translations')->files()->in('messages.fr.php');
		//$finder->files()->in('app');
		
		foreach ($finder as $file) {
			$contents = $file->getContents();
			print_r($contents);
		}
		exit;
	}

    /********Edit Room*********/
    public function editroomAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager(); 

        $room_id = $request->get('roomid');
        $hostal_id = $request->get('hostal');

        if($room_id == -1)
        // We're adding a new room to the hostal.
        {
            $hostal = $this->getDoctrine()
                           ->getRepository('MytripAdminBundle:Hostal')
                           ->find($hostal_id);

            if(!$hostal)
            {
                // ERROR!
            }

            $room = new HostalRooms();
            $room->setHostal($hostal);
        }
        else
        // Updating an existing room for this hostal.
        {
            $room = $this->getDoctrine()
                         ->getRepository('MytripAdminBundle:HostalRooms')
                         ->find($room_id);

            if(!$room)
            {
                // ERROR!
            }

            // Deleting.
            if($request->request->get('delete'))
            {
            	$em->remove($room);
        		$em->flush();

        		return $this->redirect($this->generateUrl('mytrip_admin_edithostal', array('id' => $hostal_id)));
	        }
        }

        $room->setRoomtype($request->request->get('roomtype'));
        $room->setGuests($request->request->get('guests'));
        $room->setAdults($request->request->get('adults'));
        $room->setChild($request->request->get('child'));
        $room->setPrice($request->request->get('price'));

        $em->persist($room);
        $em->flush();

        return $this->redirect($this->generateUrl('mytrip_admin_edithostal', array('id' => $hostal_id)));
        //return $this->render(
        //    'MytripAdminBundle:Default:editroom.html.php', 
        //    array('room_id' => $room_id)
        //    );
    }
}
