<?php $view->extend('::admin.html.php');?>
<div id="content"  class="clearfix">
  <div class="container">
    <div align="right" style="padding-right:50px;"></div>
    <form action="<?php echo $view['router']->generate('mytrip_admin_changepassword') ?>" id="myForm" method="post">
    <fieldset>
      <legend>Change Password</legend>
      <dl class="inline">
        <dt><label for="oldpassword">Old Password <span class="required">*</span></label></dt>
        <dd> <input type="password" id="oldpassword" name="oldpassword" class="validate[required,minSize[6]]" size="50"  /></dd>
        <dt><label for="name">New Password <span class="required">*</span></label></dt>
        <dd><input type="password" id="newpassword" name="newpassword" class="validate[required,minSize[6]]"  size="50"  /></dd>
        <dt><label for="name">Confirm Password <span class="required">*</span></label></dt>
        <dd><input type="password" id="confirmpassword" name="confirmpassword" class="validate[required,equals[newpassword]]]]"  size="50"  /></dd>
        <div class="buttons" ><input type="submit" name="save" id="submit" class="button" value="Change Password"/></div>
      </dl>
    </fieldset>
    </form>
     </div>
</div>

