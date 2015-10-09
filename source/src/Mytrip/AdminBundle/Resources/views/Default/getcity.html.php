<div name="citydiv" id="citydiv">
 <select id="city" name="city" class="validate[required]" >      
         <option value="">Select</option>
  <?php for($i=0;$i<count($city);$i++) { ?>
        <option value="<?php echo $city[$i]['cityId']; ?>"><?php echo $city[$i]['city']; ?></option>
        <?php } ?> 
</select>
</div>