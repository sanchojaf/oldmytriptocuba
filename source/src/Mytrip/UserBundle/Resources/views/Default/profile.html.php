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
<script src="<?php echo $view['assets']->getUrl('js/home_upload.js'); ?>" type="text/javascript"></script>
<div class="acc-container">
    <div class="acc-bl container">
        <div class="acc-left">
        <div class="profile-pic"><img src="<?php echo $pimg; ?>" width="222" alt="<?php echo $user[0]['firstname'];?>"></div>
        <h2><?php echo $user[0]['firstname'];?></h2>
        <ul class="profile">
            <li class="active"><a href="<?php echo $view['router']->generate('mytrip_user_profile');?>"><?php echo $view['translator']->trans('Profile');?></a></li>
            <li><a href="<?php echo $view['router']->generate('mytrip_user_account');?>"><?php echo $view['translator']->trans('Account');?></a></li>
            <li><a href="<?php echo $view['router']->generate('mytrip_user_bookinghistory');?>"><?php echo $view['translator']->trans('Booking');?></a></li>
            <li><a href="<?php echo $view['router']->generate('mytrip_user_logout');?>"><?php echo $view['translator']->trans('Logout');?></a></li>
          </ul>
      </div>
        <div class="acc-right-bl ">
        <div class=" acc-right acc-left">
            <div class="acc-right-head">
            <h1><?php echo $view['translator']->trans('Profile Details');?></h1>
          </div>
            <form class="contact acc-form" id="profileform" name="profileform" action="" method="post">
            <div class="account-from">
                <label><?php echo $view['translator']->trans('Name');?></label>
                <div class="input-class">
                <input type="text" name="firstname" id="firstname" class="validate[required,custom[onlyLetter]]" value="<?php echo $user[0]['firstname'];?>" data-prompt-position="bottomLeft:20,5" >
              </div>
              </div>
            <div class="account-from">
                <label><?php echo $view['translator']->trans('Last Name');?></label>
                <div class="input-class">
                <input type="text" name="lastname" id="lastname" class="validate[required,custom[onlyLetter]]" value="<?php echo $user[0]['lastname'];?>" data-prompt-position="bottomLeft:20,5" >
              </div>
              </div>
            <div class="account-from">
                <label><?php echo $view['translator']->trans('Gender');?></label>
                <div class="input-class">
                <select name="gender" id="gender"  data-prompt-position="bottomLeft:20,5" >
               	    <option value=""><?php echo $view['translator']->trans('Select');?></option>
                    <option value="Male" <?php echo $user[0]['gender']=='Male'?'selected="selected"':'';?>><?php echo $view['translator']->trans('Male');?></option>
                    <option value="Female" <?php echo $user[0]['gender']=='Female'?'selected="selected"':'';?>><?php echo $view['translator']->trans('Female');?></option>                   
                  </select>
              </div>
              </div>
            <div class="account-from">
                <label><?php echo $view['translator']->trans('Birthday');?></label>
                <?php 
				if($user[0]['dob']!=''){
					list($uy,$um,$ud)=explode("-",$user[0]['dob']);
				}else{
					$um=$ud=$uy=0;
				}
				?>
                <div class="input-class">
                <select name="month" id="month"  data-prompt-position="bottomLeft:20,5" >
                    <option value="">Month</option>
                    <?php for($m=1;$m<=12;$m++){ ?>
                    <option value="<?php echo sprintf('%02d',$m);?>" <?php echo $um==$m?'selected="selected"':'';?>><?php echo date('M',mktime(0,0,0,$m+1,0,0));?></option>
					<?php }?>                    
                  </select>
                <select name="day" id="day"  data-prompt-position="bottomLeft:20,5" >
                	<option value="">Day</option>
                <?php for($d=1;$d<=31;$d++){ ?>
                    <option value="<?php echo sprintf('%02d',$d);?>" <?php echo $ud==$d?'selected="selected"':'';?>><?php echo sprintf('%02d',$d);?></option>
                  <?php }?>                   
                  </select>                 
                <select name="year" id="year"  data-prompt-position="bottomLeft:20,5" >
                    <option value="">Year</option>
                <?php for($y=(date('Y')-15);$y>=(date('Y')-80);$y--){ ?>
                    <option value="<?php echo $y;?>" <?php echo $uy==$y?'selected="selected"':'';?>><?php echo $y;?></option>
                  <?php }?>
                  </select>
              </div>
              </div>
            <div class="account-from">
                <label><?php echo $view['translator']->trans('Email');?></label>
                <div class="input-class">
                <p><?php echo $user[0]['email'];?></p>
              </div>
              </div>
                <div class="account-from">
                <label><?php echo $view['translator']->trans('Address');?></label>
                <div class="input-class">
                <input type="text" name="address" id="address"  value="<?php echo $user[0]['address'];?>" data-prompt-position="bottomLeft:20,5"  >
                </div>
                </div>
                <div class="account-from">
                <label>&nbsp;</label>
                <div class="input-class">
                <input type="text" name="address2" id="address2" value="<?php echo $user[0]['address2'];?>" data-prompt-position="bottomLeft:20,5" >
                </div>
                </div>
                <div class="account-from">
                <label><?php echo $view['translator']->trans('Nationality');?></label>
                <div class="input-class">
                <select class="longselect " name="country" id="country" data-prompt-position="bottomLeft:20,5" >
                <option value=""><?php echo $view['translator']->trans('Select country');?></option>
                <?php
                foreach($country as $country){
                    ?>
                    <option value="<?php echo $country['cid'];?>" <?php if($user[0]['country']!=''){ echo ($user[0]['country']==$country['cid']?'selected="selected"':'');} ?>><?php echo $country['country'];?></option>
                    <?php
                }
                ?>                                     
                </select>
                </div>
                </div>   
                <div class="account-from">
                <label><?php echo $view['translator']->trans('Province');?></label>
                <div class="input-class">
                <select class="longselect " name="province" id="province" data-prompt-position="bottomLeft:20,5" >
                <option value=""><?php echo $view['translator']->trans('Select province');?></option>
                <?php if($user[0]['country']!=''){
                $state=$em->createQuery("SELECT s FROM MytripAdminBundle:States s  where s.cid='".$user[0]['country']."'" )->getArrayResult();	
                foreach($state as $states){
                    echo '<option value="'.$states['sid'].'" '.($user[0]['province']==$states['sid']?'selected="selected"':'').'>'.$states['state'].'</option>';
                }
                }
                ?>
                </select>
                </div>
                </div>
                <div class="account-from">
                <label><?php echo $view['translator']->trans('City');?></label>
                <div class="input-class">
                <input type="text" name="city" id="city" class="" value="<?php echo $user[0]['city'];?>" data-prompt-position="bottomLeft:20,5" >
                </div>
                </div>    
                <div class="account-from">
                <label><?php echo $view['translator']->trans('Zip');?></label>
                <div class="input-class">
                <input type="text" name="zip" id="zip" class="" value="<?php echo $user[0]['zip'];?>" data-prompt-position="bottomLeft:20,5" >
                </div>
                </div>    
            <div class="account-from">
                <label><?php echo $view['translator']->trans('Contact Number');?></label>
                <div class="input-class">
                <input type="text" class="" name="phone" id="phone" value="<?php echo $user[0]['phone'];?>" data-prompt-position="bottomLeft:20,5" >                
              </div>
              </div>
              <div class="account-from">
                <label><?php echo $view['translator']->trans('Mobile');?></label>
                <div class="input-class">
                <input type="text" class="validate[required,custom[phone]]" name="mobile" id="mobile" value="<?php echo $user[0]['mobile'];?>" data-prompt-position="bottomLeft:20,5" >                
              </div>
              </div>
            <span class="border-b"></span>
            <div class="account-from">
                <input type="submit" name="profilesubmit" id="profilesubmit" value="<?php echo $view['translator']->trans('Save Changes');?>" class="submit-review">
              </div>
          </form>
          </div>
          <form class="contact acc-form" id="imageform" name="imageform" action="" method="post" enctype="multipart/form-data">
        <div class=" acc-right acc-left">
            <div class="acc-right-head">
            <h1><?php echo $view['translator']->trans('Profile Picture');?></h1>
          </div>
            <div class="table-con">
            <div class="web-profile-pic"> <img  src="<?php echo $user[0]['image']!=''?$view['assets']->getUrl('img/user/'.$user[0]['image']):$view['assets']->getUrl('img/bigprofileimage.jpg'); ?>" width="222" alt="<?php echo $user[0]['firstname'];?>"></div>
            <div class="web-cam">
                <div class="web-cam-li"><img src="<?php echo $view['assets']->getUrl('img/cam.png') ?>" width="42" height="41" alt="camerea">
                <p><?php echo $view['translator']->trans('Take a picture with your webcam');?></p>
              </div>
                <div class="web-cam-li">
                    <span class="file-wrapper">
                    <input type="file" name="image" id="profileimage" class="validate[required,custom[image]]" data-prompt-position="bottomLeft:20,5" />
                    <span class="button"><img src="<?php echo $view['assets']->getUrl('img/folder.png') ?>" width="42" height="41" alt="camerea">
                <p><?php echo $view['translator']->trans('Load a picture from your computer');?></p></span>                
              </div>
               <p><?php echo '('.$view['translator']->trans('Please upload 220 x 220 size JPG, JPEG, PNG or GIF format image.').')';?></p>
              </div>
          </div>
           <!-- <span class="border-b"></span>
            <input type="submit" name="imagesubmit" id="imagesubmit" value="<?php //echo $view['translator']->trans('Save Changes');?>" class="submit-review">-->
          </div>
          </form>
      </div>
      </div>
  </div>
<script type="text/javascript">
$('#profileimage').change(function(){
	$('#imageform').submit();
});
</script>