<?php $view->extend('::admin.html.php');?>
<div class="helpfade"></div>
<div class="helptips"><div class="loader_block"><div class="loader_block_inner"></div><div class="loader_text">Please wait...</div></div></div>
<div class="dismsg" id="msginfo"></div>
	<div id="mainContainer"> 	
		   <div id="content"  class="clearfix">
  <div class="container">
      <div align="right" style="padding: 10px 10px 0px;">
    <a class="button" href="<?php echo $view['router']->generate('mytrip_admin_list_destination');?>">Back to Destination</a>
    </div>
    <div align="right" style="padding-right:50px;"></div>
    <form name="form" method="post" action="" id="myForm" enctype="multipart/form-data">    <fieldset>
      <legend>Add Destination</legend>
      <dl class="inline">      
        <input type="hidden" name="lan" id="lan" value="en" />        
        <dt><label for="name">Name <span class="required">*</span></label></dt>
        <dd><input type="text" id="name" name="name" class="validate[required]" value="<?php if(isset($var['name'])) echo $var['name']; ?>" /></dd>       
        
        
        <dt><label for="name">Video Url<span class="required">*</span></label></dt>
        <dd><input type="text" id="video" name="video" class="validate[required,custom[url]]" value="<?php if(isset($var['video'])) echo $var['video']; ?>" /></dd> 
               
        <!-- <dt><label for="name">Latitude <span class="required">*</span></label></dt>
        <dd><input type="text" id="latitude" name="latitude" class="validate[required]" value="<?php if(isset($var['latitude'])) echo $var['latitude']; ?>" /></dd>
        
         <dt><label for="name">Langitute <span class="required">*</span></label></dt>
        <dd><input type="text" id="langitude" name="langitude" class="validate[required]" value="<?php if(isset($var['langitude'])) echo $var['langitude']; ?>" /></dd>
        -->
        <dt><label for="name">Description<span class="required">*</span></label></dt>
        <dd><textarea id="desc" name="desc" class="validate[required]" ><?php if(isset($var['desc'])) echo $var['desc']; ?></textarea></dd> 
               
        <dt><label for="name">Location Description<span class="required">*</span></label></dt>
        <dd><textarea id="longdesc" name="longdesc" class="validate[required]" ><?php if(isset($var['longdesc'])) echo $var['longdesc']; ?></textarea></dd> 
               
        <dt><label for="name">address<span class="required">*</span></label></dt>
        <dd><input type="text" id="address" name="address" class="validate[required]" value="<?php if(isset($var['address'])) echo $var['address']; ?>" /></dd>
                
        <dt><label for="name">Country<span class="required">*</span></label></dt>
        <dd>
        <select id="country" name="country" class="validate[required]" onchange="return getprovince(this.value)">
        <option value="">Select</option>
        <?php for($i=0;$i<count($Country);$i++) { ?>
        <option value="<?php echo $Country[$i]['cid']; ?>" <?php if(isset($var['country'])) if($var['country']==$Country[$i]['cid']) echo "selected"; ?>><?php echo $Country[$i]['country']; ?></option>
        <?php } ?>
        </select>
        
        </dd>
        
        <dt><label for="name">Province<span class="required">*</span></label></dt>
        <dd>
        <div name="provincediv" id="provincediv">
           <select id="province" name="province" class="validate[required]" >  
           <option value="">Select</option>     
           </select>
           </div>
        </dd>
        
       <dt><label for="name">City<span class="required">*</span></label></dt>
        <dd>
        <input type="text" name="city" id="city" class="validate[required]">
        </dd>
        
        <dt><label for="name">Status<span class="required">*</span></label></dt>
        <dd><select name="status" id="status" >
        <option value="Active" <?php if(isset($var['staus'])) if($var['staus']=="Active") echo "selected"; ?>>Active</option>
        <option value="Inactive" <?php  if(isset($var['staus']))  if($var['staus']=="Inactive") echo "selected"; ?>>Inactive</option>
         </select></dd>
        
        <dt><label for="name">Image<span class="required">*</span></label></dt>
        <dd><input type="file" id="image" name="image" class="validate[required,custom[image]]" value="" /></dd>
        
        <div class="buttons" ><button type="submit" id="form_save" name="form[save]" class="button gray">Save</button></div>
      </dl>
    </fieldset>
   </form> </div>
</div>		
	</div>
</body>
<script type="text/javascript">
function getprovince(val)
{
	xmlHttp = new XMLHttpRequest();
    xmlHttp.open( "GET", "getstate?id="+val, false );
    xmlHttp.send( null );
   document.getElementById('provincediv').innerHTML=xmlHttp.responseText;
}
function getcity(val)
{
	xmlHttp = new XMLHttpRequest();
    xmlHttp.open( "GET", "getcity?id="+val, false );
    xmlHttp.send( null );
   document.getElementById('citydiv').innerHTML=xmlHttp.responseText;
}

</script>