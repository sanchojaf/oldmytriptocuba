<?php $view->extend('::user.html.php');
$em = $this->container->get('doctrine')->getManager();
$usersession=$view['session']->get('user');	
$pimg=$view['assets']->getUrl('img/bigprofileimage.jpg');
if($user[0]['image']!=''){
	$pimg=$view['assets']->getUrl('img/user/'.$user[0]['image']);
}else{
	$proimg=$em->createQuery("SELECT u, (RAND()) AS HIDDEN r FROM MytripAdminBundle:UserSocialLink u WHERE u.user='".$usersession['userId']."' AND u.image !='' ORDER BY r ")->setMaxResults(1)->getArrayResult();
	if(!empty($proimg)){
		$pimg=$proimg[0]['image'];
	}
}
?>
<link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('css/jquery.confirm/jquery.confirm.css') ?>"/>
<script src="<?php echo $view['assets']->getUrl('js/confirm/jquery.confirm.js') ?>" type="text/javascript"></script>
<div class="acc-container">
  <div class="acc-bl container">
    <div class="acc-left">
        <div class="profile-pic"><img src="<?php echo $pimg; ?>" width="222" alt="<?php echo $user[0]['firstname'];?>"></div>
        <h2><?php echo $user[0]['firstname'];?></h2>
        <ul class="profile">
            <li><a href="<?php echo $view['router']->generate('mytrip_user_profile');?>"><?php echo $view['translator']->trans('Profile');?></a></li>
            <li class="active"><a href="<?php echo $view['router']->generate('mytrip_user_account');?>"><?php echo $view['translator']->trans('Account');?></a></li>
            <li><a href="<?php echo $view['router']->generate('mytrip_user_bookinghistory');?>"><?php echo $view['translator']->trans('Booking');?></a></li>
            <li><a href="<?php echo $view['router']->generate('mytrip_user_logout');?>"><?php echo $view['translator']->trans('Logout');?></a></li>
          </ul>
      </div>
    <div class="acc-right-bl">
      <div class=" acc-right acc-left">
        <div class="acc-right-head">
          <h1><?php echo $view['translator']->trans('Reset Password');?></h1>
        </div>
        <br>
        <div class="table-con">
          <form class="contact acc-form" id="changepasswordform" method="post" enctype="multipart/form-data" name="changepassword">
            <div class="account-from">
              <label><?php echo $view['translator']->trans('Current Password');?></label>
              <input type="password" name="oldpassword" id="oldpassword" class="validate[required]" data-prompt-position="bottomLeft:20,5" >
            </div>
            <div class="account-from">
              <label><?php echo $view['translator']->trans('New Password');?></label>
              <input type="password" id="password" name="password" class="validate[required,minSize[6]]" data-prompt-position="bottomLeft:20,5" >
            </div>
            <div class="account-from">
              <label><?php echo $view['translator']->trans('Confirm Password');?></label>
              <input type="password" name="cpassword" id="cpassword" class="validate[required,equals[password]]" data-prompt-position="bottomLeft:20,5" >
            </div>
            <span class="border-b"></span>
            <div class="account-from">
              <input type="submit" name="passsubmit" id="passsubmit" value="<?php echo $view['translator']->trans('Reset Password');?>" class="submit-review">
            </div>
          </form>
        </div>
      </div>
      <div class=" acc-right acc-left">
        <div class="acc-right-head">
          <h1><?php echo $view['translator']->trans('Reset Account');?></h1>
        </div>
        <div class="table-con">
        <form class="contact acc-form" id="resetform" method="post" enctype="multipart/form-data" name="restform">
        <?php	
			
		$facebook=$em->createQuery("SELECT u FROM MytripAdminBundle:UserSocialLink u WHERE u.user='".$usersession['userId']."' AND u.socialLink='Facebook'")->getArrayResult();		
		$twitter=$em->createQuery("SELECT u FROM MytripAdminBundle:UserSocialLink u WHERE u.user='".$usersession['userId']."' AND u.socialLink='Twitter'")->getArrayResult();	
		$google=$em->createQuery("SELECT u FROM MytripAdminBundle:UserSocialLink u WHERE u.user='".$usersession['userId']."' AND u.socialLink='Google'")->getArrayResult();				
		?>
          <table align="left" class="soc">
            <tr>
              <td>
              <?php
			  if(!empty($facebook)){
			  ?>
              <img src="<?php echo $view['assets']->getUrl('img/facebook.png') ?>">
                <input type="checkbox" name="acc[]" id="checkbox67" class="css-checkbox lrg" value="Facebook"  />
                <label for="checkbox67" name="checkbox67_lbl" class="css-label lrg web-two-style"></label>
               <?php }else{
				   ?>
                   <a href="<?php echo $view['router']->generate('mytrip_user_facebook');?>"> <img src="<?php echo $view['assets']->getUrl('img/facebook.png') ?>"></a>
                   <?php
			   }
			   ?>
               </td>
            </tr>
            <tr>
              <td>
              <?php
			  if(!empty($twitter)){
			  ?>
              <img src="<?php echo $view['assets']->getUrl('img/twitter.png') ?>" >
                <input type="checkbox" name="acc[]" id="checkbox68" class="css-checkbox lrg" value="Twitter" />
                <label for="checkbox68" name="checkbox68_lbl" class="css-label lrg web-two-style"></label>
                 <?php }else{
				   ?>
                   <a href="<?php echo $view['router']->generate('mytrip_user_twitter');?>"> <img src="<?php echo $view['assets']->getUrl('img/twitter.png') ?>"></a>
                   <?php
			   }
			   ?></td>
            </tr>
            <tr>
              <td>
              <?php
			  if(!empty($google)){
			  ?>
              <img src="<?php echo $view['assets']->getUrl('img/goolge+.png') ?>">
                <input type="checkbox" name="acc[]" id="checkbox69" class="css-checkbox lrg" value="Google" />
                <label for="checkbox69" name="checkbox69_lbl" class="css-label lrg web-two-style"></label>
				<?php }else{
				   ?>
                   <a href="<?php echo $view['router']->generate('mytrip_user_google');?>"> <img src="<?php echo $view['assets']->getUrl('img/goolge+.png') ?>"></a>
                   <?php
			   }
			   ?></td>
            </tr>
          </table>
          <input type="hidden" name="resetacc" value="1"/>
          </form>
          <div class="web-cam">
            <p><?php echo $view['translator']->trans('Connect with your social network account to make eveything easier, more dynamic, and always keep updated');?></p>
          </div>
        </div>
        <span class="border-b"></span>
        <input type="submit" name="resetaccount" id="resetaccount" value="<?php echo $view['translator']->trans('Reset Account');?>" class="submit-review">
      </div>
      <div class=" acc-right acc-left">
        <div class="acc-right-head">
          <h1><?php echo $view['translator']->trans('Cancel Account');?></h1>
        </div>
        <p><?php echo $view['translator']->trans('Warning! one you cancel your account all of your information will be permanently lost');?>.
        <form class="contact acc-form" id="canform" method="post" enctype="multipart/form-data" name="canform">
        <input type="hidden" name="cancelacc" id="cancelacc" value="1"/>
          <input type="button" name="cancelaccount" id="cancelaccount" value="<?php echo $view['translator']->trans('Cancel my Account');?>" class="submit-review">
        </form>
        </p>
        <span class="border-b"></span> </div>
    </div>
  </div>
</div>
