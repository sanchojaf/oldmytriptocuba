<?php
namespace Mytrip\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\ArrayLoader;
use Mytrip\AdminBundle\Helper\Date;
use Mytrip\AdminBundle\Helper\Amazon;

use Mytrip\PaymentBundle\Model\PaymentDetails;
use Payum\Core\Security\GenericTokenFactoryInterface;
use Payum\Paypal\ExpressCheckout\Nvp\Api;
use Payum\Core\Registry\RegistryInterface;
use Payum\Stripe\Keys;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Extra;
use Symfony\Component\Validator\Constraints\Range;


class DefaultController extends Controller{
	
    public function indexAction(Request $request){		
		/****Check language session*******/
		$session = $request->getSession();	
		$this->langsession($session);
		$lan=$session->get('language');
		$request->setLocale($lan);			
		
		$em = $this->getDoctrine()->getManager();
		if($request->getMethod()=="POST"){
			$destination=$request->request->get('hdes');
			$checkin=$request->request->get('checkin');
			$checkout=$request->request->get('checkin');
			$guest=$request->request->get('guest');
			$destination = $em->createQuery("SELECT d,dc FROM MytripAdminBundle:Destination d LEFT JOIN MytripAdminBundle:DestinationContent dc WITH dc.destination=d.destinationId WHERE d.status='Active' AND dc.lan='".$lan."' AND dc.name LIKE '%".$destination."%'")->getArrayResult();
			if(!empty($destination)){
				return $this->redirect($this->container->get('router')->getContext()->getBaseUrl()."/".$destination[0]['url']."?search=1&checkin=".$checkin."&checkout=".$checkout."&guest=".$guest."#hdetail");
			}else{
				return $this->redirect($this->generateUrl('mytrip_user_homepage'));
			}
		}
					
		$destination = $em->createQuery("SELECT d.typeId,(SUM(d.rating)/COUNT(d)) AS HIDDEN rate FROM MytripAdminBundle:Review d  LEFT JOIN MytripAdminBundle:Destination r WITH r.destinationId=d.typeId WHERE r.status='Active' AND d.reviewType='Destination' AND d.status='Active' GROUP BY d.typeId ORDER BY rate DESC ")->setMaxResults('5')->getArrayResult(); 
		if(empty($destination)){
			$destination = $em->createQuery("SELECT d FROM MytripAdminBundle:Destination d WHERE d.status='Active'")->setMaxResults('5')->getArrayResult();
		}
		
		$hospitality = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='$lan' AND p.staticpage=8 ")->getArrayResult();			
		if(empty($hospitality)){
			$hospitality = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage=8 ")->getArrayResult();	
		}
		
		$stories = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='$lan' AND p.staticpage=9")->getArrayResult();			
		if(empty($stories)){
			$stories = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage=9")->getArrayResult();	
		}
		
		$trust = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='$lan' AND p.staticpage=10 ")->getArrayResult();			
		if(empty($trust)){
			$trust = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage=10")->getArrayResult();	
		}
			
		$home = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='$lan' AND p.staticpage=1 ")->getArrayResult();
		if(empty($home)){
			$home = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage=1")->getArrayResult();	
		}
		$seo['title']=$home[0]['pageTitle'];
		$seo['metadescription']=$home[0]['metaDescription'];
		$seo['metakeywords']=$home[0]['metaKeyword'];		
		
        return $this->render('MytripUserBundle:Default:index.html.php',array('seo'=>$seo,'destination'=>$destination,'language'=>$this->language(),'lan'=>$lan,'hospitality'=>$hospitality,'stories'=>$stories,'trust'=>$trust,'soacillink'=>$this->getsociallink(),'lan'=>$lan));
    }
	
	public function availabilityAction(Request $request){		
		/****Check language session*******/
		$session = $request->getSession();	
		$this->langsession($session);
		$lan=$session->get('language');
		$request->setLocale($lan);
			
		$em = $this->getDoctrine()->getManager();
		$home = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='$lan' AND p.staticpage=1 ")->getArrayResult();
		if(empty($home)){
			$home = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage=1")->getArrayResult();	
		}
		
		$seo['title']=$home[0]['pageTitle'];
		$seo['metadescription']=$home[0]['metaDescription'];
		$seo['metakeywords']=$home[0]['metaKeyword'];		
		
        return $this->render('MytripUserBundle:Default:availability.html.php',array('seo'=>$seo,'language'=>$this->language(),'lan'=>$lan,'soacillink'=>$this->getsociallink()));
    }
	
	public function homesearchdestinationAction(Request $request){
		$session = $request->getSession();	
		$this->langsession($session);
		$lan=$session->get('language');
		$request->setLocale($lan);
		$des=$request->get('term');
		$em = $this->getDoctrine()->getManager();		
		$destination = $em->createQuery("SELECT d,dc FROM MytripAdminBundle:Destination d LEFT JOIN MytripAdminBundle:DestinationContent dc WITH dc.destination=d.destinationId WHERE d.status='Active' AND dc.lan='".$lan."' AND dc.name LIKE '%".$des."%'")->getArrayResult(); 
		$check=array();
		if(!empty($destination)){
			for($i=0;$i<count($destination);$i+=2){
				$check[]=array('id'=>$destination[$i]['url'],'label'=>$destination[$i+1]['name'],'value'=>$destination[$i+1]['name']);
			}
		}			
		return new Response(json_encode($check));
	}
	
	/********About Us***********/
	public function aboutusAction(Request $request){
		/****Check language session*******/
		$session = $request->getSession();	
		$this->langsession($session);
		$lan=$session->get('language');
		$request->setLocale($lan);
		
		$em = $this->getDoctrine()->getManager(); 		
		
		$what_do_we_do = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p WHERE p.lan='$lan' AND p.staticpage=3")->getArrayResult();			
		if(empty($what_do_we_do)){
			$what_do_we_do = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage=3")->getArrayResult();	
		}
		
		$who_we_are = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p WHERE p.lan='$lan' AND p.staticpage=2")->getArrayResult();			
		if(empty($who_we_are)){
			$who_we_are = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage=2")->getArrayResult();	
		}
		
		$what_do_we_offer = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p WHERE (p.lan='$lan' )  AND p.staticpage=4")->getArrayResult();			
		if(empty($what_do_we_offer)){
			$what_do_we_offer = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage=4")->getArrayResult();	
		}
		
		$seo['title']=$who_we_are[0]['pageTitle'];
		$seo['metadescription']=$who_we_are[0]['metaDescription'];
		$seo['metakeywords']=$who_we_are[0]['metaKeyword'];		
		
        return $this->render('MytripUserBundle:Default:aboutus.html.php',array('seo'=>$seo,'what_do_we_do'=>$what_do_we_do,'who_we_are'=>$who_we_are,'what_do_we_offer'=>$what_do_we_offer,'language'=>$this->language(),'soacillink'=>$this->getsociallink(),'lan'=>$lan));
    }
	
	/********Payment policy***********/
	public function paymentAction(Request $request){
		/****Check language session*******/
		$session = $request->getSession();	
		$this->langsession($session);
		$lan=$session->get('language');
		$request->setLocale($lan);
		
		$em = $this->getDoctrine()->getManager(); 		
		$payment_menu = $em->createQuery("SELECT p FROM MytripAdminBundle:Staticpage p  WHERE p.staticpageId=5")->getArrayResult();
		$terms_menu = $em->createQuery("SELECT p FROM MytripAdminBundle:Staticpage p  WHERE p.staticpageId=5")->getArrayResult();
		if($payment_menu[0]['status']!="Active" && $terms_menu[0]['status']!="Active"){
			return $this->redirect($this->generateUrl('mytrip_user_homepage'));
		}
		
		$payment = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='$lan' AND p.staticpage=5")->getArrayResult();			
		if(empty($payment)){
			$payment = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage=5")->getArrayResult();	
		}
		
		$terms = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='$lan' AND p.staticpage=6")->getArrayResult();			
		if(empty($terms)){
			$terms = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage=6")->getArrayResult();	
		}
		
		$seo['title']=$payment[0]['pageTitle'];
		$seo['metadescription']=$payment[0]['metaDescription'];
		$seo['metakeywords']=$payment[0]['metaKeyword'];
		
        return $this->render('MytripUserBundle:Default:payment.html.php',array('seo'=>$seo,'payment'=>$payment,'terms'=>$terms,'language'=>$this->language(),'soacillink'=>$this->getsociallink(),'lan'=>$lan));
    }
	
	/*********Hospitality*********/
	public function hospitalityAction(Request $request){		
		/****Check language session*******/
		$session = $request->getSession();	
		$this->langsession($session);
		$lan=$session->get('language');
		$request->setLocale($lan);
		
		$em = $this->getDoctrine()->getManager(); 
				
		$hospitality = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p WHERE p.lan='$lan' AND p.staticpage=8")->getArrayResult();			
		if(empty($hospitality)){
			$hospitality = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage=8")->getArrayResult();	
		}
		
		$seo['title']=$hospitality[0]['pageTitle'];
		$seo['metadescription']=$hospitality[0]['metaDescription'];
		$seo['metakeywords']=$hospitality[0]['metaKeyword'];
		
        return $this->render('MytripUserBundle:Default:hospitality.html.php',array('seo'=>$seo,'hospitality'=>$hospitality,'language'=>$this->language(),'soacillink'=>$this->getsociallink(),'lan'=>$lan));
    }
	
	/*********Staticpage content*********/
	public function staticpagesAction(Request $request,$url){		
		/****Check language session*******/
		$session = $request->getSession();	
		$this->langsession($session);
		$lan=$session->get('language');
		$request->setLocale($lan);
		
		$em = $this->getDoctrine()->getManager(); 
		$staticpage = $em->createQuery("SELECT p FROM MytripAdminBundle:Staticpage p WHERE p.url='".$url."' AND p.status='Active'")->getArrayResult();
		if(empty($staticpage)){
			return $this->redirect($this->generateUrl('mytrip_user_homepage'));
		}
		$staticpage_content = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p WHERE p.lan='$lan' AND p.staticpage='".$staticpage[0]['staticpageId']."'")->getArrayResult();			
		if(empty($staticpage_content)){
			$staticpage_content = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage='".$staticpage[0]['staticpageId']."'")->getArrayResult();	
		}
		
		$seo['title']=$staticpage_content[0]['pageTitle'];
		$seo['metadescription']=$staticpage_content[0]['metaDescription'];
		$seo['metakeywords']=$staticpage_content[0]['metaKeyword'];
		
        return $this->render('MytripUserBundle:Default:staticpage.html.php',array('seo'=>$seo,'staticpage'=>$staticpage_content,'language'=>$this->language(),'soacillink'=>$this->getsociallink(),'lan'=>$lan));
    }
	
	/*********FAQ*********/
	public function faqAction(Request $request){		
		/****Check language session*******/
		$session = $request->getSession();	
		$this->langsession($session);
		$lan=$session->get('language');
		$request->setLocale($lan);
		
		$em = $this->getDoctrine()->getManager(); 
				
		$faq = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='$lan' AND p.staticpage=19")->getArrayResult();			
		if(empty($faq)){
			$faq = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage=19")->getArrayResult();	
		}
		$seo['title']=$faq[0]['pageTitle'];
		$seo['metadescription']=$faq[0]['metaDescription'];
		$seo['metakeywords']=$faq[0]['metaKeyword'];
		
        return $this->render('MytripUserBundle:Default:faq.html.php',array('seo'=>$seo,'faq'=>$faq,'language'=>$this->language(),'soacillink'=>$this->getsociallink(),'lan'=>$lan));
    }
	
	/*********Help*********/
	public function helpAction(Request $request){		
		/****Check language session*******/
		$session = $request->getSession();	
		$this->langsession($session);
		$lan=$session->get('language');
		$request->setLocale($lan);
		
		$em = $this->getDoctrine()->getManager(); 
				
		$help = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p WHERE p.lan='$lan' AND p.staticpage=16")->getArrayResult();			
		if(empty($help)){
			$help = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage=16")->getArrayResult();	
		}
		
		$seo['title']=$help[0]['pageTitle'];
		$seo['metadescription']=$help[0]['metaDescription'];
		$seo['metakeywords']=$help[0]['metaKeyword'];
		
        return $this->render('MytripUserBundle:Default:help.html.php',array('seo'=>$seo,'help'=>$help,'language'=>$this->language(),'soacillink'=>$this->getsociallink(),'lan'=>$lan));
    }
	
	/*********trust and safty*********/
	public function trustAction(Request $request){		
		/****Check language session*******/
		$session = $request->getSession();	
		$this->langsession($session);
		$lan=$session->get('language');
		$request->setLocale($lan);
		
		$em = $this->getDoctrine()->getManager(); 
				
		$trust = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p WHERE p.lan='$lan' AND p.staticpage=10")->getArrayResult();			
		if(empty($trust)){
			$trust = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage=10")->getArrayResult();	
		}
		
		$seo['title']=$trust[0]['pageTitle'];
		$seo['metadescription']=$trust[0]['metaDescription'];
		$seo['metakeywords']=$trust[0]['metaKeyword'];
		
        return $this->render('MytripUserBundle:Default:trust.html.php',array('seo'=>$seo,'trust'=>$trust,'language'=>$this->language(),'soacillink'=>$this->getsociallink(),'lan'=>$lan));
    }
	
	/*********recreation*********/
	public function recreationAction(Request $request){		
		/****Check language session*******/
		$session = $request->getSession();	
		$this->langsession($session);
		$lan=$session->get('language');
		$request->setLocale($lan);
		
		$em = $this->getDoctrine()->getManager(); 
				
		$recreation = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p WHERE p.lan='$lan' AND p.staticpage=11")->getArrayResult();			
		if(empty($recreation)){
			$recreation = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage=11")->getArrayResult();	
		}
		
		$seo['title']=$recreation[0]['pageTitle'];
		$seo['metadescription']=$recreation[0]['metaDescription'];
		$seo['metakeywords']=$recreation[0]['metaKeyword'];
		
        return $this->render('MytripUserBundle:Default:recreation.html.php',array('seo'=>$seo,'recreation'=>$recreation,'language'=>$this->language(),'soacillink'=>$this->getsociallink(),'lan'=>$lan));
    }
	
	/*********food and drink*********/
	public function foodAction(Request $request){		
		/****Check language session*******/
		$session = $request->getSession();	
		$this->langsession($session);
		$lan=$session->get('language');
		$request->setLocale($lan);
		
		$em = $this->getDoctrine()->getManager(); 
				
		$food = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p WHERE p.lan='$lan' AND p.staticpage=12")->getArrayResult();			
		if(empty($food)){
			$food = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage=12")->getArrayResult();	
		}
		
		$seo['title']=$food[0]['pageTitle'];
		$seo['metadescription']=$food[0]['metaDescription'];
		$seo['metakeywords']=$food[0]['metaKeyword'];	
		
        return $this->render('MytripUserBundle:Default:food.html.php',array('seo'=>$seo,'food'=>$food,'language'=>$this->language(),'soacillink'=>$this->getsociallink(),'lan'=>$lan));
    }	
	
	/*********Technical support*********/
	public function supportAction(Request $request){		
		/****Check language session*******/
		$session = $request->getSession();	
		$this->langsession($session);
		$lan=$session->get('language');
		$request->setLocale($lan);
		
		$em = $this->getDoctrine()->getManager(); 
				
		$support = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p WHERE p.lan='$lan' AND p.staticpage=13")->getArrayResult();			
		if(empty($support)){
			$support = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage=13")->getArrayResult();	
		}
		
		$seo['title']=$support[0]['pageTitle'];
		$seo['metadescription']=$support[0]['metaDescription'];
		$seo['metakeywords']=$support[0]['metaKeyword'];
		
        return $this->render('MytripUserBundle:Default:support.html.php',array('seo'=>$seo,'support'=>$support,'language'=>$this->language(),'soacillink'=>$this->getsociallink(),'lan'=>$lan));
    }
	
	/*********press*********/
	public function pressAction(Request $request){		
		/****Check language session*******/
		$session = $request->getSession();	
		$this->langsession($session);
		$lan=$session->get('language');
		$request->setLocale($lan);
		
		$em = $this->getDoctrine()->getManager(); 
				
		$press = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p WHERE p.lan='$lan' AND p.staticpage=14")->getArrayResult();			
		if(empty($press)){
			$press = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage=14")->getArrayResult();	
		}	
		
		$seo['title']=$press[0]['pageTitle'];
		$seo['metadescription']=$press[0]['metaDescription'];
		$seo['metakeywords']=$press[0]['metaKeyword'];
		
        return $this->render('MytripUserBundle:Default:press.html.php',array('seo'=>$seo,'press'=>$press,'language'=>$this->language(),'soacillink'=>$this->getsociallink(),'lan'=>$lan));
    }
	
	/*********blog*********/
	public function blogAction(Request $request){		
		/****Check language session*******/
		$session = $request->getSession();	
		$this->langsession($session);
		$lan=$session->get('language');
		$request->setLocale($lan);
		
		$em = $this->getDoctrine()->getManager(); 
				
		$blog = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p WHERE p.lan='$lan' AND p.staticpage=15")->getArrayResult();			
		if(empty($blog)){
			$blog = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage=15")->getArrayResult();	
		}	
		
		$seo['title']=$blog[0]['pageTitle'];
		$seo['metadescription']=$blog[0]['metaDescription'];
		$seo['metakeywords']=$blog[0]['metaKeyword'];
		
        return $this->render('MytripUserBundle:Default:blog.html.php',array('seo'=>$seo,'blog'=>$blog,'language'=>$this->language(),'soacillink'=>$this->getsociallink(),'lan'=>$lan));
    }
	
	/*********Contact Us*********/
	public function contactAction(Request $request){		
		/****Check language session*******/
		$session = $request->getSession();	
		$this->langsession($session);
		$lan=$session->get('language');
		$request->setLocale($lan);
		
		$em = $this->getDoctrine()->getManager(); 
		$contactus = new \Mytrip\AdminBundle\Entity\Contact();
		
		if($request->getMethod()=="POST"){	
		    $resp = recaptcha_check_answer ($this->container->get('mytrip_admin.helper.recaptcha')->getOption('privatekey'),$_SERVER["REMOTE_ADDR"],$_POST["recaptcha_challenge_field"],$_POST["recaptcha_response_field"]);	
			if ($resp->is_valid) {	
				$contactus->setName($request->request->get('name'));
				$contactus->setEmail($request->request->get('email'));
				$contactus->setPhone($request->request->get('phone'));
				$contactus->setSubject($request->request->get('subject'));
				$contactus->setMessage($request->request->get('messages'));
				$contactus->setView("No");
				$contactus->setReply("No");
				$contactus->setLan($lan);
				$contactus->setCreatedDate(new \DateTime(date('Y-m-d H:i:s')));
				$em->persist($contactus);
				$em->flush();
				$emaillist=$em->getRepository('MytripAdminBundle:EmailList')->findOneBy(array('emailListId'=>'4'));							
				$emailcontent=$em->getRepository('MytripAdminBundle:EmailContent')->findOneBy(array('emailList'=>'4','lan'=>'en'));
				$message=str_replace(array('{name}','{email}','{phone}','{subject}','{message}'),array($request->request->get('name'),$request->request->get('email'),$request->request->get('phone'),$request->request->get('subject'),$request->request->get('messages')),$emailcontent->getEmailContent());
				
				/*******Contact mail send to admin***********/								
				$this->mailsend($request->request->get('name'),$request->request->get('email'),$emaillist->getTomail(),$request->request->get('subject'),$message,$emaillist->getCcmail());
				
				$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Message send successfully. We will contact soon.'));
				return $this->redirect($this->generateUrl('mytrip_user_contact'));
			}else{
				$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Invalid captcha code'));
			}
		}
				
		$contact = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p WHERE p.lan='$lan'  AND p.staticpage=7")->getArrayResult();
		if(empty($contact)){
			$contact = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage=7")->getArrayResult();	
		}
		
		$seo['title']=$contact[0]['pageTitle'];
		$seo['metadescription']=$contact[0]['metaDescription'];
		$seo['metakeywords']=$contact[0]['metaKeyword'];
				
        return $this->render('MytripUserBundle:Default:contact.html.php',array('seo'=>$seo,'contact'=>$contact,'language'=>$this->language(),'soacillink'=>$this->getsociallink(),'lan'=>$lan));
    }
	
	/*****Destination List*****/
	public function destinationAction(Request $request){		
		/****Check language session*******/
		$session = $request->getSession();	
		$this->langsession($session);
		$lan=$session->get('language');
		$request->setLocale($lan);
				
		$em = $this->container->get('doctrine')->getManager();
		
		//$destination_query = $em->createQuery("SELECT d,c,i FROM MytripAdminBundle:Destination d LEFT JOIN MytripAdminBundle:DestinationContent c WITH d.destinationId=c.destination LEFT JOIN MytripAdminBundle:DestinationImage i WITH d.destinationId=i.destination WHERE c.lan='$lan' AND d.status='Active'");	
		$destination_query = $em->createQuery("SELECT d FROM MytripAdminBundle:Destination d WHERE d.status='Active'");			
		$destination = $destination_query->getArrayResult();
		
		$banner_query = $em->createQuery("SELECT p FROM MytripAdminBundle:Banner p LEFT JOIN MytripAdminBundle:Destination d WITH d.destinationId=p.typeId WHERE p.bannerType='Destination' AND d.status='Active' AND p.status='Active' GROUP BY p.typeId")->setMaxResults(5);			
		$banner = $banner_query->getArrayResult();
		
		$destination_content = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='$lan'  AND p.staticpage=17")->getArrayResult();
		if(empty($destination_content)){
			$destination_content = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en'  AND p.staticpage=17")->getArrayResult();
		}
		
		$seo['title']=$destination_content[0]['pageTitle'];
		$seo['metadescription']=$destination_content[0]['metaDescription'];
		$seo['metakeywords']=$destination_content[0]['metaKeyword'];
		
        return $this->render('MytripUserBundle:Default:destination.html.php',array('seo'=>$seo,'destination'=>$destination,'banner'=>$banner,'destination_content'=>$destination_content,'language'=>$this->language(),'soacillink'=>$this->getsociallink(),'lan'=>$lan));
    }
	
	/*****top Destination List*****/
	public function topdestinationAction(Request $request){		
		/****Check language session*******/
		$session = $request->getSession();	
		$this->langsession($session);
		$lan=$session->get('language');
		$request->setLocale($lan);
				
		$em = $this->container->get('doctrine')->getManager();
		
		/*$destination_query = $em->createQuery("SELECT d,c,i FROM MytripAdminBundle:Destination d LEFT JOIN MytripAdminBundle:DestinationContent c WITH d.destinationId=c.destination LEFT JOIN MytripAdminBundle:DestinationImage i WITH d.destinationId=i.destination WHERE c.lan='$lan' AND d.status='Active'")->setMaxResults(12);			
		$destination = $destination_query->getArrayResult();*/
		
		$banner_query = $em->createQuery("SELECT p FROM MytripAdminBundle:Banner p  where  p.bannerType='Destination'")->setMaxResults(5);			
		$banner = $banner_query->getArrayResult();
		
		$destination_content = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='$lan'  and p.staticpage=18")->getArrayResult();
		if(empty($destination_content)){
			$destination_content = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en'  and p.staticpage=18")->getArrayResult();	
		}
		
		$seo['title']=$destination_content[0]['pageTitle'];
		$seo['metadescription']=$destination_content[0]['metaDescription'];
		$seo['metakeywords']=$destination_content[0]['metaKeyword'];
		
        return $this->render('MytripUserBundle:Default:topdestination.html.php',array('seo'=>$seo,'banner'=>$banner,'destination_content'=>$destination_content,'language'=>$this->language(),'soacillink'=>$this->getsociallink(),'lan'=>$lan));
    }
	
	/*****Stories List*****/
	public function storyAction(Request $request){		
		/****Check language session*******/
		$session = $request->getSession();	
		$this->langsession($session);
		$lan=$session->get('language');
		$request->setLocale($lan);
				
		$em = $this->container->get('doctrine')->getManager();
		
		$story_query = $em->createQuery("SELECT d,IDENTITY(d.destination) AS destination,IDENTITY(d.hostal) AS hostal FROM MytripAdminBundle:Story d LEFT JOIN MytripAdminBundle:Destination ds WITH ds.destinationId=d.destination LEFT JOIN MytripAdminBundle:Hostal hs WITH hs.hostalId=d.hostal WHERE d.status='Active' AND ds.status='Active' AND hs.status='Active'");		
		$story = $story_query->getArrayResult();
		
		$banner_query = $em->createQuery("SELECT p FROM MytripAdminBundle:Banner p LEFT JOIN MytripAdminBundle:Story s WITH s.storyId=p.typeId LEFT JOIN MytripAdminBundle:Destination ds WITH ds.destinationId=s.destination LEFT JOIN MytripAdminBundle:Hostal hs WITH hs.hostalId=s.hostal WHERE p.bannerType='Story' AND s.status='Active' AND ds.status='Active' AND hs.status='Active' GROUP BY p.typeId")->setMaxResults(5);			
		$banner = $banner_query->getArrayResult();
		
		$story_content = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p WHERE p.lan='$lan'  and p.staticpage=9")->getArrayResult();	
		if(empty($story_content)){
			$story_content = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p WHERE p.lan='en' and p.staticpage=9")->getArrayResult();
		}
		
		$storyofthemonth=$em->createQuery("SELECT d,IDENTITY(d.destination) AS destination,IDENTITY(d.hostal) AS hostal FROM MytripAdminBundle:Story d LEFT JOIN MytripAdminBundle:Destination ds WITH ds.destinationId=d.destination LEFT JOIN MytripAdminBundle:Hostal hs WITH hs.hostalId=d.hostal WHERE d.status='Active' AND ds.status='Active' AND hs.status='Active' AND d.topStory='Yes'")->getArrayResult();
		/*if(empty($storyofthemonth)){
			$storyofthemonth=$em->createQuery("SELECT d,IDENTITY(d.destination) AS destination,IDENTITY(d.hostal) AS hostal FROM MytripAdminBundle:Story d LEFT JOIN MytripAdminBundle:Destination ds WITH ds.destinationId=d.destination LEFT JOIN MytripAdminBundle:Hostal hs WITH hs.hostalId=d.hostal WHERE d.status='Active' AND ds.status='Active' AND hs.status='Active' ORDER BY d.storyId DESC")->setMaxResults(1)->getArrayResult();
		}*/
		
		$seo['title']=$story_content[0]['pageTitle'];
		$seo['metadescription']=$story_content[0]['metaDescription'];
		$seo['metakeywords']=$story_content[0]['metaKeyword'];
		
        return $this->render('MytripUserBundle:Default:story.html.php',array('seo'=>$seo,'story'=>$story,'banner'=>$banner,'story_content'=>$story_content,'language'=>$this->language(),'soacillink'=>$this->getsociallink(),'lan'=>$lan,'storyofthemonth'=>$storyofthemonth));
    }
	
	/*****Stories Details*****/
	public function storyDetailsAction(Request $request,$destination,$hostal,$story){
		/****Check language session*******/
		$session = $request->getSession();	
		$this->langsession($session);
		$lan=$session->get('language');
		$request->setLocale($lan);
				
		$em = $this->container->get('doctrine')->getManager();
		
		$destination_query = $em->createQuery("SELECT d FROM MytripAdminBundle:Destination d WHERE d.status='Active' AND d.url='".$destination."'");			
		$destinations = $destination_query->getArrayResult();		
		if(empty($destinations)){
			return $this->redirect($this->generateUrl('mytrip_user_story'));
		}
		/*
				if(empty($what_do_we_do)){
			$what_do_we_do = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage=3")->getArrayResult();	
		}

		*/
		$hostal_query = $em->createQuery("SELECT h,hc FROM MytripAdminBundle:Hostal h LEFT JOIN MytripAdminBundle:HostalContent hc WITH hc.hostal=h.hostalId WHERE h.status='Active' AND h.url='".$hostal."'"." AND (hc.lan='$lan')");			
		$hostals = $hostal_query->getArrayResult();		
		
		if(empty($hostals)){
			$hostals = $em->createQuery("SELECT h,hc FROM MytripAdminBundle:Hostal h LEFT JOIN MytripAdminBundle:HostalContent hc WITH hc.hostal=h.hostalId WHERE h.status='Active' AND h.url='".$hostal."'"." AND (hc.lan='en')")->getArrayResult();	
			if(empty($hostals)){ 
				return $this->redirect($this->generateUrl('mytrip_user_story'));
			}
		}
		
		$story_query = $em->createQuery("SELECT s FROM MytripAdminBundle:Story s WHERE s.status='Active' AND s.url='".$story."'");			
		$storys = $story_query->getArrayResult();		
		if(empty($storys)){
			return $this->redirect($this->generateUrl('mytrip_user_story'));
		}
		
		$this->visits($request,'Story',$storys['0']['storyId']);
		
		$story_content_query = $em->createQuery("SELECT d FROM MytripAdminBundle:StoryContent d WHERE d.lan='$lan' AND d.story=".$storys[0]['storyId']);			
		$story_content = $story_content_query->getArrayResult();
		if(empty($story_content)){
			$story_content =$em->createQuery("SELECT d FROM MytripAdminBundle:StoryContent d WHERE d.lan='en' AND d.story=".$storys[0]['storyId'])->getArrayResult();	
		}
		
		$banner_query = $em->createQuery("SELECT p FROM MytripAdminBundle:Banner p WHERE p.bannerType='Story' AND p.typeId=".$storys['0']['storyId']);			
		$banner = $banner_query->getArrayResult();
		
		$seo['title']=$story_content[0]['metaTitle'];
		$seo['metadescription']=$story_content[0]['metaDescription'];
		$seo['metakeywords']=$story_content[0]['metaKeyword'];
		
		return $this->render('MytripUserBundle:Default:storydetails.html.php',array('seo'=>$seo,'story'=>$storys,'destination'=>$destinations,'banner'=>$banner,'story_content'=>$story_content,'hostals'=>$hostals,'language'=>$this->language(),'soacillink'=>$this->getsociallink(),'lan'=>$lan));
		
	}
	
	/*****Destination Details*****/
	public function destinationDetailsAction(Request $request,$name){		
		/****Check language session*******/
		$session = $request->getSession();	
		$this->langsession($session);
		$lan=$session->get('language');
		$request->setLocale($lan);		
				
		$em = $this->container->get('doctrine')->getManager();
		
		$destination_query = $em->createQuery("SELECT d FROM MytripAdminBundle:Destination d WHERE d.status='Active' AND d.url='".$name."'");			
		$destination = $destination_query->getArrayResult();		
		if(empty($destination)){
			return $this->redirect($this->generateUrl('mytrip_user_destination'));
		}
		
		$this->visits($request,'Destination',$destination['0']['destinationId']);
		
		$destination_content_query = $em->createQuery("SELECT d FROM MytripAdminBundle:DestinationContent d WHERE d.lan='$lan' AND d.destination=".$destination['0']['destinationId']);			
		$destination_content = $destination_content_query->getArrayResult();
		if(empty($destination_content)){
			$destination_content_query = $em->createQuery("SELECT d FROM MytripAdminBundle:DestinationContent d WHERE d.lan='en' AND d.destination=".$destination['0']['destinationId']);			
			$destination_content = $destination_content_query->getArrayResult();
		}
		
		$banner_query = $em->createQuery("SELECT p FROM MytripAdminBundle:Banner p WHERE p.bannerType='Destination' AND p.typeId=".$destination['0']['destinationId']);			
		$banner = $banner_query->getArrayResult();
		
		$destination_feature = $em->createQuery("SELECT f FROM MytripAdminBundle:DestinationFeature d INNER JOIN MytripAdminBundle:Feature f WITH f.featureId=d.feature WHERE d.destination=".$destination['0']['destinationId'])->getArrayResult();		
		
		$hostal_count_query=$em->createQuery("SELECT (SELECT COUNT(h) FROM MytripAdminBundle:Hostal h WHERE d.destinationId=h.destination AND h.status='Active') AS hostalcount FROM MytripAdminBundle:Destination d WHERE d.destinationId=".$destination['0']['destinationId']." AND d.status='Active' ");
		$hostal_count = $hostal_count_query->getArrayResult();
		
		$hostal_query=$em->createQuery("SELECT h FROM MytripAdminBundle:Hostal h WHERE h.status='Active' AND h.destination=".$destination['0']['destinationId']);
		$hostals = $hostal_query->getArrayResult();	
		if($request->get('search')!='' && $request->get('checkin')!='' && $request->get('checkout')!='' && $request->get('guest')!=''){
			$checkin=$request->get('checkin');
			$checkout=$request->get('checkout');
			if(!empty($hostals)){
				$hostalid=array();
				foreach($hostals as $hostal){					
					$hostal_rooms=$em->createQuery("SELECT p FROM MytripAdminBundle:HostalRooms p WHERE p.hostal='".$hostal['hostalId']."'")->getArrayResult();
					$guest=$hostal_rooms['0']['guests'];
					$rooms=ceil($request->get('guest')/$guest);
					$avl=1;
					$dates=$this->getDatesBetween2Dates($checkin,$checkout);
					foreach($dates as $date){
						$booking=$em->createQuery("SELECT SUM(p.noOfRooms) AS totrooms,p FROM MytripAdminBundle:Booking p WHERE p.status NOT IN ('Cancelled') AND p.hostal='".$hostal['hostalId']."' AND p.fromDate <='".$date."' AND p.toDate >='".$date."'")->getArrayResult();	
						$tot=$booking[0]['totrooms']+$rooms;						
						if(/*$hostal_rooms[0]['rooms']*/count($hostal_rooms)<$tot){
							$avl=0;
							break;							
						}			
					}
					if($avl==1){
						$hostalid[]=$hostal['hostalId'];	
					}
					if(!empty($hostalid)){
						$hid=implode(",",$hostalid);
						$hostals=$em->createQuery("SELECT h FROM MytripAdminBundle:Hostal h WHERE h.status='Active' AND h.destination=".$destination['0']['destinationId'])->getArrayResult();
						$hostal_count['0']['hostalcount']=count($hid);
					}else{
						$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Sorry, Hostal not available. Please try another dates'));
						return $this->redirect($this->container->get('router')->getContext()->getBaseUrl()."/".$destination[0]['url']);
					}
				}
			}else{
				$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Sorry, Hostal not available in this destination'));
				return $this->redirect($this->container->get('router')->getContext()->getBaseUrl()."/".$destination[0]['url']);
			}
		}
		
		$seo['title']=$destination_content[0]['metaTitle'];
		$seo['metadescription']=$destination_content[0]['metaDescription'];
		$seo['metakeywords']=$destination_content[0]['metaKeyword'];
				
        return $this->render('MytripUserBundle:Default:destinationdetails.html.php',array('seo'=>$seo,'destination'=>$destination,'banner'=>$banner,'destination_content'=>$destination_content,'destination_feature'=>$destination_feature,'hostal_count'=>$hostal_count,'hostals'=>$hostals,'language'=>$this->language(),'soacillink'=>$this->getsociallink(),'lan'=>$lan));
    }
	
	/*****search Details*****/
	public function searchAction(Request $request){		
		/****Check language session*******/
		$session = $request->getSession();	
		$this->langsession($session);
		$lan=$session->get('language');
		$request->setLocale($lan);		
				
		$em = $this->container->get('doctrine')->getManager();
		if($request->getMethod()=="POST" && $request->request->get('searchtext') !=''){
			return $this->redirect($this->generateUrl('mytrip_user_search',array('search'=>$request->request->get('searchtext'))));
		}
		if($request->get('search') ==''){
			$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Enter the search term'));
			return $this->redirect($this->generateUrl('mytrip_user_homepage'));
		}else{
			$search=$request->get('search');
			$hostals=$em->createQuery("SELECT h,IDENTITY(h.destination) AS destinations FROM MytripAdminBundle:Hostal h LEFT JOIN MytripAdminBundle:HostalContent hc WITH hc.hostal=h.hostalId  AND hc.lan='".$lan."' LEFT JOIN MytripAdminBundle:HostalRooms hr WITH hr.hostal=h.hostalId WHERE h.status='Active' AND (hc.name LIKE '%".$search."%' OR hc.description LIKE '%".$search."%' OR hc.city LIKE '%".$search."%' OR hr.roomtype LIKE '%".$search."%') ")->getArrayResult();
			//"SELECT h,IDENTITY(h.destination) AS destinations FROM MytripAdminBundle:Hostal h LEFT JOIN MytripAdminBundle:HostalContent hc WITH hc.hostal=h.hostalId LEFT JOIN MytripAdminBundle:HostalRooms hr WITH hr.hostal=h.hostalId WHERE h.status='Active' AND (hc.name LIKE '%".$search."%' OR hc.description LIKE '%".$search."%' OR hc.city LIKE '%".$search."%' OR hr.roomtype LIKE '%".$search."%') ")
		}
		//Sprint_r($hostals);exit;
		
		$seo['title']=$search.'.......';
		$seo['metadescription']=$search.'.......';
		$seo['metakeywords']=$search.'.......';
		
		
		return $this->render('MytripUserBundle:Default:search.html.php',array('hostals'=>$hostals,'seo'=>$seo,'language'=>$this->language(),'soacillink'=>$this->getsociallink(),'lan'=>$lan));
	}
	
	/*****Destination Details*****/
	public function hostalAction(Request $request,$destination,$hostal){
		/****Check language session*******/
		$session = $request->getSession();	
		$this->langsession($session);
		$lan=$session->get('language');
		$request->setLocale($lan);
				
		$em = $this->container->get('doctrine')->getManager();
		
		$destination_query = $em->createQuery("SELECT d FROM MytripAdminBundle:Destination d WHERE d.status='Active' AND d.url='".$destination."'");			
		$destinations = $destination_query->getArrayResult();		
		if(empty($destinations)){
			return $this->redirect($this->generateUrl('mytrip_user_destination'));
		}
		
		$hostal_query = $em->createQuery("SELECT h FROM MytripAdminBundle:Hostal h WHERE h.status='Active' AND h.url='".$hostal."' AND h.destination='".$destinations['0']['destinationId']."'");			
		$hostals = $hostal_query->getArrayResult();		
		if(empty($hostals)){
			return $this->redirect($this->generateUrl('mytrip_user_destination'));
		}
		
		$this->visits($request,'Hostal',$hostals['0']['hostalId']);
		
		$hostal_content_query = $em->createQuery("SELECT d FROM MytripAdminBundle:HostalContent d WHERE d.lan='$lan' AND d.hostal=".$hostals[0]['hostalId']);			
		$hostal_content = $hostal_content_query->getArrayResult();
		if(empty($hostal_content)){
			$hostal_content_query = $em->createQuery("SELECT d FROM MytripAdminBundle:HostalContent d WHERE d.lan='en' AND d.hostal=".$hostals[0]['hostalId']);			
			$hostal_content = $hostal_content_query->getArrayResult();
		}
		
		$hostal_feature_query = $em->createQuery("SELECT f FROM MytripAdminBundle:HostalFeature d INNER JOIN MytripAdminBundle:Feature f WITH f.featureId=d.feature WHERE d.hostal=".$hostals['0']['hostalId']);			
		$hostal_feature = $hostal_feature_query->getArrayResult();
		
		$banner_query = $em->createQuery("SELECT p FROM MytripAdminBundle:Banner p WHERE p.bannerType='Hostal' AND p.typeId=".$hostals['0']['hostalId']);			
		$banner = $banner_query->getArrayResult();
		
		$seo['title']=$hostal_content[0]['metaTitle'];
		$seo['metadescription']=$hostal_content[0]['metaDescription'];
		$seo['metakeywords']=$hostal_content[0]['metaKeyword'];
		
		return $this->render('MytripUserBundle:Default:hostal.html.php',array('seo'=>$seo,'hostals'=>$hostals,'destinations'=>$destinations,'banner'=>$banner,'hostal_content'=>$hostal_content,'hostal_feature'=>$hostal_feature,'language'=>$this->language(),'soacillink'=>$this->getsociallink(),'lan'=>$lan));
	}
	
	/*****Booking Details*****/
	public function bookingAction(Request $request,$destination,$hostal){
		/****Check language session*******/
		$session = $request->getSession();	
		$this->langsession($session);
		$lan=$session->get('language');
		$request->setLocale($lan);
		
		if($session->get('booking')){
			$session->remove('booking');
		}
				
		$em = $this->container->get('doctrine')->getManager();
		
		$destination_query = $em->createQuery("SELECT d FROM MytripAdminBundle:Destination d WHERE d.status='Active' AND d.url='".$destination."'");			
		$destinations = $destination_query->getArrayResult();		
		if(empty($destinations)){
			return $this->redirect($this->generateUrl('mytrip_user_destination'));
		}
		
		$hostal_query = $em->createQuery("SELECT h FROM MytripAdminBundle:Hostal h WHERE h.status='Active' AND h.url='".$hostal."'");			
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
		
		$hostal_rooms = $em->createQuery("SELECT d FROM MytripAdminBundle:HostalRooms d WHERE d.hostal=".$hostals[0]['hostalId'])->getResult();

		if($request->getMethod()=="POST"){			
			$available=$this->availabilitychecking($request->request->get('checkin'),$request->request->get('checkout'),$hostals[0]['hostalId'],count($hostal_rooms));			
			if($available['avl']=="1"){			
				if($session->get('user')==''){
					$emailcheck = $em->createQuery("SELECT p FROM MytripAdminBundle:User p WHERE p.email='".$request->request->get('email')."'")->getArrayResult();
					if(empty($emailcheck)){				
						$randno=sha1($this->str_rand().date('Y-m-d H:i:s'));
						$password=$request->request->get('password');
						$member = new \Mytrip\AdminBundle\Entity\User();
						$user_name=$request->request->get('firstname').' '.$request->request->get('lastname');
						$address=$request->request->get('address').', '.$request->request->get('city');
						$member->setFirstname($request->request->get('firstname'));
						$member->setLastname($request->request->get('lastname'));
						$member->setEmail($request->request->get('email'));
						$member->setPassword(sha1($password));
						$member->setGender($request->request->get('gender'));
						$member->setPhone($request->request->get('phone'));
						$member->setMobile($request->request->get('mobile'));
						$member->setAddress($request->request->get('address'));
						$member->setCountry($request->request->get('country'));
						$member->setProvince($request->request->get('province'));
						$member->setCity($request->request->get('city'));
						$member->setZip($request->request->get('zip'));
						$member->setLan($lan);
						$member->setUserKey($randno);	
						$member->setStatus('Pending');				
						$member->setCreatedDate(new \DateTime(date('Y-m-d H:i:s')));
						$member->setModifyDate(new \DateTime(date('Y-m-d H:i:s')));			
						$em->persist($member);
						$em->flush();
						$uid=$member->getUserId();
						$user_temp = $em->createQuery("SELECT p FROM MytripAdminBundle:User p WHERE p.userId='".$uid."' ")->getArrayResult();
						$session->set('user',$user_temp[0]);
						$session->set('usertemp', "1");
						$emaillist=$em->getRepository('MytripAdminBundle:EmailList')->findOneBy(array('emailListId'=>'6'));							
						$emailcontent=$em->getRepository('MytripAdminBundle:EmailContent')->findOneBy(array('emailList'=>'6','lan'=>$lan));
						if(!empty($emailcontent)){
							$emailcontent=$em->getRepository('MytripAdminBundle:EmailContent')->findOneBy(array('emailList'=>'6','lan'=>'en'));
						}				
						$link=$this->getRequest()->getSchemeAndHttpHost()."/".$this->container->get('router')->getContext()->getBaseUrl()."/".$this->generateUrl('mytrip_user_confirm')."?u_my_code=".$randno."_".sha1($uid);
						$message=str_replace(array('{name}','{username}','{password}','{link}'),array($request->request->get('firstname').' '.$request->request->get('lastname'),$request->request->get('email'),$password,$link),$emailcontent->getEmailContent());
						$subject=str_replace(array('{name}','{username}','{password}','{link}'),array($request->request->get('firstname').' '.$request->request->get('lastname'),$request->request->get('email'),$password,$link),$emailcontent->getSubject());
						
						/*******signup mail send to admin***********/								
						$this->mailsend($emaillist->getFromname(),$emaillist->getFromemail(),$request->request->get('email'),$subject,$message,$emaillist->getCcmail());
					}else{
						$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Email id already exists'));
						$session->set('booking',$request->request);	
					}
				}else{
					$buser=$session->get('user');
					$uid=$buser['userId'];
					$busers=$em->createQuery("SELECT p FROM MytripAdminBundle:User p  WHERE  p.userId='".$uid."'")->getArrayResult();
					if($busers[0]['province']!=''){
						$province=$em->createQuery("SELECT d FROM MytripAdminBundle:States d WHERE d.sid=".$busers[0]['province'])->getArrayResult();
					}
					if($busers[0]['country']!=''){
						$country=$em->createQuery("SELECT d FROM MytripAdminBundle:Country d WHERE d.cid=".$busers[0]['country'])->getArrayResult();
					}
					$user_name=$busers[0]['firstname'].' '.$busers[0]['lastname'];
					$address=$busers[0]['address'].', '.$busers[0]['city'].', '.($busers[0]['province']!=''?$province[0]['state'].', ':'').($busers[0]['country']!=''?$country[0]['country']:'');
				}				
				$rooms_data = '| ';
				if(!empty($uid))
				{					
					$booking = new \Mytrip\AdminBundle\Entity\Booking();
					$booking->setHostal($this->getDoctrine()->getRepository('MytripAdminBundle:Hostal')->find($hostals[0]['hostalId']));

					$guests = $adults = $children = $count = $price = 0;
					foreach ($hostal_rooms as $room) {
						$id = $room->getRoomId();
						$selected = $request->request->get("selected_rooms");
						if(isset($selected[$id])) {
							$booking->addRoom($room);
							$guests += $room->getGuests();
							$adults += $room->getAdults();
							$children += $room->getChild();
							$price += $room->getPrice();
							$count++;
							$rooms_data .= $room->getRoomtype() . ' | ';
						}
					}

					$booking->setUser($this->getDoctrine()->getRepository('MytripAdminBundle:User')->find($uid));
					$booking->setFromDate(new \DateTime($request->request->get('checkin')));
					$booking->setToDate(new \DateTime($request->request->get('checkout')));
					$booking->setNoOfDays($this->noofdays($request->request->get('checkin'),$request->request->get('checkout')));
					$booking->setNoOfRooms($count);
					$booking->setGuests($guests);
					$booking->setAdults($adults);
					$booking->setChild($children);
					$booking->setCreatedDate(new \DateTime(date('Y-m-d H:i:s')));
					$booking->setStatus('Pending');	
					$em->persist($booking);
					$em->flush();					
					$booking_id=$booking->getBookingId();					
					$booking_info = new \Mytrip\AdminBundle\Entity\BookingInfo();
					$booking_info->setBooking($this->getDoctrine()->getRepository('MytripAdminBundle:Booking')->find($booking_id));	
					$booking_info->setFirstname($request->request->get('firstname'));
					$booking_info->setLastname($request->request->get('lastname'));
					$booking_info->setEmail($request->request->get('email'));
					$booking_info->setGender($request->request->get('gender'));
					$booking_info->setCccode($request->request->get('cccode')*1);
					$booking_info->setPhone($request->request->get('phone'));
					$booking_info->setCmcode($request->request->get('cmcode')*1);
					$booking_info->setMobile($request->request->get('mobile'));
					$booking_info->setAddress($request->request->get('address'));
					$booking_info->setAddress1($request->request->get('address1'));
					$booking_info->setCountry($request->request->get('country'));
					$booking_info->setProvince($request->request->get('province'));
					$booking_info->setZip($request->request->get('zip'));
					$booking_info->setCity($request->request->get('city'));	
					$em->persist($booking_info);
					$em->flush();
					$booking_price = new \Mytrip\AdminBundle\Entity\BookingPrice();
					$booking_price->setBooking($this->getDoctrine()->getRepository('MytripAdminBundle:Booking')->find($booking_id));	
					$totalprice=$price*/*$count**/$this->noofdays($request->request->get('checkin'),$request->request->get('checkout'));
					$booking_price->setTotalPrice($totalprice);
					$booking_price->setDefaultCurrency('CAD');
					$conversion=$this->currencyconversion();
					$booking_price->setConversionRate($conversion['conversionrate']);
					$booking_price->setConversionPrice($conversion['conversionrate']*$totalprice);
					$booking_price->setConversionCurrency($conversion['currency']);
					$em->persist($booking_price);
					$em->flush();
					$session->set('bookingId',$booking_id);
					
					/*******Contact mail send to admin***********/								
					$this->mailsend("Mytrip Cuba","info@mytriptocuba.com",$request->request->get('email'),$this->get('translator')->trans('Booking Details'),'','',0,'','prebook');
					$login = $this->container->get('mytrip_admin.helper.sms')->getOption('smsusername');
					$password = $this->container->get('mytrip_admin.helper.sms')->getOption('smspassword');
					$prefix = $request->request->get('cmcode');	
					$number = $request->request->get('mobile');
					$msg = urlencode($this->get('translator')->trans('Dear Customer, We thank you for making a reservation in our site. Your reference no is').' '."venacuba-".$booking_id*1024);					
					$URL="http://api.smsacuba.com/api10allcountries.php?";
					$URL.="login=".$login."&password=".$password."&prefix=".$prefix."&number=".$number."&sender=Mytriptocuba"."&msg=".$msg;						
					$r=@file($URL);
					$succmsg = $r[0];
					
					/**Pre-booking send to the hostal owner email id***/
					$emaillist=$em->getRepository('MytripAdminBundle:EmailList')->findOneBy(array('emailListId'=>'8'));							
					$emailcontent=$em->getRepository('MytripAdminBundle:EmailContent')->findOneBy(array('emailList'=>'8','lan'=>$lan));
					if(empty($emailcontent)){
						$emailcontent=$em->getRepository('MytripAdminBundle:EmailContent')->findOneBy(array('emailList'=>'8','lan'=>'en'));
					}			
					$setting=$em->createQuery("SELECT p FROM MytripAdminBundle:Settings p")->getArrayResult();	
					if($hostals[0]['ownerEmail']!=''){
						$message=str_replace(array('{owner_name}','{hostal_name}','{check_in}','{check_out}','{rooms}','{nights}','{username}','{address}','{accommodation_cost}','{reservation_charge}','{total_cost}','{ref_no}'),
							array($hostal_content[0]['ownerName'],$hostal_content[0]['name'],$request->request->get('checkin'),$request->request->get('checkout'),					
							$rooms_data,$this->noofdays($request->request->get('checkin'),$request->request->get('checkout')),$user_name,$address,number_format($totalprice*$conversion['conversionrate'],2).' '.$conversion['currency'],number_format($totalprice*$setting[0]['reservationCharge']/100,2).' '.$conversion['currency'],number_format($totalprice+($totalprice*$setting[0]['reservationCharge']/100)*$conversion['conversionrate'],2).' '.$conversion['currency'],"venacuba-".$booking_id*1024),
							$emailcontent->getEmailContent());
						$subject=str_replace(array('{owner_name}','{hostal_name}','{check_in}','{check_out}','{rooms}','{nights}','{username}','{address}','{accommodation_cost}','{reservation_charge}','{total_cost}','{ref_no}'),array($hostal_content[0]['ownerName'],$hostal_content[0]['name'],$request->request->get('checkin'),$request->request->get('checkout'),					
							$rooms_data,$this->noofdays($request->request->get('checkin'),$request->request->get('checkout')),$user_name,$address,number_format($totalprice*$conversion['conversionrate'],2).' '.$conversion['currency'],number_format($totalprice*$setting[0]['reservationCharge']/100,2).' '.$conversion['currency'],number_format($totalprice+($totalprice*$setting[0]['reservationCharge']/100)*$conversion['conversionrate'],2).' '.$conversion['currency'],"venacuba-".$booking_id*1024),
							$emailcontent->getSubject());

						$this->mailsend($emaillist->getFromname(),$emaillist->getFromemail(),$hostals[0]['ownerEmail'],$subject,$message,$emaillist->getCcmail());
					}
					
					if($hostals[0]['cmcode']!='' && $hostals[0]['mobile']!=''){
						/**Pre-booking send to the hostal owner mobile***/
						$prefix = $hostals[0]['cmcode'];	
						$number = $hostals[0]['mobile'];
						$msg = urlencode($this->get('translator')->trans('Dear '.$hostal_content[0]['ownerName'].', '.$user_name.' has pre-booking in the '.$hostal_content[0]['name'].'. Reference no is').' '."venacuba-".$booking_id*1024);					
						$msg = urlencode($this->get('translator')->trans('Estimado(a) '.$hostal_content[0]['ownerName'].', '.$user_name.' tiene una pre-reserva en '.$hostal_content[0]['name'].'. No de referencia ').' '."venacuba-".$booking_id*1024);
                                                $URL="http://api.smsacuba.com/api10allcountries.php?";
						$URL.="login=".$login."&password=".$password."&prefix=".$prefix."&number=".$number."&sender=Mytriptocuba"."&msg=".$msg;						
						$r=@file($URL);
						$succmsg = $r[0];
					}
					
					/**Pre-booking send to the Site Admin owner email id***/
					$emaillist=$em->getRepository('MytripAdminBundle:EmailList')->findOneBy(array('emailListId'=>'9'));							
					$emailcontent=$em->getRepository('MytripAdminBundle:EmailContent')->findOneBy(array('emailList'=>'9','lan'=>$lan));
					if(empty($emailcontent)){
						$emailcontent=$em->getRepository('MytripAdminBundle:EmailContent')->findOneBy(array('emailList'=>'9','lan'=>'en'));
					}
					$admin=$em->createQuery("SELECT p FROM MytripAdminBundle:Admin p WHERE p.adminId='1'")->getArrayResult();				
					$setting=$em->createQuery("SELECT p FROM MytripAdminBundle:Settings p")->getArrayResult();	
					$message=str_replace(array('{admin_name}','{owner_name}','{hostal_name}','{check_in}','{check_out}','{rooms}','{nights}','{username}','{address}','{accommodation_cost}','{reservation_charge}','{total_cost}','{ref_no}'),
										 array($admin[0]['name'],$hostal_content[0]['ownerName'],$hostal_content[0]['name'],$request->request->get('checkin'),$request->request->get('checkout'),$rooms_data,$this->noofdays($request->request->get('checkin'),$request->request->get('checkout')),$user_name,$address,number_format($totalprice*$conversion['conversionrate'],2).' '.$conversion['currency'],number_format($totalprice*$setting[0]['reservationCharge']/100,2).' '.$conversion['currency'],number_format($totalprice+($totalprice*$setting[0]['reservationCharge']/100)*$conversion['conversionrate'],2).' '.$conversion['currency'],"venacuba-".$booking_id*1024),$emailcontent->getEmailContent());
					$subject=str_replace(array('{admin_name}','{owner_name}','{hostal_name}','{check_in}','{check_out}','{rooms}','{nights}','{username}','{address}','{accommodation_cost}','{reservation_charge}','{total_cost}','{ref_no}'),
										 array($admin[0]['name'],$hostal_content[0]['ownerName'],$hostal_content[0]['name'],$request->request->get('checkin'),$request->request->get('checkout'),$rooms_data,$this->noofdays($request->request->get('checkin'),$request->request->get('checkout')),$user_name,$address,number_format($totalprice*$conversion['conversionrate'],2).' '.$conversion['currency'],number_format($totalprice*$setting[0]['reservationCharge']/100,2).' '.$conversion['currency'],number_format($totalprice+($totalprice*$setting[0]['reservationCharge']/100)*$conversion['conversionrate'],2).' '.$conversion['currency'],"venacuba-".$booking_id*1024),$emailcontent->getSubject());
												
					$this->mailsend($emaillist->getFromname(),$emaillist->getFromemail(),$admin[0]['email'],$subject,$message,$emaillist->getCcmail());
					
					if($admin[0]['cmcode']!='' && $admin[0]['mobile']!=''){
						/**Pre-booking send to the Site Admin owner mobile***/
						$prefix = $admin[0]['cmcode'];	
						$number = $admin[0]['mobile'];
						$msg = urlencode($this->get('translator')->trans('Dear '.$admin[0]['name'].', '.$user_name.' has pre-booking in the '.$hostal_content[0]['name'].'. Reference no is').' '."venacuba-".$booking_id*1024);					
						$URL="http://api.smsacuba.com/api10allcountries.php?";
						$URL.="login=".$login."&password=".$password."&prefix=".$prefix."&number=".$number."&sender=Mytriptocuba"."&msg=".$msg;						
						$r=@file($URL);
						$succmsg = $r[0];
					}
					
					$session->remove('bookingId');
					
					if($succmsg=="SMS ENVIADO"){
						$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Rooms booking successfull. Booking details sent to your mail id and SMS.'));
					}else{
						$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Rooms booking successfull. Booking details sent to your mail id.'));
					}
					
					if($session->get('usertemp')!=''){
						$session->remove('user');
						$session->remove('usertemp');
					}
					
										
					if($session->get('user')!=''){
						return $this->redirect($this->generateUrl('mytrip_user_makepayment',array('bookingId'=>$booking_id*1024)));
					}else{
						$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Successfully Registered. Confirmation link sent to your mail id.').' '.$this->get('translator')->trans('Once complete your registration process, you can make the payment of booking room'));
						return $this->redirect($this->generateUrl('mytrip_user_homepage'));
					}
				}
			}else{
				$this->get('session')->getFlashBag()->add('success',$available['msg']);
				$session->set('booking',$request->request);			
				//return $this->redirect($request->server->get('HTTP_REFERER'));
			}
			
		}
		
		$country=$em->createQuery("SELECT p FROM MytripAdminBundle:Country p")->getArrayResult();	
		
		$book_content = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='$lan' AND p.staticpage=20 ")->getArrayResult();
		if(empty($book_content)){
			$book_content = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage=20")->getArrayResult();	
		}
		
		$seo['title']=$book_content[0]['pageTitle'];
		$seo['metadescription']=$book_content[0]['metaDescription'];
		$seo['metakeywords']=$book_content[0]['metaKeyword'];
		
		return $this->render('MytripUserBundle:Default:booking.html.php',array('seo'=>$seo,'hostals'=>$hostals,'destinations'=>$destinations,'country'=>$country,'hostal_content'=>$hostal_content,'hostal_rooms'=>$hostal_rooms,'language'=>$this->language(),'soacillink'=>$this->getsociallink()));
	}
	
	public function makepaymentAction(Request $request){		
		$response=$this->checkUser($request->getSession());		
		if($response){
			return $response;
		}
		/****Check language session*******/
		$session = $request->getSession();	
		$this->langsession($session);
		$session->remove('bookingId');$session->remove('payment');
		$lan=$session->get('language');
		$request->setLocale($lan);
		
		$usersession=$session->get('user');		
		$em = $this->container->get('doctrine')->getManager();	
		$bookingid=$request->get('bookingId')/1024;	
		$booking=$em->createQuery("SELECT d,IDENTITY(d.user) AS userid,IDENTITY(d.hostal) AS hostal FROM MytripAdminBundle:Booking d WHERE d.bookingId=".$bookingid)->getArrayResult();	
		$hostal=$em->createQuery("SELECT d FROM MytripAdminBundle:Hostal d WHERE d.hostalId='".$booking[0]['hostal']."'")->getArrayResult();	
		if(empty($booking) || $booking[0]['userid']!=$usersession['userId']){
			$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Sorry, Your booking id is wrong. Please check once again'));
			return $this->redirect($this->generateUrl('mytrip_user_homepage'));
		}
		
		if($booking[0][0]['status']=="Confirmed" || $booking[0][0]['status']=="Cancelled"){
			$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Sorry, Payment already finished'));
			return $this->redirect($this->generateUrl('mytrip_user_homepage'));
		}
		
		$booking_price=$em->createQuery("SELECT d FROM MytripAdminBundle:BookingPrice d WHERE d.booking=".$bookingid)->getArrayResult();
		if($booking_price[0]['conversionCurrency']!=$session->get('currency')){
			$conversion=$this->currencyconversion();			
			$em->createQuery("UPDATE MytripAdminBundle:BookingPrice p SET p.conversionRate='".$conversion['conversionrate']."',p.conversionPrice='".$booking_price[0]['totalPrice']*$conversion['conversionrate']."',p.conversionCurrency='".$conversion['currency']."' WHERE p.bookingPriceId='".$booking_price[0]['bookingPriceId']."'")->execute();
		}
		
		if($request->getMethod()=="POST"){			
			$payment=$request->request->get('payment');
			$session->set('bookingId',$bookingid);
			$session->set('payment',$payment);
			
			$booking_price=$em->createQuery("SELECT d FROM MytripAdminBundle:BookingPrice d WHERE d.booking=".$bookingid)->getArrayResult();
			$setting=$em->createQuery("SELECT d FROM MytripAdminBundle:Settings d ")->getArrayResult();
			
			if($request->request->get('paymenttype')=="full"){
				$reservationamount=$booking_price[0]['totalPrice'];
			}else{
				$reservationamount=round(($setting[0]['bookingPercentage']*$booking_price[0]['totalPrice'])/100,2);
			}
			
			$reservation_charge=round(($booking_price[0]['totalPrice']*$setting[0]['reservationCharge']/100),2);
			$reservation_total_amount=$reservationamount+$reservation_charge;
			
			$em->createQuery("UPDATE MytripAdminBundle:BookingPrice p SET p.reservationPrice='".$reservationamount."', p.reservationCharge='".$reservation_charge."', p.reservationTotalPrice='".$reservation_total_amount."', p.paymenttype='".$request->request->get('paymenttype')."' WHERE p.booking='".$bookingid."'")->execute();	
					
			if($payment=="paypal"){
				return $this->redirect($this->generateUrl('mytrip_user_paypal'));
			}/*elseif($payment=="stripe"){
				$this->get('session')->getFlashBag()->add('stripe',1);				 
			}elseif($payment=="skrill"){
				return $this->redirect($this->generateUrl('mytrip_user_skrill'));
			}*/elseif($payment=="beanstream"){
				$this->get('session')->getFlashBag()->add('beanstream',1);
			}elseif($payment=="globalone"){
				$this->get('session')->getFlashBag()->add('globalone',1);
			}
		}
		$keys = $this->container->get('payum.context.stripe_checkout.keys');
		
		$make_payment_content = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='$lan' AND p.staticpage=21 ")->getArrayResult();
		if(empty($make_payment_content)){
			$make_payment_content = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage=21")->getArrayResult();	
		}
		
		$seo['title']=$make_payment_content[0]['pageTitle'];
		$seo['metadescription']=$make_payment_content[0]['metaDescription'];
		$seo['metakeywords']=$make_payment_content[0]['metaKeyword'];
		
		return $this->render('MytripUserBundle:Default:makepayment.html.php',array('seo'=>$seo,'booking'=>$booking,'language'=>$this->language(),'soacillink'=>$this->getsociallink(),'keys'=>$keys,'hostal'=>$hostal));
	}
	
	/*****Currency Conversion*********/
	private function currencyconversion(){
		$from_Currency='CAD';
		$session = $this->getRequest()->getSession();
		if($session->get('currency')!='' && $session->get('currency')!='CAD'){
			$amount=1;	
			$to_Currency=$session->get('currency');			
			$url = 'http://finance.yahoo.com/d/quotes.csv?e=.csv&f=sl1d1t1&s='. $from_Currency . $to_Currency .'=X';
			$handle = @fopen($url, 'r');
			if ($handle){
				$result = fgets($handle, 4096);
				fclose($handle);
			}
			$allData = explode(',',$result);
			$dollarValue = $allData[1];
			$var=round($dollarValue,2)*$amount;
			return array('currency'=>$session->get('currency'),'conversionrate'=>$this->formatMoney($var, true));	
		}else{
			return array('currency'=>$from_Currency,'conversionrate'=>1);	
		}
	}
	
	private function formatMoney($number, $fractional=false) {
		if ($fractional) {
			$number = sprintf('%.2f', $number);
		}
		while (true) {
			$replaced = preg_replace('/(-?\d+)(\d\d\d)/', '$1,$2', $number);
			if ($replaced != $number) {
				$number = $replaced;
			} else {
				break;
			}
		}
		return $number;
	}
	
	/*****using ajax Booking Details*****/
	public function ajaxcheckAction(Request $request){		
		/* return 0-Not Available, 1-To Confirm, 2-Available */
		
		/****Check language session*******/
		$session = $request->getSession();	
		$this->langsession($session);
		$lan=$session->get('language');
		$request->setLocale($lan);
		$des=$request->get('des');
		$hostal=$request->get('hostal');
		$checkin=$request->get('checkin');
		$checkout=$request->get('checkout');
		$rooms=$request->get('rooms');
		if($rooms==''){
			$rooms=1;
		}
		$avl=1;
		$msg=$this->get('translator')->trans('Rooms are available');;
		$em = $this->container->get('doctrine')->getManager();
		
		$destination=$em->createQuery("SELECT p FROM MytripAdminBundle:Destination p WHERE p.url='".$des."'")->getArrayResult();		
		$hostal_details=$em->createQuery("SELECT p FROM MytripAdminBundle:Hostal p WHERE p.url='".$hostal."' AND p.destination='".$destination[0]['destinationId']."'")->getArrayResult();
		
		$hostal_rooms=$em->createQuery("SELECT p FROM MytripAdminBundle:HostalRooms p WHERE p.hostal='".$hostal_details[0]['hostalId']."'")->getArrayResult();
		
		$dates=$this->getDatesBetween2Dates($checkin,$checkout);
		foreach($dates as $date){
			$booking=$em->createQuery("SELECT SUM(p.noOfRooms) AS totrooms,p FROM MytripAdminBundle:Booking p WHERE p.status NOT IN ('Cancelled') AND p.hostal='".$hostal_details[0]['hostalId']."' AND p.fromDate <='".$date."' AND p.toDate >='".$date."'")->getArrayResult();	
			$tot=$booking[0]['totrooms']+$rooms;
			
			if(/*$hostal_rooms[0]['rooms']*/count($hostal_rooms)<$tot){
				$avl=0;
				$msg=$this->get('translator')->trans('Rooms not available. Please check another dates');
				break;
			}			
		}
		$check=array('avl'=>$avl,'msg'=>$msg);
		 return new Response(json_encode($check));
			
	}
	
	private function availabilitychecking($start,$end,$hostalid,$rooms){
		$em = $this->container->get('doctrine')->getManager();
		$hostal_rooms=$em->createQuery("SELECT p FROM MytripAdminBundle:HostalRooms p WHERE p.hostal='".$hostalid."'")->getArrayResult();
		$dates=$this->getDatesBetween2Dates($start,$end);
		$avl=1;$msg=$this->get('translator')->trans('Rooms are available');;
		foreach($dates as $date){
			$booking=$em->createQuery("SELECT SUM(p.noOfRooms) AS totrooms,p FROM MytripAdminBundle:Booking p WHERE p.status NOT IN ('Cancelled') AND p.hostal='".$hostalid."' AND p.fromDate <='".$date."' AND p.toDate >='".$date."'")->getArrayResult();
			$tot=$booking[0]['totrooms']+$rooms;
			if(/*$hostal_rooms[0]['rooms']*/count($hostal_rooms)<$tot){
				$avl=0;
				$msg=$this->get('translator')->trans('Rooms not available. Please check another dates');
				break;
			}			
		}
		$check=array('avl'=>$avl,'msg'=>$msg);
		 return $check;
	}
	
	public function roomavailablityAction(Request $request,$destination,$hostal){
		
		$em = $this->container->get('doctrine')->getManager();
		
		$destinations = $em->createQuery("SELECT d FROM MytripAdminBundle:Destination d WHERE d.status='Active' AND d.url='".$destination."'")->getArrayResult();
		$hostals = $em->createQuery("SELECT h FROM MytripAdminBundle:Hostal h WHERE h.status='Active' AND h.url='".$hostal."' AND h.destination='".$destinations['0']['destinationId']."'")->getArrayResult();
		$hostalid=$hostals[0]['hostalId'];
		$hostal_rooms=$em->createQuery("SELECT p FROM MytripAdminBundle:HostalRooms p WHERE p.hostal='".$hostalid."'")->getArrayResult();		
		$toconfirm=array();$unavailable=array();
		foreach($_REQUEST['keys'] as $keys){
			$start=$keys.'-01';
			$end=$keys.'-'.date('t',strtotime($start));		
			$dates=$this->getDatesBetween2Dates($start,$end);				
			foreach($dates as $date){				
				$booking=$em->createQuery("SELECT SUM(p.noOfRooms) AS totrooms,p FROM MytripAdminBundle:Booking p WHERE p.status NOT IN ('Cancelled') AND p.hostal='".$hostalid."' AND p.fromDate <='".$date."' AND p.toDate >='".$date."'")->getArrayResult();									
				$tot=$booking[0]['totrooms']; 
				if(count($hostal_rooms)<=$tot){
					$status=$em->createQuery("SELECT p FROM MytripAdminBundle:Booking p WHERE p.status IN ('Pending') AND p.hostal='".$hostalid."' AND p.fromDate <='".$date."' AND p.toDate >='".$date."'")->getArrayResult();
					if(!empty($status)){
						$toconfirm[]=$date;
					}else{
						$unavailable[]=$date;
					}
					
				}			
			}
		}		
		return new Response(json_encode(array('unavailable'=>$unavailable,'toconfirm'=>$toconfirm)));
	}
	
	/****Sign up*******/
	public function signupAction(Request $request){
		$checkrefer=$this->referercheck($request);		
		if($checkrefer==false){
			return $this->redirect($this->generateUrl('mytrip_user_homepage'));
		}		
		/****Check language session*******/
		$session = $request->getSession();	
		$this->langsession($session);
		$lan=$session->get('language');
		$request->setLocale($lan);
                
		if($session->get('register')){
			$session->remove('register');
		}
				
		$em = $this->container->get('doctrine')->getManager();
			
		if($request->getMethod()=="POST"){
			$emailcheck = $em->createQuery("SELECT p FROM MytripAdminBundle:User p WHERE p.email='".$request->request->get('email')."' AND p.status NOT IN ('Trash')")->getArrayResult();
			if(empty($emailcheck)){				
				$randno=sha1($this->str_rand().date('Y-m-d H:i:s'));
				$password=$request->request->get('password');
				$member = new \Mytrip\AdminBundle\Entity\User();
				$member->setFirstname($request->request->get('firstname'));
				$member->setLastname($request->request->get('lastname'));
				$member->setEmail($request->request->get('email'));
				$member->setPassword(sha1($password));
				$member->setLan($lan);
				$member->setUserKey($randno);	
				$member->setStatus('Pending');				
				$member->setCreatedDate(new \DateTime(date('Y-m-d H:i:s')));
				$member->setModifyDate(new \DateTime(date('Y-m-d H:i:s')));			
				$em->persist($member);
				$em->flush();
				$uid=$member->getUserId();
				$emaillist=$em->getRepository('MytripAdminBundle:EmailList')->findOneBy(array('emailListId'=>'6'));							
				$emailcontent=$em->getRepository('MytripAdminBundle:EmailContent')->findOneBy(array('emailList'=>'6','lan'=>$lan));
				if(empty($emailcontent)){
					$emailcontent=$em->getRepository('MytripAdminBundle:EmailContent')->findOneBy(array('emailList'=>'6','lan'=>'en'));
				}				
				$link=$this->getRequest()->getSchemeAndHttpHost().$this->generateUrl('mytrip_user_confirm')."?u_my_code=".$randno."_".sha1($uid);

				$message=str_replace(array('{name}','{username}','{password}','{link}'),array($request->request->get('firstname').' '.$request->request->get('lastname'),$request->request->get('email'),$password,$link),$emailcontent->getEmailContent());
				$subject=str_replace(array('{name}','{username}','{password}','{link}'),array($request->request->get('firstname').' '.$request->request->get('lastname'),$request->request->get('email'),$password,$link),$emailcontent->getSubject());
				
				/*******Contact mail send to admin***********/								
				$this->mailsend($emaillist->getFromname(),$emaillist->getFromemail(),$request->request->get('email'),$subject,$message,$emaillist->getCcmail());
				
				$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Successfully Registered. Confirmation link sent to your mail id.'));
				return $this->redirect($this->generateUrl('mytrip_user_homepage'));
			}else{
				$this->get('session')->getFlashBag()->add('signuperror',$this->get('translator')->trans('Email id already exists'));				
				$session->set('register',$request->request);
				return $this->redirect($request->server->get('HTTP_REFERER'));
			}
		}		
		return $this->redirect($this->generateUrl('mytrip_user_homepage'));
	}
	
	/****Sign in/ login*******/
	public function signinAction(Request $request){
		$checkrefer=$this->referercheck($request);		
		if($checkrefer==false){
			return $this->redirect($this->generateUrl('mytrip_user_homepage'));
		}
                $session = $request->getSession();	
		$this->langsession($session);
		$lan=$session->get('language');
		$request->setLocale($lan);
                
		$em = $this->container->get('doctrine')->getManager();
			
		if($request->getMethod()=="POST"){
			$emailcheck = $em->createQuery("SELECT p FROM MytripAdminBundle:User p WHERE p.email='".$request->request->get('email')."' AND p.status NOT IN ('Trash')")->getArrayResult();
			if(!empty($emailcheck)){
				if($emailcheck[0]['password']==sha1($request->request->get('password'))){
					if($emailcheck[0]['status']=='Active'){
						$session = $request->getSession();
						$session->set('user',$emailcheck[0]);
						$session->set('UserLogin', "True");
						if($request->server->get('HTTP_REFERER')!=''){
							$ref=explode("/",$request->server->get('HTTP_REFERER'));
							if(in_array('booking',$ref)){
								return $this->redirect($request->server->get('HTTP_REFERER'));
							}
						}
						if($session->get('review')!=''){
							if($session->get('hostal')!=''){
								$url=$this->container->get('router')->getContext()->getBaseUrl()."/".$session->get('destination')."/".$session->get('hostal');
							}else{
								$url=$this->container->get('router')->getContext()->getBaseUrl()."/".$session->get('destination');
							}
							
							$user=$session->get('user');
							$typeid=0;
							
							if($session->get('type')=='Destination'){
								$destination = $em->createQuery("SELECT d FROM MytripAdminBundle:Destination d WHERE d.status='Active' AND d.url='".$session->get('destination')."'")->getArrayResult();
								$typeid=$destination[0]['destinationId'];
							}elseif($session->get('type')=='Hostal'){
								$destination = $em->createQuery("SELECT d FROM MytripAdminBundle:Destination d WHERE d.status='Active' AND d.url='".$session->get('destination')."'")->getArrayResult();				
								$hostal = $em->createQuery("SELECT d FROM MytripAdminBundle:Hostal d WHERE d.status='Active' AND d.destination='".$destination[0]['destinationId']."' AND d.url='".$session->get('hostal')."'")->getArrayResult();
								$typeid=$hostal[0]['hostalId'];
							}
							
							if($typeid >0){
								$lan=$session->get('language');
								$reviews = new \Mytrip\AdminBundle\Entity\Review();
								$reviews->setUser($this->getDoctrine()->getRepository('MytripAdminBundle:User')->find($user['userId']));
								$reviews->setTypeId($typeid);
								$reviews->setReviewType($session->get('type'));
								$reviews->setRating($session->get('rate'));
								$reviews->setReview($session->get('review'));
								$reviews->setCreatedDate($session->get('created_date'));
								$reviews->setLan($lan);
								$reviews->setStatus('Active');
								$em->persist($reviews);
								$em->flush();
								$session->remove('type');$session->remove('review');$session->remove('rate');$session->remove('destination');$session->remove('hostal');
								$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Your review was successfully posted'));
							}else{
								$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Sorry, your review was not posted. Please try again.'));
							}
							
							return $this->redirect($url);
							
						}
						return $this->redirect($this->generateUrl('mytrip_user_profile'));
					}else{
						if($emailcheck[0]['status']=="Inactive"){
							$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('You are deactivated by admin. Please contact admin'));
						}else{
							$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Please check your mail. Click the confirmation link.'));
						}						
					}						
				}else{
					$this->get('session')->getFlashBag()->add('loginerror',$this->get('translator')->trans('Invalid Username or Password'));
				}
			}else{
				$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Sorry, you are not a registered member!'));	
			}
			return $this->redirect($request->server->get('HTTP_REFERER'));
		}
		return $this->redirect($this->generateUrl('mytrip_user_homepage'));
	}
	
	/*****Forgot Password**********/
	public function forgotAction(Request $request){
		$checkrefer=$this->referercheck($request);		
		if($checkrefer==false){
			return $this->redirect($this->generateUrl('mytrip_user_homepage'));
		}
		/****Check language session*******/
		$session = $request->getSession();	
		$this->langsession($session);
		$lan=$session->get('language');
		$request->setLocale($lan);
		
		$em = $this->container->get('doctrine')->getManager();
		
		if($request->getMethod()=="POST"){
			$emailcheck = $em->createQuery("SELECT p FROM MytripAdminBundle:User p WHERE p.email='".$request->request->get('email')."' AND p.status NOT IN ('Trash')")->getArrayResult();
			if(!empty($emailcheck)){
				if($emailcheck[0]['status']=='Active'){
					$emaillist=$em->getRepository('MytripAdminBundle:EmailList')->findOneBy(array('emailListId'=>'7'));	
					if($emailcheck[0]['lan']!=''){
						$lan=$emailcheck[0]['lan'];
					}
					$emailcontent=$em->getRepository('MytripAdminBundle:EmailContent')->findOneBy(array('emailList'=>'7','lan'=>$lan));
					if(!empty($emailcontent)){
						$emailcontent=$em->getRepository('MytripAdminBundle:EmailContent')->findOneBy(array('emailList'=>'7','lan'=>'en'));
					}
					$password=$this->str_rand(6);
					$em->createQuery("UPDATE MytripAdminBundle:User p SET p.password='".sha1($password)."',p.modifyDate='".date('Y-m-d H:i:s')."' WHERE p.userId='".$emailcheck[0]['userId']."'")->execute();									
					$link=$this->getRequest()->getSchemeAndHttpHost()."/".$this->container->get('router')->getContext()->getBaseUrl()."/".$this->generateUrl('mytrip_user_homepage');					
					$message=str_replace(array('{name}','{email}','{password}','{link}'),array($emailcheck[0]['firstname'].' '.$emailcheck[0]['lastname'],$emailcheck[0]['email'],$password,$link),$emailcontent->getEmailContent());
					$subject=str_replace(array('{name}','{email}','{password}','{link}'),array($emailcheck[0]['firstname'].' '.$emailcheck[0]['lastname'],$emailcheck[0]['email'],$password,$link),$emailcontent->getSubject());
				
				/*******forgot password mail send to user***********/								
				$this->mailsend($emaillist->getFromname(),$emaillist->getFromemail(),$emailcheck[0]['email'],$subject,$message,$emaillist->getCcmail());
				$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('New password send to your mail id. Please check it'));
				return $this->redirect($request->server->get('HTTP_REFERER'));
				}else{
					if($emailcheck[0]['status']=="Inactive"){
						$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('You are deactivated by admin. please contact admin'));
					}else{
						$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Please check your mail. Click the confirmation link.'));
					}	// $view['translator']->trans(					
				}
			}else{
				$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Sorry, You are not a register member'));	
			}
			return $this->redirect($request->server->get('HTTP_REFERER'));
		}
	}
	
	/******confirmation**************/
	public function confirmAction(Request $request){				
		/****Check language session*******/
		$session = $request->getSession();	
		$this->langsession($session);
		$lan=$session->get('language');
		
		$em = $this->container->get('doctrine')->getManager();
		
		$u_my_code=$this->container->get('request')->get('u_my_code');
		if(empty($u_my_code)){
			return $this->redirect($this->generateUrl('mytrip_user_homepage'));
		}
		list($key,$id)=explode("_",$u_my_code);
		if($key!='' && $id!=''){
			$query = $em->createQuery("SELECT p FROM MytripAdminBundle:User p WHERE p.userKey='".$key."' AND p.status NOT IN ('Trash')")->getArrayResult();			
			if(!empty($query)){
				if(sha1($query[0]['userId'])==$id){
					if(in_array($query[0]['status'],array('Pending','Resend'))){						
						$em->createQuery("UPDATE MytripAdminBundle:User p SET p.status='Active',p.modifyDate='".date('Y-m-d H:i:s')."' WHERE p.userId='".$query[0]['userId']."'")->execute();
						$session = $request->getSession();
						$session->set('user',$query[0]);
						$session->set('UserLogin', "True");
						$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Successfully verified your email id'));
						return $this->redirect($this->generateUrl('mytrip_user_profile'));
					}else{
						$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Your mail id has already been verified'));	
					}
				}
			}
		}
		$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Your confirmation link is wrong'));	
		return $this->redirect($this->generateUrl('mytrip_user_homepage'));
	}
	
	/******My Profile**************/
	public function profileAction(Request $request){
		$response=$this->checkUser($request->getSession());		
		if($response){
			return $response;
		}
		/****Check language session*******/
		$session = $request->getSession();	
		$this->langsession($session);
		$lan=$session->get('language');
		$request->setLocale($lan);
		
		$usersession=$session->get('user');

		$em = $this->container->get('doctrine')->getManager();
		if($request->getMethod()=="POST"){			
			if(($request->files->get('image')!='')){
				$checkimage=$em->createQuery("SELECT p FROM MytripAdminBundle:User p WHERE p.userId=".$usersession['userId'])->getArrayResult();
				if(!empty($checkimage)){
					$file_path="img/user/".$checkimage[0]['image'];
					if($checkimage[0]['image']!='' && file_exists($file_path)){
						unlink($file_path);
					}					 
				}
				$ext=$request->files->get('image')->getClientOriginalExtension() ;
				$filename=$this->str_rand(8,"alphanum").".".$ext;
				$request->files->get('image')->move("img/user",$filename);				
				$em->createQuery("UPDATE MytripAdminBundle:User p SET p.image='".$filename."' WHERE p.userId='".$usersession['userId']."'")->execute();
				$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Profile image updated successfully'));	
			}else{
				//if($request->request->get('profilesubmit')!=''){
					$dob=$request->request->get('year').'-'.$request->request->get('month').'-'.$request->request->get('day');		
					$em->createQuery("UPDATE MytripAdminBundle:User p SET p.firstname='".$request->request->get('firstname')."', p.lastname='".$request->request->get('lastname')."', p.dob='".$dob."', p.gender='".$request->request->get('gender')."', p.phone='".$request->request->get('phone')."', p.mobile='".$request->request->get('mobile')."', p.address='".$request->request->get('address')."', p.address2='".$request->request->get('address2')."', p.city='".$request->request->get('city')."', p.zip='".$request->request->get('zip')."', p.country='".$request->request->get('country')."', p.province='".$request->request->get('province')."', p.modifyDate='".date('Y-m-d H:i:s')."' WHERE p.userId='".$usersession['userId']."'")->execute();
					$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Profile updated successfully'));
				//}
			}
			return $this->redirect($this->generateUrl('mytrip_user_profile'));
		}
		
		$user=$em->createQuery("SELECT p FROM MytripAdminBundle:User p WHERE p.userId='".$usersession['userId']."'")->getArrayResult();
		$country=$em->createQuery("SELECT p FROM MytripAdminBundle:Country p")->getArrayResult();
		
		$seo['title']=$user[0]['firstname']." ".$user[0]['lastname']." - ".$this->get('translator')->trans('Profile');
		$seo['metadescription']=$user[0]['firstname']." ".$user[0]['lastname']." - ".$this->get('translator')->trans('Profile');
		$seo['metakeywords']=$user[0]['firstname']." ".$user[0]['lastname']." - ".$this->get('translator')->trans('Profile');
				
		return $this->render('MytripUserBundle:Default:profile.html.php',array('seo'=>$seo,'user'=>$user,'country'=>$country,'language'=>$this->language(),'soacillink'=>$this->getsociallink()));
	}
	
	/******My Account**************/
	public function accountAction(Request $request){
		$response=$this->checkUser($request->getSession());		
		if($response){
			return $response;
		}
		/****Check language session*******/
		$session = $request->getSession();	
		$this->langsession($session);
		$lan=$session->get('language');
		$request->setLocale($lan);

		$usersession=$session->get('user');
		
		$em = $this->container->get('doctrine')->getManager();
		if($request->getMethod()=="POST"){
			if($request->request->get('oldpassword')!=''){
				$checkpassword=$em->createQuery("SELECT p FROM MytripAdminBundle:User p WHERE p.userId='".$usersession['userId']."'")->getArrayResult();
				if($checkpassword[0]['password']==sha1($request->request->get('oldpassword'))){
					if($request->request->get('password')==$request->request->get('cpassword')){
						$em->createQuery("UPDATE MytripAdminBundle:User p SET p.password='".sha1($request->request->get('password'))."',p.modifyDate='".date('Y-m-d H:i:s')."' WHERE p.userId='".$usersession['userId']."'")->execute();
						$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Password changed successfully'));
					}else{
						$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Password and confirm password are mis match'));
					}
				}else{
					$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Current Password is wrong'));
				}
				return $this->redirect($this->generateUrl('mytrip_user_account'));
			}
			
			if($request->request->get('resetacc')!=''){
				$acc=implode("','",$request->request->get('acc'));
				$checkacc=$em->createQuery("SELECT p FROM MytripAdminBundle:UserSocialLink p WHERE p.user='".$usersession['userId']."' AND p.socialLink IN ('".$acc."')")->getArrayResult();
				if(!empty($checkacc)){
					 $em->createQuery("DELETE FROM MytripAdminBundle:UserSocialLink p WHERE p.user='".$usersession['userId']."' AND p.socialLink IN ('".$acc."')")->execute(); 
					 $this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Successfully reseted your account'));
				}else{
					$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Sorry, you have not registered in the social networks'));
				}
				return $this->redirect($this->generateUrl('mytrip_user_account'));
			}
			
			if($request->request->get('cancelacc')!=''){
				 $em->createQuery("UPDATE MytripAdminBundle:User p SET p.status='Trash' WHERE p.userId='".$usersession['userId']."'")->execute(); 
				 $this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Successfully deleted your account'));
				 return $this->redirect($this->generateUrl('mytrip_user_logout'));
			}
		}
		$user=$em->createQuery("SELECT p FROM MytripAdminBundle:User p WHERE p.userId='".$usersession['userId']."'")->getArrayResult();		
		
		$seo['title']=$user[0]['firstname']." ".$user[0]['lastname']." - ".$this->get('translator')->trans('Change password');
		$seo['metadescription']=$user[0]['firstname']." ".$user[0]['lastname']." - ".$this->get('translator')->trans('Change password');
		$seo['metakeywords']=$user[0]['firstname']." ".$user[0]['lastname']." - ".$this->get('translator')->trans('Change password');
		
		return $this->render('MytripUserBundle:Default:account.html.php',array('seo'=>$seo,'user'=>$user,'language'=>$this->language(),'soacillink'=>$this->getsociallink()));
	}
	
	/******Boooking**************/
	public function bookinghistoryAction(Request $request){
		$response=$this->checkUser($request->getSession());		
		if($response){
			return $response;
		}
		/****Check language session*******/
		$session = $request->getSession();	
		$this->langsession($session);
		$lan=$session->get('language');
		$usersession=$session->get('user');
		$request->setLocale($lan);
		
		$em = $this->container->get('doctrine')->getManager();	
		
		$prebooking=$em->createQuery("SELECT b FROM MytripAdminBundle:Booking b WHERE b.user='".$usersession['userId']."' AND b.status='Confirmed' AND b.fromDate>='".date('Y-m-d',strtotime("-1 month"))."' AND  b.fromDate<='".date('Y-m-d')."'")->getArrayResult();
		
		$settings=$em->createQuery("SELECT b FROM MytripAdminBundle:Settings b WHERE b.settingId='1'")->getArrayResult();
		
		$topay=$em->createQuery("SELECT b FROM MytripAdminBundle:Booking b WHERE b.user='".$usersession['userId']."' AND b.status='Pending' AND b.createdDate>='".date('Y-m-d H:i:s',strtotime("-".$settings[0]['bookingConfirmationDays']." days"))."'")->getArrayResult();		
		
		$confirmation=$em->createQuery("SELECT b FROM MytripAdminBundle:Booking b WHERE b.user='".$usersession['userId']."' AND b.status='Confirmed' AND b.fromDate>='".date('Y-m-d H:i:s')."'")->getArrayResult();
		
		$cancelticket=$em->createQuery("SELECT b FROM MytripAdminBundle:Booking b WHERE b.user='".$usersession['userId']."' AND b.status='Cancelled' AND b.fromDate>='".date('Y-m-d H:i:s')."'")->getArrayResult();		
		
		$user=$em->createQuery("SELECT p FROM MytripAdminBundle:User p WHERE p.userId='".$usersession['userId']."'")->getArrayResult();
		
		$seo['title']=$user[0]['firstname']." ".$user[0]['lastname']." - ".$this->get('translator')->trans('Booking History');
		$seo['metadescription']=$user[0]['firstname']." ".$user[0]['lastname']." - ".$this->get('translator')->trans('Booking History');
		$seo['metakeywords']=$user[0]['firstname']." ".$user[0]['lastname']." - ".$this->get('translator')->trans('Booking History');
		
		return $this->render('MytripUserBundle:Default:bookinghistory.html.php',array('seo'=>$seo,'prebooking'=>$prebooking,'topay'=>$topay,'confirmation'=>$confirmation,'cancelticket'=>$cancelticket,'user'=>$user,'language'=>$this->language(),'soacillink'=>$this->getsociallink()));
	}
	
	public function bookingdetailsAction(Request $request){
		$response=$this->checkUser($request->getSession());		
		if($response){
			return $response;
		}
		/****Check language session*******/
		$session = $request->getSession();	
		$this->langsession($session);
		$lan=$session->get('language');
		$usersession=$session->get('user');
		$request->setLocale($lan);
		
		$bookingid=$request->get('bookingid')/1024;	
		$em = $this->container->get('doctrine')->getManager();	
			
		$booking=$em->createQuery("SELECT b,IDENTITY(b.hostal) AS hostal FROM MytripAdminBundle:Booking b WHERE b.user='".$usersession['userId']."' AND b.bookingId='".$bookingid."'")->getArrayResult();
		
		if(empty($booking)){
			$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Sorry, Your booking id is wrong. Please check once again'));
			return $this->redirect($this->generateUrl('mytrip_user_homepage'));	
		}
		
		$user=$em->createQuery("SELECT p FROM MytripAdminBundle:User p WHERE p.userId='".$usersession['userId']."'")->getArrayResult();
		
		$seo['title']="venacuba-".($bookingid*1024).$request->get('bookingid')." -  Details, ".$user[0]['firstname']." ".$user[0]['lastname'];
		$seo['metadescription']="venacuba-".($bookingid*1024).$request->get('bookingid')." -  Details, ".$user[0]['firstname']." ".$user[0]['lastname'];
		$seo['metakeywords']="venacuba-".($bookingid*1024).$request->get('bookingid')." -  Details, ".$user[0]['firstname']." ".$user[0]['lastname'];
		
		return $this->render('MytripUserBundle:Default:bookingdetails.html.php',array('seo'=>$seo,'booking'=>$booking,'user'=>$user,'language'=>$this->language(),'soacillink'=>$this->getsociallink()));
	}
	
	public function cancelbookingAction(Request $request){
		$response=$this->checkUser($request->getSession());		
		if($response){
			return $response;
		}
		/****Check language session*******/
		$session = $request->getSession();	
		$this->langsession($session);
		$lan=$session->get('language');
                $request->setLocale($lan);
                
		$usersession=$session->get('user');
		$bookingid=$request->get('bookingId')/1024;	
		$em = $this->container->get('doctrine')->getManager();
		$booking=$em->createQuery("SELECT b,IDENTITY(b.hostal) AS hostal FROM MytripAdminBundle:Booking b WHERE b.bookingId='".$bookingid."' AND b.user='".$usersession['userId']."' AND b.status='Confirmed' AND b.fromDate>='".date('Y-m-d H:i:s')."'")->getArrayResult();
		
		$user=$em->createQuery("SELECT p FROM MytripAdminBundle:User p WHERE p.userId='".$usersession['userId']."'")->getArrayResult();
		
		if(empty($booking)){
			$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Sorry, Your booking id is wrong. Please check once again'));
			return $this->redirect($this->generateUrl('mytrip_user_homepage'));	
		}
		
		if($booking[0][0]['status']=="Pending" || $booking[0][0]['status']=="Cancelled"){
			$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Sorry, Your booking id is wrong. Please check once again'));
			return $this->redirect($this->generateUrl('mytrip_user_bookinghistory'));
		}
		
		$startdate=date('Y-m-d');
		$enddate=$booking[0][0]['fromDate']->format('Y-m-d');
		$betweendays=$this->noofdays($startdate,$enddate);
		$bookingprice=$em->createQuery("SELECT p FROM MytripAdminBundle:BookingPrice p WHERE p.booking='".$booking[0][0]['bookingId']."'")->getArrayResult();
		$bookingtransaction=$em->createQuery("SELECT p FROM MytripAdminBundle:BookingTransaction p WHERE p.booking='".$booking[0][0]['bookingId']."'")->getArrayResult();
		if($request->getMethod()=="POST"){			
			$hostal_cancel=$em->createQuery("SELECT p FROM MytripAdminBundle:HostalCancelDetails p WHERE p.hostal='".$booking[0]['hostal']."' AND p.days <='".$betweendays."' ORDER BY p.days DESC")->setMaxResults('1')->getArrayResult();
			if(!empty($hostal_cancel)){
				$tamount=$bookingprice[0]['reservationTotalPrice']-$bookingprice[0]['reservationCharge'];
				$cancelamount=$tamount*($hostal_cancel[0]['percentage']/100);
				$cancel_percentage=$hostal_cancel[0]['percentage'];
			}else{
				$cancelamount=0;
				$cancel_percentage=0;
			}
			$refunds=array('refund_booking_id'=>$bookingid,'cancelamount'=>$cancelamount,'cancel_percentage'=>$cancel_percentage,'currency'=>$bookingprice[0]['conversionCurrency']);
			$session->set('refund',$refunds);
						
			if($cancelamount==0){
				$cancel = new \Mytrip\AdminBundle\Entity\BookingCancel();
				$cancel->setBooking($this->getDoctrine()->getRepository('MytripAdminBundle:Booking')->find($bookingid));
				$cancel->setCancelPercentage($cancel_percentage);
				$cancel->setCancelDate(new \DateTime(date('Y-m-d')));
				$cancel->setRefundAmount($cancelamount);
				$cancel->setRefundDate(new \DateTime(date('Y-m-d')));
                                $cancel->setCreatedDate(new \DateTime(date('Y-m-d')));
				$cancel->setRefundCurrency($bookingprice[0]['conversionCurrency']);
				$cancel->setStatus('Refund');				
				$em->persist($cancel);
				$em->flush();				
				$em->createQuery("UPDATE MytripAdminBundle:Booking p SET p.status='Cancelled' WHERE p.bookingId='".$bookingid."'")->execute();
				
				$bookingid=$refunds['refund_booking_id'];
				$booking=$em->createQuery("SELECT d,IDENTITY(d.hostal) AS hostal FROM MytripAdminBundle:Booking d WHERE d.bookingId=".$bookingid)->getArrayResult();			
				$booking_info=$em->createQuery("SELECT d FROM MytripAdminBundle:BookingInfo d WHERE d.booking=".$bookingid)->getArrayResult();
				
				$this->mailsend("Mytrip Cuba","info@mytriptocuba.com",$booking_info[0]['email'],$this->get('translator')->trans('Reservation Cancel Details'),'','','0','','refund');				
				$login = $this->container->get('mytrip_admin.helper.sms')->getOption('smsusername');
				$password = $this->container->get('mytrip_admin.helper.sms')->getOption('smspassword');
				$prefix = $booking_info[0]['cmcode'];	
				$number = $booking_info[0]['mobile'];
				$msg = urlencode($this->get('translator')->trans('Dear Customer, Your booking cancelled successfully for the reference no is').' '.("venacuba-".$bookingid*1024).$this->get('translator')->trans('Refund amount is').' '.$refunds['cancelamount'].$refunds['currency']);			
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
			
			$booking_price=$em->createQuery("SELECT d FROM MytripAdminBundle:BookingPrice d WHERE d.booking=".$bookingid)->getArrayResult();
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
			if(empty($emailcontent)){
				$emailcontent=$em->getRepository('MytripAdminBundle:EmailContent')->findOneBy(array('emailList'=>'12','lan'=>'en'));
			}	
			$fromdate=$booking[0][0]['fromDate'];$todate=$booking[0][0]['toDate'];	
			if($hostals[0]['ownerEmail']!=''){
				$message=str_replace(array('{owner_name}','{hostal_name}','{check_in}','{check_out}','{room_type}','{rooms}','{nights}','{room_details}','{username}','{address}','{room_price}','{accommodation_cost}','{reservation_charge}','{total_cost}','{ref_no}','{paid_amount}','{balance_amount}','{refund_amount}'),
									 array($hostal_content[0]['ownerName'],$hostal_content[0]['name'],$fromdate->format('Y-m-d H:i:s'),$todate->format('Y-m-d H:i:s'),$hostal_room[0]['roomtype'],$booking[0][0]['noOfRooms'],$booking[0][0]['noOfDays'],'Guests:'.$hostal_room[0]['guests'].',Adults:'.$hostal_room[0]['adults'].',Child:'.$hostal_room[0]['child'],$user_name,$address,$hostal_room[0]['price'],number_format($bookingprice[0]['totalPrice']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format($bookingprice[0]['reservationCharge']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format(($bookingprice[0]['totalPrice']+$bookingprice[0]['reservationCharge'])*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],"venacuba-".$bookingid*1024,number_format($bookingprice[0]['reservationTotalPrice']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format((($bookingprice[0]['totalPrice']+$bookingprice[0]['reservationCharge'])-$bookingprice[0]['reservationTotalPrice'])*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],$bookingcancel[0]['refundAmount'].' '.$bookingcancel[0]['refundCurrency']),$emailcontent->getEmailContent());	
							
				$subject=str_replace(array('{owner_name}','{hostal_name}','{check_in}','{check_out}','{room_type}','{rooms}','{nights}','{room_details}','{username}','{address}','{room_price}','{accommodation_cost}','{reservation_charge}','{total_cost}','{ref_no}','{paid_amount}','{balance_amount}','{refund_amount}'),
									 array($hostal_content[0]['ownerName'],$hostal_content[0]['name'],$fromdate->format('Y-m-d H:i:s'),$todate->format('Y-m-d H:i:s'),$hostal_room[0]['roomtype'],$booking[0][0]['noOfRooms'],$booking[0][0]['noOfDays'],'Guests:'.$hostal_room[0]['guests'].',Adults:'.$hostal_room[0]['adults'].',Child:'.$hostal_room[0]['child'],$user_name,$address,$hostal_room[0]['price'],number_format($bookingprice[0]['totalPrice']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format($bookingprice[0]['reservationCharge']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format(($bookingprice[0]['totalPrice']+$bookingprice[0]['reservationCharge'])*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],"venacuba-".$bookingid*1024,number_format($bookingprice[0]['reservationTotalPrice']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format((($bookingprice[0]['totalPrice']+$bookingprice[0]['reservationCharge'])-$bookingprice[0]['reservationTotalPrice'])*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],$bookingcancel[0]['refundAmount'].' '.$bookingcancel[0]['refundCurrency']),$emailcontent->getSubject());
											
				$this->mailsend($emaillist->getFromname(),$emaillist->getFromemail(),$hostals[0]['ownerEmail'],$subject,$message,'');
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
			$emaillist=$em->getRepository('MytripAdminBundle:EmailList')->findOneBy(array('emailListId'=>'11'));							
			$emailcontent=$em->getRepository('MytripAdminBundle:EmailContent')->findOneBy(array('emailList'=>'11','lan'=>$lan));
			if(!empty($emailcontent)){
				$emailcontent=$em->getRepository('MytripAdminBundle:EmailContent')->findOneBy(array('emailList'=>'11','lan'=>'en'));
			}	
			$admin=$em->createQuery("SELECT p FROM MytripAdminBundle:Admin p WHERE p.adminId='1'")->getArrayResult();	
			
			$message=str_replace(array('{admin_name}','{owner_name}','{hostal_name}','{check_in}','{check_out}','{room_type}','{rooms}','{nights}','{room_details}','{username}','{address}','{room_price}','{accommodation_cost}','{reservation_charge}','{total_cost}','{ref_no}','{paid_amount}','{balance_amount}','{refund_amount}'),
								 array($admin[0]['name'],$hostal_content[0]['ownerName'],$hostal_content[0]['name'],$fromdate->format('Y-m-d H:i:s'),$todate->format('Y-m-d H:i:s'),$hostal_room[0]['roomtype'],$booking[0][0]['noOfRooms'],$booking[0][0]['noOfDays'],'Guests:'.$hostal_room[0]['guests'].',Adults:'.$hostal_room[0]['adults'].',Child:'.$hostal_room[0]['child'],$user_name,$address,$hostal_room[0]['price'],number_format($bookingprice[0]['totalPrice']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format($bookingprice[0]['reservationCharge']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format(($bookingprice[0]['totalPrice']+$bookingprice[0]['reservationCharge'])*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],"venacuba-".$bookingid*1024,number_format($bookingprice[0]['reservationTotalPrice']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format((($bookingprice[0]['totalPrice']+$bookingprice[0]['reservationCharge'])-$bookingprice[0]['reservationTotalPrice'])*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],$bookingcancel[0]['refundAmount'].' '.$bookingcancel[0]['refundCurrency']),$emailcontent->getEmailContent());
							
			$subject=str_replace(array('{admin_name}','{owner_name}','{hostal_name}','{check_in}','{check_out}','{room_type}','{rooms}','{nights}','{room_details}','{username}','{address}','{room_price}','{accommodation_cost}','{reservation_charge}','{total_cost}','{ref_no}','{paid_amount}','{balance_amount}','{refund_amount}'),
								 array($admin[0]['name'],$hostal_content[0]['ownerName'],$hostal_content[0]['name'],$fromdate->format('Y-m-d H:i:s'),$todate->format('Y-m-d H:i:s'),$hostal_room[0]['roomtype'],$booking[0][0]['noOfRooms'],$booking[0][0]['noOfDays'],'Guests:'.$hostal_room[0]['guests'].',Adults:'.$hostal_room[0]['adults'].',Child:'.$hostal_room[0]['child'],$user_name,$address,$hostal_room[0]['price'],number_format($bookingprice[0]['totalPrice']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format($bookingprice[0]['reservationCharge']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format(($bookingprice[0]['totalPrice']+$bookingprice[0]['reservationCharge'])*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],"venacuba-".$bookingid*1024,number_format($bookingprice[0]['reservationTotalPrice']*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],number_format((($bookingprice[0]['totalPrice']+$bookingprice[0]['reservationCharge'])-$bookingprice[0]['reservationTotalPrice'])*$bookingprice[0]['conversionRate'],2).' '.$bookingprice[0]['conversionCurrency'],$bookingcancel[0]['refundAmount'].' '.$bookingcancel[0]['refundCurrency']),$emailcontent->getSubject());
											
			$this->mailsend($emaillist->getFromname(),$emaillist->getFromemail(),$admin[0]['email'],$subject,$message,'');
			
			
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
			}else{				
								
				if($bookingtransaction[0]['paymentType']=="Paypal"){				
					return $this->redirect($this->generateUrl('mytrip_user_paypal_refund'));
				}elseif($bookingtransaction[0]['paymentType']=="Stripe"){				
					return $this->redirect($this->generateUrl('mytrip_user_stripe_refund'));
				}elseif($bookingtransaction[0]['paymentType']=="Skrill"){				
					return $this->redirect($this->generateUrl('mytrip_user_skrill_refund'));
				}elseif($bookingtransaction[0]['paymentType']=="Beanstream"){				
					return $this->redirect($this->generateUrl('mytrip_user_beanstream_refund'));
				}elseif($bookingtransaction[0]['paymentType']=="Globalone"){				
					return $this->redirect($this->generateUrl('mytrip_user_globalone_refund'));
				}
			}
		}
		
		$seo['title']="venacuba-".($bookingid*1024).$request->get('bookingid')." -  Details, ".$user[0]['firstname']." ".$user[0]['lastname'];
		$seo['metadescription']="venacuba-".($bookingid*1024).$request->get('bookingid')." -  Details, ".$user[0]['firstname']." ".$user[0]['lastname'];
		$seo['metakeywords']="venacuba-".($bookingid*1024).$request->get('bookingid')." -  Details, ".$user[0]['firstname']." ".$user[0]['lastname'];
		
		return $this->render('MytripUserBundle:Default:cancelbooking.html.php',array('seo'=>$seo,'betweendays'=>$betweendays,'booking'=>$booking,'user'=>$user,'language'=>$this->language(),'soacillink'=>$this->getsociallink()));
		
	}
	
	
	
	private function noofdays($startDate,$endDate){
		$days = floor((strtotime($endDate) - strtotime($startDate)) / (60 * 60 * 24));
		return $days;
	}
	
	/****user Email id duplicate checking******/
	public function emailcheckAction(Request $request){
		$em = $this->container->get('doctrine')->getManager();	
		$email=$this->container->get('request')->get('fieldValue');
		$query = $em->createQuery("SELECT p FROM MytripAdminBundle:User p WHERE p.email='".$email."'")->getArrayResult();
		if(empty($query)){
			 return new Response('["signupemail",true]');
		}else{
			 return new Response('["signupemail",false]');
		}		
	}
	
	/****Get language from db******/
	private function language(){
		$em = $this->container->get('doctrine')->getManager();	
		$query = $em->createQuery("SELECT p FROM MytripAdminBundle:Language p");			
		return $query->getArrayResult();
	}
	
	/*******Get language session************/
	private function langsession($session){		
		if($session->get('language')==''){			
			$session->set('language', 'en');
		}
		if($session->get('currency')==''){			
			$session->set('currency', 'CAD');
			$conversion=$this->currencyconversion();
			$session->set('conversionrate',$conversion['conversionrate']);
		}
		if($session->get('conversionrate')==''){
			$conversion=$this->currencyconversion();
			$session->set('conversionrate',$conversion['conversionrate']);
		}
	}
	
	/************Referer checking***********/
	private function referercheck($request){
		$refer=$request->server->get('HTTP_REFERER');
		$url=$this->getRequest()->getSchemeAndHttpHost().$this->container->get('router')->getContext()->getBaseUrl();
		if(strpos($refer, $url)===false){
			$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Sorry, Please check once again.'));
			return false;
		}else{
			return true;
		}
	}
	
	/*******Change Language***********/
	public function changelanguageAction(Request $request){	
	    $lan=$this->container->get('request')->get('lan');
		$em = $this->container->get('doctrine')->getManager();	
		$query = $em->createQuery("SELECT p FROM MytripAdminBundle:Language p WHERE p.lanCode='".$lan."'");
		$lang = $query->getArrayResult();
		if(!empty($lang)){
			$lan_code=$lang[0]['lanCode'];
		}else{
			$lan_code='en';
		}
		$session = $request->getSession();		
		$session->set('language', $lan_code);  
		if($request->server->get('HTTP_REFERER')!=''){		
			return $this->redirect($request->server->get('HTTP_REFERER'));
		}else{
			return $this->redirect($this->generateUrl('mytrip_user_homepage'));
		}
    }
	
	/******Change Currency**********/
	public function changecurrencyAction(Request $request){	
	
	    $currency=$this->container->get('request')->get('currency');
		$session = $request->getSession();
		if(in_array($currency,array('CAD','USD','EUR'))){			
			$session->set('currency', $currency);
		}else{
			$session->set('currency', 'CAD');
		}
		$conversion=$this->currencyconversion();
		$session->set('conversionrate',$conversion['conversionrate']); 
		if($request->server->get('HTTP_REFERER')!=''){		
			return $this->redirect($request->server->get('HTTP_REFERER'));
		}else{
			return $this->redirect($this->generateUrl('mytrip_user_homepage'));
		}
    }
		
	/***********Social Link in footer*************/
	private function getsociallink(){		
		$em = $this->container->get('doctrine')->getManager();		
		$query = $em->createQuery("SELECT p FROM MytripAdminBundle:SocialLink p");			
		return $query->getArrayResult();		
	}
	
	/********Visits count************/
	private function visits($request,$type,$id){
		$session = $request->getSession();		
		if($session->get($type)==''){
			$session->set($type,array());
		}
		$visitsarray=$session->get($type);
		$em = $this->getDoctrine()->getManager();		
		if(!in_array($id,$visitsarray)){
			$visitsarray[]=$id;	
			$session->set($type,$visitsarray);			
			$visits_array=$em->createQuery("SELECT p FROM MytripAdminBundle:Visits p WHERE p.visitDate='".date('Y-m-d')."' AND p.visitType='".$type."' AND p.typeId='".$id."'")->getArrayResult();
			if(!empty($visits_array)){
				$em->createQuery("UPDATE MytripAdminBundle:Visits p SET p.count=p.count+1 WHERE p.visitDate='".date('Y-m-d')."' AND p.visitType='".$type."' AND p.typeId='".$id."'")->execute();
			}else{
				$visits = new \Mytrip\AdminBundle\Entity\Visits();	
				$visits->setCount(1);
				$visits->setVisitDate(new \DateTime(date('Y-m-d')));		
				$visits->setVisitType($type);
				$visits->setTypeId($id);		
				$em->persist($visits);				
				$em->flush();				
			}
		}
		
	}
	

	
	/*Between dates of two dates*/
	private function getDatesBetween2Dates($startTime, $endTime) {
		$day = 86400;
		$format = 'Y-m-d';
		$startTime = strtotime($startTime);
		$endTime = strtotime($endTime);
		$numDays = round(($endTime - $startTime) / $day) + 1;
		$days = array();			
		for ($i = 0; $i < $numDays; $i++) {
			$da=date($format, ($startTime + ($i * $day)));
			if($da>=date('Y-m-d')){
				$days[] = $da;
			}
		}			
		return $days;
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
	
	/*******Logout***********/
	public function logoutAction(Request $request){
		$session=$request->getSession();
		$session->remove('user');
		$session->remove('UserLogin');
		return $this->redirect($this->generateUrl('mytrip_user_homepage'));			
	}
	
	/*******Checking Admin session***********/
	private function checkUser($session){
		$username=$session->get('user');
		if(empty($username)){			
			return $this->redirect($this->generateUrl('mytrip_user_homepage'));
		}
	}
	
	/***Fetch the state corresponding country****/
	public function getstateAction(Request $request){			
		$em =  $this->getDoctrine()->getManager();		 
		$id=$request->get('sid');
		$query = $em->createQuery("SELECT s FROM MytripAdminBundle:States s  where s.cid=$id" );		
		$state=$query->getArrayResult();				 
		return $this->render('MytripAdminBundle:Default:getstate.html.php',array('state'=>$state));		
	}
	
}
