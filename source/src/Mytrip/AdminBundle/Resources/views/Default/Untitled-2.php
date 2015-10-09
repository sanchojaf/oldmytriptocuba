<?php $view->extend('::admin.html.php');?>
<div class="helpfade"></div>
<div class="helptips"><div class="loader_block"><div class="loader_block_inner"></div><div class="loader_text">Please wait...</div></div></div>
<div class="dismsg" id="msginfo"></div>
	<div id="mainContainer"> 	
		   <div id="content"  class="clearfix">
  <div class="container">
    <div align="right" style="padding-right:50px;"></div>
    <form name="form" method="post" action="" id="myForm">    <fieldset>
      <legend>Add Hostal</legend>
      <dl class="inline">
        <dt><label for="name">Name <span class="required">*</span></label></dt>
        <dd><input type="text" id="name" name="name" class="validate[required]" value="" /></dd>
        
        <dt><label for="name">Phone <span class="required">*</span></label></dt>
        <dd><input type="text" id="phone" name="phone" class="validate[required,custom[number]]" value="" /></dd>
        
         <dt><label for="name">Mobile <span class="required">*</span></label></dt>
        <dd><input type="text" id="mobile" name="mobile" class="validate[required,custom[number]]" value="" /></dd>
        
        <dt><label for="name">Video Url<span class="required">*</span></label></dt>
        <dd><input type="text" id="video" name="video" class="validate[required,custom[url]]" value="" /></dd> 
               
         <dt><label for="name">Latitude <span class="required">*</span></label></dt>
        <dd><input type="text" id="latitude" name="latitude" class="validate[required]" value="" /></dd>
        
         <dt><label for="name">Langitute <span class="required">*</span></label></dt>
        <dd><input type="text" id="langitude" name="langitude" class="validate[required]" value="" /></dd>
        
        <dt><label for="name">Rooms Available<span class="required">*</span></label></dt>
        <dd><input type="text" id="rooms" name="rooms" class="validate[required,custom[number]]" value="" /></dd> 
               
        <dt><label for="name">Guest<span class="required">*</span></label></dt>
        <dd><input type="text" id="guest" name="guest" class="validate[required,custom[number]]" value="" /></dd> 
               
        <dt><label for="name">Adults<span class="required">*</span></label></dt>
        <dd><input type="text" id="adult" name="adult" class="validate[required,custom[number]]" value="" /></dd>
        
        <dt><label for="name">Children<span class="required">*</span></label></dt>
        <dd><input type="text" id="child" name="child" class="validate[required,custom[number]]" value="" /></dd>
        
        <dt><label for="name">Price<span class="required">*</span></label></dt>
        <dd><input type="text" id="price" name="price" class="validate[required,custom[number]]" value="" /></dd>
        <dt><label for="name">Status<span class="required">*</span></label></dt>
        <dd><select name="status" id="status" >
        <option value="Active" <?php if(isset($var['staus'])) if($var['staus']=="Active") echo "selected"; ?>>Active</option>
        <option value="Inactive" <?php  if(isset($var['staus']))  if($var['staus']=="Inactive") echo "selected"; ?>>Inactive</option>
         </select></dd>
        
        <dt><label for="name">Image<span class="required">*</span></label></dt>
        <dd><input type="file" id="image" name="image" class="validate[required,custom[image]]" value="" /></dd>
        
        <div class="buttons" ><button type="submit" id="form_save" name="form[save]" class="button gray">Login</button></div>
      </dl>
    </fieldset>
    <input type="hidden" id="form__token" name="form[_token]" value="3jZB04nVdi8rozfQpoljFEvLsevWeSiOEtcICo6GLPA" /></form> </div>
</div>		
	</div>
</body>
