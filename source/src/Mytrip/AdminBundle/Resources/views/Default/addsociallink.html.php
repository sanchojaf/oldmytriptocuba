<?php $view->extend('::admin.html.php');?>
<div id="content"  class="clearfix">
  <div class="container">
     <div align="right" style="padding: 10px 10px 0px;"><a href="<?php echo $view['router']->generate('mytrip_admin_sociallinks');?>" class="button">Back to Social Links</a></div>
    <form action="" id="myForm" method="post" enctype="multipart/form-data">
    <fieldset>
      <legend>Add Social Link</legend>
      <dl class="inline">       
        <dt><label for="name">Website<span class="required">*</span></label></dt>
        <dd><input type="text" id="site" name="site" class="validate[required]"  size="50" value="<?php echo (isset($_REQUEST['site'])?$_REQUEST['site']:'');?>"  /></dd>
        <dt><label for="name">Link<span class="required">*</span></label></dt>
        <dd><textarea id="link" name="link" class="validate[required]" rows="5" cols="60"><?php echo (isset($_REQUEST['link'])?$_REQUEST['link']:'');?></textarea></dd>
        <div class="buttons" ><input type="submit" name="save" id="submit" class="button" value="Save"/></div>
      </dl>
    </fieldset>
    </form>
     </div>
</div>

