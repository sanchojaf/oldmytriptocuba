<?php $view->extend('::admin.html.php');
$bucketurl=$this->container->get('mytrip_admin.helper.amazon')->getOption('url');
?>
<?php $destination=$destination[0];?>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tbody><tr>
<!--<td width="230" valign="top" align="right" class="sidepromenu">
<ul class="projectmenu">
	<li <?php echo (in_array($this->container->get('request')->get('_route'),array('mytrip_admin_editdestination'))?'class="active"':'') ?>><a href="<?php echo $view['router']->generate('mytrip_admin_editdestination',array('id'=>$_REQUEST['id'],'lan'=>$_REQUEST['lan']));?>">Destination details</a></li>
    <li <?php echo (in_array($this->container->get('request')->get('_route'),array('mytrip_admin_destinationhostals'))?'class="active"':'') ?>><a href="<?php echo $view['router']->generate('mytrip_admin_destinationhostals',array('id'=>$_REQUEST['id'],'lan'=>$_REQUEST['lan']));?>">Destination Hostals</a></li>
    <li <?php echo (in_array($this->container->get('request')->get('_route'),array('mytrip_admin_destinationcomments'))?'class="active"':'') ?>><a href="<?php echo $view['router']->generate('mytrip_admin_destinationcomments',array('id'=>$_REQUEST['id'],'lan'=>$_REQUEST['lan']));?>">Destination Comments</a></li>
</ul></td>-->
<td valign="top"><div id="content"  class="clearfix"><div class="container">
     <div align="right" style="padding: 10px 10px 0px;"><a href="<?php echo $view['router']->generate('mytrip_admin_destination');?>" class="button">Back to Destination</a></div>
    <form action="" id="myForm" method="post" enctype="multipart/form-data">
    <fieldset>
      <legend>Edit Destination</legend>
      <dl class="inline"> 
        <dt><label for="name">Language <span class="required">*</span></label></dt>
        <dd><select name="lan" id="lan">
        <?php
		foreach($language as $lans){			
			echo '<option value="'.$lans['lanCode'].'" '.($_REQUEST['lan']==$lans['lanCode']?'selected="selected"':'').'>'.$lans['language'].'</option>';
		}
		?>
        </select></dd>      
        <dt><label for="name">Destination Name <span class="required">*</span></label></dt>
        <dd><input type="text" id="name" name="name" class="validate[required]"  size="50" value="<?php echo $destination_content[0]['name'];?>"  /></dd>
        <dt><label for="name">Country <span class="required">*</span></label></dt>
        <dd>
        <?php if($_REQUEST['lan']=='en'){?>
        <select name="country" id="country" class="validate[required]">
        <option value="">[-Select Country-]</option>
        <?php
		foreach($country as $countrys){
			echo '<option value="'.$countrys['cid'].'" '.($destination['country']==$countrys['cid']?'selected="selected"':'').'>'.$countrys['country'].'</option>';
		}
		?>
        </select>
        <?php }else{?>
        <input type="text" id="country" name="country" class="validate[required]"  size="50" value="<?php echo $destination_content[0]['country'];?>"  />
        <?php }?>
        </dd>
        <dt><label for="name">Province <span class="required">*</span></label></dt>
        <dd>
        <?php if($_REQUEST['lan']=='en'){?>
        <select name="province" id="province" class="validate[required]">
        <option value="">[-Select Province-]</option>
        <?php
		foreach($state as $states){
			echo '<option value="'.$states['sid'].'" '.($destination['province']==$states['sid']?'selected="selected"':'').'>'.$states['state'].'</option>';
		}
		?>
        </select>
        <?php }else{?>
        <input type="text" id="province" name="province" class="validate[required]"  size="50" value="<?php echo $destination_content[0]['province'];?>"  />
        <?php }?></dd>
        <dt><label for="name">Description <span class="required">*</span></label></dt>
        <dd><textarea  id="description" name="description"  rows="5" cols="60" class="validate[required]" ><?php echo $destination_content['0']['description'];?></textarea></dd>
        <dt><label for="name">Location Description <span class="required">*</span></label></dt>
        <dd><textarea  id="location_desc" name="location_desc"  rows="5" cols="60" class="validate[required]" ><?php echo $destination_content['0']['locationDesc'];?></textarea></dd>
        <!--<dt><label for="name">Address <span class="required">*</span></label></dt>
        <dd><table width="70%"><tr><td><textarea  id="address" name="address"  rows="5" cols="60" class="validate[required]" ></textarea></td><td>&nbsp;&nbsp;</td><td>Address Format : <br/><strong>"1600 Amphitheatre Pkwy, Mountain View, CA 94043, USA"</strong></td></tr></table></dd>-->
        <?php if($_REQUEST['lan']=='en'){?>
        <dt><label for="name">Video <span class="required">*</span></label></dt>
        <dd><textarea  id="video" name="svdesc"  rows="5" cols="60" class="validate[required]" ><?php echo $destination['0']['video'];?></textarea></dd>
        <dt><label></label></dt><dd><strong>(E.g - Youtube URL : https://www.youtube.com/watch?v=TmE-_XbuyTM )</strong></dd> 
       <!-- <dd><table width="70%"><tr><td><textarea  id="video" name="svdesc"  rows="5" cols="60" class="validate[required]" ><?php echo $destination['0']['video'];?></textarea></td><td>&nbsp;&nbsp;</td><td><strong>(Youtube iframe video source <br/>E.g: "&lt;iframe width="420" height="315" src="//www.youtube.com/embed/O6L0bXjsQzY" frameborder="0" allowfullscreen&gt;&lt;/iframe&gt;")</strong></td></tr></table></dd>
        <dt><label></label></dt><dd><strong>(Please enter the youtube url)</strong></dd> -->
         <dt><label for="name">Tripadvisor Widget</label></dt>
        <dd><table width="70%"><tr><td><textarea  id="tripadvisor" name="tripadvisor"  rows="5" cols="60" ><?php echo $destination['0']['tripadvisor'];?></textarea></td></tr>
        <tr><td><strong>(Tripadvisor script source <br/>E.g: "<?php echo htmlentities('<div id="TA_cdsdmo204" class="TA_cdsdmo">
<ul id="o2FJBk" class="TA_links ScVKm8UH14CN">
<li id="jeyphNIU" class="1acmjX">
<a target="_blank" href="http://www.tripadvisor.com/"><img src="http://www.tripadvisor.com/img/cdsi/partner/tripadvisor_logo_146x22-11324-2.gif" alt="TripAdvisor"/></a>
</li>
</ul>
</div>
<script src="http://www.jscache.com/wejs?wtype=cdsdmo&amp;uniq=204&amp;locationId=677686&amp;lang=en_US&amp;photo=true&amp;hotel=y&amp;attraction=y&amp;restaurant=y&amp;display_version=2"></script>');?>
")</strong></td></tr></table></dd>
        <dt><label for="name">Features <span class="required">*</span></label></dt>
        <dd><table><tr><?php
				
		$i=0;		
		foreach($feature as $feature){
			echo '<td><input type="checkbox" name="feature[]" id="feature'.$i.'" value="'.$feature['featureId'].'" class="validate[minCheckbox[1]] checkbox" '.(in_array(array('feature'=>$feature['featureId']),$destination_feature)?'checked="checked"':'').' /> '.$feature['feature'].' </td>';
			$i++;
			if($i%3==0){
				echo '</tr><tr>';
			}
		}
		?>
        </tr></table>
        </dd>
        <dt><label for="name">Destination Image <span class="required">*</span></label></dt>
        <dd><input type="file" id="image" name="image" class="validate[custom[image]]" /></dd> 
        <dt><label></label></dt><dd><strong>(Upload image size 673 x 369 size, Accept image types are png,jpg,jpeg)</strong></dd>  
        <?php if(!empty($destination_image)){?>       
        <dt><label for="name"></label></dt>
        <dd><img src="<?php echo $bucketurl.$destination_image['0']['image'];?>" width="225"/></dd> 
        <?php }?> 
        <?php }?>     
        <dt><label for="name">Meta Title <span class="required">*</span> </label></dt>
        <dd><textarea  id="metatitle" name="metatitle"  rows="5" cols="60" class="validate[required]" ><?php echo $destination_content['0']['metaTitle'];?></textarea></dd>
        <dt><label for="name">Meta Description </label></dt>
        <dd><textarea  id="metadescription" name="metadescription"  rows="5" cols="60"><?php echo $destination_content['0']['metaDescription'];?></textarea></dd>
        <dt><label for="name">Meta Keyword </label></dt>
        <dd><textarea  id="metakeyword" name="metakeyword"  rows="5" cols="60"><?php echo $destination_content['0']['metaKeyword'];?></textarea></dd>       
        <div class="buttons"><input type="submit" name="save" id="submit" class="button" value="Save"/></div>
      </dl>
    </fieldset>
    </form>
     </div></div></td></tr></tbody></table>

<script type="text/javascript">
$('#country').change(function(){
	var id=$(this).val();
	$.ajax({
		type: "POST",
		url: "getstate",
		data: "sid="+id,
		success: function(msg){ 
			$('#province').html(msg);
			$('#province').prev('span').html('[-Select Province-]');							
		}
	});
});

$('#lan').change(function(){
	$('.helpfade').show();
	$('.helptips').show();
	window.location="editdestination?id=<?php echo $_REQUEST['id'];?>&lan="+$(this).val();
});
</script>
