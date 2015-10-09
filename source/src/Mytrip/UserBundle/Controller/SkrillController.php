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

class SkrillController extends Controller
{
   
    public function indexAction(Request $request)
    {
		
        $paymentName = 'skrill_checkout';
		    
        $session = $request->getSession();		
		$lan=$session->get('language');			
		
        if ($session->get('bookingId')) {
			
			$bookingid=$session->get('bookingId');	
			$email  = $this->container->get('mytrip_admin.helper.date')->getOption('skrillusername');	
					
			return $this->render('MytripUserBundle:Skrill:index.html.php',array('bookingid'=>$bookingid,'skrill_email'=>$email,'url'=>array('url'=>$this->getRequest()->getSchemeAndHttpHost(),'rootfolder'=>$this->container->get('router')->getContext()->getBaseUrl())));
			           
        }else{
			return $this->redirect($this->generateUrl('mytrip_user_homepage'));
		}		
		
    }

   
}