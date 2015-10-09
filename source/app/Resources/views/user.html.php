<?php $em = $this->container->get('doctrine')->getManager();?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<title><?php if(!empty($seo) && !empty($seo['title'])){ echo $seo['title'];}else{ echo 'My Trip in Cuba';}?></title>
<meta name="description" content="<?php if(!empty($seo) && !empty($seo['metadescription'])){ echo $seo['metadescription'];}else{ echo 'My Trip in Cuba';}?>">
<meta name="keywords" content="<?php if(!empty($seo) && !empty($seo['metakeywords'])){ echo $seo['metakeywords'];}else{ echo 'My Trip in Cuba';}?>" >

<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="BaseUrl" content="<?php echo $this->container->get('request')->getSchemeAndHttpHost().$this->container->get('router')->getContext()->getBaseUrl();?>"/>

<link rel="shortcut icon" href="<?php echo $view['assets']->getUrl('img/favicon.ico') ?>" type="image/x-icon">
<link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('css/normalize.css') ?>">
<link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('css/main.css') ?>">
<link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('css/mediaqueries.css') ?>">
<link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('css/fonts.css') ?>">
<link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('css/font-awesome.css') ?>">
<link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('css/zebra_calender.css') ?>">
<link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('css/jQuery.validation/validationEngine.jquery.css'); ?>" >
<link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('css/jquery-ui.css'); ?>" >

<script type="text/javascript" src="<?php echo $view['assets']->getUrl('js/jquery-1.8.3.js') ?>"></script>
<script type="text/javascript" src="<?php echo $view['assets']->getUrl('js/jquery-ui.js') ?>"></script>
<script type="text/javascript" src="<?php echo $view['assets']->getUrl('js/jquery.leanModal.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo $view['assets']->getUrl('js/jQuery.validation/jquery.validationEngine.js'); ?>"></script>
<script type="text/javascript" src="<?php echo $view['assets']->getUrl('js/jQuery.validation/languages/jquery.validationEngine-'.$view['session']->get('language').'.js'); ?>"></script>
<script type="text/javascript" src="<?php echo $view['assets']->getUrl('js/home_common.js') ?>"></script>
<script type="text/javascript" src="<?php echo $view['assets']->getUrl('js/jquery.maxlength.js') ?>"></script>
<?php if($view['session']->get('user')==''){?>
<script type="text/javascript" src="<?php echo $view['assets']->getUrl('js/home_login.js') ?>"></script>
<?php }?>
</head>
<body>
<div class="helpfade"></div>
<div class="helptips"><div class="loader_block"><div class="loader_block_inner"></div><div class="loader_text"><?php echo $view['translator']->trans('Please wait');?>...</div></div></div>
<div class="sign-sec">
  <div class="container top-container">
    <div class="logo"><a href="<?php echo $view['router']->generate('mytrip_user_homepage');?>"><img src="<?php echo $view['assets']->getUrl('img/logo-'.$view['session']->get('language').'.png'); ?>" alt="<?php echo $view['translator']->trans('Mytrip to cuba');?>"></a></div>
    <ul class="log">
     <?php if($view['session']->get('user')==''){ ?>
      <li><a href="#signup"  name="topsignup" rel="leanModal" id="topsignup" class="go"><?php echo $view['translator']->trans('Sign Up');?></a></li>
      <li><a href="#login"  name="toplogin" rel="leanModal" id="toplogin" class="go"><?php echo $view['translator']->trans('Log In');?></a></li>
      <?php }else{	
	 	 $user=$view['session']->get('user');
		 $em =  $this->container->get('doctrine')->getManager();
		 $topuser=$em->createQuery("SELECT p FROM MytripAdminBundle:User p WHERE p.userId=".$user['userId'])->getArrayResult();	 
		 $pimg=$view['assets']->getUrl('img/bigprofileimage.jpg');
		 $imgurl=str_replace(array("/app_dev.php","/app.php"),"",$this->container->get('router')->getContext()->getBaseUrl())."/timthumb.php";
		if($topuser[0]['image']!=''){
			$pimg=$view['assets']->getUrl('img/user/'.$topuser[0]['image']);
		}else{
			$proimg=$em->createQuery("SELECT u, (RAND()) AS HIDDEN r FROM MytripAdminBundle:UserSocialLink u WHERE u.user='".$user['userId']."' AND u.image !='' ORDER BY r ")->setMaxResults(1)->getArrayResult();
			if(!empty($proimg)){
				$pimg=$proimg[0]['image'];
			}
		} 
		  ?>
      <li><a href="#"><span class="pro-p"><img src="<?php echo $imgurl.'?src='.$pimg.'&w=15&h=15';?>" width="15" height="15"  alt="<?php echo $topuser['0']['firstname'];?>"></span><?php echo $topuser['0']['firstname'];?> <i class="fa fa-angle-down"></i></a>
        <ul class="top_profile">
            <li><a href="<?php echo $view['router']->generate('mytrip_user_profile');?>"><?php echo $view['translator']->trans('Profile');?></a></li>
            <li><a href="<?php echo $view['router']->generate('mytrip_user_account');?>"><?php echo $view['translator']->trans('Account');?></a></li>
            <li><a href="<?php echo $view['router']->generate('mytrip_user_bookinghistory');?>"><?php echo $view['translator']->trans('Booking');?></a></li>
            <li><a href="<?php echo $view['router']->generate('mytrip_user_logout');?>"><?php echo $view['translator']->trans('Logout');?></a></li>
          </ul>
      </li>
          <?php
	  }?>
      <?php
	    $help_menu = $em->createQuery("SELECT p FROM MytripAdminBundle:Staticpage p  WHERE p.staticpageId=16 ")->getArrayResult();
		if($help_menu[0]['status']=="Active"){
	    $help_menu_content = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='".$view['session']->get('language')."' AND p.staticpage=16 ")->getArrayResult();
		if(empty($help_menu_content)){
			$help_menu_content = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage=16")->getArrayResult();	
		}
		
	  ?>
      <li><a href="<?php echo $view['router']->generate('mytrip_user_help');?>"><?php echo $help_menu_content['0']['name'];?><?php //echo $view['translator']->trans('Help');?> <i class="fa fa-angle-down"></i></a>
      <?php
	    $faq_menu = $em->createQuery("SELECT p FROM MytripAdminBundle:Staticpage p  WHERE p.staticpageId=19 ")->getArrayResult();		
	    $faq_menu_content = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='".$view['session']->get('language')."' AND p.staticpage=19 ")->getArrayResult();
		if(empty($faq_menu_content)){
			$faq_menu_content = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage=19")->getArrayResult();	
		}
		$help_menus= $em->createQuery("SELECT p FROM MytripAdminBundle:Staticpage p  WHERE p.menuId=16 AND p.status='Active'")->getArrayResult();		
		if($faq_menu[0]['status']=="Active" || !empty($help_menus)){
	  ?>
      <ul>
      <?php if($faq_menu[0]['status']=="Active"){?>
       <li><a href="<?php echo $view['router']->generate('mytrip_user_faq');?>"><?php echo $faq_menu_content['0']['name'];?><?php //echo $view['translator']->trans('FAQ');?></a></li> 
      <?php }?> 
      <?php
	  if(!empty($help_menus)){
		  foreach($help_menus as $help_menus){
			$help_menus_content = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='".$view['session']->get('language')."' AND p.staticpage='".$help_menus['staticpageId']."' ")->getArrayResult();
			if(empty($help_menus_content)){
				$help_menus_content = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage='".$help_menus['staticpageId']."'")->getArrayResult();	
			}  
		  ?>
           <li><a href="<?php echo $this->container->get('router')->getContext()->getBaseUrl()."/pages/".$help_menus['url'];?>"><?php echo $help_menus_content[0]['name'];?><?php //echo $view['translator']->trans('FAQ');?></a></li> 
          <?php
		  }
	  }
	  ?>          
        </ul>
      <?php }?>  
      </li>
      <?php }?>
      <li class="cur"><a><?php echo $view['session']->get('currency');?>  <i class="fa fa-angle-down"></i></a>
        <ul>
          <li><a href="<?php echo $view['router']->generate('mytrip_user_changecurrency',array('currency'=>'CAD'));?>">CAD</a></li>
          <li><a href="<?php echo $view['router']->generate('mytrip_user_changecurrency',array('currency'=>'USD'));?>">USD</a></li>
          <li><a href="<?php echo $view['router']->generate('mytrip_user_changecurrency',array('currency'=>'EUR'));?>">EUR</a></li>
        </ul>
      </li>
      <li><a><img src="<?php echo $view['assets']->getUrl('img/language/'.$view['session']->get('language').'.jpg');?>" width="17" height="11" alt="flag"><i class="fa fa-angle-down"></i></a>
        <ul class="lan">
        <?php
		foreach($language as $language){
          echo '<li><a href="'.$view['router']->generate('mytrip_user_changelanguage',array('lan'=>$language['lanCode'])).'"><img src="'.$view['assets']->getUrl('img/language/'.$language['flag']).'" alt="'.$language['language'].'">&nbsp;'.$language['language'].'</a></li>';
		}
		?>         
        </ul>
      </li>
    </ul>
  </div>
</div>
<div id="menu"> <span class="line-li flip" >≡</span>
  <div class="menu-container">
      <div class="dummy"><a name="top"></a></div>
    <ul class="menu-in panel" >
      <li <?php echo (in_array($this->container->get('request')->get('_route'),array('mytrip_user_homepage'))?'class="active"':'');?> ><a href="<?php echo $view['router']->generate('mytrip_user_homepage');?>" class="homebg">
	  <?php
	    $homemenu = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='".$view['session']->get('language')."' AND p.staticpage=1 ")->getArrayResult();
		if(empty($homemenu)){
			$homemenu = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage=1")->getArrayResult();	
		}
	  ?>
	  <?php echo $homemenu['0']['name'];?></a></li>
      <li class="has-sub <?php echo (in_array($this->container->get('request')->get('_route'),array('mytrip_user_aboutus'))?'active':'');?>"> <a href="<?php echo $view['router']->generate('mytrip_user_aboutus');?>"><span class="fl-left"><?php echo $view['translator']->trans('About Us');?> </span> </a>
      <?php
	  $aboutmenu1 = $em->createQuery("SELECT p FROM MytripAdminBundle:Staticpage p  WHERE p.status='Active' AND p.staticpageId=3 ")->getArrayResult();
	  $aboutmenu2 = $em->createQuery("SELECT p FROM MytripAdminBundle:Staticpage p  WHERE p.status='Active' AND p.staticpageId=2 ")->getArrayResult();
	  $aboutmenu3 = $em->createQuery("SELECT p FROM MytripAdminBundle:Staticpage p  WHERE p.status='Active' AND p.staticpageId=4 ")->getArrayResult();
	  $aboutmenus= $em->createQuery("SELECT p FROM MytripAdminBundle:Staticpage p  WHERE p.menuId=22 AND p.status='Active'")->getArrayResult();		  
	  if(!empty($aboutmenu1) || !empty($aboutmenu2) || !empty($aboutmenu3) || !empty($aboutmenus)){
	  ?>
       <i class="fa fa-angle-down line-plus flips" id="panel1"></i>
        <ul class="hidesub panels">
        <?php		
		if(!empty($aboutmenu1)){
	    $aboutmenu1_content = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='".$view['session']->get('language')."' AND p.staticpage=3 ")->getArrayResult();
		if(empty($aboutmenu1_content)){
			$aboutmenu1_content = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage=3")->getArrayResult();	
		}
	  ?>
          <li><a  href="<?php echo $view['router']->generate('mytrip_user_aboutus');?>?term=what_do_we_do"><?php echo $aboutmenu1_content['0']['name'];?></a></li>
        <?php }?>
        <?php
		
		if(!empty($aboutmenu2)){
	    $aboutmenu2_content = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='".$view['session']->get('language')."' AND p.staticpage=2 ")->getArrayResult();
		if(empty($aboutmenu2_content)){
			$aboutmenu2_content = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage=2")->getArrayResult();	
		}
	  ?>
          <li><a  href="<?php echo $view['router']->generate('mytrip_user_aboutus');?>?term=what_we_are"><?php echo $aboutmenu2_content['0']['name'];?></a></li>
        <?php }?>
        <?php
		
		if(!empty($aboutmenu3)){
	    $aboutmenu3_content = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='".$view['session']->get('language')."' AND p.staticpage=4 ")->getArrayResult();
		if(empty($aboutmenu3_content)){
			$aboutmenu3_content = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage=4")->getArrayResult();	
		}
	  ?>
          <li><a  href="<?php echo $view['router']->generate('mytrip_user_aboutus');?>?term=what_do_we_offer"><?php echo $aboutmenu3_content['0']['name'];?></a></li>
        <?php }?>
         <?php
	  if(!empty($aboutmenus)){
		  foreach($aboutmenus as $aboutmenus){
			$aboutmenus_content = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='".$view['session']->get('language')."' AND p.staticpage='".$aboutmenus['staticpageId']."' ")->getArrayResult();
			if(empty($aboutmenus_content)){
				$aboutmenus_content = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage='".$aboutmenus['staticpageId']."'")->getArrayResult();	
			}  
		  ?>
           <li><a href="<?php echo $this->container->get('router')->getContext()->getBaseUrl()."/pages/".$aboutmenus['url'];?>"><?php echo $aboutmenus_content[0]['name'];?></a></li> 
          <?php
		  }
	  }
	  ?> 
        </ul>
        <?php }?>
      </li>
      <li class="has-sub <?php echo (in_array($this->container->get('request')->get('_route'),array('mytrip_user_destination','mytrip_user_destinationDetails','mytrip_user_hostal'))?'active':'');?>"> <a href="<?php echo $view['router']->generate('mytrip_user_destination');?>"><span class="fl-left">
	   <?php
	    $destination_guide_menu = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='".$view['session']->get('language')."' AND p.staticpage=17 ")->getArrayResult();
		if(empty($destination_guide_menu)){
			$destination_guide_menu = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage=17")->getArrayResult();	
		}
	  ?>
	  <?php echo $destination_guide_menu['0']['name'];?></span> </a>
      <?php
	    $destinationmenus= $em->createQuery("SELECT p FROM MytripAdminBundle:Staticpage p  WHERE p.menuId=17 AND p.status='Active'")->getArrayResult();
		$destinations_list=$em->createQuery("SELECT d FROM MytripAdminBundle:Destination d WHERE d.status='Active'")->getArrayResult();
		if(!empty($destinationmenus) || !empty($destinations_list)){	
		?>
       <i class="fa fa-angle-down line-plus flips" id="panel1"></i>
        <ul class="hidesub panels">
       <?php
	   if(!empty($destinations_list)){
		 foreach($destinations_list as $destinations_list){
			 $destinations_list_content=$em->createQuery("SELECT p FROM MytripAdminBundle:DestinationContent p  WHERE  p.destination='".$destinations_list['destinationId']."' AND p.lan='".$view['session']->get('language')."'")->getArrayResult();
			 if(empty($destinations_list_content)){
				  $destinations_list_content=$em->createQuery("SELECT p FROM MytripAdminBundle:DestinationContent p  WHERE p.destination='".$destinations_list['destinationId']."' AND p.lan='en'")->getArrayResult();
			 }
			 ?>
             <li><a href="<?php echo $this->container->get('router')->getContext()->getBaseUrl()."/".$destinations_list['url'];?>"><?php echo $destinations_list_content[0]['name'];?></a></li>
             <?php
		 }
	   }
	   if(!empty($destinationmenus)){
	    foreach($destinationmenus as $destinationmenus){
			$destinationmenus_content = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='".$view['session']->get('language')."' AND p.staticpage='".$destinationmenus['staticpageId']."' ")->getArrayResult();
			if(empty($destinationmenus_content)){
				$destinationmenus_content = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage='".$destinationmenus['staticpageId']."'")->getArrayResult();	
			}  
		  ?>
           <li><a href="<?php echo $this->container->get('router')->getContext()->getBaseUrl()."/pages/".$destinationmenus['url'];?>"><?php echo $destinationmenus_content[0]['name'];?></a></li> 
          <?php
		  }
		}
	   ?>
        </ul>
      <?php }?>
      </li>   
      <li <?php echo (in_array($this->container->get('request')->get('_route'),array('mytrip_user_story','mytrip_user_storyDetails'))?'class="active"':'');?>><a href="<?php echo $view['router']->generate('mytrip_user_story');?>">
	  <?php
	    $stories_menu = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='".$view['session']->get('language')."' AND p.staticpage=9 ")->getArrayResult();
		if(empty($stories_menu)){
			$stories_menu = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage=9")->getArrayResult();	
		}
	  ?>
	  <?php echo $stories_menu['0']['name'];?></a>
        <?php
	    $storiesmenus= $em->createQuery("SELECT p FROM MytripAdminBundle:Staticpage p  WHERE p.menuId=9 AND p.status='Active'")->getArrayResult();
		if(!empty($storiesmenus)){	
		?>
       <i class="fa fa-angle-down line-plus flips" id="panel1"></i>
        <ul class="hidesub panels">
       <?php
	    foreach($storiesmenus as $storiesmenus){
			$storiesmenus_content = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='".$view['session']->get('language')."' AND p.staticpage='".$storiesmenus['staticpageId']."' ")->getArrayResult();
			if(empty($storiesmenus_content)){
				$storiesmenus_content = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage='".$storiesmenus['staticpageId']."'")->getArrayResult();	
			}  
		  ?>
           <li><a href="<?php echo $this->container->get('router')->getContext()->getBaseUrl()."/pages/".$storiesmenus['url'];?>"><?php echo $storiesmenus_content[0]['name'];?></a></li> 
          <?php
		  }
	   ?>
        </ul>
      <?php }?></li>   
      <li <?php echo (in_array($this->container->get('request')->get('_route'),array('mytrip_user_topdestination'))?'class="active"':'');?> ><a href="<?php echo $view['router']->generate('mytrip_user_topdestination');?>">
	  <?php
	    $top_destination_menu = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='".$view['session']->get('language')."' AND p.staticpage=18 ")->getArrayResult();
		if(empty($top_destination_menu)){
			$top_destination_menu = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage=18")->getArrayResult();	
		}
	  ?>
	  <?php echo $top_destination_menu['0']['name'];?></a>
       <?php
	    $top_destinationmenus= $em->createQuery("SELECT p FROM MytripAdminBundle:Staticpage p  WHERE p.menuId=18 AND p.status='Active'")->getArrayResult();
		if(!empty($top_destinationmenus)){	
		?>
       <i class="fa fa-angle-down line-plus flips" id="panel1"></i>
        <ul class="hidesub panels">
       <?php
	    foreach($top_destinationmenus as $top_destinationmenus){
			$top_destinationmenus_content = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='".$view['session']->get('language')."' AND p.staticpage='".$top_destinationmenus['staticpageId']."' ")->getArrayResult();
			if(empty($top_destinationmenus_content)){
				$top_destinationmenus_content = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage='".$top_destinationmenus['staticpageId']."'")->getArrayResult();	
			}  
		  ?>
           <li><a href="<?php echo $this->container->get('router')->getContext()->getBaseUrl()."/pages/".$top_destinationmenus['url'];?>"><?php echo $top_destinationmenus_content[0]['name'];?></a></li> 
          <?php
		  }
	   ?>
        </ul>
      <?php }?>
      </li>
      <li <?php echo (in_array($this->container->get('request')->get('_route'),array('mytrip_user_contact'))?'class="active"':'');?>><a href="<?php echo $view['router']->generate('mytrip_user_contact');?>">
	  <?php
	    $contactus_menu = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='".$view['session']->get('language')."' AND p.staticpage=7 ")->getArrayResult();
		if(empty($contactus_menu)){
			$contactus_menu = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage=7")->getArrayResult();	
		}
	  ?>
	  <?php echo $contactus_menu['0']['name'];?></a>
      <?php
	    $contactusmenus= $em->createQuery("SELECT p FROM MytripAdminBundle:Staticpage p  WHERE p.menuId=18 AND p.status='Active'")->getArrayResult();
		if(!empty($contactusmenus)){	
		?>
       <i class="fa fa-angle-down line-plus flips" id="panel1"></i>
        <ul class="hidesub panels">
       <?php
	    foreach($contactusmenus as $contactusmenus){
			$contactusmenus_content = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='".$view['session']->get('language')."' AND p.staticpage='".$contactusmenus['staticpageId']."' ")->getArrayResult();
			if(empty($contactusmenus_content)){
				$contactusmenus_content = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage='".$contactusmenus['staticpageId']."'")->getArrayResult();	
			}  
		  ?>
           <li><a href="<?php echo $this->container->get('router')->getContext()->getBaseUrl()."/pages/".$contactusmenus['url'];?>"><?php echo $contactusmenus_content[0]['name'];?></a></li> 
          <?php
		  }
	   ?>
        </ul>
      <?php }?>
      </li>
      <?php if (1>2): ?><li class="has-sub <?php echo (in_array($this->container->get('request')->get('_route'),array('mytrip_user_press','mytrip_user_blog'))?'active':'');?>"> <a><span class="fl-left"><?php echo $view['translator']->trans('More');?> </span> </a>
      <?php 
	  $press_menu = $em->createQuery("SELECT p FROM MytripAdminBundle:Staticpage p  WHERE p.status='Active' AND p.staticpageId=14 ")->getArrayResult();
	  $blog_menu = $em->createQuery("SELECT p FROM MytripAdminBundle:Staticpage p  WHERE p.status='Active' AND p.staticpageId=15 ")->getArrayResult();
	  $moremenus= $em->createQuery("SELECT p FROM MytripAdminBundle:Staticpage p  WHERE p.menuId=23 AND p.status='Active'")->getArrayResult();
	   if(!empty($press_menu) || !empty($blog_menu) || !empty($moremenus)){		 
	  ?>
      <i class="fa fa-angle-down line-plus flips" id="panel4"></i>
        <ul class="hidesub panels">
        <?php
	    $press_menu_content = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='".$view['session']->get('language')."' AND p.staticpage=14 ")->getArrayResult();
		if(empty($press_menu_content)){
			$press_menu_content = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage=14")->getArrayResult();	
		}
	  ?>
          	<li><a  href="<?php echo $view['router']->generate('mytrip_user_press');?>"><?php echo $press_menu_content['0']['name'];?></a></li>
      <?php
	    $blog_menu_content = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='".$view['session']->get('language')."' AND p.staticpage=15 ")->getArrayResult();
		if(empty($blog_menu_content)){
			$blog_menu_content = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage=15")->getArrayResult();	
		}
	  ?>
            <li><a  href="<?php echo $view['router']->generate('mytrip_user_blog');?>"><?php echo $blog_menu_content['0']['name'];?></a></li>  
            <?php
	  if(!empty($moremenus)){
		  foreach($moremenus as $moremenus){
			$moremenus_content = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='".$view['session']->get('language')."' AND p.staticpage='".$moremenus['staticpageId']."' ")->getArrayResult();
			if(empty($moremenus_content)){
				$moremenus_content = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage='".$moremenus['staticpageId']."'")->getArrayResult();	
			}  
		  ?>
           <li><a href="<?php echo $this->container->get('router')->getContext()->getBaseUrl()."/pages/".$moremenus['url'];?>"><?php echo $moremenus_content[0]['name'];?></a></li> 
          <?php
		  }
	  }
	  ?>         
        </ul>
        <?php }?>
      </li><?php endif; ?>
    </ul>
    <form class="search" id="searchform" name="searchform" action="<?php echo $view['router']->generate('mytrip_user_search') ?>" method="post">
      <input type="text" id="searchtext" name="searchtext" class="validate[required]" placeholder="<?php echo $view['translator']->trans('Search');?>">
      <button type="submit" name="searchsubmit" id="searchsubmit" class="search-icon"> <i class="fa fa-search "></i></button>
    </form>
  </div>
</div>
<div class="body-sec">
	<?php $view['slots']->output('_content'); ?>
  <div class="footer">
    <div class="container">
        <div class="foot">
        <div class="foot-inside">
          <h3><?php echo $view['translator']->trans('About Us');?></h3>
          <ul class="foot-menu">
          <?php if(!empty($aboutmenu1)){?>
            <li><a  href="<?php echo $view['router']->generate('mytrip_user_aboutus');?>?term=what_do_we_do"><?php echo $aboutmenu1_content['0']['name'];?></a></li>
            <?php }?>
            <?php if(!empty($aboutmenu2)){?>
            <li><a  href="<?php echo $view['router']->generate('mytrip_user_aboutus');?>?term=what_we_are"><?php echo $aboutmenu2_content['0']['name'];?></a></li>
              <?php }?>
            <?php if(!empty($aboutmenu3)){?>
            <li><a  href="<?php echo $view['router']->generate('mytrip_user_aboutus');?>?term=what_do_we_offer"><?php echo $aboutmenu3_content['0']['name'];?></a></li>
              <?php }?>
              <?php
		   $payment_menus = $em->createQuery("SELECT p FROM MytripAdminBundle:Staticpage p  WHERE p.status='Active' AND p.staticpageId=5 ")->getArrayResult();
		   if(!empty($payment_menus)){
		  ?>
            <?php
			$payment_menu = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='".$view['session']->get('language')."' AND p.staticpage=5 ")->getArrayResult();
			if(empty($payment_menu)){
				$payment_menu = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage=5")->getArrayResult();	
			}
		  ?>
            <li><a  href="<?php echo $view['router']->generate('mytrip_user_payment');?>"><?php echo $payment_menu['0']['name'];?></a></li>
             <?php }?>
              <?php
		   $terms_menus = $em->createQuery("SELECT p FROM MytripAdminBundle:Staticpage p  WHERE p.status='Active' AND p.staticpageId=6 ")->getArrayResult();
		   if(!empty($paymen_menus)){
		  ?>
            <?php
			$terms_menu = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='".$view['session']->get('language')."' AND p.staticpage=6 ")->getArrayResult();
			if(empty($terms_menu)){
				$terms_menu = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage=6")->getArrayResult();	
			}
		  ?>
            <li><a  href="<?php echo $view['router']->generate('mytrip_user_payment');?>?terms=1"><?php echo $terms_menu['0']['name'];?></a></li>
            <?php }?>
            <li><a  href="<?php echo $view['router']->generate('mytrip_user_contact');?>"><?php echo $contactus_menu['0']['name'];?></a></li>
          </ul>
        </div>
      </div>
      <div class="foot foot-two">
        <div class="foot-inside">
          <h3><?php echo $view['translator']->trans('Destination Guides');?></h3>
          <ul class="foot-menu">
            <li><a  href="<?php echo $view['router']->generate('mytrip_user_topdestination');?>"> <?php echo $top_destination_menu['0']['name'];?><?php //echo $view['translator']->trans('Top Destinations');?></a></li>
            <?php
			$em = $this->container->get('doctrine')->getManager();	
			$topreviews=$em->createQuery("SELECT d.typeId,(SUM(d.rating)/COUNT(d)) AS HIDDEN rate FROM MytripAdminBundle:Review d  LEFT JOIN MytripAdminBundle:Destination r WITH r.destinationId=d.typeId WHERE r.status='Active' AND  d.reviewType='Destination' AND d.status='Active' GROUP BY d.typeId ORDER BY rate DESC ")->setMaxResults('12')->getArrayResult(); 
			if(empty($topreview)){
				 $topreviews=$em->createQuery("SELECT d FROM MytripAdminBundle:Destination d WHERE d.status='Active'")->setMaxResults('12')->getArrayResult();
			 }		
			$t=1;
			foreach($topreviews as $topreviews){
				 if(empty($topreviews['typeId'])){
					  $topreviews['typeId']=$topreviews['destinationId'];
				  }				
				$topdestination=$em->createQuery("SELECT c FROM MytripAdminBundle:Destination c WHERE  c.destinationId='".$topreviews['typeId']."'")->getArrayResult();
				$topdestination_content=$em->createQuery("SELECT c FROM MytripAdminBundle:DestinationContent c WHERE c.lan='".$view['session']->get('language')."' AND c.destination='".$topdestination[0]['destinationId']."'")->getArrayResult();
			  if(empty($topdestination_content)){
				  $topdestination_content=$em->createQuery("SELECT c FROM MytripAdminBundle:DestinationContent c WHERE c.lan='en' AND c.destination='".$topdestination[0]['destinationId']."'")->getArrayResult();
			  }
				?>
            <li><a  href="<?php echo $this->container->get('router')->getContext()->getBaseUrl()."/".$topdestination[0]['url'];?>"><?php echo $topdestination_content[0]['name'];?></a></li>
            <?php 
				if($t%6==0){
					echo '</ul>
          <ul class="foot-menu foot-sub"><li><a>&nbsp;</a></li>';
				}
				$t++;
			}?>          
          </ul>         
        </div>
      </div>
      <div class="foot">
        <div class="foot-inside">
          <h3><?php echo $view['translator']->trans('Company');?></h3>
          <ul class="foot-menu">
          <?php
		   $hospitality_menus = $em->createQuery("SELECT p FROM MytripAdminBundle:Staticpage p  WHERE p.status='Active' AND p.staticpageId=8 ")->getArrayResult();
		   if(!empty($hospitality_menus)){
		  ?>
          <?php
			$hospitality_menu = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='".$view['session']->get('language')."' AND p.staticpage=8 ")->getArrayResult();
			if(empty($hospitality_menu)){
				$hospitality_menu = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage=8")->getArrayResult();	
			}
		  ?>
            <li><a  href="<?php echo $view['router']->generate('mytrip_user_hospitality');?>"><?php echo $hospitality_menu['0']['name'];?></a></li>
          <?php }?>
            <li><a  href="<?php echo $view['router']->generate('mytrip_user_story');?>"><?php echo $stories_menu['0']['name'];?></a></li>
            <?php
		   $trust_menus = $em->createQuery("SELECT p FROM MytripAdminBundle:Staticpage p  WHERE p.status='Active' AND p.staticpageId=10 ")->getArrayResult();
		   if(!empty($trust_menus)){
		  ?>
            <?php
			$trust_menu = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='".$view['session']->get('language')."' AND p.staticpage=10 ")->getArrayResult();
			if(empty($trust_menu)){
				$trust_menu = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage=10")->getArrayResult();	
			}
		  ?>
            <li><a  href="<?php echo $view['router']->generate('mytrip_user_trust');?>"><?php echo $trust_menu['0']['name'];?></a></li>
              <?php }?>
               <?php
		   $recreation_menus = $em->createQuery("SELECT p FROM MytripAdminBundle:Staticpage p  WHERE p.status='Active' AND p.staticpageId=11 ")->getArrayResult();
		   if(!empty($recreation_menus)){
		  ?>
              <?php
			$recreation_menu = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='".$view['session']->get('language')."' AND p.staticpage=11 ")->getArrayResult();
			if(empty($recreation_menu)){
				$recreation_menu = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage=11")->getArrayResult();	
			}
		  ?>
            <li><a  href="<?php echo $view['router']->generate('mytrip_user_recreation');?>"><?php echo $recreation_menu['0']['name'];?></a></li>
             <?php }?>
                <?php
		   $food_menus = $em->createQuery("SELECT p FROM MytripAdminBundle:Staticpage p  WHERE p.status='Active' AND p.staticpageId=12 ")->getArrayResult();
		   if(!empty($food_menus)){
		  ?>
             <?php
			$food_menu = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='".$view['session']->get('language')."' AND p.staticpage=12 ")->getArrayResult();
			if(empty($food_menu)){
				$food_menu = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage=12")->getArrayResult();	
			}
		  ?>
            <li><a  href="<?php echo $view['router']->generate('mytrip_user_food');?>"><?php echo $food_menu['0']['name'];?></a></li>
            <?php }?>
               <?php
		   $technical_menus = $em->createQuery("SELECT p FROM MytripAdminBundle:Staticpage p  WHERE p.status='Active' AND p.staticpageId=13 ")->getArrayResult();
		   if(!empty($technical_menus)){
		  ?>
            <?php
			$technical_menu = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='".$view['session']->get('language')."' AND p.staticpage=13 ")->getArrayResult();
			if(empty($technical_menu)){
				$technical_menu = $em->createQuery("SELECT p FROM MytripAdminBundle:StaticpageContent p  WHERE p.lan='en' AND p.staticpage=13")->getArrayResult();	
			}
		  ?>
            <li><a  href="<?php echo $view['router']->generate('mytrip_user_support');?>"><?php echo $technical_menu['0']['name'];?></a></li>
             <?php }?>
          </ul>
        </div>
      </div>
      <div class="foot foot-small">
        <div class="foot-inside">
          <h3><?php echo $view['translator']->trans('More');?></h3>
          <ul class="foot-menu">
          <?php if(!empty($press_menu)){?>
            <li><a  href="<?php echo $view['router']->generate('mytrip_user_press');?>"><?php echo $press_menu_content['0']['name'];?></a></li>
            <?php }?>
             <?php if(!empty($blog_menu)){?>
            <li><a  href="<?php echo $view['router']->generate('mytrip_user_blog');?>"><?php echo $blog_menu_content['0']['name'];?></a></li>
             <?php }?>
            <?php if($help_menu[0]['status']=="Active"){?>
            <li><a  href="<?php echo $view['router']->generate('mytrip_user_help');?>"><?php echo $help_menu_content['0']['name'];?></a></li>
            <?php }?>
            <?php if($view['session']->get('user')==''){?>
            <li><a id="footersignup" class="go"><?php echo $view['translator']->trans('Sign Up');?></a></li>
            <li><a id="footerlogin" class="go"><?php echo $view['translator']->trans('Log In');?></a></li>
            <?php }else{
				?><li><a>&nbsp;</a></li><li><a>&nbsp;</a></li>
                <?php
			}?>
            <li><a>&nbsp;</a></li>            
          </ul>
        </div>
      </div>
      <div class="foot foot-medium">
        <div class="foot-inside">
          <h3><?php echo $view['translator']->trans('Find us online');?></h3>
          <ul class="foot-menu">
            <li><a  href="<?php echo $soacillink[0]['link']; ?>" target="_blank"><?php echo $view['translator']->trans('Join us on Facebook');?></a></li>
            <li><a  href="<?php echo $soacillink[1]['link']; ?>" target="_blank"><?php echo $view['translator']->trans('Follow us on Twitter');?></a></li>
            <li><a  href="<?php echo $soacillink[2]['link']; ?>" target="_blank"><?php echo $view['translator']->trans('Tripadvisor');?></a></li>
            <li><a  href="<?php echo $soacillink[3]['link']; ?>" target="_blank"><?php echo $view['translator']->trans('Flickr');?></a></li>
            <li><a  href="<?php echo $soacillink[4]['link']; ?>" target="_blank"><?php echo $view['translator']->trans('You tube');?> </a></li>
          </ul>
        </div>
      </div>     
    </div>
      <div class="footer-sub">
      <div class="container">
        <p class="copy">&copy; <?php echo date('Y');?> <?php echo $view['translator']->trans('mytrip to cuba S.A.');?> <?php echo $view['translator']->trans('All rights reserved');?>.<br/>
          <?php echo $view['translator']->trans('mytrip to cuba S.A.');?> <a href="<?php echo $view['router']->generate('mytrip_user_payment');?>#terms"><?php echo $view['translator']->trans('Terms of use and Privacy policy');?></a></p>
        <div class="foot-logo"><img src="<?php echo $view['assets']->getUrl('img/horizontal-logo-'.$view['session']->get('language').'.png'); ?>" width="197" height="74" alt="<?php echo $view['translator']->trans('Mytrip to cuba');?>"></div>
      </div>
    </div>
  </div>
</div>
<?php if($view['session']->get('user')==''){?>
<div id="login" class="signup">
<a class="modal_close"></a>
  <h1><?php echo $view['translator']->trans('Log In');?> <img src="<?php echo $view['assets']->getUrl('img/login-icon.png');?>" width="57" height="39"> </h1>
  <div class="social"> 
  <a href="<?php echo $view['router']->generate('mytrip_user_facebook');?>"><img src="<?php echo $view['assets']->getUrl('img/login-facebook-'.$view['session']->get('language').'.png');?>" alt="<?php echo $view['translator']->trans('Login with'); ?> Facebook"></a>
  <a href="<?php echo $view['router']->generate('mytrip_user_twitter');?>"><img src="<?php echo $view['assets']->getUrl('img/login-twitter-'.$view['session']->get('language').'.png');?>" alt="<?php echo $view['translator']->trans('Login with'); ?> Twitter"></a>
  <a href="<?php echo $view['router']->generate('mytrip_user_google');?>"><img src="<?php echo $view['assets']->getUrl('img/login-google+-'.$view['session']->get('language').'.png');?>" alt="<?php echo $view['translator']->trans('Login with'); ?> Goolge"></a>
  </div>
  <span class="or"><?php echo $view['translator']->trans('OR');?> <span class="or-border"></span></span>
   <?php $loginerror=$view['session']->getFlash('loginerror');
	if(!empty($loginerror[0])){
		?>
        <div class="dismsg" id="msginfo"><div class="success msg"><?php echo $loginerror[0];?></div></div>
		<script type="text/javascript">
		$(function() {
			$("#toplogin").click(); 
			setTimeout(function () {  $('#msginfo').fadeOut(); }, 3000);       
		});
		</script>
		<?php
	}?>
  <form name="login" id="loginform" method="post" enctype="multipart/form-data" action="<?php echo $view['router']->generate('mytrip_user_signin');?>">
    <div class="input">
      <input type="text" name="email" id="email" class="validate[required,custom[email]" maxlength="120" placeholder="<?php echo $view['translator']->trans('Email');?>"  data-prompt-position="bottomLeft:20,5"  />
      <img src="<?php echo $view['assets']->getUrl('img/message-1.png');?>" alt="<?php echo $view['translator']->trans('Email');?>"> </div>
    <div class="input">
      <input type="password" name="password" id="password" class="validate[required,minSize[6]]" placeholder="<?php echo $view['translator']->trans('Password');?>"  data-prompt-position="bottomLeft:20,5"  />
      <img src="<?php echo $view['assets']->getUrl('img/pass-1.png');?>" alt="<?php echo $view['translator']->trans('Password');?>"> </div>
    <div class="login-b">
      <input type="submit" name="submit" id="submit" class="submit-review login-b" value="<?php echo $view['translator']->trans('Log In');?>">
    </div>
  </form>
  <span class="already"><a id="signupbutton" style="float:left;"><?php echo $view['translator']->trans('Sign Up');?></a><a id="forgotbutton" style="color:#5a574f; float:right;"><?php echo $view['translator']->trans('Forgot your password?');?></a></span>
  </div>
<div id="forgotdiv" class="signup">
<a class="modal_close"></a>
  <h1><?php echo $view['translator']->trans('Forgot your password?');?> <img src="<?php echo $view['assets']->getUrl('img/login-icon.png');?>" width="57" height="39"> </h1>  
  <form name="forgot" id="forgotform" method="post" enctype="multipart/form-data" action="<?php echo $view['router']->generate('mytrip_user_forgot');?>">
    <div class="input">
      <input type="text" name="email" id="email" class="validate[required,custom[email]" maxlength="120" placeholder="<?php echo $view['translator']->trans('Email');?>"  data-prompt-position="bottomLeft:20,5"  />
      <img src="<?php echo $view['assets']->getUrl('img/message-1.png');?>" alt="<?php echo $view['translator']->trans('Email');?>"> 
      </div>    
    <div class="login-b">
      <input type="submit" name="submit" id="submit" class="submit-review login-b" value="<?php echo $view['translator']->trans('Submit');?>">
    </div>
  </form>
  <span class="already"><?php echo $view['translator']->trans('Already a member of mytrip to cuba?');?> <a id="already_logins"><?php echo $view['translator']->trans('Log In');?></a></span>
  <a href="#forgotdiv" id="topforgot" style="display:none" rel="leanModal" name="forgotdiv"></a>
  </div>
<div id="signup" class="signup">
<a  class="modal_close"></a>
  <h1><?php echo $view['translator']->trans('Sign Up');?> <img src="<?php echo $view['assets']->getUrl('img/sign-in.png');?>" > </h1>
  <div class="social"> 
  <a href="<?php echo $view['router']->generate('mytrip_user_facebook');?>"><img src="<?php echo $view['assets']->getUrl('img/signup-facebook-'.$view['session']->get('language').'.png');?>" alt="<?php echo $view['translator']->trans('Sign up with'); ?> Facebook"></a>
  <a href="<?php echo $view['router']->generate('mytrip_user_twitter');?>"><img src="<?php echo $view['assets']->getUrl('img/signup-twitter-'.$view['session']->get('language').'.png');?>" alt="<?php echo $view['translator']->trans('Sign up with'); ?> Twitter"></a>
  <a href="<?php echo $view['router']->generate('mytrip_user_google');?>"><img src="<?php echo $view['assets']->getUrl('img/signup-google+-'.$view['session']->get('language').'.png');?>" alt="<?php echo $view['translator']->trans('Sign up with'); ?> Goolge"></a>
  </div>
  <span class="or"><?php echo $view['translator']->trans('OR');?></span> 
  <?php $signuperror=$view['session']->getFlash('signuperror');
	if(!empty($signuperror[0])){
		?>
        <div class="dismsg" id="msginfo"><div class="success msg"><?php echo $signuperror[0];?></div></div>
		<script type="text/javascript">
		$(function() {
			$("#topsignup").click(); 
			setTimeout(function () {  $('#msginfo').fadeOut(); }, 3000);       
		});
		</script>
		<?php
	}?>
  <form name="signup" id="signupform" method="post" enctype="multipart/form-data" action="<?php echo $view['router']->generate('mytrip_user_signup');?>">
    <div class="input">
      <input type="text" name="firstname" id="firstname" class="validate[required,custom[onlyLetter]]" maxlength="25" placeholder="<?php echo $view['translator']->trans('Name');?>" data-prompt-position="bottomLeft:20,5" <?php if($view['session']->get('register')!=''){?>value="<?php echo $view['session']->get('register')->get('firstname');?>"<?php }?> />
      <img src="<?php echo $view['assets']->getUrl('img/name.png');?>" alt="<?php echo $view['translator']->trans('Name');?>"> </div>
    <div class="input">
      <input type="text" name="lastname" id="lastname" class="validate[required,custom[onlyLetter]]" maxlength="25" placeholder="<?php echo $view['translator']->trans('Lastname');?>" data-prompt-position="bottomLeft:20,5"  <?php if($view['session']->get('register')!=''){?>value="<?php echo $view['session']->get('register')->get('lastname');?>"<?php }?> />
      <img src="<?php echo $view['assets']->getUrl('img/name.png');?>" alt="<?php echo $view['translator']->trans('Last Name');?>"> </div>
    <div class="input">
      <input type="text" name="email" id="signupemail" class="validate[required,custom[email]]"  maxlength="150" placeholder="<?php echo $view['translator']->trans('Email');?>" data-prompt-position="bottomLeft:20,5"  <?php if($view['session']->get('register')!=''){?>value="<?php echo $view['session']->get('register')->get('email');?>"<?php }?> />
      <img src="<?php echo $view['assets']->getUrl('img/message-1.png');?>" alt="<?php echo $view['translator']->trans('Email');?>"> </div>
     <div class="input">
      <input type="password" name="password" id="passwords" class="validate[required,minSize[6]]" maxlength="20" placeholder="<?php echo $view['translator']->trans('Password');?>" data-prompt-position="bottomLeft:20,5" />
      <img src="<?php echo $view['assets']->getUrl('img/pass-1.png');?>" alt="<?php echo $view['translator']->trans('Password');?>"> </div>
    <div class="input">
      <input type="password" name="cpassword" id="cpassword" class="validate[required,equals[passwords]]" maxlength="20" placeholder="<?php echo $view['translator']->trans('Confirm Password');?>" data-prompt-position="bottomLeft:20,5" />
      <img src="<?php echo $view['assets']->getUrl('img/pass-1.png');?>" alt="<?php echo $view['translator']->trans('Password');?>"> </div>
    <span class="terms"><?php echo $view['translator']->trans('By registering, i agree to the');?> <a href="<?php echo $view['router']->generate('mytrip_user_payment');?>?terms=1"><?php echo $view['translator']->trans('Terms of service');?></a>, <a href="<?php echo $view['router']->generate('mytrip_user_payment');?>?terms=1"><?php echo $view['translator']->trans('Privacy policy');?></a>, <a href="<?php echo $view['router']->generate('mytrip_user_payment');?>"><?php echo $view['translator']->trans('Refund policy');?></a> <?php echo $view['translator']->trans('and');?> <a href="<?php echo $view['router']->generate('mytrip_user_payment');?>"><?php echo $view['translator']->trans('Payment policy');?></a> <?php echo $view['translator']->trans('of mytrip to cuba');?></span>
    <div class="login-b">
      <input type="submit" name="submit" id="signupsubmit" class="submit-review login-b" value="<?php echo $view['translator']->trans('Sign Up');?>" />
    </div>
  </form>
  <span class="already"><?php echo $view['translator']->trans('Already a member of mytrip to cuba?');?> <a id="already_login"><?php echo $view['translator']->trans('Log In');?></a></span> </div>
<?php }?>
<?php $msg=$view['session']->getFlash('success');?>
<div id="alert_box" class="alert_box signup">
  <h1><?php echo $view['translator']->trans('Message');?><img src="<?php echo $view['assets']->getUrl('img/sign-in.png');?>" > </h1>  
  <p><?php if(!empty($msg[0])){echo $msg[0];}?></p>   
  <span class="buttons"><a class="submit-review mclose"><?php echo $view['translator']->trans('OK');?></a></span> </div>
<a href="#alert_box" id="showDivId" style="display:none" rel="leanModal" name="alert_box"></a>
<?php
if(!empty($msg[0])){
?><script type="text/javascript">
$(function() {
    $("#showDivId").click();        
});
</script>
<?php }?>

<?php if($view['session']->getFlash('twit')){ ?>
<a href="#twitter_div"  rel="leanModal" id="twitter_link" class="go" style="display:none;" ><?php echo $view['translator']->trans('Twitter');?></a>
<div id="twitter_div" class="signup"> <a  class="modal_close"></a>
  <h1><?php echo $view['translator']->trans('Sign Up');?> <img src="<?php echo $view['assets']->getUrl('img/sign-in.png');?>" > </h1>
  <form name="twitterform" id="twitterform" method="post" enctype="multipart/form-data" action="<?php echo $view['router']->generate('mytrip_user_twitter_register');?>">   
    <div class="input">
      <input type="text" name="email" id="twitter_email" class="validate[required,custom[email]]"  maxlength="150" placeholder="<?php echo $view['translator']->trans('Email');?>" data-prompt-position="bottomLeft:20,5"   />
      <img src="<?php echo $view['assets']->getUrl('img/message-1.png');?>" alt="<?php echo $view['translator']->trans('Lastname');?>"> </div>
    <div class="login-b">
      <input type="submit" name="submit" id="twittersignupsubmit" class="submit-review login-b" value="<?php echo $view['translator']->trans('Sign Up');?>" />
    </div>
  </form>  
</div>
<script type="text/javascript">
$(function() {
	$("#twitter_link").leanModal({closeButton: ".modal_close"});
	$('#twitter_link').click();
});
</script>
 <?php }?>
<script type="text/javascript" src="<?php echo $view['assets']->getUrl('js/back-to-top.js') ?>"></script> 
<script type="text/javascript" src="<?php echo $view['assets']->getUrl('js/zebra_datepicker.js') ?>"></script> 
<script type="text/javascript" src="<?php echo $view['assets']->getUrl('js/home_cal.js') ?>"></script> 
<?php $jscriptmsg=array('card_msg'=>$view['translator']->trans('Please enter the valid credit card number'),
'rest_acc_msg'=>$view['translator']->trans('Please select any one of the account'),
'email_exit_msg'=>$view['translator']->trans('Email id already exists'),
'term_msg'=>$view['translator']->trans('Please accept the terms and conditions'),
'avail_msg'=>$view['translator']->trans('Please select check in and check out dates'),
'date_msg'=>$view['translator']->trans('Please select the dates'),
'payment_msg'=>$view['translator']->trans('Please check the payment type'),
'payment_mode_msg'=>$view['translator']->trans('Please check the payment mode'),
'rating_msg'=>$view['translator']->trans('Please select a rating'),
'rating_write_msg'=>$view['translator']->trans('Please enter the review'),
'cancel_booking_msg'=>$view['translator']->trans('Are you sure, You want to cancel this booking?'),
'del_acc_msg'=>$view['translator']->trans('Are you sure, You want to delete your account?'),
);?>
<script type="text/javascript">error_msg=<?php echo json_encode($jscriptmsg);?> </script>
<!--[if lt IE 7]>
    <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->
</body>
</html>