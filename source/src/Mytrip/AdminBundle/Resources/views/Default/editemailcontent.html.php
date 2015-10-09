<?php $view->extend('::admin.html.php');?>
<script src="<?php echo $view['assets']->getUrl('js/ckeditor/ckeditor.js');?>" type="text/javascript"></script>
<div id="content"  class="clearfix">
  <div class="container">
     <div align="right" style="padding: 10px 10px 0px;"><a href="<?php echo $view['router']->generate('mytrip_admin_emailcontent');?>" class="button">Back to Email Content</a></div>
    <form action="" id="myForm" method="post" enctype="multipart/form-data">
    <fieldset>
      <legend>Edit Email Content</legend>
      <dl class="inline"> 
      <?php if(!in_array($_REQUEST['id'],array('1','2','3','4','5'))){?> 
       <dt><label for="name">Language <span class="required">*</span></label></dt>
        <dd><select name="lan" id="lan">
        <?php
		foreach($language as $lans){			
			echo '<option value="'.$lans['lanCode'].'" '.($_REQUEST['lan']==$lans['lanCode']?'selected="selected"':'').'>'.$lans['language'].'</option>';
		}
		?>
        </select></dd> 
        <?php }?> 
        <?php if($_REQUEST['lan']=="en"){  ?>    
        <dt><label for="name">Title<span class="required">*</span></label></dt>
        <dd><input type="text" id="title" name="title" class="validate[required]"  size="50" value="<?php echo $emaillist['0']['title'];?>"  /></dd>  
        <?php if(!in_array($_REQUEST['id'],array('4'))){?>       
        <dt><label for="name">From Name<span class="required">*</span></label></dt>
        <dd><input type="text" id="fromname" name="fromname" class="validate[required]"  size="50" value="<?php echo $emaillist['0']['fromname'];?>"  /></dd>
        <dt><label for="name">From Address<span class="required">*</span></label></dt>
        <dd><input type="text" id="fromemail" name="fromemail" class="validate[required,custom[email]]"  size="50" value="<?php echo $emaillist['0']['fromemail'];?>"  /></dd>
         <?php }?> 
        <?php if(!in_array($_REQUEST['id'],array('1','2','3','5','6','7'))){?> 
        <dt><label for="name">To Address<?php echo (in_array($_REQUEST['id'],array('4'))?'<span class="required">*</span>':'')?></label></dt>
        <dd><input type="text" id="tomail" name="tomail" class="validate[<?php echo (in_array($_REQUEST['id'],array('4'))?'required,':'')?>custom[email]]"  size="50" value="<?php echo $emaillist['0']['tomail'];?>"  /></dd>
        <?php }?> 
        <dt><label for="name">CC Address</label></dt>
        <dd><textarea id="ccmail" name="ccmail" class="" rows="5" cols="60"><?php echo $emaillist['0']['ccmail'];?></textarea></dd>        
        <dt><label>&nbsp;</label></dt><dd><strong>(Multiple Email id separate with ",")</strong></dd>
        <?php }?>
        <?php if(!in_array($_REQUEST['id'],array('4','5'))){?>
        <dt><label for="name">Subject<span class="required">*</span></label></dt>
        <dd><input type="text" id="subject" name="subject" class="validate[required]"  size="50" value="<?php echo $email_content['0']['subject'];?>"  /></dd>
        <?php }?> 
        <?php if(!in_array($_REQUEST['id'],array('5'))){?>
        <dt><label for="name">Email Content<span class="required">*</span></label></dt>
        <dd><table><tr><td style="width:550px;" valign="top"><textarea id="emailcontent" name="emailcontent" class="ckeditor" rows="5" cols="60"><?php echo $email_content['0']['emailcontent'];?></textarea></td><td>
        <?php
		$label= explode(",",$emaillist[0]['label']);
		$tbltxt='';				
		foreach($label as $lab){
			$labtxt=explode(":",$lab);
			$tbltxt.='<tr><td>'.$labtxt[0].'</td><td>:</td><td>'.$labtxt[1].'</td></tr>';
		}
		?>
        <table cellpadding="5">
        <tr><td><h3>Dynamic Variables</h3></td></tr>
		<?php echo $tbltxt;?>
        </table></td></tr></table>
        </td></tr></table></dd>
          <?php }?>        
        <div class="buttons"><input type="submit" name="save" id="submit" class="button" value="Save"/></div>
      </dl>
    </fieldset>
    </form>
     </div>
</div>
<?php if(!in_array($_REQUEST['id'],array('1','2','3'))){?> 
<script type="text/javascript">
$('#lan').change(function(){
	$('.helpfade').show();
	$('.helptips').show();
	window.location="editemailcontent?id=<?php echo $_REQUEST['id'];?>&lan="+$(this).val();
});
</script>
<?php }?>