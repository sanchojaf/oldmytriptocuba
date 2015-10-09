<?php $view->extend('::admin.html.php');?>
<div id="content"  class="clearfix">
  <div class="container">
     <div align="right" style="padding: 10px 10px 0px;"><a href="<?php echo $view['router']->generate('mytrip_admin_destination');?>" class="button">Back to Destination</a></div>
    <form action="" id="myForm" method="post" enctype="multipart/form-data">
    <fieldset>
      <legend>Add Destination</legend>
      <dl class="inline">       
        <dt><label for="name">Destination Name <span class="required">*</span></label></dt>
        <dd><input type="text" id="name" name="name" class="validate[required]"  size="50"  /></dd>
        <dt><label for="name">Country <span class="required">*</span></label></dt>
        <dd><select name="country" id="country" class="validate[required]">
        <option value="">[-Select Country-]</option>
        <?php
		foreach($country as $country){
			echo '<option value="'.$country['cid'].'">'.$country['country'].'</option>';
		}
		?>
        </select></dd>
        <dt><label for="name">Province <span class="required">*</span></label></dt>
        <dd><select name="province" id="province" class="validate[required]">
        <option value="">[-Select Province-]</option>
        </select></dd>
        <dt><label for="name">Description <span class="required">*</span></label></dt>
        <dd><textarea  id="description" name="description"  rows="5" cols="60" class="validate[required]" ></textarea></dd>
        <dt><label for="name">Location Description <span class="required">*</span></label></dt>
        <dd><textarea  id="location_desc" name="location_desc"  rows="5" cols="60" class="validate[required]" ></textarea></dd>
        <!--<dt><label for="name">Address <span class="required">*</span></label></dt>
        <dd><table width="70%"><tr><td><textarea  id="address" name="address"  rows="5" cols="60" class="validate[required]" ></textarea></td><td>&nbsp;&nbsp;</td><td>Address Format : <br/><strong>"1600 Amphitheatre Pkwy, Mountain View, CA 94043, USA"</strong></td></tr></table></dd>-->
        <dt><label for="name">Video <span class="required">*</span></label></dt>
       <dd> <textarea  id="video" name="svdesc"  rows="5" cols="60" class="validate[required]" ></textarea></dd>
       <dt><label></label></dt><dd><strong>(E.g - Youtube URL : https://www.youtube.com/watch?v=TmE-_XbuyTM )</strong></dd>       
       <!-- <dd><table width="70%"><tr><td><textarea  id="video" name="svdesc"  rows="5" cols="60" class="validate[required]" ></textarea></td><td>&nbsp;&nbsp;</td><td><strong>(Youtube iframe video source <br/>E.g: "&lt;iframe width="420" height="315" src="//www.youtube.com/embed/O6L0bXjsQzY" frameborder="0" allowfullscreen&gt;&lt;/iframe&gt;")</strong></td></tr></table></dd>-->
         <dt><label for="name">Tripadvisor Widget</label></dt>
        <dd><table width="70%"><tr><td><textarea  id="tripadvisor" name="tripadvisor"  rows="5" cols="60" ></textarea></td></tr>
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
			echo '<td><input type="checkbox" name="feature[]" id="feature'.$i.'" value="'.$feature['featureId'].'" class="validate[minCheckbox[1]] checkbox"/> '.$feature['feature'].' </td>';
			$i++;
			if($i%3==0){
				echo '</tr><tr>';
			}
		}
		?>
        </tr></table>
        </dd>
        <dt><label for="name">Destination Image <span class="required">*</span></label></dt>
        <dd><input type="file" id="image" name="image" class="validate[required,custom[image]]" /></dd> 
        <dt><label></label></dt><dd><strong>(Upload image size 673 x 369 size, Accept image types are png,jpg,jpeg)</strong></dd>       
        <dt><label for="name">Meta Title <span class="required">*</span> </label></dt>
        <dd><textarea  id="metatitle" name="metatitle"  rows="5" cols="60" class="validate[required]" ></textarea></dd>
        <dt><label for="name">Meta Description </label></dt>
        <dd><textarea  id="metadescription" name="metadescription"  rows="5" cols="60"></textarea></dd>
        <dt><label for="name">Meta Keyword </label></dt>
        <dd><textarea  id="metakeyword" name="metakeyword"  rows="5" cols="60"></textarea></dd>       
        <div class="buttons"><input type="submit" name="save" id="submit" class="button" value="Save"/></div>
      </dl>
    </fieldset>
    </form>
     </div>
</div>
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
</script>
