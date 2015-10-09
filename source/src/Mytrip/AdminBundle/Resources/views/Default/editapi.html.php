<?php $view->extend('::admin.html.php');?>
<div id="content"  class="clearfix">
  <div class="container">
     <div align="right" style="padding: 10px 10px 0px;"><a href="<?php echo $view['router']->generate('mytrip_admin_api');?>" class="button">Back to API List</a></div>
    <form action="" id="myForm" method="post">
    <fieldset>
      <legend>Edit <?php echo $api['0']['gateway'];?> API </legend>
      <dl class="inline">       
        <dt><label for="name">API Gateway <span class="required">*</span></label></dt>
        <dd><p><?php echo $api['0']['gateway'];?></p></dd>
        <?php
		foreach($apiinfo as $apiinfos){			
         	echo '<dt><label for="name">'.ucwords(str_replace("_"," ",$apiinfos['metaKey'])).' <span class="required">*</span></label></dt>';
			if($apiinfos['metaKey']=="Paypal_mode"){
				echo '<input name="'.$apiinfos['metaKey'].'" type="radio" id="paypal" value="Sandbox" '.($apiinfos['metaValue']=='Sandbox'?'checked="checked"':'').' />&nbsp;Sandbox&nbsp;&nbsp;&nbsp;&nbsp;
        			  <input name="'.$apiinfos['metaKey'].'" type="radio" id="paypals" value="Live" '.($apiinfos['metaValue']=='Live'?'checked="checked"':'').' />&nbsp;Live';
			}else{
				echo '<dd><input type="text" id="'.$apiinfos['metaKey'].'" name="'.$apiinfos['metaKey'].'" class="validate[required]"  size="50" value="'.$apiinfos['metaValue'].'" /></dd>';
			}
			
        	
		}
		?>        
        <div class="buttons" ><input type="submit" name="save" id="submit" class="button" value="Save"/></div>
      </dl>
    </fieldset>
    </form>
     </div>
</div>

