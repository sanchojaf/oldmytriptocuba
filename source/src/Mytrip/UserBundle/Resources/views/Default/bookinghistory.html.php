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
<div class="acc-container">
    <div class="acc-bl container">
        <div class="acc-left">
        <div class="profile-pic"><img src="<?php echo $pimg; ?>" width="222" alt="<?php echo $user[0]['firstname'];?>"></div>
        <h2><?php echo $user[0]['firstname'];?></h2>
        <ul class="profile">
            <li><a href="<?php echo $view['router']->generate('mytrip_user_profile');?>"><?php echo $view['translator']->trans('Profile');?></a></li>
            <li><a href="<?php echo $view['router']->generate('mytrip_user_account');?>"><?php echo $view['translator']->trans('Account');?></a></li>
            <li class="active"><a href="<?php echo $view['router']->generate('mytrip_user_bookinghistory');?>"><?php echo $view['translator']->trans('Booking');?></a></li>
            <li><a href="<?php echo $view['router']->generate('mytrip_user_logout');?>"><?php echo $view['translator']->trans('Logout');?></a></li>
          </ul>
      </div>
        <div class="acc-right-bl">
        <div class=" acc-right acc-left">
            <div class="acc-right-head">
            <h1><?php echo $view['translator']->trans('Pre Booking');?></h1>
          </div>
            <div class="table-con">
            <table width="100%" class="pre-book">
            <?php
			if(!empty($prebooking)){
				$i=1;
				foreach($prebooking as $prebookings){
				?>
             <tr <?php echo $i%2==0?'':'class="color"';?>>
                <td align="left"><?php echo 'venacuba-'.$prebookings['bookingId']*1024;?></td>
                <td align="right"><a href="<?php echo $view['router']->generate('mytrip_user_bookingdetails',array('bookingid'=>($prebookings['bookingId']*1024)));?>">(<?php echo $view['translator']->trans('Click To View');?>)</a></td>
              </tr>
             <?php
				}
			}else{
			?>
              <tr class="color">
                <td colspan="2"><?php echo $view['translator']->trans('No records found');?></td>
              </tr>
             <?php }?>            
              </table>
          </div>
          </div>
        <div class=" acc-right acc-left">
            <div class="acc-right-head">
            <h1><?php echo $view['translator']->trans('To Pay');?></h1>
          </div>
            <div class="table-con">
            <table width="100%" class="pre-book">
            <?php
			if(!empty($topay)){
				foreach($topay as $topays){
				?>
                <tr class="color">
                <td align="left"><?php echo 'venacuba-'.$topays['bookingId']*1024;?></td>
                <td align=""><a href="<?php echo $view['router']->generate('mytrip_user_bookingdetails',array('bookingid'=>($topays['bookingId']*1024)));?>">(<?php echo $view['translator']->trans('Click To View');?>)</a></td>
                <td align=""><a href="<?php echo $view['router']->generate('mytrip_user_makepayment',array('bookingId'=>$topays['bookingId']*1024));?>"><span class="submit-review"><?php echo $view['translator']->trans('Pay Now'); ?></span></a></td>
              </tr>
              <?php }
			}else{
				?>
                <tr class="color">
                <td colspan="3"><?php echo $view['translator']->trans('No records found');?></td>
              </tr>
             <?php }?>
              </table>
            <span class="border-b"></span>
            <p>* <?php echo $view['translator']->trans('If a pre-booking is not paid within 48 hours, this will be automatically cancelled and will forfeit its validity. So if you are still interested shall make a new reservation request.');?></p>
          </div>
          </div>
        <div class=" acc-right acc-left">
            <div class="acc-right-head">
            <h1><?php echo $view['translator']->trans('Confirmed Reservations');?></h1>
          </div>
            <div class="table-con">
            <table width="100%" class="pre-book">
              <?php
			if(!empty($confirmation)){
				$i=1;
				foreach($confirmation as $confirmations){
				?>
             <tr <?php echo $i%2==0?'':'class="color"';?>>
                <td align="left"><?php echo 'venacuba-'.$confirmations['bookingId']*1024;?></td>
                <td align="right"><a href="<?php echo $view['router']->generate('mytrip_user_bookingdetails',array('bookingid'=>($confirmations['bookingId']*1024)));?>">(<?php echo $view['translator']->trans('Click To View');?>)</a></td>
              </tr>
             <?php
				}
			}else{
			?>
              <tr class="color">
                <td colspan="2"><?php echo $view['translator']->trans('No records found');?></td>
              </tr>
             <?php }?>
              </table>
          </div>
          </div>
          <div class=" acc-right acc-left">
            <div class="acc-right-head">
            <h1><?php echo $view['translator']->trans('Cancelled Reservations');?></h1>
          </div>
            <div class="table-con">
            <table width="100%" class="pre-book">
              <?php
			if(!empty($cancelticket)){
				$i=1;
				foreach($cancelticket as $canceltickets){
				?>
             <tr <?php echo $i%2==0?'':'class="color"';?>>
                <td align="left"><?php echo 'venacuba-'.$canceltickets['bookingId']*1024;?></td>
                <td align="right"><a href="<?php echo $view['router']->generate('mytrip_user_bookingdetails',array('bookingid'=>($canceltickets['bookingId']*1024)));?>">(<?php echo $view['translator']->trans('Click To View');?>)</a></td>
              </tr>
             <?php
				}
			}else{
			?>
              <tr class="color">
                <td colspan="2"><?php echo $view['translator']->trans('No records found');?></td>
              </tr>
             <?php }?>
              </table>
          </div>
          </div>
      </div>
      </div>
  </div>
