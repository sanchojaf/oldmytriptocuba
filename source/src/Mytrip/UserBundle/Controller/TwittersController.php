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

class TwittersController extends Controller{
	
    public function indexAction(Request $request){		
		/****Check language session*******/
		$session = $request->getSession();	
		$this->langsession($session);
		$lan=$session->get('language');
		$request->setLocale($lan);	
				
		$username=$session->get('user');
		
		$em = $this->getDoctrine()->getManager();
		
		$appkey = $this->container->get('mytrip_admin.helper.twitter')->getOption('apikey');
		$appsecretkey = $this->container->get('mytrip_admin.helper.twitter')->getOption('apisecretkey');
		
		$twitteroauth = new \TwitterOAuth($appkey, $appsecretkey);
		
		//\TwitterOAuth::setAuth($appkey, $appsecretkey);
		$link=$this->getRequest()->getSchemeAndHttpHost().$this->generateUrl('mytrip_user_twitter_callback');		
		
		$request_token = $twitteroauth->getRequestToken($link);
		
		$session->set('oauth_token',$request_token['oauth_token']);	
		$session->set('oauth_token_secret',$request_token['oauth_token_secret']);
		$url = $twitteroauth->getAuthorizeURL($request_token['oauth_token']);
		return $this->redirect($url);
    }
	
	public function callbackAction(Request $request){
		/****Check language session*******/
		$session = $request->getSession();	
		$this->langsession($session);
		$lan=$session->get('language');
		$request->setLocale($lan);	
				
		$username=$session->get('user');
		
		$em = $this->getDoctrine()->getManager();
		
		$appkey = $this->container->get('mytrip_admin.helper.twitter')->getOption('apikey');
		$appsecretkey = $this->container->get('mytrip_admin.helper.twitter')->getOption('apisecretkey');
		
		$twitteroauth = new \TwitterOAuth($appkey, $appsecretkey,$session->get('oauth_token'), $session->get('oauth_token_secret'));
		
		//\TwitterOAuth::setAuth($appkey, $appsecretkey,$session->get('oauth_token'), $session->get('oauth_token_secret'));
		$access_token = $twitteroauth->getAccessToken($_REQUEST['oauth_verifier']);	
		$content = $twitteroauth->get('account/verify_credentials');
				
		if(!empty($content)){
			if(!empty($username)){	
				$uid=$username['userId'];
				$check_social_link = $em->createQuery("SELECT p FROM MytripAdminBundle:UserSocialLink p WHERE p.user='".$uid."' AND p.socialLink='Twitter' AND p.id='".$content->id."'")->getArrayResult();
				if(empty($check_social_link)){
					$social_links = new \Mytrip\AdminBundle\Entity\UserSocialLink();
					$social_links->setUser($this->getDoctrine()->getRepository('MytripAdminBundle:User')->find($uid));
					$social_links->setSocialLink('Twitter');
					$social_links->setId($content->id);
					$social_links->setImage($content->profile_image_url);
					$social_links->setCreatedDate(new \DateTime(date('Y-m-d H:i:s')));
					$em->persist($social_links);
					$em->flush();
				}
				return $this->redirect($this->generateUrl('mytrip_user_profile'));
			}else{
				$check_social_link = $em->createQuery("SELECT p,IDENTITY(p.user) AS user FROM MytripAdminBundle:UserSocialLink p WHERE p.socialLink='Twitter' AND p.id='".$content->id."'")->getArrayResult();
				if(!empty($check_social_link)){
					$user=$em->createQuery("SELECT p FROM MytripAdminBundle:User p WHERE p.userId='".$check_social_link[0]['user']."' AND p.status NOT IN ('Trash')")->getArrayResult();
					if(!empty($user)){
						$session->set('user',$user[0]);
						$session->set('UserLogin', "True");	
						return $this->redirect($this->generateUrl('mytrip_user_profile'));
					}else{
						$session->set('twittercontent',$content);
						$this->get('session')->getFlashBag()->add('twit','1');
						return $this->redirect($this->generateUrl('mytrip_user_homepage'));
					}
				}else{
					$session->set('twittercontent',$content);
					$this->get('session')->getFlashBag()->add('twit','1');
					return $this->redirect($this->generateUrl('mytrip_user_homepage'));
				}
			}
		}else{
			$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Sorry, Please try again'));
			if(empty($username)){	
				return $this->redirect($this->generateUrl('mytrip_user_homepage'));
			}else{
				return $this->redirect($this->generateUrl('mytrip_user_profile'));
			}
		}
	}
	
	public function registerAction(Request $request){
		/****Check language session*******/
		$session = $request->getSession();	
		$this->langsession($session);
		$lan=$session->get('language');
		$request->setLocale($lan);	
				
		$twitter=$session->get('twittercontent');
		if(empty($twitter)){
			return $this->redirect($this->generateUrl('mytrip_user_homepage'));
		}
		
		$em = $this->getDoctrine()->getManager();
		if($request->getMethod()=="POST"){
			$emailcheck = $em->createQuery("SELECT p FROM MytripAdminBundle:User p WHERE p.email='".$request->request->get('email')."' AND p.status NOT IN ('Trash')")->getArrayResult();
			if(empty($emailcheck)){	
			
				$randno=sha1($this->str_rand().date('Y-m-d H:i:s'));
				$password=$this->str_rand(6);
				$member = new \Mytrip\AdminBundle\Entity\User();
				$member->setFirstname($twitter->name);
				$member->setLastname(' ');
				$member->setEmail($request->request->get('email'));
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
				$social_links->setSocialLink('Twitter');
				$social_links->setId($twitter->id);
				$social_links->setImage($twitter->profile_image_url);
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
				
				//$message=str_replace(array('{name}','{username}','{password}','{link}'),array($user[0]['firstname'].' '.$user[0]['lastname'],$user[0]['email'],$password,$link),$emailcontent->getEmailContent());
				$message=str_replace(array('{name}','{link}'),array($twitter->name,$link),$emailcontent->getEmailContent());
				$subject=str_replace(array('{name}','{link}'),array($twitter->name,$link),$emailcontent->getSubject());
				
				/*******Contact mail send to admin***********/								
				$this->mailsend($emaillist->getFromname(),$emaillist->getFromemail(),$user[0]['email'],$subject,$message,$emaillist->getCcmail());
				
				$this->get('session')->getFlashBag()->add('success',$this->get('translator')->trans('Successfully Registered. Confirmation link sent to your mail id.'));
				return $this->redirect($this->generateUrl('mytrip_user_profile'));
				
			}else{
				$uid=$emailcheck[0]['userId'];
				$check_social_link = $em->createQuery("SELECT p FROM MytripAdminBundle:UserSocialLink p WHERE p.user='".$uid."' AND p.socialLink='Twitter' AND p.id='".$twitter->id."'")->getArrayResult();
				if(empty($check_social_link)){
					$social_links = new \Mytrip\AdminBundle\Entity\UserSocialLink();
					$social_links->setUser($this->getDoctrine()->getRepository('MytripAdminBundle:User')->find($uid));
					$social_links->setSocialLink('Twitter');
					$social_links->setId($twitter->id);
					$social_links->setImage($twitter->profile_image_url);
					$em->persist($social_links);
					$em->flush();
				}
				
				$user = $em->createQuery("SELECT p FROM MytripAdminBundle:User p WHERE p.userId='".$uid."'")->getArrayResult();	
				$session->set('user',$user[0]);
				$session->set('UserLogin', "True");	
				return $this->redirect($this->generateUrl('mytrip_user_profile'));
			}
		}
		return $this->redirect($this->generateUrl('mytrip_user_homepage'));
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
