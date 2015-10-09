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


class ReviewController extends Controller{
	
    public function indexAction(Request $request){
		/****Check language session*******/
		$session = $request->getSession();	
		$user=$session->get('user');
		
		if(!empty($user)){			
			$lan=$session->get('language');
			$request->setLocale($lan);				
			$type=$request->request->get('type');	
			$em = $this->container->get('doctrine')->getManager();	
			$typeid=0;	
			if($type=='Destination'){
				$destination = $em->createQuery("SELECT d FROM MytripAdminBundle:Destination d WHERE d.status='Active' AND d.url='".$request->request->get('destination')."'")->getArrayResult();
				$typeid=$destination[0]['destinationId'];
			}elseif($type=='Hostal'){	
				$destination = $em->createQuery("SELECT d FROM MytripAdminBundle:Destination d WHERE d.status='Active' AND d.url='".$request->request->get('destination')."'")->getArrayResult();			
				$hostal = $em->createQuery("SELECT d FROM MytripAdminBundle:Hostal d WHERE d.status='Active' AND d.destination='".$destination[0]['destinationId']."' AND d.url='".$request->request->get('hostal')."'")->getArrayResult();
				$typeid=$hostal[0]['hostalId'];
			}			
			if($typeid >0){
				$reviews = new \Mytrip\AdminBundle\Entity\Review();
				$reviews->setUser($this->getDoctrine()->getRepository('MytripAdminBundle:User')->find($user['userId']));
				$reviews->setTypeId($typeid);
				$reviews->setReviewType($request->request->get('type'));
				$reviews->setRating($request->request->get('rate'));
				$reviews->setReview($request->request->get('review'));
				$reviews->setLan($lan);
				$reviews->setStatus('Active');
                                $reviews->setCreatedDate(new \DateTime());
				$em->persist($reviews);
				$em->flush();
				
				$check=array('suc'=>'1','msg'=>$this->get('translator')->trans('Thanks for your interest on us'));	
			}else{
				$check=array('suc'=>'0','msg'=>$this->get('translator')->trans('Sorry, Please try again'));
			}
			
		}else{
			$session->set('destination',$request->request->get('destination'));
			$session->set('hostal',$request->request->get('hostal'));
			$session->set('type',$request->request->get('type'));
			$session->set('rate',$request->request->get('rate'));
			$session->set('review',$request->request->get('review'));
                        $session->set('created_date', new \DateTime());
			$this->get('session')->getFlashBag()->add('loginerror',$this->get('translator')->trans('Please Login'));
			$check=array('suc'=>'2','msg'=>$this->get('translator')->trans('Please Login'));
		}
		 return new Response(json_encode($check));
    }
	
}
