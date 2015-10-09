<?php $view->extend('::admin.html.php');
?>
<div id="content"  class="clearfix">
  <div class="container">
    <div align="right" style="padding-right:50px;"></div>
    <form action="<?php echo $view['router']->generate('mytrip_admin_payment_settings') ?>" id="myForm" method="post">
    <fieldset>
      <legend>Payment Settings</legend>
      <dl class="inline">
        <dt><label for="paymentsetting">Payment Setting <span class="required">*</span></label></dt>
        <dd> <input type="radio" id="globalone" name="apikey" class="validate[required]" value="<?php echo $api['0']['apiId'];?>" <?php echo $api[0]['status']=="Active"?'checked="checked"':'';?>  /> Global One  <input type="radio" id="beanstream" name="apikey" class="validate[required]" value="<?php echo $api['1']['apiId'];?>"  <?php echo $api[1]['status']=="Active"?'checked="checked"':'';?> /> Bean Stream</dd>
       
        <div class="buttons" ><input type="submit" name="save" id="submit" class="button" value="Save"/></div>
      </dl>
    </fieldset>
    </form>
     </div>
</div>

