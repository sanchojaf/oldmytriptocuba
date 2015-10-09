<?php $view->extend('::user.html.php');
?>
<link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('css/jquery.confirm/jquery.confirm.css') ?>"/>
<script src="<?php echo $view['assets']->getUrl('js/confirm/jquery.confirm.js') ?>" type="text/javascript"></script>
<?php
$em = $this->container->get('doctrine')->getManager();
$bookingprice=$em->createQuery("SELECT p FROM MytripAdminBundle:BookingPrice p WHERE p.booking='".$booking[0][0]['bookingId']."'")->getArrayResult();
$bookingtransaction=$em->createQuery("SELECT p FROM MytripAdminBundle:BookingTransaction p WHERE p.booking='".$booking[0][0]['bookingId']."'")->getArrayResult();
$bookingcancel=$em->createQuery("SELECT p FROM MytripAdminBundle:BookingCancel p WHERE p.booking='".$booking[0][0]['bookingId']."'")->getArrayResult();
$hostal_content=$em->createQuery("SELECT p FROM MytripAdminBundle:HostalContent p WHERE p.hostal='".$booking[0]['hostal']."'")->getArrayResult();
$hostal_cancel=$em->createQuery("SELECT p FROM MytripAdminBundle:HostalCancelDetails p WHERE p.hostal='".$booking[0]['hostal']."' AND p.days <='".$betweendays."' ORDER BY p.days DESC")->setMaxResults('1')->getArrayResult();
if(!empty($hostal_cancel)){	
	$tamount=$bookingprice[0]['reservationTotalPrice']-$bookingprice[0]['reservationCharge'];
	$cancelamount=$tamount*($hostal_cancel[0]['percentage']/100);
}else{
	$cancelamount=0;
}

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
            <h1><?php echo $view['translator']->trans('Cancel Details');?></h1>
          </div>
            <div class="table-con">
            <form name="cancelbooking" action="" id="cancelbooking" method="post">
            <table width="100%" class="pre-book">
        <tbody>
        <tr>
            <td align="left"><?php echo $view['translator']->trans('Reference No');?></td>
            <td align="right"><?php echo 'venacuba-'.$booking[0][0]['bookingId']*1024;?></td>
          </tr>
           <tr class="color">
            <td align="left"><?php echo $view['translator']->trans('Hostal');?></td>
            <td align="right"><?php echo $hostal_content[0]['name'];?></td>
          </tr>
           <tr>
            <td align="left" valign="top"><?php echo $view['translator']->trans('Hostal Address');?></td>
            <td align="right"><?php echo $hostal_content[0]['address']."<br/>".$hostal_content[0]['city'].",".$hostal_content[0]['province']."<br/>".$hostal_content[0]['country'];?></td>
          </tr>
           <tr class="color">
            <td align="left"><?php echo $view['translator']->trans('Reference No');?></td>
            <td align="right"><?php echo 'venacuba-'.$booking[0][0]['bookingId']*1024;?></td>
          </tr>
         
          <tr>
            <td align="left"><?php echo $view['translator']->trans('Total Price');?></td>
            <td align="right"><?php echo number_format(($bookingprice[0]['totalPrice']+$bookingprice[0]['reservationCharge'])*$bookingprice[0]['conversionRate'],2);?> <?php echo $bookingprice[0]['conversionCurrency'];?></td>
          </tr>
           <tr class="color">
            <td align="left"><?php echo $view['translator']->trans('Paid Amount');?></td>
            <td align="right"><?php echo number_format($bookingprice[0]['reservationTotalPrice']*$bookingprice[0]['conversionRate'],2);?> <?php echo $bookingprice[0]['conversionCurrency'];?></td>
          </tr>
          <tr class="highlight">
            <td align="left"><?php echo $view['translator']->trans('Refund Amount');?></td>
            <td align="right"><?php echo number_format($cancelamount*$bookingprice[0]['conversionRate'],2);?> <?php echo $bookingprice[0]['conversionCurrency'];?></td>
          </tr>
          <tr>
            <td align="left" colspan="2"><a class="cancelbook"><span class="submit-review cancelbook"><?php echo $view['translator']->trans('Cancel Booking');?></span></a></td></tr>
        </tbody>
      </table>
      </form>
          </div>
       </div>
           
      </div>
      </div>
  </div>
