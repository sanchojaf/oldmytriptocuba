<?php $view->extend('::admin.html.php');?>
<script src="<?php echo $view['assets']->getUrl('js/ckeditor/ckeditor.js');?>" type="text/javascript"></script>
<div id="content"  class="clearfix">
  <div class="container">
     <div align="right" style="padding: 10px 10px 0px;"><a href="<?php echo $view['router']->generate('mytrip_admin_contact');?>" class="button">Back to Enquiries List</a></div>
    <form action="" id="myForms" method="post" enctype="multipart/form-data">
    <fieldset>
      <legend>View Enquiry</legend>
      <dl class="inline">       
        <dt><label for="name">Name</label></dt>
        <dd><p><?php echo $contact[0]['name'];?></p></dd>
        <dt><label for="name">Email</label></dt>
        <dd><p><?php echo $contact[0]['email'];?></p></dd>
        <dt><label for="name">Phone</label></dt>
        <dd><p><?php echo $contact[0]['phone'];?></p></dd>       
        <dt><label for="name">Subject</label></dt>
        <dd><p><?php echo $contact[0]['subject'];?></p></dd>       
        <dt><label for="name">Message</label></dt>
        <dd><p><?php echo $contact[0]['message'];?></p></dd> 
        <?php if($contact['0']['reply']=='No'){?>
        <dt><label for="name">&nbsp;</label></dt>
        <dd><input name="submit" type="button" class="button" value="Reply" id="replybutton" /></dd> 
        <?php }?> 
      </dl>
    </fieldset>
    </form>
     <?php	 
	  if($contact['0']['reply']=='No'){?>
    <div class="replydiv" style="display:none;">
   <form id="myForm" class="uniform" method="post" >
     <fieldset>
	<legend>Reply</legend>
    <dl class="inline">
    <dt><label for="name">Email</label></dt>
    <dd><input type="text" name="email" id="email" class="validate[required,custom[email]]" size="30" value="<?php  echo $contact['0']['email'];?>" readonly="readonly"/></dd>
     <dt><label for="name">Subject</label></dt>
    <dd><input type="text" name="replysubject" maxlength="25" size="30" id="replysubject" class="validate[required]" value="Re-<?php  echo $contact['0']['subject'];?>"/></dd>
    <dt><label for="name">Reply Message</label></dt>
    <dd><textarea rows="10" cols="60" name="replymessage" class="validate[required] ckeditor"  id="message"></textarea></dd>
    <dt><label for="name">&nbsp;</label></dt>
    <dd><input name="submit" type="submit" class="button" value="Submit" id="submit_btn" />&nbsp;<input name="cancel" type="button" class="button white" value="Cancel" id="cancel" /></dd> 
    </dl>      
    </fieldset>
    </form>
    </div> 
    <?php }else{?>
     <form id="myForms" class="uniform" method="post" >
     <fieldset>
	<legend>Reply</legend>
    <dl class="inline">
    <dt><label for="name">Email</label></dt>
    <dd><p><?php  echo $contact['0']['email'];?></p></dd>
     <dt><label for="name">Subject</label></dt>
    <dd><p><?php  echo $contact['0']['replysubject'];?></p></dd>
    <dt><label for="name">Reply Message</label></dt>
    <dd><p><?php echo $contact['0']['replyMessage'];?></p></dd> 
     <dt><label for="name">Reply Date</label></dt>
    <dd><p><?php $cdate=$contact['0']['replyDate']; echo $this->container->get('mytrip_admin.helper.date')->viewformat($cdate->format('Y-m-d H:i:s'),true);?></p></dd>    
    </dl>      
    </fieldset>
    </form>
    <?php }?> 
     </div>
</div>
<script type="text/javascript">
$('#replybutton').live('click',function(){
	$('.replydiv').show();
	$('#replybutton').hide();
});
$('#cancel').live('click',function(){
	$('.replydiv').hide();
	$('#replybutton').show();
});

</script>