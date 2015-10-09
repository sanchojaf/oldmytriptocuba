<link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('css/main.css') ?>">
<script type="text/javascript" src="<?php echo $view['assets']->getUrl('js/jquery.min.js') ?>"></script>
<?php 
//$view->extend('::user.html.php');
$lan=$view['session']->get('language');
$em = $this->container->get('doctrine')->getManager();	
$booking=$em->createQuery("SELECT d,IDENTITY(d.hostal) AS hostal,IDENTITY(d.room) AS room FROM MytripAdminBundle:Booking d WHERE d.bookingId=".$bookingid)->getArrayResult();	
$hostal_content=$em->createQuery("SELECT d FROM MytripAdminBundle:HostalContent d WHERE d.hostal=".$booking[0]['hostal']." AND d.lan='".$lan."'")->getArrayResult();		
$bookingprice=$em->createQuery("SELECT p FROM MytripAdminBundle:BookingPrice p WHERE p.booking='".$booking[0][0]['bookingId']."'")->getArrayResult();
$bookingprice=$em->createQuery("SELECT p FROM MytripAdminBundle:BookingPrice p WHERE p.booking='".$booking[0][0]['bookingId']."'")->getArrayResult();
?><!--https://www.moneybookers.com/app/payment.pl-->
<form action="https://sandbox.dev.skrillws.net/app/payment.pl" method="post"  id="skrillform">
    <input type="hidden" name="pay_to_email" value="<?php echo $skrill_email;?>">
    <input type="hidden" name="return_url" value="<?php echo $url['url'].$view['router']->generate('mytrip_user_skrill_view'); ?>">
    <input type="hidden" name="cancel_url" value="<?php echo $url['url'].$view['router']->generate('mytrip_user_homepage'); ?>">
    <input type="hidden" name="language" value="<?php echo $lan;?>">
    <input type="hidden" name="status_url" value="<?php echo $url['url'].$view['router']->generate('mytrip_user_skrill_view'); ?>">
    <input type="hidden" name="transaction_id" value="venacuba-<?php echo $bookingid*1024;?>">
    <input type="hidden" name="amount2_description" value="<?php echo $view['translator']->trans('Booking Price');?>">
    <input type="hidden" name="amount2" value="<?php echo $bookingprice[0]['reservationPrice'];?>">
    <input type="hidden" name="amount3_description" value="<?php echo $view['translator']->trans('Reservation Charge');?>">
    <input type="hidden" name="amount3" value="<?php echo $bookingprice[0]['reservationCharge'];?>">
    <input type="hidden" name="amount" value="<?php echo $bookingprice[0]['reservationTotalPrice'];?>">
    <input type="hidden" name="currency" value="<?php echo $bookingprice[0]['conversionCurrency'];?>">
</form>
<script type="text/javascript">
$(document).ready(function(){
	$('.helpfade').show();
	$('.helptips').show();
	$('#skrillform').submit();
});
</script>