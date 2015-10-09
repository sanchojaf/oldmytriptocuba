<?php $view->extend('::admin.html.php');?>
<div class="helpfade"></div>
<div class="helptips"><div class="loader_block"><div class="loader_block_inner"></div><div class="loader_text">Please wait...</div></div></div>
<div class="dismsg" id="msginfo"></div>
	<div id="mainContainer"> 	
		   <div id="content"  class="clearfix">
  <div class="container">
    <div align="right" style="padding-right:50px;"></div>
    <form name="form" method="post" action="" id="myForm" enctype="multipart/form-data">    <fieldset>
      <legend>Add Features</legend>
      <dl class="inline">
      
        <dt><label for="name">Language<span class="required">*</span></label></dt>
        <dd><select name="lan" id="lan" >
        <?php  // for($i=0;$i<count($languages);$i++) { ?>
        <option value="en">English</option>
        <?php // } ?>
        </select></dd>
        
        <dt><label for="name">Feature <span class="required">*</span></label></dt>
        <dd><input type="text" id="feature" name="feature" class="validate[required]" value="<?php if(isset($var['feature'])) echo $var['feature']; ?>" /></dd>               
        
        <dt><label for="name">Icon<span class="required">*</span></label></dt>
        <dd><input type="file" id="icon" name="icon" class="validate[required,custom[image]]" value="" /></dd>
        
        <div class="buttons" ><button type="submit" id="form_save" name="form[save]" class="button gray">Add</button></div>
      </dl>
    </fieldset>
   </form> </div>
</div>		
	</div>
</body>
