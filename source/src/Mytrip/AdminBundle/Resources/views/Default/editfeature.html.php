<?php $view->extend('::admin.html.php');?>
<div id="content"  class="clearfix">
  <div class="container">
     <div align="right" style="padding: 10px 10px 0px;"><a href="<?php echo $view['router']->generate('mytrip_admin_features');?>" class="button">Back to Feature</a></div>
    <form action="" id="myForm" method="post" enctype="multipart/form-data">
    <fieldset>
      <legend>Edit Feature</legend>
      <dl class="inline">  
        <dt><label for="name">Language <span class="required">*</span></label></dt>
        <dd><select name="lan" id="lan">
        <?php
		foreach($language as $lans){			
			echo '<option value="'.$lans['lanCode'].'" '.($_REQUEST['lan']==$lans['lanCode']?'selected="selected"':'').'>'.$lans['language'].'</option>';
		}
		?>
        </select></dd>      
        <dt><label for="name">Feature Name<span class="required">*</span></label></dt>
        <dd><input type="text" id="feature" name="feature" class="validate[required]"  size="50" value="<?php echo $feature_content['0']['feature'];?>"  /></dd>
        <dt><label for="name">Icon </label></dt>
        <dd><input type="file" id="icon" name="icon" class="validate[custom[image]]" /></dd>
        <dt><label for="name"></label></dt>
        <dd><strong>(Upload Image size 24 x 20)</strong></dd>
        <?php if($feature['0']['icon']!=''){?>
        <dt><label for="name"></label></dt>
        <dd><?php		
			echo '<img src="'.$view['assets']->getUrl('img/feature_icon/').$feature['0']['icon'].'" width="20"/>';		
        ?></dd>
        <?php } ?>
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
	window.location="editfeature?id=<?php echo $_REQUEST['id'];?>&lan="+$(this).val();
});
</script>
