<?php $view->extend('::admin.html.php');?>
<div id="content"  class="clearfix">
  <div class="container">
    <div align="right" style="padding-right:50px;"></div>
   <form action="" method="post">
    <fieldset>
      <legend>Api Status</legend>
      <dl class="inline">
        <dt><label for="name"><?php echo $apilist[0]['gatway']; ?> <span class="required">*</span></label></dt>
        <dd><select name="<?php echo $apilist[0]['apiId']; ?>">
        <option value="Active" <?php if($apilist[0]['status']=="Active") echo "selected"; ?>>Active</option>
        <option value="Inactive"  <?php if($apilist[0]['status']=="Inactive") echo "selected"; ?>>Inactive</option>
        </select></dd>
            <dt><label for="name"><?php echo $apilist[1]['gatway'];  ?> <span class="required">*</span></label></dt>
        <dd><select name="<?php echo $apilist[1]['apiId'] ?>">
        <option value="Active" <?php if($apilist[1]['status']=="Active") echo "selected";?>>Active</option>
        <option value="Inactive"  <?php if($apilist[1]['status']=="Inactive") echo "selected"; ?>>Inactive</option>
        </select></dd>
           <dt><label for="name"><?php echo $apilist[2]['gatway']; ?> <span class="required">*</span></label></dt>
        <dd><select name="<?php echo $apilist[2]['apiId'] ?>">
        <option value="Active" <?php if($apilist[2]['status']=="Active") echo "selected"; ?>>Active</option>
        <option value="Inactive"  <?php if($apilist[2]['status']=="Inactive") echo "selected"; ?>>Inactive</option>
        </select></dd>
        <div class="buttons">
         <button id="form_save" class="button gray" name="save" type="submit">update</button></div>
        </dl>        
    </fieldset>
    
    </form>    
</div>
