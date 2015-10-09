<?php $view->extend('::admin.html.php');?>
<div id="content"  class="clearfix">
  <div class="container">
     <div align="right" style="padding: 10px 10px 0px;"><a href="<?php echo $view['router']->generate('mytrip_admin_api');?>" class="button">Back to API List</a></div>
    <form action="" id="myForm" method="post">
    <fieldset>
      <legend>Add API</legend>
      <dl class="inline">       
        <dt><label for="name">API Gateway <span class="required">*</span></label></dt>
        <dd><select name="apikey" id="apikey" class="validate[required]">
        <?php
		foreach($api as $apis){
			echo '<option value="'.$apis['apiId'].'">'.$apis['gateway'].'</option>';
		}
		?>
        </select></dd>
        <dt><label for="name">API Key <span class="required">*</span></label></dt>
        <dd><input type="text" id="metakey" name="metakey" class="validate[required]"  size="50"  /></dd>
        <dt><label for="name">API Key Value <span class="required">*</span></label></dt>
        <dd><input type="text" id="metavalue" name="metavalue" class="validate[required]"  size="50"  /></dd>
        <div class="buttons" ><input type="submit" name="save" id="submit" class="button" value="Save"/></div>
      </dl>
    </fieldset>
    </form>
     </div>
</div>

