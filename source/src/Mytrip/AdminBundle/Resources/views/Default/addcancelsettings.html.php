<?php $view->extend('::admin.html.php');?>
<div id="content"  class="clearfix">
  <div class="container">
    <div align="right" style="padding: 10px 10px 0px;"><a href="<?php echo $view['router']->generate('mytrip_admin_cancel_settings');?>" class="button">Back to Cancel Settings</a></div>
    <form action="" id="myForm" method="post">
    <fieldset>
      <legend>Add Cancel settings</legend>
      <dl class="inline">
        <dt><label for="oldpassword">Days <span class="required">*</span></label></dt>
        <dd> <input type="text" id="days" name="days" class="validate[required,maxSize[2],custom[integer]]" size="50" maxlength="2" value="<?php if(isset($_REQUEST['days'])){ echo $_REQUEST['days'];} ?>" /></dd>
        <dt><label for="name">Percentage <span class="required">*</span></label></dt>
        <dd><input type="text" id="percentage" name="percentage" class="validate[required,maxSize[5],custom[integer]]"  size="50" maxlength="5" value="<?php if(isset($_REQUEST['percentage'])){ echo $_REQUEST['percentage'];} ?>"   /> %</dd>        
        <div class="buttons" ><input type="submit" name="save" id="submit" class="button" value="Save"/></div>
      </dl>
    </fieldset>
    </form>
     </div>
</div>

