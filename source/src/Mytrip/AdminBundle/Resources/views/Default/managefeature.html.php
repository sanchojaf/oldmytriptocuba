<?php $view->extend('::admin.html.php');?>
<div id="content"  class="clearfix">
  <div class="container">    
   <div align="right" style="padding: 10px 10px 0px;">
    <a class="button" href="<?php echo $view['router']->generate('mytrip_admin_list_destination');?>">Back to Destination</a>
    </div>
  <form action="" method="post">
    <fieldset>
      <legend>Mange Feature</legend>
      <dl class="inline">
       <dt><label for="name">Destination <span class="required"></span></label></dt>
        <dd><?php echo $destination[0]['name'] ?>
        <input type="hidden" name="destination" id="destination" value="<?php  echo $destination[0]['destinationId'];  ?>" /></dd>
         <dt><label for="name">Features<span class="required"></span></label></dt>
          <dd>
      <?php for($i=0;$i<count($feature);$i++) { ?>
     <input type="checkbox" name="id[]" value="<?php echo $feature[$i]['featureId']; ?>" <?php for($k=0;$k<count($selectedfeature);$k++) {   if($feature[$i]['featureId']==$selectedfeature[$k][1]) { echo "checked";}  } ?>><?php echo $feature[$i]['feature']; ?></br>
      
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
