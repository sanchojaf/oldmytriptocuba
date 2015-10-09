<?php $view->extend('::user.html.php');
$em = $this->container->get('doctrine')->getManager();
$price=$em->createQuery("SELECT MIN(d.price) AS price FROM MytripAdminBundle:HostalRooms d WHERE d.hostal='".$hostals[0]['hostalId']."'")->getArrayResult();
?>
<link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('css/dateTimePicker.css'); ?>" />
<div class="container">
  <div class="about-banner pay-banner"><img src="<?php echo $view['assets']->getUrl('img/search-result.jpg');?>"  alt="about"></div>
  <input type="hidden" id="hprice" value="<?php echo $price[0]['price']*$view['session']->get('conversionrate');?> <?php echo $view['session']->get('currency');?>"/>
  <form name="bookform" id="bookform" class="contact acc-form" action="" method="post">
    <div class="faq">
      <h1><?php echo $view['translator']->trans('Book Now');?><img src="<?php echo $view['assets']->getUrl('img/calender-in.png');?>" class="our-icon"/></h1>
    </div>
    <h2><?php echo $view['translator']->trans('You are booking in');?> "<?php echo $hostal_content[0]['name'];?>"</h2>
    <span class="border-b"></span>
    <div class="booking-bl">
      <h2><?php echo $view['translator']->trans('Important Information');?></h2>
      <p><?php echo $view['translator']->trans('In order to confirm your booking we request a 20% pre-paid of the total, via paypal to our info@venacuba.com. You have free cancellation until 30 days before arrival, the pre-paid value does not expire. Prepaid value can be used to book at later time or other properties under the B&B category in Cuba.');?></p>
    </div>
    <span class="border-b"></span>
    <div>
      <div class="pay-ment">
        <h2><?php echo $view['translator']->trans('Dates & Guest');?></h2>
      </div>
      <?php $bookingsession=$view['session']->get('booking'); ?>
      <div class="date-g-bl">
        <div class="date-g">
          <div class="account-from">
            <label><?php echo $view['translator']->trans('Check In');?><span class="required">*</span></label>
            <div class="input-class">
           <input type="text" name="checkin" id="checkin" class="d-date" data-prompt-position="bottomLeft:20,5" placeholder="<?php echo $view['translator']->trans('Check in');?>" value="<?php echo ($bookingsession!=''?$bookingsession->get('checkin'):'');?>">            
            </div>
          </div>
          <div class="account-from">
            <label><?php echo $view['translator']->trans('Check Out');?><span class="required">*</span></label>
            <div class="input-class">
            <input type="text" name="checkout" id="checkout" class="d-date" data-prompt-position="bottomLeft:20,5" placeholder="<?php echo $view['translator']->trans('Check out');?>"  value="<?php echo ($bookingsession!=''?$bookingsession->get('checkout'):'');?>">            
            </div>
          </div>
          <!-- <div class="account-from">
            <label><?php echo $view['translator']->trans('No. of Rooms');?><span class="required">*</span></label>
            <div class="input-class">
              <select name="rooms" id="rooms" class="validate[required']">
              <?php

              // for($i=1;$i<=$hostal_rooms[0]['rooms'];$i++){
              for($i=1;$i<=count($hostal_rooms);$i++){
				  echo '<option value="'.$i.'">'.$i.' '.($i==1?$view['translator']->trans('Room'):$view['translator']->trans('Rooms')).'</option>';
			  }?>               
              </select>
            </div>
          </div> -->
        </div>
        <div class="date-g">
          <div>
          <input type="hidden" id="bdestination" value="<?php echo $destinations[0]['url'];?>"/><input type="hidden" id="bhostal" value="<?php echo $hostals[0]['url'];?>"/>
            <!--<input type="button" id="bcheckavailability" class="submit-review date-button" value="<?php echo $view['translator']->trans('Check Availability');?>">-->
            <a href="#availabilitycalender"  name="availabilitycalender" rel="leanModal" id="checkavail" class="go">
        <div class="chk-button  make-b"><?php echo $view['translator']->trans('Check Availability');?></div></a>
          </div>
        </div>
      </div>
    </div>
    <span class="border-b"></span>
    <div class="booking-bl">
      <div class="pay-ment">
        <h2><?php echo $view['translator']->trans('Select Rooms');?></h2>
      </div>
      <div class="date-g-bl">
        <table width="100%" class="pre-book">
          <tbody>
            <tr>
              <th>&nbsp;</th>
              <th><?php echo $view['translator']->trans('No. of Guests');?></th>
              <th><?php echo $view['translator']->trans('No. of Adults');?></th>
              <th><?php echo $view['translator']->trans('No. of Child');?></th>
              <th><?php echo $view['translator']->trans('Amount');?></th>
            </tr>
            <?php foreach($hostal_rooms as $room): ?>
            <tr class="">
              <td><input type="checkbox" name="selected_rooms[<?php echo $room->getRoomId(); ?>]" id="selected_rooms[<?php echo $room->getRoomId(); ?>]" value="1"/></td>             
              <td><?php echo $room->getGuests(); ?></td>
              <td><?php echo $room->getAdults(); ?> <?php echo ($room->getAdults()==1?$view['translator']->trans('Adult'):$view['translator']->trans('Adults'));?></td>
              <td><?php echo $room->getChild(); ?> <?php echo ($room->getChild()==1?$view['translator']->trans('Child'):$view['translator']->trans('Children'));?></td>
              <td><?php echo $room->getPrice()*$view['session']->get('conversionrate');?> <?php echo $view['session']->get('currency');?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>   
    <span class="border-b"></span>
    <?php if($view['session']->get('user')==''){ ?>
     <div class="already booklogin"><?php echo $view['translator']->trans('Already a member of mytrip to cuba?');?> <a id="already_login"><?php echo $view['translator']->trans('Log In');?></a></div>     
     <span class="border-b"></span><?php }?>
    <div class="booking-bl">   
      <div class="pay-ment">
        <h2><?php echo $view['translator']->trans('Contact Details');?></h2>
      </div>
      <?php 
	  if($view['session']->get('user')!=''){
		  $buser=$view['session']->get('user');
		  $busers=$em->createQuery("SELECT p FROM MytripAdminBundle:User p  WHERE  p.userId='".$buser['userId']."'")->getArrayResult();
	  }?>
      <div class="account-from">
        <label><?php echo $view['translator']->trans('Name');?><span class="required">*</span></label>
        <div class="input-class">
          <input type="text" name="firstname" id="bfirstname" class="validate[required,custom[onlyLetter]]" maxlength="25" data-prompt-position="bottomLeft:20,5" value="<?php echo ($bookingsession!=''?$bookingsession->get('firstname'):($view['session']->get('user')!=''?$busers[0]['firstname']:''));?>" />
        </div>
      </div>
      <div class="account-from">
        <label><?php echo $view['translator']->trans('Last Name');?><span class="required">*</span></label>
        <div class="input-class">
         <input type="text" name="lastname" id="blastname" class="validate[required,custom[onlyLetter]]" maxlength="25" data-prompt-position="bottomLeft:20,5" value="<?php echo ($bookingsession!=''?$bookingsession->get('lastname'):($view['session']->get('user')!=''?$busers[0]['lastname']:''));?>" />
        </div>
      </div>
      <div class="account-from">
        <label><?php echo $view['translator']->trans('Email');?><span class="required">*</span></label>
        <div class="input-class">
          <input type="text" name="email" id="bemail" class="validate[required,custom[email]]"  maxlength="150" data-prompt-position="bottomLeft:20,5"  value="<?php echo ($bookingsession!=''?$bookingsession->get('email'):($view['session']->get('user')!=''?$busers[0]['email']:''));?>" />
        </div>
      </div>
      <?php if($view['session']->get('user')==''){ ?>
      <div class="account-from">
        <label><?php echo $view['translator']->trans('Password');?><span class="required">*</span></label>
        <div class="input-class">
          <input type="password" name="password" id="bpasswords" class="validate[required,minSize[6]]" maxlength="20" data-prompt-position="bottomLeft:20,5" />
        </div>
      </div>
      <div class="account-from">
        <label><?php echo $view['translator']->trans('Confirm Password');?><span class="required">*</span></label>
        <div class="input-class">
           <input type="password" name="cpassword" id="bcpassword" class="validate[required,equals[bpasswords]]" maxlength="20" data-prompt-position="bottomLeft:20,5" />
        </div>
      </div>
      <?php }?>
      <div class="account-from">
        <label><?php echo $view['translator']->trans('Gender');?><span class="required">*</span></label>
        <div class="input-class">
          <select name="gender" id="bgender"  data-prompt-position="bottomLeft:20,5" class="validate[required]" >
            <option value=""><?php echo $view['translator']->trans('Select');?></option>
            <option value="Male" <?php echo ($bookingsession!=''?($bookingsession->get('gender')=='Male'?'selected="selected"':''):($view['session']->get('user')!=''?($busers[0]['gender']=='Male'?'selected="selected"':''):''));?>><?php echo $view['translator']->trans('Male');?></option>
            <option value="Female" <?php echo ($bookingsession!=''?($bookingsession->get('gender')=='Female'?'selected="selected"':''):($view['session']->get('user')!=''?($busers[0]['gender']=='Female'?'selected="selected"':''):''));?>><?php echo $view['translator']->trans('Female');?></option>                   
          </select>
        </div>
      </div>
      <div class="account-from">
        <label><?php echo $view['translator']->trans('Contact Number');?><span class="required">&nbsp;</span></label>
        <div class="input-class">
          <input type="text" class="validate[custom[integer]] small" name="cccode" id="cccode" maxlength="4" placeholder="001" value="<?php echo ($bookingsession!=''?$bookingsession->get('cccode'):($view['session']->get('user')!=''?$busers[0]['cccode']:''));?>" data-prompt-position="bottomLeft:20,5" >  <input type="text" class="validate[custom[integer]] medium" name="phone" id="bphone" value="<?php echo ($bookingsession!=''?$bookingsession->get('phone'):($view['session']->get('user')!=''?$busers[0]['phone']:''));?>" data-prompt-position="bottomLeft:20,5" >   
        </div>
      </div>
      <div class="account-from">
        <label><?php echo $view['translator']->trans('Mobile');?><span class="required">*</span></label>
        <div class="input-class">
         <input type="text" class="validate[required,custom[integer]] small" name="cmcode" id="cmcode" maxlength="4" placeholder="001" value="<?php echo ($bookingsession!=''?$bookingsession->get('cmcode'):($view['session']->get('user')!=''?$busers[0]['cmcode']:''));?>" data-prompt-position="bottomLeft:20,5" >  <input type="text" class="validate[required,custom[integer]] medium" name="mobile" id="bmobile" value="<?php echo ($bookingsession!=''?$bookingsession->get('mobile'):($view['session']->get('user')!=''?$busers[0]['mobile']:''));?>" data-prompt-position="bottomLeft:20,5" >        
        </div>
      </div>
    <div class="account-from">
    <label><?php echo $view['translator']->trans('Address');?><span class="required">&nbsp;</span></label>
    <div class="input-class">
    <input type="text" name="address" id="baddress" value="<?php echo ($bookingsession!=''?$bookingsession->get('address'):($view['session']->get('user')!=''?$busers[0]['address']:''));?>" data-prompt-position="bottomLeft:20,5"  >
    </div>
    </div>
    <div class="account-from">
    <label>&nbsp;</label>
    <div class="input-class">
    <input type="text" name="address2" id="baddress2" value="<?php echo ($bookingsession!=''?$bookingsession->get('address2'):($view['session']->get('user')!=''?$busers[0]['address2']:''));?>" data-prompt-position="bottomLeft:20,5" >
    </div>
    </div>
    <div class="account-from">
    <label><?php echo $view['translator']->trans('Nationality');?><span class="required">*</span></label>
    <div class="input-class">
    <select class="validate[required] longselect" name="country" id="bcountry" data-prompt-position="bottomLeft:20,5">
    <option value=""><?php echo $view['translator']->trans('Select country');?></option>
    <?php
    foreach($country as $country){
        ?>
        <option value="<?php echo $country['cid'];?>" <?php echo ($bookingsession!=''?($bookingsession->get('country')==$country['cid']?'selected="selected"':''):($view['session']->get('user')!=''?($busers[0]['country']==$country['cid']?'selected="selected"':''):''));?> ><?php echo $country['country'];?></option>
        <?php
    }
    ?>                                     
    </select>
    </div>
    </div>   
    <div class="account-from">
    <label><?php echo $view['translator']->trans('Province');?><span class="required">*</span></label>
    <div class="input-class">
    <select class="validate[required] longselect" name="province" id="bprovince" data-prompt-position="bottomLeft:20,5">
    <option value=""><?php echo $view['translator']->trans('Select province');?></option>
    <?php 
	if($bookingsession!=''){
		if($bookingsession->get('country')!=''){
			$state=$em->createQuery("SELECT s FROM MytripAdminBundle:States s  where s.cid='".$bookingsession->get('country')."'" )->getArrayResult();	
			foreach($state as $states){
				echo '<option value="'.$states['sid'].'" '.($bookingsession->get('province')==$states['sid']?'selected="selected"':'').'>'.$states['state'].'</option>';
			}
   		 }
	}elseif($view['session']->get('user')!=''){
		if($busers[0]['country']!=''){
			$state=$em->createQuery("SELECT s FROM MytripAdminBundle:States s  where s.cid='".$busers[0]['country']."'" )->getArrayResult();	
			foreach($state as $states){
				echo '<option value="'.$states['sid'].'" '.($busers[0]['province']==$states['sid']?'selected="selected"':'').'>'.$states['state'].'</option>';
			}
   		 }
	}
    ?>
    </select>
    </div>
    </div>
    <div class="account-from">
    <label><?php echo $view['translator']->trans('City');?><span class="required">&nbsp;</span></label>
    <div class="input-class">
    <input type="text" name="city" id="bcity"  value="<?php echo ($bookingsession!=''?$bookingsession->get('city'):($view['session']->get('user')!=''?$busers[0]['city']:''));?> " data-prompt-position="bottomLeft:20,5" >
    </div>
    </div>    
    <div class="account-from">
    <label><?php echo $view['translator']->trans('Zip');?><span class="required">&nbsp;</span></label>
    <div class="input-class">
    <input type="text" name="zip" id="bzip"  value="<?php echo ($bookingsession!=''?$bookingsession->get('zip'):($view['session']->get('user')!=''?$busers[0]['zip']:''));?>" data-prompt-position="bottomLeft:20,5" >
    </div>
    </div> 
    </div>
    <span class="border-b"></span>
    <div class="account-from booking-bl"> <span class="terms-con"><?php echo $view['translator']->trans('I Accept the');?> <strong><a onclick='window.open("<?php echo $view['router']->generate('mytrip_user_payment');?>", "MsgWindow", " scrollbars=yes, resizable=yes,width=500, height=500");' ><?php echo $view['translator']->trans('Terms and Conditions');?></a></strong></span>
      <div>
        <input type="checkbox" name="checkbox[checkbox]" id="checkbox1" class="css-checkbox lrg" value="1"  checked="checked"  data-prompt-position="bottomLeft:-60,10" />
        <label for="checkbox1" name="checkbox1_lbl" class="css-label lrg web-two-style"></label>
        <input type="hidden" name="userstatus" id="userstatus" value="<?php echo $view['session']->get('user')!=''?'1':'0';?>"/>
        <input type="submit" name="booksubmit" id="booksubmit"  value="<?php echo $view['translator']->trans('Make Booking');?>" class="submit-review">
      </div>
    </div>
    <span class="border-b"></span>
  </form>
</div>
<div id="availabilitycalender" class="avail_cal">
<a class="modal_close"></a>
<div class="avail_cal_head">
<ul class="cal"><li><div class="avail"></div><?php echo $view['translator']->trans('Available');?></li><li><div class="navail"></div><?php echo $view['translator']->trans('Not Available');?></li><li><div class="to_conf"></div><?php echo $view['translator']->trans('To Confirm');?></li><li><div class="to_past"></div><?php echo $view['translator']->trans('Past');?></li></ul>
<ul class="cal_page"><li><div class="cal_prev"><img src="<?php echo $view['assets']->getUrl('img/cal_arrow_left.png');?>"> <?php echo $view['translator']->trans('Previous');?></div></li><li>|</li><li><div class="cal_next"><?php echo $view['translator']->trans('Next');?> <img src="<?php echo $view['assets']->getUrl('img/cal_arrow_right.png');?>"></div></li></ul>
</div>
 <div id="show-next-month" data-toggle="calendar"></div>
 <div class="avail_cal_head">
<p>* <?php echo $view['translator']->trans('The calender is updated every five minutes and is only an approximation of availability. We suggest that you contact us to confirm availability before submitting a reservation request. Some hosts set custom pricing for certain days on their calendar, like weekends or holidays. The rates listed are per day and do not include any cleaning fee or rates for extra people the host may have for this listing. Please refer to the listings Description tab for more details.');?></p>

</div>
  </div> 
<script src="<?php echo $view['assets']->getUrl('js/dateTimePicker.js'); ?>" type="text/javascript"></script> 
<script src="<?php echo $view['assets']->getUrl('js/pay_cal_script.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
<?php
if(isset($_REQUEST['cal'])){
	?>
	$(document).ready(function(){
		$('#checkavail').click();
		$('#checkavail').click(function(){
			 $("html, body").animate({ scrollTop: 0 }, 600);
		});
	});
	<?php
}
?>

$(document).ready(function(){		
	$('#checkavail').click(function(){
		 $("html, body").animate({ scrollTop: 0 }, 600);
	});
});
</script>
