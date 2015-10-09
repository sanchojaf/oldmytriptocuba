<?php $view->extend('::admin.html.php');?>
<div id="content"  class="clearfix">
  <div class="container">
    <div align="right" style="padding-right:50px;"></div>
    <form action="<?php echo $view['router']->generate('mytrip_admin_settings') ?>" id="myForm" method="post">
    <fieldset>
      <legend>Booking Settings</legend>
      <dl class="inline">
        <dt><label for="oldpassword">Reservation Charge <span class="required">*</span></label></dt>
        <dd> <input type="text" id="reservationcharge" name="reservationcharge" class="validate[required,custom[number],maxSize[5]]" size="50" value="<?php echo $settings['0']['reservationCharge'];?>"  /> %</dd>
        <dt><label for="name">Booking Percentage <span class="required">*</span></label></dt>
        <dd><input type="text" id="bookingpercentage" name="bookingpercentage" class="validate[required,custom[number],maxSize[5]]"  size="50" value="<?php echo $settings['0']['bookingPercentage'];?>"   /> %</dd>
        <dt><label for="name">Booking Confirmation Days <span class="required">*</span></label></dt>
        <dd><input type="text" id="bookingconfirmationdays" name="bookingconfirmationdays" class="validate[required,custom[number],maxSize[5]]"  size="50" value="<?php echo $settings['0']['bookingConfirmationDays'];?>"   /> %</dd>
        <div class="buttons" ><input type="submit" name="save" id="submit" class="button" value="Save"/></div>
      </dl>
    </fieldset>
    </form>
     </div>
</div>

