<?php $view->extend('::admin.html.php');
$em = $this->container->get('doctrine')->getManager();
$province=$em->createQuery("SELECT p FROM MytripAdminBundle:States p WHERE p.sid='".$user[0]['province']."'")->getArrayResult();;
$country=$em->createQuery("SELECT p FROM MytripAdminBundle:Country p WHERE p.cid='".$user[0]['country']."'")->getArrayResult();;
?>
<div id="content"  class="clearfix">
  <div class="container">
    <div align="right" style="padding: 10px 10px 0px;"><a href="<?php echo $view['router']->generate('mytrip_admin_users');?>" class="button">Back to Users</a></div>
    <form action="<?php echo $view['router']->generate('mytrip_admin_viewusers',array('id'=>$_REQUEST['id'])) ?>" id="myForm" method="post">
    <fieldset>
      <legend>User</legend>
      <dl class="inline">
        <dt><label>First Name</label></dt>
        <dd><p><?php echo $user[0]['firstname'];?></p></dd>
        <dt><label>Last Name</label></dt>
        <dd><p><?php echo $user[0]['lastname'];?></p></dd>
        <dt><label>Email</label></dt>
        <dd><p><?php echo $user[0]['email'];?></p></dd>       
        <dt><label>Date of Birth</label></dt>
        <dd><p><?php echo $user[0]['dob']!=''?$user[0]['dob']:'-';?></p></dd>
        <dt><label>Gender</label></dt>
        <dd><p><?php echo $user[0]['gender']!=''?$user[0]['gender']:'-';?></p></dd>
        <dt><label>Phone</label></dt>
        <dd><p><?php echo $user[0]['phone']!=''?$user[0]['phone']:'-';?></p></dd> 
        <dt><label>Mobile</label></dt>
        <dd><p><?php echo $user[0]['mobile']!=''?$user[0]['mobile']:'-';?></p></dd> 
        <dt><label>Address</label></dt>
        <dd><p><?php echo $user[0]['address']!=''?$user[0]['address']:'-';?></p></dd> 
        <dt><label>Address1</label></dt>
        <dd><p><?php echo $user[0]['address2']!=''?$user[0]['address2']:'-';?></p></dd> 
        <dt><label>City</label></dt>
        <dd><p><?php echo $user[0]['city']!=''?$user[0]['city']:'-';?></p></dd> 
        <dt><label>Province</label></dt>
        <dd><p><?php if(!empty($province)){echo $province[0]['state'];}else{echo '-';}?></p></dd> 
        <dt><label>Country</label></dt>
        <dd><p><?php if(!empty($country)){echo $country[0]['country'];}else{echo '-';}?></p></dd> 
        <dt><label>Zip</label></dt>
        <dd><p><?php echo $user[0]['zip'];?></p></dd> 
        <dt><label>Image</label></dt>
        <dd><p><img src="<?php echo $view['assets']->getUrl('img/'.($user[0]['image']!=''?'user/'.$user[0]['image']:'bigprofileimage.jpg')); ?>" width="100"/></p></dd> 
        <dt><label>Status</label></dt>
        <dd><select name="status" id="status" class="validate[required]">
        <option value="">[--Select--]</option>
        <option value="Active" <?php echo $user[0]['status']=="Active"?'selected="selected"':'';?> >Active</option>
        <option value="Inactive" <?php echo $user[0]['status']=="Inactive"?'selected="selected"':'';?> >Inactive</option>
        </select></dd> 
        <div class="buttons" ><input type="submit" name="save" id="submit" class="button" value="Save"/></div>
      </dl>
    </fieldset>
    </form>
     </div>
</div>

