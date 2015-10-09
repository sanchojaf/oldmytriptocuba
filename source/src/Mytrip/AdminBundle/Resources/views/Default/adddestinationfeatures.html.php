<?php $view->extend('::admin.html.php');?>
<div class="helpfade"></div>
<div class="helptips"><div class="loader_block"><div class="loader_block_inner"></div><div class="loader_text">Please wait...</div></div></div>
<div class="dismsg" id="msginfo"></div>
	<div id="mainContainer"> 	
		   <div id="content"  class="clearfix">
  <div class="container">
    <div align="right" style="padding-right:50px;"></div>
    <form name="form" method="post" action="" id="myForm" enctype="multipart/form-data">    <fieldset>
      <legend>Add Hostal</legend>
      <dl class="inline">
      
        <dt><label for="name">Language<span class="required">*</span></label></dt>
        <dd><select name="lan" id="lan" >
        <?php  // for($i=0;$i<count($languages);$i++) { ?>
        <option value="en">English</option>
        <?php // } ?>
        </select></dd>
        
        <dt><label for="name">Name <span class="required">*</span></label></dt>
        <dd><input type="text" id="name" name="name" class="validate[required]" value="" /></dd>       
        
        
        <dt><label for="name">Video Url<span class="required">*</span></label></dt>
        <dd><input type="text" id="video" name="video" class="validate[required,custom[url]]" value="" /></dd> 
               
         <dt><label for="name">Latitude <span class="required">*</span></label></dt>
        <dd><input type="text" id="latitude" name="latitude" class="validate[required]" value="" /></dd>
        
         <dt><label for="name">Langitute <span class="required">*</span></label></dt>
        <dd><input type="text" id="langitude" name="langitude" class="validate[required]" value="" /></dd>
        
        <dt><label for="name">Description<span class="required">*</span></label></dt>
        <dd><input type="text" id="desc" name="desc" class="validate[required]" value="" /></dd> 
               
        <dt><label for="name">Long Description<span class="required">*</span></label></dt>
        <dd><input type="text" id="longdesc" name="longdesc" class="validate[required]" value="" /></dd> 
               
        <dt><label for="name">address<span class="required">*</span></label></dt>
        <dd><input type="text" id="address" name="address" class="validate[required]" value="" /></dd>
        
        <dt><label for="name">City<span class="required">*</span></label></dt>
        <dd><input type="text" id="city" name="city" class="validate[required]" value="" /></dd>
        
        <dt><label for="name">Province<span class="required">*</span></label></dt>
        <dd><input type="text" id="province" name="province" class="validate[required]" value="" /></dd>
        
        <dt><label for="name">Country<span class="required">*</span></label></dt>
        <dd><input type="text" id="country" name="country" class="validate[required]" value="" /></dd>
        
        <dt><label for="name">Status<span class="required">*</span></label></dt>
        <dd><select name="status" id="status" >
        <option value="Active" <?php if(isset($var['staus'])) if($var['staus']=="Active") echo "selected"; ?>>Active</option>
        <option value="Inactive" <?php  if(isset($var['staus']))  if($var['staus']=="Inactive") echo "selected"; ?>>Inactive</option>
         </select></dd>
        
        <dt><label for="name">Image<span class="required">*</span></label></dt>
        <dd><input type="file" id="image" name="image" class="validate[required,custom[image]]" value="" /></dd>
        
        <div class="buttons" ><button type="submit" id="form_save" name="form[save]" class="button gray">Add</button></div>
      </dl>
    </fieldset>
   </form> </div>
</div>		
	</div>
</body>
