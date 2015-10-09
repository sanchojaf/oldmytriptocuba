<?php $view->extend('::admin.html.php');
$bucketurl=$this->container->get('mytrip_admin.helper.amazon')->getOption('url');
?>
<script src="<?php echo $view['assets']->getUrl('js/ckeditor/ckeditor.js');?>" type="text/javascript"></script>
<div id="content"  class="clearfix">
  <div class="container">
     <div align="right" style="padding: 10px 10px 0px;"><a href="<?php echo $view['router']->generate('mytrip_admin_story');?>" class="button">Back to Story</a></div>
    <form action="" id="myForm" method="post" enctype="multipart/form-data">
    <fieldset>
      <legend>Edit Story</legend>
      <dl class="inline">  
        <dt><label for="name">Language <span class="required">*</span></label></dt>
        <dd><select name="lan" id="lan">
        <?php
		foreach($language as $lans){			
			echo '<option value="'.$lans['lanCode'].'" '.($_REQUEST['lan']==$lans['lanCode']?'selected="selected"':'').'>'.$lans['language'].'</option>';
		}
		?>
        </select></dd>
        <?php if($_REQUEST['lan']=='en'){?> 
        <dt><label for="name">Destination <span class="required">*</span></label></dt>
        <dd><select name="destination" id="destination" class="validate[required]">
        <option value="">[-Select Destination-]</option>
        <?php
		foreach($destination as $destinations){
			echo '<option value="'.$destinations['destinationId'].'" '.($destinations['destinationId']==$story[0]['destination']?'selected="selected"':'').' >'.$destinations['name'].'</option>';
		}
		?>
        </select></dd> 
        <dt><label for="name">Hostal <span class="required">*</span></label></dt>
        <dd><select name="hostal" id="hostal" class="validate[required]">
        <option value="">[-Select Hostal-]</option> 
        <?php
		foreach($hostal as $hostals){
			echo '<option value="'.$hostals['hostalId'].'" '.($hostals['hostalId']==$story[0]['hostal']?'selected="selected"':'').' >'.$hostals['name'].'</option>';
		}
		?>       
        </select></dd>  
        <?php }?>    
        <dt><label for="name">Story Name <span class="required">*</span></label></dt>
        <dd><input type="text" id="name" name="name" class="validate[required,maxSize[50]]"  size="50" value="<?php echo $story_content[0]['name'];?>"  /></dd>         
        <dt><label for="name">Story Phrase <span class="required">*</span></label></dt>
        <dd><input type="text" id="subhead" name="subhead" class="validate[required,maxSize[50]]"  size="50" value="<?php echo $story_content[0]['subHead'];?>"  /></dd>            
        <dt><label for="name">Content <span class="required">*</span></label></dt>
        <dd><textarea  id="content" name="content"  rows="5" cols="60" class="validate[required] ckeditor" ><?php echo $story_content[0]['content'];?></textarea></dd> 
        <?php if($_REQUEST['lan']=='en'){?>             
        <dt><label for="name">Story Image </label></dt>
        <dd><input type="file" id="image" name="image" class="validate[custom[image]]" /></dd> 
        <dt><label></label></dt><dd><strong>(Upload image size 354 x 319 size, Accept image types are png,jpg,jpeg)</strong></dd> 
        <?php if(!empty($story_image)){?>
         <dt><label for="name"></label></dt>
        <dd><img src="<?php echo $bucketurl.$story_image['0']['image'];?>" width="225"/></dd> 
        <?php }?>
        <?php }?>       
        <dt><label for="name">Meta Title <span class="required">*</span> </label></dt>
        <dd><textarea  id="metatitle" name="metatitle"  rows="5" cols="60" class="validate[required]" ><?php echo $story_content[0]['metaTitle'];?></textarea></dd>
        <dt><label for="name">Meta Description </label></dt>
        <dd><textarea  id="metadescription" name="metadescription"  rows="5" cols="60"><?php echo $story_content[0]['metaDescription'];?></textarea></dd>
        <dt><label for="name">Meta Keyword </label></dt>
        <dd><textarea  id="metakeyword" name="metakeyword"  rows="5" cols="60"><?php echo $story_content[0]['metaKeyword'];?></textarea></dd>       
        <div class="buttons"><input type="submit" name="save" id="submit" class="button" value="Save"/></div>
      </dl>
    </fieldset>
    </form>
     </div>
</div>
<script type="text/javascript">
$('#destination').change(function(){
	var id=$(this).val();
	$.ajax({
		type: "POST",
		url: "gethostal",
		data: "did="+id,
		success: function(msg){ 
			$('#hostal').html(msg);
			$('#hostal').prev('span').html('[-Select Hostal-]');							
		}
	});
});

$('#lan').change(function(){
	$('.helpfade').show();
	$('.helptips').show();
	window.location="editstory?id=<?php echo $_REQUEST['id'];?>&lan="+$(this).val();
});
</script>
