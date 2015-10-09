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

class GooglesController extends Controller{
	
    public function indexAction(Request $request){		
		/****Check language session*******/
		$session = $request->getSession();	
		$this->langsession($session);
		$lan=$session->get('language');
		$request->setLocale($lan);	
				
		$username=$session->get('user');
		
		$em = $this->getDoctrine()->getManager();
		
		$appkey = $this->container->get('mytrip_admin.helper.google')->getOption('apikey');
		$appsecretkey = $this->container->get('mytrip_admin.helper.google')->getOption('apisecretkey');
		$developerkey = $this->container->get('mytrip_admin.helper.google')->getOption('developerkey');
		$product = $this->container->get('mytrip_admin.helper.google')->getOption('productname');
		$redirect_url=$this->getRequest()->getSchemeAndHttpHost().$this->generateUrl('mytrip_user_google');	;
		
		$gClient = new \Google_Client();
		$gClient->setApplicationName($product);
		$gClient->setClientId($appkey);
		$gClient->setClientSecret($appsecretkey);
		$gClient->setRedirectUri($redirect_url);
		$gClient->setDeveloperKey($developerkey);
		
		$google_oauthV2 = new \Google_Oauth2Service($gClient);		
		
		if (isset($_REQUEST['reset'])){
			$session->remove('token');
			//unset($_SESSION['token']);
			$gClient->revokeToken();
			return $this->redirect(filter_var($redirect_url, FILTER_SANITIZE_URL));
			//header('Location: ' . filter_var($google_redirect_url, FILTER_SANITIZE_URL)); //redirect user back to page
		}
		if (isset($_GET['code'])){ 		
			$gClient->authenticate($_GET['code']);			
			$session->set('token',$gClient->getAccessToken());					
			return $this->redirect(filter_var($redirect_url, FILTER_SANITIZE_URL));
			
		}
		
		if ($session->get('token')) { 
			$gClient->setAccessToken($session->get('token'));
		}


		if ($gClient->getAccessToken()){
			  //For logged in user, get details from google using access token
			  $user 				= $google_oauthV2->userinfo->get();
			  $user_id 				= $user['id'];
			  $user_name 			= filter_var($user['name'], FILTER_SANITIZE_SPECIAL_CHARS);
			  $email 				= filter_var($user['email'], FILTER_SANITIZE_EMAIL);
			  $profile_url 			= filter_var($user['link'], FILTER_VALIDATE_URL);
			  $profile_image_url 	= filter_var($user['picture'], FILTER_VALIDATE_URL);
			  $personMarkup 		= "$email<div><img src='$profile_image_url?sz=50'></div>";
			  		  
			  $session->set('token',$gClient->getAccessToken());
			  
			  if(empty($username)){			
				$emailcheck = $em->createQuery("SELECT p FROM MytripAdminBundle:User p WHERE p.email='".$email."' AND p.status NOT IN ('Trash')")->getArrayResult();
				if(empty($emailcheck)){	
							
					$randno=sha1($this->str_rand().date('Y-m-d H:i:s'));
					$password=$this->str_rand(6);
					$member = new \Mytrip\AdminBundle\Entity\User();
					$member->setFirstname($user_name);
					$member->setLastname(' ');
					$member->setEmail($email);
					$member->setPassword(sha1($password));
					$member->setLan($lan);
					$member->setUserKey($randno);	
					$member->setStatus('Active');				
					$member->setCreatedDate(new \DateTime(date('Y-m-d H:i:s')));
					$member->setModifyDate(new \DateTime(date('Y-m-d H:i:s')));			
					$em->persist($member);
					$em->flush();
					
					$uid=$member->getUserId();
					$social_links = new \Mytrip\AdminBundle\Entity\UserSocialLink();
					$social_links->setUser($this->getDoctrine()->getRepository('MytripAdminBundle:User')->find($uid));
					$social_links->setSocialLink('Google');
					$social_links->setId($user_id);
					$social_links->setImage($profile_image_url);
					$social_links->setCreatedDate(new \DateTime(date('Y-m-d H:i:s')));
					$em->persist($social_links);
					$em->flush();
					
					$user = $em->createQuery("SELECT p FROM MytripAdminBundle:User p WHERE p.userId='".$uid."'")->getArrayResult();	
					$session->set('user',$user[0]);
					$session->set('UserLogin', "True");		
							
					$emaillist=$em->getRepository('MytripAdminBundle:EmailList')->findOneBy(array('emailListId'=>'14'));							
					$emailcontent=$em->getRepository('MytripAdminBundle:EmailContent')->findOneBy(array('emailList'=>'14','lan'=>$lan));
					if(!empty($emailcontent)){
						$emailcontent=$em->getRepository('MytripAdminBundle:EmailContent')->findOneBy(array('emailList'=>'14','lan'=>'en'));
					}				
					$link=$this->getRequest()->getSchemeAndHttpHost().$this->generateUrl('mytrip_user_confirm')."?u_my_code=".$randno."_".sha1($uid);
					
					$message=str_replace(array('{name}','{link}'),array($user[0]['firstname'].' '.$user[0]['lastname'],$link),$emailcontent->getEmailContent());
					//$message=str_replace(array('{name}','{username}','{password}','{link}'),array($user_name,$email,$password,$link),$emailcontent->getEmailContent());
					$subject=str_replace(array('{name}','{link}'),array($user[0]['firstname'].' '.$user[0]['lastname'],$link),$emailcontent->getSubject());
					
					/*******Contact mail send to admin***********/								
					$this->mailsend($emaillist->getFromname(),$emaillist->getFromemail(),$user[0]['email'],$subject,$message,$emaillist->getCcmail());
					
					$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Successfully Registered. Confirmation link sent to your mail id.'));
					return $this->redirect($this->generateUrl('mytrip_user_profile'));
				}else{				
					$uid=$emailcheck[0]['userId'];
					$check_social_link = $em->createQuery("SELECT p FROM MytripAdminBundle:UserSocialLink p WHERE p.user='".$uid."' AND p.socialLink='Google' AND p.id='".$user_id."'")->getArrayResult();
					if(empty($check_social_link)){
						$social_links = new \Mytrip\AdminBundle\Entity\UserSocialLink();
						$social_links->setUser($this->getDoctrine()->getRepository('MytripAdminBundle:User')->find($uid));
						$social_links->setSocialLink('Google');
						$social_links->setId($user_id);
						$social_links->setImage($profile_image_url);
						$social_links->setCreatedDate(new \DateTime(date('Y-m-d H:i:s')));
						$em->persist($social_links);
						$em->flush();
					}
					
					$user = $em->createQuery("SELECT p FROM MytripAdminBundle:User p WHERE p.userId='".$uid."'")->getArrayResult();	
					$session->set('user',$user[0]);
					$session->set('UserLogin', "True");	
					return $this->redirect($this->generateUrl('mytrip_user_profile'));
				}	
			}else{
				$uid=$username['userId'];
				$check_social_link = $em->createQuery("SELECT p FROM MytripAdminBundle:UserSocialLink p WHERE p.user='".$uid."' AND p.socialLink='Google' AND p.id='".$user_profile['id']."'")->getArrayResult();
				if(empty($check_social_link)){
					$social_links = new \Mytrip\AdminBundle\Entity\UserSocialLink();
					$social_links->setUser($this->getDoctrine()->getRepository('MytripAdminBundle:User')->find($uid));
					$social_links->setSocialLink('Google');
					$social_links->setId($user_id);
					$social_links->setImage($profile_image_url);
					$social_links->setCreatedDate(new \DateTime(date('Y-m-d H:i:s')));
					$em->persist($social_links);
					$em->flush();
				}
				return $this->redirect($this->generateUrl('mytrip_user_profile'));
			}
		}
		else 
		{
			//For Guest user, get google login url
			$authUrl = $gClient->createAuthUrl();
			return $this->redirect($authUrl);
		}	
		
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
}
