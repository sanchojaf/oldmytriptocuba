<?php $view->extend('::admin.html.php');
$bucketurl=$this->container->get('mytrip_admin.helper.amazon')->getOption('url');
if(!empty($destination)){
	$name="Destination";
	$title=$destination[0]['name'];
	$folder=$type="destination";
}elseif(!empty($hostal)){
	$name="Hostal";
	$title=$hostal[0]['name'];
	$folder=$type="hostal";
}else{
	$name="Story";
	$title=$story[0]['name'];
	$folder=$type="story";
}

?>
<div id="content" class="clearfix"> 
    <div class="mainheading">   
        <div class="btnlink"><a href="<?php echo $view['router']->generate('mytrip_admin_'.$type);?>" class="button">Back to <?php echo $name;?></a></div> 		
        <div class="titletag"><h1><?php echo $name;?> Banner</h1></div>
    </div>
  <form id="myForm" class="uniform" method="post" enctype="multipart/form-data">
    <fieldset>
      <legend><?php echo $name?> - Banner</legend>
      <dl class="inline" >
        <dt><label for="name"><?php echo $name;?> Name </label></dt>
        <dd><p><?php echo $title;?></p></dd>
        <dt><label for="name">Upload image <span class="required">*</span></label></dt>
        <dd><input name="image" type="file"  id="image"  class="validate[required,custom[image]]"/></dd>
        <dt></dt>
        <dd><strong>(Please Upload Image size 1457 x 491)</strong></dd>
      </dl>
      <div class="buttons" >
        <input type="submit" class="button" id="submit_btn" value="Save" />
        <?php if(isset($this->params['pass'][0])){
				echo $html->link('Cancel',array('action'=>'banner'),array('class'=>'button white'));
			}
		?>
      </div>
    </fieldset>
  </form>
  <form id="myForm1" class="uniform" method="post" enctype="multipart/form-data">
    <fieldset style="padding:20px 0;">
      <legend>Banner</legend>
      <!--<label style="margin-left:20px">
        <input type="checkbox" id="checkAllAuto" name="action[]" value="0" class="validate[minCheckbox[1]] checkbox" />
        Select All</label>-->
      <div style="float:left; width:100%;">
        <ul id="gallery" style="padding:10px 0;">
          <?php if(!empty($banner)) {
					foreach($banner as $banners){?>
          <li style="position:relative;margin:15px 8px;min-height:110px;width:160px;"><img src="<?php echo $bucketurl.$banners['image'];?><?php //echo $view['assets']->getUrl('img/banner/'.$folder.'/').$banners['image'];?>" width="150" class="img"/>  <br />
            <div style="margin-top:10px;">
              <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                 <!-- <td align="left"><input type="checkbox" name="action[]" value="<?php echo $banners['bannerId']; ?>" rel="action" /></td>-->
                  <td align="center"><a href="<?php echo $view['router']->generate('mytrip_admin_deletebanner',array('id'=>$banners['bannerId'],'type'=>$type));?>" class="confirdel"><img src="<?php echo $view['assets']->getUrl('img/icons/cross.png');?>"/></a></td>
                </tr>
              </table>
            </div>
          </li>
          <?php }} else{?>
          <li style="width:100%;text-align:center;color:#F00;" > NO IMAGE FOUND</li>
          <?php }?>
        </ul>
      </div>
      <div class="tablefooter clearfix">
        <!--<div class="actions">
          <input type="submit" id="action_btn" class="button" value="Delete" onclick="return confirm('Are you sure you want to delete this image?')" />
        </div>-->
      </div>
    </fieldset>
  </form>
</div>

