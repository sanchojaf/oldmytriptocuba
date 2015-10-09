<?php $view->extend('::admin.html.php');?>
<script src="<?php echo $view['assets']->getUrl('js/ckeditor/ckeditor.js');?>" type="text/javascript"></script>
<div id="content"  class="clearfix">
  <div class="container">
     <div align="right" style="padding: 10px 10px 0px;"><a href="<?php echo $view['router']->generate('mytrip_admin_emailcontent');?>" class="button">Back to Social Links</a></div>
    <form action="" id="myForm" method="post" enctype="multipart/form-data">
    <fieldset>
      <legend>Add Email Content</legend>
      <dl class="inline">       
        <dt><label for="name">Title<span class="required">*</span></label></dt>
        <dd><input type="text" id="title" name="title" class="validate[required]"  size="50" value=""  /></dd>
        <dt><label for="name">Label<span class="required">*</span></label></dt>
        <dd><textarea id="label" name="label" class="validate[required]" rows="5" cols="60"></textarea></dd>
        <dt><label for="name">From Name<span class="required">*</span></label></dt>
        <dd><input type="text" id="fromname" name="fromname" class="validate[required]"  size="50" value=""  /></dd>
        <dt><label for="name">From Address<span class="required">*</span></label></dt>
        <dd><input type="text" id="fromemail" name="fromemail" class="validate[required,custom[email]]"  size="50" value=""  /></dd>
        <dt><label for="name">To Address</label></dt>
        <dd><input type="text" id="tomail" name="tomail" class="validate[custom[email]]"  size="50" value=""  /></dd>
        <dt><label for="name">CC Address</label></dt>
        <dd><textarea id="ccmail" name="ccmail" class="" rows="5" cols="60"></textarea></dd>        
        <dt><label>&nbsp;</label></dt><dd><strong>(Multiple Email id separate with ",")</strong></dd>
        <dt><label for="name">Subject<span class="required">*</span></label></dt>
        <dd><input type="text" id="subject" name="subject" class="validate[required]"  size="50" value=""  /></dd>
        <dt><label for="name">Email Content<span class="required">*</span></label></dt>
        <dd><textarea id="emailcontent" name="emailcontent" class="ckeditor" rows="5" cols="60"></textarea></dd>        
        <div class="buttons"><input type="submit" name="save" id="submit" class="button" value="Save"/></div>
      </dl>
    </fieldset>
    </form>
     </div>
</div>

