<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Mytrip cuba</title>
<link href="http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:400,200,300,700" rel="stylesheet" type="text/css">

<style type="text/css">
body{
	font-family:'Yanone Kaffeesatz', sans-serif;
	color:#0C7C87;
	font-size:19px;
	font-weight:300;
	background:#E3EFEF;
}
h1,h2,h3,h4,p{
	margin:0;
}
table.contenttable{
	width:640px;
	margin:0px auto;
	padding:15px 15px 140px 15px;
	background:url(img/bottom.png) center bottom no-repeat #fff;
}
table.contenttable h1{
	font-size:20px;
	font-weight:300;
}
table.contenttable h2{
	color:#231F20;
}
table.contenttable p{
	font-family:'Yanone Kaffeesatz', sans-serif;
	color:#231F20;
}
.border{
	border:2px solid #0C7C87;
	width:100%;
	display:table;
	border-radius:5px;
	-moz-border-radius:5px;
	-webkit-border-radius:5px;
}
table{
	border-spacing:5px;
}
th{
	padding:15px 10pX;
}
.fk{
	float:left;
}
</style></head>
<?php 
$em = $this->container->get('doctrine')->getManager();
$bucketurl=$this->container->get('mytrip_admin.helper.amazon')->getOption('url');
$bookingid=$view['session']->get('bookingId');
$usersession=$view['session']->get('user');
$booking=$em->createQuery("SELECT d,IDENTITY(d.hostal) AS hostal FROM MytripAdminBundle:Booking d WHERE d.bookingId=".$bookingid)->getArrayResult();			
$booking_info=$em->createQuery("SELECT d FROM MytripAdminBundle:BookingInfo d WHERE d.booking=".$bookingid)->getArrayResult();	
if($booking_info[0]['province']!=''){
	$province=$em->createQuery("SELECT d FROM MytripAdminBundle:States d WHERE d.sid=".$booking_info[0]['province'])->getArrayResult();
}
if($booking_info[0]['country']!=''){
	$country=$em->createQuery("SELECT d FROM MytripAdminBundle:Country d WHERE d.cid=".$booking_info[0]['country'])->getArrayResult();
}
$booking_price=$em->createQuery("SELECT d FROM MytripAdminBundle:BookingPrice d WHERE d.booking=".$bookingid)->getArrayResult();
$hostal=$em->createQuery("SELECT d FROM MytripAdminBundle:Hostal d WHERE d.hostalId=".$booking[0]['hostal'])->getArrayResult();
$hostal_room=$em->createQuery("SELECT d FROM MytripAdminBundle:HostalRooms d WHERE d.hostal=".$booking[0]['hostal'])->getArrayResult();
$hostal_image=$em->createQuery("SELECT d FROM MytripAdminBundle:HostalImage d WHERE d.hostal=".$booking[0]['hostal'])->getArrayResult();
$user=$em->createQuery("SELECT d FROM MytripAdminBundle:User d WHERE d.userId=".$usersession['userId'])->getArrayResult();
?>
<body style="font-family: 'Yanone Kaffeesatz', sans-serif;color: #0C7C87;font-size: 19px;font-weight: 300;background: #E3EFEF;">
<table width="600" cellpadding="0" cellspacing="0" border="0" class="contenttable" style="border-spacing: 5px;width: 640px;margin: 0px auto;padding: 15px 15px 140px 15px;background:#fff;">
  <tbody>
    <tr>
      <td colspan="2"><a href="<?php echo $url['url']; ?>"><img src="<?php echo $url['url'].'/img/emaillogo.png'; ?>" width="191" height="185" alt="logo" /></a></td>
    </tr>
    <tr>
      <td colspan="2"><h1 style="margin: 0;font-size: 20px;font-weight: 300;">Thanks, <?php echo $booking_info[0]['firstname']." ".$booking_info[0]['lastname'];?>! </h1>
        <p style="margin: 0;font-family: 'Yanone Kaffeesatz', sans-serif;color: #231F20;"><?php echo $view['translator']->trans('Your Booking is pre-confirmed');?></p></td>
    </tr>
    <tr>
      <td align="left"><span class="border" style="border: 2px solid #0C7C87;width: 100%;display: table;border-radius: 5px;-moz-border-radius: 5px;-webkit-border-radius: 5px;">
        <h1 style="margin: 0;font-size: 20px;font-weight: 300;"><?php echo $view['translator']->trans('HOSTAL');?>:</h1>
        <img src="<?php echo $bucketurl.$hostal_image[0]['image']; ?>" width="227"  />
        <h2 style="margin: 0;color: #231F20;"><?php echo $hostal[0]['name'];?></h2>
        </span><span class="border" style="border: 2px solid #0C7C87;width: 100%;display: table;border-radius: 5px;-moz-border-radius: 5px;-webkit-border-radius: 5px;">
        <h1 style="margin: 0;font-size: 20px;font-weight: 300;"><?php echo $view['translator']->trans('BOOKING NUMBER');?>:</h1>
        <h2 style="margin: 0;color: #231F20;">venacuba-<?php echo $bookingid*1024;?></h2>
        </span></td>
      <td align="left"><span class="border" style="border: 2px solid #0C7C87;width: 100%;display: table;border-radius: 5px;-moz-border-radius: 5px;-webkit-border-radius: 5px;">
        <h1 style="margin: 0;font-size: 20px;font-weight: 300;"><?php echo $view['translator']->trans('ADDRESS');?>:</h1>
        <p style="margin: 0;font-family: 'Yanone Kaffeesatz', sans-serif;color: #231F20;"><?php echo $booking_info[0]['firstname']." ".$booking_info[0]['lastname'];?> 
        <?php if($booking_info[0]['address']!=''){?>
        #<?php echo $booking_info[0]['address']." ".$booking_info[0]['address1'];?>, 
		<?php }?>
		<?php if($booking_info[0]['city']!=''){?>
		<?php echo $booking_info[0]['city'];?>,
        <?php }?>
        <?php if($booking_info[0]['province']!=''){?>
         <?php echo $booking_info[0]['province']!=''?$province[0]['state']:'';?>, 
         <?php }?>
         <?php if($booking_info[0]['country']!=''){?>
		 <?php echo $booking_info[0]['country']!=''?$country[0]['country']:'';?>
         <?php }?>
           </p>
        </span><span class="border" style="border: 2px solid #0C7C87;width: 100%;display: table;border-radius: 5px;-moz-border-radius: 5px;-webkit-border-radius: 5px;">
        <h1 style="margin: 0;font-size: 20px;font-weight: 300;"><?php echo $view['translator']->trans('EMAIL');?>:</h1>
        <p style="margin: 0;font-family: 'Yanone Kaffeesatz', sans-serif;color: #231F20;"><?php echo $booking_info[0]['email'];?> </p>
        </span><span class="border" style="border: 2px solid #0C7C87;width: 100%;display: table;border-radius: 5px;-moz-border-radius: 5px;-webkit-border-radius: 5px;">
        <h1 style="margin: 0;font-size: 20px;font-weight: 300;"><?php echo $view['translator']->trans('DETAILS');?>:</h1>
        <p style="margin: 0;font-family: 'Yanone Kaffeesatz', sans-serif;color: #231F20;"><?php echo $view['translator']->trans('Your reservation');?>: <?php echo $booking[0][0]['noOfDays'];?> <?php echo $view['translator']->trans('nights');?>, <?php echo $booking[0][0]['noOfRooms'];?> <?php echo $hostal_room[0]['roomtype'];?>, <?php echo $booking[0][0]['adults'];?> <?php echo $view['translator']->trans('adults');?>, <?php echo $booking[0][0]['child'];?> <?php echo $view['translator']->trans('child');?><br />
                  <?php echo $view['translator']->trans('Check In');?>: <?php echo date('l dS M Y',strtotime($booking[0][0]['fromDate']->format('Y-m-d H:i:s')));?><br />
                 <?php echo $view['translator']->trans('Check Out');?>: <?php echo date('l dS M Y',strtotime($booking[0][0]['toDate']->format('Y-m-d H:i:s')));?></p>
        </span></td>
    </tr>
    <tr>
      <td align="left" colspan="2"><span class="border" style="border: 2px solid #0C7C87;width: 100%;display: table;border-radius: 5px;-moz-border-radius: 5px;-webkit-border-radius: 5px;">
        <h1 style="margin: 0;font-size: 20px;font-weight: 300;"><?php echo $view['translator']->trans('BOOKED BY');?>:</h1>
        <p style="margin: 0;font-family: 'Yanone Kaffeesatz', sans-serif;color: #231F20;"><?php echo $user[0]['firstname']." ".$user[0]['lastname'];?> < <?php echo $user[0]['email'];?> > </p>
        </span></td>
    </tr>
    <tr>
      <td align="left" class="border" style="border: 2px solid #0C7C87;width: 100%;display: table;border-radius: 5px;-moz-border-radius: 5px;-webkit-border-radius: 5px;"> 
        <h1 style="margin: 0;font-size: 20px;font-weight: 300;"><?php echo $view['translator']->trans('PRE-PAID');?>:</h1>
        <p style="margin: 0;font-family: 'Yanone Kaffeesatz', sans-serif;color: #231F20;"><?php echo ($booking_price[0]['conversionRate']*$booking_price[0]['totalPrice'])+($booking_price[0]['conversionRate']*$booking_price[0]['reservationCharge']);?>  <?php echo $booking_price[0]['conversionCurrency'];?> </p>
     </td> 
        
        
        
        
    </tr>
    
    <tr>
    <td colspan="2">
    <p style="margin: 0;font-family: 'Yanone Kaffeesatz', sans-serif;color: #231F20;"><?php echo $view['translator']->trans('In order to confirm your booking we request a 20% pre-paid of the total, via').' '.$view['session']->get('payment').' '.$view['translator']->trans('to our account info@venacuba.com');?>
    <br><br>
 <?php echo $view['translator']->trans('You have free cancellation until 30 days before arrival, the pre-paid value does not expire. Prepaid value can used to book at later time or other properties under the B&B category in Cuba');?>.
<br><br>
<?php echo $view['translator']->trans('Thank you for your business');?>!
    </p>
    </td>
    </tr>
    <tr>
      <td  colspan="2"><img src="<?php echo $url['url'].$view['assets']->getUrl('img/bottom.png'); ?>" /></td>
    </tr>
  </tbody>
</table>
</body>
</html>