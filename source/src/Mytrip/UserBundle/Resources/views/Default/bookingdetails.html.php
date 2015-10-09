<?php $view->extend('::user.html.php');
$em = $this->container->get('doctrine')->getManager();
$bookingprice=$em->createQuery("SELECT p FROM MytripAdminBundle:BookingPrice p WHERE p.booking='".$booking[0][0]['bookingId']."'")->getArrayResult();
$bookingtransaction=$em->createQuery("SELECT p FROM MytripAdminBundle:BookingTransaction p WHERE p.booking='".$booking[0][0]['bookingId']."'")->getArrayResult();
$hostal_content=$em->createQuery("SELECT p FROM MytripAdminBundle:HostalContent p WHERE p.hostal='".$booking[0]['hostal']."'")->getArrayResult();
$hostal_rooms=$em->find('MytripAdminBundle:Booking', $booking[0][0]['bookingId'])->getRooms();

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
            <h1><?php echo $view['translator']->trans('Booking Details');?></h1>
          </div>
            <div class="table-con">
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
            <td align="left"><?php echo $view['translator']->trans('Check In');?></td>
            <td align="right"><?php echo date('l dS M Y',strtotime($booking[0][0]['fromDate']->format('Y-m-d H:i:s')));?></td>
          </tr>
          <tr class="color">
            <td align="left"><?php echo $view['translator']->trans('Check Out');?></td>
            <td align="right"><?php echo date('l dS M Y',strtotime($booking[0][0]['toDate']->format('Y-m-d H:i:s')));?></td>
          </tr>
          <tr class="">
            <td align="left"><?php echo $view['translator']->trans('No. of Days');?></td>
            <td align="right"><?php echo $booking[0][0]['noOfDays'];?> <?php echo $view['translator']->trans('nights');?></td>
          </tr>
          <tr class="color">
            <td align="left"><?php echo $view['translator']->trans('No. of Rooms');?></td>
            <td align="right"><?php echo $booking[0][0]['noOfRooms'];?> <?php echo $view['translator']->trans('rooms');?></td>
          </tr>
          <?php foreach ($hostal_rooms as $room): ?>
           <tr class="">
            <td align="left"><?php echo $view['translator']->trans($room->getRoomtype());?> (<?php echo $room->getAdults();?> <?php echo $view['translator']->trans('Adults');?> / <?php echo $room->getChild();?> <?php echo $view['translator']->trans('Child');?>)</td>
            <td align="right"><?php echo number_format($room->getPrice()*$bookingprice[0]['conversionRate'],2);?> <?php echo $bookingprice[0]['conversionCurrency'];?></td>
          </tr>
          <?php endforeach; ?>
          <tr class="color">
            <td align="left"><?php echo $view['translator']->trans('Accommodation total cost');?></td>
            <td align="right"><?php echo number_format($bookingprice[0]['totalPrice']*$bookingprice[0]['conversionRate'],2);?> <?php echo $bookingprice[0]['conversionCurrency'];?></td>
          </tr>
           <tr class="">
            <td align="left"><?php echo $view['translator']->trans('Reservation fee');?></td>
            <td align="right"><?php echo number_format($bookingprice[0]['reservationCharge']*$bookingprice[0]['conversionRate'],2);?> <?php echo $bookingprice[0]['conversionCurrency'];?></td>
          </tr>
          <tr class="highlight">
            <td align="left"><?php echo $view['translator']->trans('Total Price');?></td>
            <td align="right"><?php echo number_format(($bookingprice[0]['totalPrice']+$bookingprice[0]['reservationCharge'])*$bookingprice[0]['conversionRate'],2);?> <?php echo $bookingprice[0]['conversionCurrency'];?></td>
          </tr>
           <?php if($booking[0][0]['status']=="Pending"){?>
             <tr>
            <td align="left" colspan="2"><a href="<?php echo $view['router']->generate('mytrip_user_makepayment',array('bookingId'=>$booking[0][0]['bookingId']*1024));?>"><span class="submit-review"><?php echo $view['translator']->trans('Pay Now');?></span></a></td></tr>
           <?php }?>
        </tbody>
      </table>
          </div>
       </div>
         
       <?php if($booking[0][0]['status']=="Confirmed" || $booking[0][0]['status']=="Cancelled"){?> 
        <div class=" acc-right acc-left">
            <div class="acc-right-head">
            <h1><?php echo $view['translator']->trans('Payments');?></h1>
          </div>
            <div class="table-con">
            <table width="100%" class="pre-book">
        <tbody>
         <tr class="color">
            <td align="left"><?php echo $view['translator']->trans('Total Price');?></td>
            <td align="right"><?php echo number_format(($bookingprice[0]['totalPrice']+$bookingprice[0]['reservationCharge'])*$bookingprice[0]['conversionRate'],2);?> <?php echo $bookingprice[0]['conversionCurrency'];?></td>
          </tr>
          <?php
		  if($bookingprice[0]['paymenttype']=="partial"){
		  ?>
        <tr>
            <td align="left"><?php echo $view['translator']->trans('Paid Amount');?></td>
            <td align="right"><?php echo number_format($bookingprice[0]['reservationTotalPrice']*$bookingprice[0]['conversionRate'],2);?> <?php echo $bookingprice[0]['conversionCurrency'];?></td>
          </tr>
           <tr class="highlight">
            <td align="left"><?php echo $view['translator']->trans('Balance Amount');?></td>
            <td align="right"><?php echo number_format((($bookingprice[0]['totalPrice']+$bookingprice[0]['reservationCharge'])-$bookingprice[0]['reservationTotalPrice'])*$bookingprice[0]['conversionRate'],2);?> <?php echo $bookingprice[0]['conversionCurrency'];?></td>
          </tr>
        <?php }else{?>         
          <tr class="highlight">
            <td align="left"><?php echo $view['translator']->trans('Paid Amount');?></td>
            <td align="right"><?php echo number_format(($bookingprice[0]['reservationTotalPrice'] * $bookingprice[0]['conversionRate']),2);?> <?php echo $bookingprice[0]['conversionCurrency'];?></td>
          </tr>        
         <?php }?>      
        </tbody>
      </table>
          </div>
          </div>       
        <div class=" acc-right acc-left">
            <div class="acc-right-head">
            <h1><?php echo $view['translator']->trans('Payments Details');?></h1>
          </div>
            <div class="table-con">
            <table width="100%" class="pre-book">
        <tbody>
         <tr class="color">
            <td align="left"><?php echo $view['translator']->trans('Payment Mode');?></td>
            <td align="right"><?php echo $bookingtransaction[0]['paymentType'];?></td>
          </tr>          
        <tr>
            <td align="left"><?php echo $view['translator']->trans('Transaction Id');?></td>
            <td align="right"><?php echo $bookingtransaction[0]['transactionId'];?></td>
          </tr>          
          <tr class="color">
            <td align="left"><?php echo $view['translator']->trans('Transaction Date');?></td>
            <td align="right"><?php echo $this->container->get('mytrip_admin.helper.date')->viewformat($bookingtransaction[0]['transactionDate']);?></td>
          </tr> 
           <tr class="highlight">
            <td align="left"><?php echo $view['translator']->trans('Transaction Amount');?></td>
            <td align="right"><?php echo $bookingtransaction[0]['transactionAmount'];?> <?php echo $bookingtransaction[0]['transactionCurrency'];?></td>
          </tr>                  
        </tbody>
      </table>
          </div>
          </div>  
          <?php
		  if($booking[0][0]['fromDate']->format('Y-m-d')>=date('Y-m-d') && $booking[0][0]['status']=="Confirmed"){
			  ?>
             <div class=" acc-right acc-left">
           
            <table width="100%" class="pre-book">
        <tbody>
         <tr class="noborder">
            <td align="left" colspan="2"><a href="<?php echo $view['router']->generate('mytrip_user_cancelbooking',array('bookingId'=>$booking[0][0]['bookingId']*1024));?>"><span class="submit-review"><?php echo $view['translator']->trans('Cancel Booking');?></span></a></td></tr>                
        </tbody>
      </table>
          
             </div>  
              <?php
		  }
		  ?>  
          <?php if($booking[0][0]['status']=="Cancelled"){ 
		  $bookingcancel=$em->createQuery("SELECT p FROM MytripAdminBundle:BookingCancel p WHERE p.booking='".$booking[0][0]['bookingId']."'")->getArrayResult();
		  
		  ?>
          <div class=" acc-right acc-left">
            <div class="acc-right-head">
            <h1><?php echo $view['translator']->trans('Cancellation Details');?></h1>
          </div>
            <div class="table-con">
            <table width="100%" class="pre-book">
        <tbody>                  
        <tr>
            <td align="left"><?php echo $view['translator']->trans('Transaction Id');?></td>
            <td align="right"><?php echo $bookingcancel[0]['refundReferenceno'];?></td>
          </tr>          
          <tr class="color">
            <td align="left"><?php echo $view['translator']->trans('Refund Date');?></td>
            <td align="right"><?php 
			$return_date=$bookingcancel[0]['refundDate'];
                        echo $this->container->get('mytrip_admin.helper.date')->viewformat($return_date->format('Y-m-d'));?></td>
          </tr> 
           <tr class="highlight">
            <td align="left"><?php echo $view['translator']->trans('Refund Amount');?></td>
            <td align="right"><?php echo $bookingcancel[0]['refundAmount'];?> <?php echo $bookingcancel[0]['refundCurrency'];?></td>
          </tr>                  
        </tbody>
      </table>
          </div>
          </div>
          
		  <?php }?>
                 
           <?php }?>  
          
           
      </div>
      </div>
  </div>
