<?php $view->extend('::admin.html.php');?>
<div id="content"  class="clearfix">
  <div class="container">
     <div align="right" style="padding: 10px 10px 0px;"><a href="<?php echo $view['router']->generate('mytrip_admin_hostal');?>" class="button">Back to Hostal</a></div>
    <form action="" id="myForm" method="post" enctype="multipart/form-data">
    <fieldset>
      <legend>Add Hostal</legend>
      <dl class="inline">       
        <dt><label for="name">Hostal Name <span class="required">*</span></label></dt>
        <dd><input type="text" id="name" name="name" class="validate[required]"  size="50"  /></dd>
        <dt><label for="name">Destination <span class="required">*</span></label></dt>
        <dd><select name="destination" id="destination" class="validate[required]">
        <option value="">[-Select Destination-]</option>
        <?php
		foreach($destination as $destinations){
			echo '<option value="'.$destinations['destinationId'].'">'.$destinations['name'].'</option>';
		}
		?>
        </select></dd>  
        <dt><label for="name">Small Description <span class="required">*</span></label></dt>
        <dd><textarea  id="smalldescription" name="smalldescription"  rows="5" cols="60" class="validate[required,maxSize[220]]" ></textarea></dd> 
        <dt></dt><dd><p><strong>(Maximum 220 Characters)</strong></p></dd>    
        <dt><label for="name">Description <span class="required">*</span></label></dt>
        <dd><textarea  id="description" name="description"  rows="5" cols="60" class="validate[required,maxSize[525]]" ></textarea></dd>
        <dt></dt><dd><p><strong>(Maximum 525 Characters)</strong></p></dd>   
        <dt><label for="name">Location Description <span class="required">*</span></label></dt>
        <dd><textarea  id="location_desc" name="location_desc"  rows="5" cols="60" class="validate[required,maxSize[160]]" ></textarea></dd>
        <dt></dt><dd><p><strong>(Maximum 160 Characters)</strong></p></dd>   
        <dt><label for="name">Address <span class="required">*</span></label></dt>
        <dd><table width="70%"><tr><td><textarea  id="address" name="address"  rows="5" cols="60" class="validate[required]" ></textarea></td><td>&nbsp;&nbsp;</td><td>Address Format : <br/><strong>"1600 Amphitheatre Pkwy, Mountain View, CA 94043"</strong></td></tr></table></dd>
        <dt><label for="name">Video <span class="required">*</span></label></dt>
        <dd><textarea  id="video" name="svdesc"  rows="5" cols="60" class="validate[required]" ></textarea></dd>
       <!-- <dd><table width="70%"><tr><td><textarea  id="video" name="svdesc"  rows="5" cols="60" class="validate[required]" ></textarea></td><td>&nbsp;&nbsp;</td><td><strong>(Youtube iframe video source <br/>E.g: "&lt;iframe width="420" height="315" src="//www.youtube.com/embed/O6L0bXjsQzY" frameborder="0" allowfullscreen&gt;&lt;/iframe&gt;")</strong></td></tr></table></dd>-->
        <dt><label></label></dt><dd><strong>(E.g - Youtube URL : https://www.youtube.com/watch?v=TmE-_XbuyTM )</strong></dd> 
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
        <dt><label for="name">Owner Name <span class="required">*</span></label></dt>
        <dd><input type="text" id="ownername" name="ownername" class="validate[required]"  size="50"  /></dd>
        <dt><label for="name">Owner Email <span class="required">*</span></label></dt>
        <dd><input type="text" id="owneremail" name="owneremail" class="validate[required,custom[email]]"  size="50"  /></dd>
         <dt><label for="name">Phone No. <span class="required">*</span></label></dt>
        <dd><input type="text" id="cccode" name="cccode" class="validate[required,custom[integer]]" size="5"  />&nbsp;<input type="text" id="phone" name="phone" class="validate[required,custom[integer]]"  size="39"/></dd>
        <dt><label for="name">Mobile No. <span class="required">*</span></label></dt>
        <dd><input type="text" id="cmcode" name="cmcode" class="validate[required,custom[integer]]" size="5" />&nbsp;<input type="text" id="mobile" name="mobile" class="validate[required,custom[integer]]"  size="39" /></dd>
       <!-- <dt><label for="name">Phone No. <span class="required">*</span></label></dt>
        <dd><input type="text" id="phone" name="phone" class="validate[required]"  size="50"  /></dd>
        <dt><label for="name">Mobile No. <span class="required">*</span></label></dt>
        <dd><input type="text" id="mobile" name="mobile" class="validate[required]"  size="50"  /></dd>-->
        <!-- <dt><label for="name">Room Type. <span class="required">*</span></label></dt>
        <dd><input type="text" id="roomtype" name="roomtype" class="validate[required]"  size="50"  /></dd>
        <dt><label for="name">Total Rooms. <span class="required">*</span></label></dt>
        <dd><input type="text" id="rooms" name="rooms" class="validate[required,custom[integer]]"  size="50"  /></dd>
        <dt><label for="name">Guest per Room. <span class="required">*</span></label></dt>
        <dd><input type="text" id="guests" name="guests" class="validate[required,custom[integer]]"  size="50"  /></dd>
        <dt><label for="name">Adults per Room. <span class="required">*</span></label></dt>
        <dd><input type="text" id="adults" name="adults" class="validate[required,custom[integer]]"  size="50"  /></dd>
        <dt><label for="name">Child per Room. <span class="required">*</span></label></dt>
        <dd><input type="text" id="child" name="child" class="validate[required,custom[integer]]"  size="50"  /></dd>
         <dt><label for="name">Price. <span class="required">*</span></label></dt>
        <dd><input type="text" id="price" name="price" class="validate[required,custom[number]]"  size="50"  />&nbsp;CAD</dd>   -->      
        <dt><label for="name">Hostal Image <span class="required">*</span></label></dt>
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
     </div>
</div>

