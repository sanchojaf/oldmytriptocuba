<?php $view->extend('::admin.html.php');
$em = $this->container->get('doctrine')->getManager();
$user=$em->createQuery("SELECT p FROM MytripAdminBundle:User p WHERE p.userId='".$comment[0]['user']."'")->getArrayResult();	
?>
<div id="content"  class="clearfix">
  <div class="container">
    <div align="right" style="padding: 10px 10px 0px;"><a href="<?php echo $view['router']->generate('mytrip_admin_comments');?>" class="button">Back to Reviews</a></div>
    <form action="<?php echo $view['router']->generate('mytrip_admin_viewcomments',array('id'=>$_REQUEST['id'])) ?>" id="myForm" method="post">
    <fieldset>
      <legend>Review</legend>
      <dl class="inline">
        <dt><label>User</label></dt>
        <dd><p><?php echo $user[0]['email'];?></p></dd>
        <dt><label>Review Type</label></dt>
        <dd><p><?php echo $comment[0][0]['reviewType'];?></p></dd>
        <dt><label><?php echo $comment[0][0]['reviewType']=="Destination"?"Destination Name":"Hostal Name";?></label></dt>
        <dd><p><?php echo $comment[0][0]['reviewType'];?></p></dd>       
        <dt><label>Rating</label></dt>
        <dd><p><ul class="rating"><li class="current" style="width:<?php echo $comment[0][0]['rating']*20;?>%"></li></ul></p></dd>
        <dt><label>Reviews</label></dt>
        <dd><p><?php echo $comment[0][0]['review'];?></p></dd> 
        <dt><label>Status</label></dt>
        <dd><select name="status" id="status">
        <option value="Active" <?php echo $comment[0][0]['status']=="Active"?'selected="selected"':'';?> >Active</option>
        <option value="Inactive" <?php echo $comment[0][0]['status']=="Inactive"?'selected="selected"':'';?> >Inactive</option>
        </select></dd> 
        <div class="buttons" ><input type="submit" name="save" id="submit" class="button" value="Save"/></div>
      </dl>
    </fieldset>
    </form>
     </div>
</div>

