<?php $view->extend('::admin.html.php');?>
<div id="content"  class="clearfix">
  <div class="container">
     <div align="right" style="padding: 10px 10px 0px;"><a href="<?php echo $view['router']->generate('mytrip_admin_features');?>" class="button">Back to Feature</a></div>
    <form action="" id="myForm" method="post" enctype="multipart/form-data">
    <fieldset>
      <legend>Add Feature</legend>
      <dl class="inline">       
        <dt><label for="name">Feature Name<span class="required">*</span></label></dt>
        <dd><input type="text" id="feature" name="feature" class="validate[required]"  size="50" value="<?php echo (isset($_REQUEST['feature'])?$_REQUEST['feature']:'');?>"  /></dd>
        <dt><label for="name">Icon </label></dt>
        <dd><input type="file" id="icon" name="icon" class="validate[custom[image]]" /></dd>
        <dt><label for="name"></label></dt>
        <dd><strong>(Upload Image size 24 x 20)</strong></dd>
        <div class="buttons" ><input type="submit" name="save" id="submit" class="button" value="Save"/></div>
      </dl>
    </fieldset>
    </form>
     </div>
</div>

