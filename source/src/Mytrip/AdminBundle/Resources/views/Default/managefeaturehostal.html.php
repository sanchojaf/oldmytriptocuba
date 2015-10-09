<?php $view->extend('::admin.html.php');?>
<div id="content"  class="clearfix">
  <div class="container">    
  <div align="right" style="padding: 10px 10px 0px;">
    <a class="button" href="<?php echo $view['router']->generate('mytrip_admin_list_hostal');?>">Back to Hostal</a>
    </div>
  <form action="" method="post">
    <fieldset>
      <legend>Mange Feature</legend>
      <dl class="inline">
       <dt><label for="name">Hostal <span class="required"></span></label></dt>
        <dd><?php echo $hostal[0]['name'] ?>
        <input type="hidden" name="hostal" id="hostal" value="<?php  echo $hostal[0]['hostalId'];  ?>" /></dd>
         <dt><label for="name">Features<span class="required"></span></label></dt>
          <dd>
      <?php for($i=0;$i<count($feature);$i++) { ?>
     <input type="checkbox" name="id[]" value="<?php echo $feature[$i]['featureId']; ?>" <?php for($k=0;$k<count($selectedhostal);$k++) {   if($feature[$i]['featureId']==$selectedhostal[$k][1]) { echo "checked";}  } ?>><?php echo $feature[$i]['feature']; ?></br>
      
      <?php } 
	  
	 	  ?></dd>
          <div class="buttons">
<input id="submit" class="button" type="submit" value="Save" name="save">
</div>
	  
      </dl>
      
    </fieldset>
    </form>
  </div>
</div>
