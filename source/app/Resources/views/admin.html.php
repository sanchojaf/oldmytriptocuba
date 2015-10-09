<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>My trip Cuba - Admin panel</title>
<meta name="description" content="My trip Cuba - Admin panel" />
<meta name="keywords" content="My trip Cuba - Admin panel" />
<meta name="author" content="My trip Cuba" />
<meta name="copyright" content="My trip Cuba" />
<link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('css/style.css') ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('css/reset.css') ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('css/jqueryslidemenu.css') ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('css/uniform.default.css') ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('css/jQuery.validation/validationEngine.jquery.css') ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('css/jquery.confirm/jquery.confirm.css') ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('css/ui/jquery-ui-timepicker-addon.css') ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('css/smoothness/jquery-ui-1.8.16.custom.css') ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('css/jquery.fancybox-1.3.4.css') ?>"/>

<script src="<?php echo $view['assets']->getUrl('js/jquery-1.8.3.js') ?>" type="text/javascript"></script>
<script src="<?php echo $view['assets']->getUrl('js/jqueryslidemenu.js') ?>" type="text/javascript"></script>
<script src="<?php echo $view['assets']->getUrl('js/jquery.cookie.js') ?>" type="text/javascript"></script>
<script src="<?php echo $view['assets']->getUrl('js/styleswitch.js') ?>" type="text/javascript"></script>
<script src="<?php echo $view['assets']->getUrl('js/ui/jquery-ui-1.8.16.custom.min.js') ?>" type="text/javascript"></script>
<script src="<?php echo $view['assets']->getUrl('js/uniform/jquery.uniform.js') ?>" type="text/javascript"></script>
<script src="<?php echo $view['assets']->getUrl('js/jquery.fancybox-1.3.4.pack.js') ?>" type="text/javascript"></script>
<script src="<?php echo $view['assets']->getUrl('js/jQuery.validation/jquery.validationEngine.js') ?>" type="text/javascript"></script>
<script src="<?php echo $view['assets']->getUrl('js/jQuery.validation/languages/jquery.validationEngine-en.js') ?>" type="text/javascript"></script>
<script src="<?php echo $view['assets']->getUrl('js/confirm/jquery.confirm.js') ?>" type="text/javascript"></script>
<script src="<?php echo $view['assets']->getUrl('js/ui/jquery-ui-timepicker-addon.js') ?>" type="text/javascript"></script>
<script src="<?php echo $view['assets']->getUrl('js/admin-common.js') ?>" type="text/javascript"></script>
</head>
<body>
<?php //echo $this->container->get('mytrip_admin.helper.date')->format('16-07-2014 08:47:52');?>
<div class="helpfade"></div>
<div class="helptips"><div class="loader_block"><div class="loader_block_inner"></div><div class="loader_text">Please wait...</div></div></div>
<div class="dismsg" id="msginfo"><?php 
	foreach ($view['session']->getFlash('error') as $message){ 
		echo $message.'<div class="close"> Click to close.</div>';
	} 
?></div>
	<div id="mainContainer"> 	
		<div id="header" class="clearfix">
			<div id="topHeader" >			
				<div id="logo"><a href="<?php echo $view['router']->generate('mytrip_admin_dashboard');?>"><img src="<?php echo $view['assets']->getUrl('img/admin-logo.png');?>"/></a></div>				
				<div id="topLinks">
                <a href="<?php echo $view['router']->generate('mytrip_admin_profile');?>" class="settings">My Account</a>
                <a href="<?php echo $view['router']->generate('mytrip_admin_logout');?>" class="logout">Logout</a>
                </div>								
				<div id="welcomeUser" >Welcome, <?php echo $view['session']->get('username'); ?></div>
			</div>			
			<div id="myslidemenu" class="jqueryslidemenu">
				<ul>
                <li <?php echo (in_array($this->container->get('request')->get('_route'),array('mytrip_admin_dashboard'))?'class="active"':'');?>><a href="<?php echo $view['router']->generate('mytrip_admin_dashboard');?>">Dashboard</a></li>
                <li <?php echo (in_array($this->container->get('request')->get('_route'),array('mytrip_admin_profile','mytrip_admin_changepassword','mytrip_admin_api','mytrip_admin_editapi','mytrip_admin_settings','mytrip_admin_cancel_settings','mytrip_admin_add_cancel_setting','mytrip_admin_edit_cancel_settings','mytrip_admin_payment_settings'))?'class="active"':'');?>><a>Settings</a>
                    <ul>  
                        <li id="changeinfo"><a href="<?php echo $view['router']->generate('mytrip_admin_profile');?>">My Account</a></li>
                        <li id="changepass"><a href="<?php echo $view['router']->generate('mytrip_admin_changepassword');?>">Change Password</a></li>
                        <li id="bookingsetting"><a href="<?php echo $view['router']->generate('mytrip_admin_settings');?>">Booking Settings</a></li>
                        <li id="canceldetails"><a href="<?php echo $view['router']->generate('mytrip_admin_cancel_settings');?>">Booking&nbsp;Cancel&nbsp;Settings</a></li>
                        <li id="paymentsetting"><a href="<?php echo $view['router']->generate('mytrip_admin_payment_settings');?>">Payment Settings</a></li>
                        <!--<li id="api"><a href="<?php //echo $view['router']->generate('mytrip_admin_api');?>">API</a></li>-->
                    </ul>
                </li> 
                 <?php //if($view['session']->get('adminid')==1){?>
                <li <?php echo (in_array($this->container->get('request')->get('_route'),array('mytrip_admin_adminusers','mytrip_admin_addadmin','mytrip_admin_editadmin','mytrip_admin_adminpassword'))?'class="active"':'');?>><a>Admin</a>
                    <ul>  
                        <li id="adminusers"><a href="<?php echo $view['router']->generate('mytrip_admin_adminusers');?>">Admin Users</a></li>
                    </ul>
                </li> 
                 <?php //}?>                
                <li <?php echo (in_array($this->container->get('request')->get('_route'),array('mytrip_admin_staticpage','mytrip_admin_addstaticpage','mytrip_admin_editstaticpage','mytrip_admin_features','mytrip_admin_addfeature','mytrip_admin_editfeature','mytrip_admin_sociallinks','mytrip_admin_addsociallink','mytrip_admin_editsociallink','mytrip_admin_emailcontent','mytrip_admin_addemailcontent','mytrip_admin_editemailcontent','mytrip_admin_contact','mytrip_admin_viewcontact'))?'class="active"':'');?>><a>CMS</a>
                    <ul>  
                        <li id="contentpage"><a href="<?php echo $view['router']->generate('mytrip_admin_staticpage');?>">Content Page</a></li> 
                        <li id="emailcontent"><a href="<?php echo $view['router']->generate('mytrip_admin_emailcontent');?>">Email Content</a></li> 
                        <li id="enquires"><a href="<?php echo $view['router']->generate('mytrip_admin_contact');?>">Manage Enquiries</a></li> 
                        <li id="features"><a href="<?php echo $view['router']->generate('mytrip_admin_features');?>">Features</a></li> 
                        <li id="sociallinks"><a href="<?php echo $view['router']->generate('mytrip_admin_sociallinks');?>">Social Links</a></li>                        
                    </ul>
                </li>
                 <li <?php echo (in_array($this->container->get('request')->get('_route'),array('mytrip_admin_destination','mytrip_admin_adddestination','mytrip_admin_editdestination','mytrip_admin_banner','mytrip_admin_hostal','mytrip_admin_addhostal','mytrip_admin_edithostal','mytrip_admin_story','mytrip_admin_addstory','mytrip_admin_editstory','mytrip_admin_viewcomments','mytrip_admin_comments','mytrip_admin_hostal_cancel','mytrip_admin_hostal_add_cancel_setting','mytrip_admin_hostal_edit_cancel_settings'))?'class="active"':'');?>><a>Destination Guides</a>
                    <ul>  
                        <li id="distinations"><a href="<?php echo $view['router']->generate('mytrip_admin_destination');?>">Destinations</a></li>                       
                        <li id="hostals"><a href="<?php echo $view['router']->generate('mytrip_admin_hostal');?>">Hostals</a></li> 
                        <li id="stories"><a href="<?php echo $view['router']->generate('mytrip_admin_story');?>">Stories</a></li> 
                        <li id="comments"><a href="<?php echo $view['router']->generate('mytrip_admin_comments');?>">Reviews</a></li>                        
                    </ul>
                </li> 
                <li <?php echo (in_array($this->container->get('request')->get('_route'),array('mytrip_admin_users','mytrip_admin_viewusers'))?'class="active"':'');?>><a>Users</a>
                    <ul>  
                        <li id="users"><a href="<?php echo $view['router']->generate('mytrip_admin_users');?>">Users</a></li>                                            
                    </ul>
                </li>    
                <li <?php echo (in_array($this->container->get('request')->get('_route'),array('mytrip_admin_confirm_booking','mytrip_admin_cancel_booking','mytrip_admin_viewbooking'))?'class="active"':'');?>><a>Booking</a>
                    <ul>                  
                        <li id="hostals"><a href="<?php echo $view['router']->generate('mytrip_admin_confirm_booking');?>">Confirm Booking</a></li>
                        <li id="cancelbooking"><a href="<?php echo $view['router']->generate('mytrip_admin_cancel_booking');?>">Cancel Booking</a></li>                                               
                    </ul>
                </li>            				
			</ul>
			</div>
		</div>
		   <?php $view['slots']->output('_content'); ?>
		<div id="footer" class="clearfix">
        
			<div class="copyright">Copyrights &copy; All rights are reserved</div>
			<div class="designInfo">Designed & Developed by <a href="http://www.lynchpintechnologies.com/" target="_blank">Lynchpin Technologies</a></div>
		</div>
	</div>
</body>

</html>