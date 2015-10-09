<option value="">[-Select Hostal-]</option>
<?php foreach($hostal as $hostals){?>
<option value="<?php echo $hostals['hostalId']; ?>"><?php echo $hostals['name']; ?></option>
<?php } ?> 
