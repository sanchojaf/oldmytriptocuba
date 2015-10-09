<?php $view->extend('::admin.html.php');
$bucketurl=$this->container->get('mytrip_admin.helper.amazon')->getOption('url');
$hostalinfo=$hostal['0'];
?>
<div id="content"  class="clearfix">
  <div class="container">
     <div align="right" style="padding: 10px 10px 0px;"><a href="<?php echo $view['router']->generate('mytrip_admin_hostal');?>" class="button">Back to Hostal</a></div>
    <form action="" id="myForm" method="post" enctype="multipart/form-data">
    <fieldset>
      <legend>Edit Hostal</legend>
      <dl class="inline">
        <dt><label for="name">Language <span class="required">*</span></label></dt>
        <dd><select name="lan" id="lan">
        <?php
		foreach($language as $lans){			
			echo '<option value="'.$lans['lanCode'].'" '.($_REQUEST['lan']==$lans['lanCode']?'selected="selected"':'').'>'.$lans['language'].'</option>';
		}
		?>
        </select></dd>       
        <dt><label for="name">Hostal Name <span class="required">*</span></label></dt>
        <dd><input type="text" id="name" name="name" class="validate[required]"  size="50" value="<?php echo $hostal_content['0']['name'];?>"  /></dd>
        <?php if($_REQUEST['lan']=='en'){?>
        <dt><label for="name">Destination <span class="required">*</span></label></dt>
        <dd><select name="destination" id="destination" class="validate[required]">
        <option value="">[-Select Destination-]</option>
        <?php
		foreach($destination as $destinations){
			echo '<option value="'.$destinations['destinationId'].'" '.($destinations['destinationId']==$hostalinfo['destination']?'selected="selected"':'').' >'.$destinations['name'].'</option>';
		}
		?>
        </select></dd> 
        <?php }?> 
        <dt><label for="name">Small Description <span class="required">*</span></label></dt>
        <dd><textarea  id="smalldescription" name="smalldescription"  rows="5" cols="60" class="validate[required,maxSize[220]]" ><?php echo $hostal_content['0']['smallDesc'];?></textarea></dd> 
        <dt></dt><dd><p><strong>(Maximum 220 Characters)</strong></p></dd>    
        <dt><label for="name">Description <span class="required">*</span></label></dt>
        <dd><textarea  id="description" name="description"  rows="5" cols="60" class="validate[required,maxSize[525]]" ><?php echo $hostal_content['0']['description'];?></textarea></dd>
        <dt></dt><dd><p><strong>(Maximum 525 Characters)</strong></p></dd>   
        <dt><label for="name">Location Description <span class="required">*</span></label></dt>
        <dd><textarea  id="location_desc" name="location_desc"  rows="5" cols="60" class="validate[required,maxSize[160]]" ><?php echo $hostal_content['0']['locationDesc'];?></textarea></dd>
        <dt></dt><dd><p><strong>(Maximum 160 Characters)</strong></p></dd>   
        <dt><label for="name">Address <span class="required">*</span></label></dt>
        <dd><table width="70%"><tr><td><textarea  id="address" name="address"  rows="5" cols="60" class="validate[required]" ><?php echo $hostal_content['0']['address'];?></textarea></td><td>&nbsp;&nbsp;</td><td>Address Format : <br/><strong>"1600 Amphitheatre Pkwy, Mountain View, CA 94043"</strong></td></tr></table></dd>
        <?php if($_REQUEST['lan']=='en'){?>
        <dt><label for="name">Video <span class="required">*</span></label></dt>
        <dd><textarea  id="video" name="svdesc"  rows="5" cols="60" class="validate[required]" ><?php echo $hostalinfo['0']['video'];?></textarea></dd>
        <dt><label></label></dt><dd><strong>(E.g - Youtube URL : https://www.youtube.com/watch?v=TmE-_XbuyTM )</strong></dd> 
        <!--<dd><table width="70%"><tr><td><textarea  id="video" name="svdesc"  rows="5" cols="60" class="validate[required]" ><?php echo $hostalinfo['0']['video'];?></textarea></td><td>&nbsp;&nbsp;</td><td><strong>(Youtube iframe video source <br/>E.g: "&lt;iframe width="420" height="315" src="//www.youtube.com/embed/O6L0bXjsQzY" frameborder="0" allowfullscreen&gt;&lt;/iframe&gt;")</strong></td></tr></table></dd>-->
         <dt><label for="name">Tripadvisor Widget</label></dt>
        <dd><table width="70%"><tr><td><textarea  id="tripadvisor" name="tripadvisor"  rows="5" cols="60" ><?php echo $hostalinfo['0']['tripadvisor'];?></textarea></td></tr>
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
			echo '<td><input type="checkbox" name="feature[]" id="feature'.$i.'" value="'.$feature['featureId'].'" class="validate[minCheckbox[1]] checkbox"  '.(in_array(array('feature'=>$feature['featureId']),$hostal_feature)?'checked="checked"':'').' /> '.$feature['feature'].' </td>';
			$i++;
			if($i%3==0){
				echo '</tr><tr>';
			}
		}
		?>
        </tr></table>
        </dd>
        <?php }?>
        <dt><label for="name">Owner Name <span class="required">*</span></label></dt>
        <dd><input type="text" id="ownername" name="ownername" class="validate[required]"  size="50" value="<?php echo $hostal_content['0']['ownerName'];?>"  /></dd>
        <?php if($_REQUEST['lan']=='en'){?>
        <dt><label for="name">Owner Email <span class="required">*</span></label></dt>
        <dd><input type="text" id="owneremail" name="owneremail" class="validate[required,custom[email]]"  size="50" value="<?php echo $hostalinfo['0']['ownerEmail'];?>" /></dd>
        <dt><label for="name">Phone No. <span class="required">*</span></label></dt>
        <dd><input type="text" id="cccode" name="cccode" class="validate[required,custom[integer]]" size="5" value="<?php echo $hostalinfo['0']['cccode'];?>" />&nbsp;<input type="text" id="phone" name="phone" class="validate[required,custom[integer]]"  size="39" value="<?php echo $hostalinfo['0']['phone'];?>" /></dd>
        <dt><label for="name">Mobile No. <span class="required">*</span></label></dt>
        <dd><input type="text" id="cmcode" name="cmcode" class="validate[required,custom[integer]]" size="5" value="<?php echo $hostalinfo['0']['cmcode'];?>" />&nbsp;<input type="text" id="mobile" name="mobile" class="validate[required,custom[integer]]"  size="39" value="<?php echo $hostalinfo['0']['mobile'];?>" /></dd>

        <dt><label for="name">Hostal Image <span class="required">*</span></label></dt>
        <dd><input type="file" id="image" name="image" class="validate[custom[image]]" /></dd> 
        <dt><label></label></dt><dd><strong>(Upload image size 673 x 369 size, Accept image types are png,jpg,jpeg)</strong></dd> 
         <dt><label for="name"></label></dt>
        <dd><img src="<?php echo $bucketurl.$hostal_image['0']['image'];?>" width="225"/></dd> 
        <?php }?>      
        <dt><label for="name">Meta Title <span class="required">*</span> </label></dt>
        <dd><textarea  id="metatitle" name="metatitle"  rows="5" cols="60" class="validate[required]" ><?php echo $hostal_content['0']['metaTitle'];?></textarea></dd>
        <dt><label for="name">Meta Description </label></dt>
        <dd><textarea  id="metadescription" name="metadescription"  rows="5" cols="60"><?php echo $hostal_content['0']['metaDescription'];?></textarea></dd>
        <dt><label for="name">Meta Keyword </label></dt>
        <dd><textarea  id="metakeyword" name="metakeyword"  rows="5" cols="60"><?php echo $hostal_content['0']['metaKeyword'];?></textarea></dd>       
        <div class="buttons"><input type="submit" name="save" id="submit" class="button" value="Save"/></div>
      </dl>
    </fieldset>
    </form>
    <form action="<?php echo $view['router']->generate('mytrip_admin_editroom');?>" method="post">
    <fieldset>
      <legend>Edit Hostal Rooms</legend>
      <dl class="inline">
        <dt><label for="name">Select room. <span class="required">*</span></label></dt>
        <dd>
            <select name="roomid" id="roomid">
                <option value="-1" selected="selected">[-Add new room-]</option>
                    <?php
                        $counter = 1;
                        foreach($hostal_rooms as $room){            
                            echo '<option value='.$room['roomId'].'>Room '.$counter++.'</option>';
                        }
                    ?>
            </select>
        </dd>
        <dt><label for="name">Room Type. <span class="required">*</span></label></dt>
        <dd><input type="text" id="roomtype" name="roomtype" class="validate[required]"  size="50"/></dd>
        <dt><label for="name">Guests per Room. <span class="required">*</span></label></dt>
        <dd><input type="text" id="guests" name="guests" class="validate[required,custom[integer]]"  size="50"/></dd>
        <dt><label for="name">Adults per Room. <span class="required">*</span></label></dt>
        <dd><input type="text" id="adults" name="adults" class="validate[required,custom[integer]]"  size="50"/></dd>
        <dt><label for="name">Children per Room. <span class="required">*</span></label></dt>
        <dd><input type="text" id="child" name="child" class="validate[required,custom[integer]]"  size="50"/></dd>
        <dt><label for="name">Price. <span class="required">*</span></label></dt>
        <dd><input type="text" id="price" name="price" class="validate[required,custom[number]]"  size="50"/>&nbsp;CAD</dd>        
        <div class="buttons">
            <input type="hidden" name="hostal" value="<?php echo $hostalinfo[0]['hostalId']; ?>"/>
            <input type="submit" name="save" id="submit" class="button" value="Save"/>
            <input type="submit" name="delete" id="delete" class="button" value="Delete"/>
        </div>
       </dl> 
    </fieldset>
    </form>
    </div>
</div>

<script type="text/javascript">
$('#lan').change(function(){
	$('.helpfade').show();
	$('.helptips').show();
	window.location="edithostal?id=<?php echo $_REQUEST['id'];?>&lan="+$(this).val();
});

var hostal_rooms = [];

<?php
    foreach($hostal_rooms as $room){            
        echo "hostal_rooms['".$room['roomId']."'] = [];\n";
        echo "hostal_rooms['".$room['roomId']."']['roomtype'] = '".$room['roomtype']."';\n";
        echo "hostal_rooms['".$room['roomId']."']['guests'] = '".$room['guests']."';\n";
        echo "hostal_rooms['".$room['roomId']."']['adults'] = '".$room['adults']."';\n";
        echo "hostal_rooms['".$room['roomId']."']['child'] = '".$room['child']."';\n";
        echo "hostal_rooms['".$room['roomId']."']['price'] = '".$room['price']."';\n\n";
    }
?>


$('#roomid').change(function() {
   var room_id = $('#roomid option:selected').val();
   if(room_id != -1) {
        $('#roomtype').val(hostal_rooms[room_id]['roomtype']);
        $('#guests').val(hostal_rooms[room_id]['guests']);
        $('#adults').val(hostal_rooms[room_id]['adults']);
        $('#child').val(hostal_rooms[room_id]['child']);
        $('#price').val(hostal_rooms[room_id]['price']);

        $('#delete').show();
   }
   else {
        $('#roomtype').val("");
        $('#guests').val("");
        $('#adults').val("");
        $('#child').val("");
        $('#price').val("");

        $('#delete').hide();
   }
   
})
.change();

</script>