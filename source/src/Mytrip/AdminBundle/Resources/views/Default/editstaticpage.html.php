<?php $view->extend('::admin.html.php');
$em = $this->container->get('doctrine')->getManager();
$mainmenu= $em->createQuery("SELECT p FROM MytripAdminBundle:Staticpage p WHERE p.mainMenu= 'Yes'")->getArrayResult();
?>
<script src="<?php echo $view['assets']->getUrl('js/ckeditor/ckeditor.js');?>" type="text/javascript"></script>
<div id="content"  class="clearfix">
  <div class="container">
     <div align="right" style="padding: 10px 10px 0px;"><a href="<?php echo $view['router']->generate('mytrip_admin_staticpage');?>" class="button">Back to Content Page</a></div>
    <form action="" id="myForm" method="post">
    <fieldset>
      <legend>Edit Content page</legend>
      <dl class="inline">  
        <dt><label for="name">Language <span class="required">*</span></label></dt>
        <dd><select name="lan" id="lan">
        <?php
		foreach($language as $lans){			
			echo '<option value="'.$lans['lanCode'].'" '.($_REQUEST['lan']==$lans['lanCode']?'selected="selected"':'').'>'.$lans['language'].'</option>';
		}
		?>
        </select></dd> 
        <?php if($staticpage[0]['staticpageId'] > 23){?>
        <dt><label for="name">Main Menu <span class="required">*</span></label></dt>
        <dd><select id="mainmenu" name="mainmenu" class="validate[required]">
        <?php
		foreach($mainmenu as $mainmenu){
			echo '<option value="'.$mainmenu['staticpageId'].'" '.($staticpage[0]['menuId']==$mainmenu['staticpageId']?'selected="selected"':'').'>'.$mainmenu['pagename'].'</option>';
		}
		?>
        </select></dd>  
        <?php }?>    
        <dt><label for="name">Page Name <span class="required">*</span></label></dt>
        <dd><input type="text" id="pagename" name="pagename" class="validate[required]" size="50" value="<?php echo (!empty($static_content[0]['name'])?$static_content[0]['name']:'');?>"  /></dd>
        <?php if($staticpage[0]['seo']=="Yes"){?>
        <dt><label for="name">Page Title </label></dt>
        <dd><textarea  id="pagetitle" name="pagetitle"  rows="5" cols="60" class="validate[required]"><?php echo (!empty($static_content[0]['pageTitle'])?$static_content[0]['pageTitle']:'');?></textarea></dd>
        <dt><label for="name">Meta Description </label></dt>
        <dd><textarea  id="metadescription" name="metadescription"  rows="5" cols="60"><?php echo (!empty($static_content[0]['metaDescription'])?$static_content[0]['metaDescription']:'');?></textarea></dd>
        <dt><label for="name">Meta Keyword </label></dt>
        <dd><textarea  id="metakeyword" name="metakeyword"  rows="5" cols="60"><?php echo (!empty($static_content[0]['metaKeyword'])?$static_content[0]['metaKeyword']:'');?></textarea></dd>
        <?php }?>
         <?php if($staticpage[0]['content']=="Yes"){?>
        <dt><label for="name">Page Content <span class="required">*</span></label></dt>
        <dd><div style="width:710px"><textarea  id="pagecontent" name="pagecontent" class="validate[required] ckeditor"  rows="5" cols="60"><?php echo (!empty($static_content[0]['content'])?$static_content[0]['content']:'');?></textarea></div></dd>
        <?php }?>
         <?php if(!in_array($staticpage[0]['staticpageId'],array(1,7,9,17,18,20,21))){?>
        <dt><label for="name">Status <span class="required">*</span></label></dt>
        <dd><select name="status" id="status">
       <option value="Active" <?php echo $staticpage[0]['status']=='Active'?'selected="selected"':'';?>>Active</option>
       <option value="Inactive" <?php echo $staticpage[0]['status']=='Inactive'?'selected="selected"':'';?>>Inactive</option>
        </select></dd>  
        <?php }?>  
        <div class="buttons" ><input type="submit" name="save" id="submit" class="button" value="Save"/></div>
      </dl>
    </fieldset>
    </form>
     </div>
</div>

<script type="text/javascript">
$('#lan').change(function(){
	$('.helpfade').show();
	$('.helptips').show();
	window.location="editstaticpage?id=<?php echo $_REQUEST['id'];?>&lan="+$(this).val();
});
</script>

