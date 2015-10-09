<?php $view->extend('::user.html.php');
$em = $this->container->get('doctrine')->getManager();
$bookingprice=$em->createQuery("SELECT p FROM MytripAdminBundle:BookingPrice p WHERE p.booking='".$booking[0][0]['bookingId']."'")->getArrayResult();
$setting=$em->createQuery("SELECT p FROM MytripAdminBundle:Settings p")->getArrayResult();	
$paymentsetting=$em->createQuery("SELECT p FROM MytripAdminBundle:ApiGateway p WHERE p.gateway IN ('Global One','Bean Stream') AND p.status='Active'")->getArrayResult();
$hostal_rooms=$em->find('MytripAdminBundle:Booking', $booking[0][0]['bookingId'])->getRooms();
?>
<script type="text/javascript" src="<?php echo $view['assets']->getUrl('js/jquery.creditCardValidator.js') ?>"></script>
<div class="container">
  <div class="about-banner pay-banner"><img src="<?php echo $view['assets']->getUrl('img/search-result.jpg');?>"  alt="<?php echo $view['translator']->trans('Make Payment');?>"></div>
  <div class="faq">
    <h1><?php echo $view['translator']->trans('Make Payment');?></h1>
  </div>
  <div class="booking-bl">
    <div class="pay-ment">
      <h2><?php echo $view['translator']->trans('Booking Summary');?></h2>
    </div>
    <div class="date-g-bl">
      <table width="100%" class="pre-book">
        <tbody>
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
            <td align="left"><?php echo $view['translator']->trans('Reservation fee');?>(<?php echo $setting[0]['reservationCharge'];?>%)</td>
            <td align="right"><?php echo number_format($bookingprice[0]['totalPrice']*$setting[0]['reservationCharge']/100,2);?> <?php echo $bookingprice[0]['conversionCurrency'];?></td>
          </tr>
          <tr class="highlight">
            <td align="left"><?php echo $view['translator']->trans('Total Price');?></td>
            <td align="right"><?php echo number_format($bookingprice[0]['totalPrice']+($bookingprice[0]['totalPrice']*$setting[0]['reservationCharge']/100)*$bookingprice[0]['conversionRate'],2);?> <?php echo $bookingprice[0]['conversionCurrency'];?></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <span class="border-b"></span>
  <div>
    <div class="pay-ment">
      <h2><?php echo $view['translator']->trans('Payments');?></h2>
    </div>
    <div class="date-g-bl">
      <form class="contact acc-form" name="paymentsubmit" id="paymentsubmit" action="" method="post">
        <div class="date-g">
        <div class="account-from">
            <label><?php echo $view['translator']->trans('Payment Type');?></label>
            <div class="input-class">
              <label style="text-align:left"><input type="radio" id="partial" name="paymenttype" value="partial"  style="width:auto !important;" <?php if(isset($_REQUEST['paymenttype']) && $_REQUEST['paymenttype']=='partial'){ echo 'checked="checked"';} if(!isset($_REQUEST['paymenttype'])){ echo 'checked="checked"';}?> >
              <?php echo $view['translator']->trans('Partial');?><br/>(<?php echo $setting[0]['bookingPercentage'];?>% <?php echo $view['translator']->trans('Payment');?>)</label>&nbsp;
              <label style="text-align:left"><input type="radio" id="full" name="paymenttype" value="full" style="width:auto !important;" <?php if(isset($_REQUEST['paymenttype']) && $_REQUEST['paymenttype']=='full'){ echo 'checked="checked"';}?> >
              <?php echo $view['translator']->trans('Full Payment');?><br/>&nbsp;</label> &nbsp;
              </div>
          </div>
          <div class="account-from">
            <label><?php echo $view['translator']->trans('Payment Mode');?></label>
            <div class="input-class">
              <input type="radio" id="paypal" name="payment" value="paypal" <?php if(isset($_REQUEST['payment']) && $_REQUEST['payment']=='paypal'){ echo 'checked="checked"';}?> <?php if(!isset($_REQUEST['payment'])){ echo 'checked="checked"';}?> style="width:auto !important;" >
              <img src="<?php echo $view['assets']->getUrl('img/paypal.png');?>"/>&nbsp;
              <?php
			  if($paymentsetting[0]['gateway']=="Bean Stream"){
			  if($bookingprice[0]['conversionCurrency']=="USD" || $bookingprice[0]['conversionCurrency']=="CAD"){?>
              <input type="radio" id="beanstream" name="payment" value="beanstream" style="width:auto !important;" <?php if(isset($_REQUEST['payment']) && $_REQUEST['payment']=='beanstream'){ echo 'checked="checked"';}?>>
              <img src="<?php echo $view['assets']->getUrl('img/ccard.png');?>"/>&nbsp;
              <?php }
			  }?>
              <!--<input type="radio" id="stripe"  name="payment" value="stripe" style="width:auto !important;" <?php if(isset($_REQUEST['payment']) && $_REQUEST['payment']=='stripe'){ echo 'checked="checked"';}?>>
              <img src="<?php echo $view['assets']->getUrl('img/stripe.png');?>"/>-->
              <?php if($paymentsetting[0]['gateway']=="Global One"){?>
              <input type="radio" id="globalone"  name="payment" value="globalone" style="width:auto !important;" <?php if(isset($_REQUEST['payment']) && $_REQUEST['payment']=='globalone'){ echo 'checked="checked"';}?>>
              <img src="<?php echo $view['assets']->getUrl('img/ccard.png');?>"/>
              <?php } ?>
              </div>
          </div>
        </div>       
        <span class="border-b"></span>
        <div class="pay-ment">
          <div class="date-g">
            <div>
              <input type="submit" name="paysubmit" id="paysubmit" class="submit-review date-button" value="<?php echo $view['translator']->trans('Pay Now');?>">
            </div>
          </div>
        </div>
      </form>
      <?php if($view['session']->getFlash('stripe')){		 
		  ?>
      <form action="<?php echo $view['router']->generate('mytrip_user_stripe') ?>" method="POST" id="stripe_submit" style="display:none">
        <script
                src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                data-key="<?php echo $keys->getPublishableKey();?>"
                data-image=""
                data-name=""
                data-description="<?php echo $hostal[0]['name'];?>"
                data-amount="<?php echo $bookingprice[0]['reservationTotalPrice']*100;?>">
        </script>
        <script type="text/javascript">
		jQuery('.helpfade').show();
		jQuery('.helptips').show();
		setTimeout(function(){
			jQuery('.helpfade').hide();
			jQuery('.helptips').hide();
		}, 5000);
		$('.stripe-button-el').click();
		</script>
    </form>
   <?php }?>
      <span class="border-b"></span> </div>
  </div>
</div>
 <?php if($view['session']->getFlash('globalone')){ ?>
<a href="#beanstream_div"  rel="leanModal" id="beanstream_link" class="go" style="display:none;" ><?php echo $view['translator']->trans('Beanstream');?></a>
<div id="beanstream_div" class="signup"> <a  class="modal_close"></a>
  <h1><?php echo $view['translator']->trans('Credit card details');?> <img src="<?php echo $view['assets']->getUrl('img/sign-in.png');?>" > </h1>
  <form name="beanstream" id="beanstreamform" method="post" enctype="multipart/form-data" action="<?php echo $view['router']->generate('mytrip_user_globalone');?>">
    <div class="input">
      <input type="text" name="cardnumber" id="cardnumber" class="validate[required]" maxlength="25" placeholder="<?php echo $view['translator']->trans('Credit card number');?>" data-prompt-position="bottomLeft:20,5"  />
       <input type="hidden" name="cardtype" id="cardtype" value=""   />
     </div>
    <div class="input">
      <input type="text" name="cardowner" id="cardowner" class="validate[required,custom[onlyLetter]]" maxlength="25" placeholder="<?php echo $view['translator']->trans('Card holder name');?>" data-prompt-position="bottomLeft:20,5"   />
      <img src="<?php echo $view['assets']->getUrl('img/name.png');?>" alt="<?php echo $view['translator']->trans('Last Name');?>"> </div>
    <div class="input padbottom">
    <span class="terms"><?php echo $view['translator']->trans('Exp. Month');?></span>
    <select name="exmonth" id="exmonth" class="validate[required]">
    <option value=""><?php echo $view['translator']->trans('Select');?></option>
    <?php
	for($i=1;$i<=12;$i++){
		echo '<option value="'.sprintf('%02d',$i).'">'.date('M',mktime(0,0,0,$i+1,0,0)).'</option>';
	}
	?>
    </select>
      </div>
    <div class="input padbottom">
    <span class="terms"><?php echo $view['translator']->trans('Exp. Year');?></span>
      <select name="exyear" id="exyear" class="validate[required]">
    <option value=""><?php echo $view['translator']->trans('Select');?></option>
     <?php
	for($i=date('Y');$i<=date('Y')+20;$i++){
		echo '<option value="'.date('y',mktime(0,0,0,0,0,$i+1)).'">'.$i.'</option>';
	}
	?>
    </select>
    </div>
    <div class="input">
      <input type="text" name="cvv" id="cvv" class="validate[required,custom[integer]]" maxlength="4" placeholder="<?php echo $view['translator']->trans('CVV');?>" data-prompt-position="bottomLeft:20,5" />
      <img src="<?php echo $view['assets']->getUrl('img/pass-1.png');?>" alt="<?php echo $view['translator']->trans('Password');?>"> </div>
    
    <div class="login-b">
      <input type="submit" name="submit" id="signupsubmit" class="submit-review login-b" value="<?php echo $view['translator']->trans('Pay');?>" />
    </div>
  </form>  
</div>
<script type="text/javascript">
$(function() {
	$("#beanstream_link").leanModal({closeButton: ".modal_close"});
	$('#beanstream_link').click();
	$('#cardnumber').validateCreditCard(function(e){ $("#cardnumber").removeClass('visa').removeClass('visa_electron').removeClass('maestro').removeClass('mastercard').removeClass('discover');
	if(e.card_type!=null){$("#cardnumber").addClass(e.card_type.name),$("#cardtype").val(e.card_type.name),e.length_valid&&e.luhn_valid?$("#cardnumber").addClass("valid"):$("#cardnumber").removeClass("valid")}});
});
</script>
 <?php }?>
  <?php if($view['session']->getFlash('beanstream')){ ?>
<a href="#beanstream_div"  rel="leanModal" id="beanstream_link" class="go" style="display:none;" ><?php echo $view['translator']->trans('Beanstream');?></a>
<div id="beanstream_div" class="signup"> <a  class="modal_close"></a>
  <h1><?php echo $view['translator']->trans('Credit card details');?> <img src="<?php echo $view['assets']->getUrl('img/sign-in.png');?>" > </h1>
  <form name="beanstream" id="beanstreamform" method="post" enctype="multipart/form-data" action="<?php echo $view['router']->generate('mytrip_user_beanstream');?>">
    <div class="input">
      <input type="text" name="cardnumber" id="cardnumber" class="validate[required]" maxlength="25" placeholder="<?php echo $view['translator']->trans('Credit card number');?>" data-prompt-position="bottomLeft:20,5"  />
     </div>
    <div class="input">
      <input type="text" name="cardowner" id="cardowner" class="validate[required,custom[onlyLetter]]" maxlength="25" placeholder="<?php echo $view['translator']->trans('Card holder name');?>" data-prompt-position="bottomLeft:20,5"   />
      <img src="<?php echo $view['assets']->getUrl('img/name.png');?>" alt="<?php echo $view['translator']->trans('Last Name');?>"> </div>
    <div class="input padbottom">
    <span class="terms"><?php echo $view['translator']->trans('Exp. Month');?></span>
    <select name="exmonth" id="exmonth" class="validate[required]">
    <option value=""><?php echo $view['translator']->trans('Select');?></option>
    <?php
	for($i=1;$i<=12;$i++){
		echo '<option value="'.sprintf('%02d',$i).'">'.date('M',mktime(0,0,0,$i+1,0,0)).'</option>';
	}
	?>
    </select>
      </div>
    <div class="input padbottom">
    <span class="terms"><?php echo $view['translator']->trans('Exp. Year');?></span>
      <select name="exyear" id="exyear" class="validate[required]">
    <option value=""><?php echo $view['translator']->trans('Select');?></option>
     <?php
	for($i=date('Y');$i<=date('Y')+20;$i++){
		echo '<option value="'.date('y',mktime(0,0,0,0,0,$i+1)).'">'.$i.'</option>';
	}
	?>
    </select>
    </div>
    <div class="input">
      <input type="text" name="cvv" id="cvv" class="validate[required,custom[integer]]" maxlength="4" placeholder="<?php echo $view['translator']->trans('CVV');?>" data-prompt-position="bottomLeft:20,5" />
      <img src="<?php echo $view['assets']->getUrl('img/pass-1.png');?>" alt="<?php echo $view['translator']->trans('Password');?>"> </div>
    
    <div class="login-b">
      <input type="submit" name="submit" id="signupsubmit" class="submit-review login-b" value="<?php echo $view['translator']->trans('Pay');?>" />
    </div>
  </form>  
</div>
<script type="text/javascript">
$(function() {
	$("#beanstream_link").leanModal({closeButton: ".modal_close"});
	$('#beanstream_link').click();
	$('#cardnumber').validateCreditCard(function(e){ $("#cardnumber").removeClass('visa').removeClass('visa_electron').removeClass('maestro').removeClass('mastercard').removeClass('discover');
	if(e.card_type!=null){$("#cardnumber").addClass(e.card_type.name),e.length_valid&&e.luhn_valid?$("#cardnumber").addClass("valid"):$("#cardnumber").removeClass("valid")}});
});
</script>
 <?php }?>