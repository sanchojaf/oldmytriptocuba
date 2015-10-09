<?php $view->extend('::admin.html.php');?>
<link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('js/ckeditor/ckeditor.js') ?>"/>
<script src="<?php echo $view['assets']->getUrl('js/uniform/jquery.uniform.js') ?>" type="text/javascript"></script>

<?php
include  $view['assets']->getUrl('/js/ckeditor/ckeditor.php');
$ckeditor = new CKEditor();
$ckeditor->basePath =  $view['assets']->getUrl('js/ckeditor/');
$ckeditor->config['filebrowserBrowseUrl'] = $view['assets']->getUrl('js/ckfinder/ckfinder.html');
$ckeditor->config['filebrowserImageBrowseUrl'] = $view['assets']->getUrl('js/ckfinder/ckfinder.html?type=Images');
$ckeditor->config['filebrowserFlashBrowseUrl'] = $view['assets']->getUrl('js/ckfinder/ckfinder.html?type=Flash');
$ckeditor->config['filebrowserUploadUrl'] = $view['assets']->getUrl('js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files');
$ckeditor->config['filebrowserImageUploadUrl'] = $view['assets']->getUrl('js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images');
$ckeditor->config['filebrowserFlashUploadUrl'] = $view['assets']->getUrl('js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash');
?>

<div id="mainContainer"> 	

<div id="content" class="clearfix"> 
<div class="container">
    <div class="tablefooter clearfix"> 
  <!--  <div align="right" style="padding-right:50px;"><a href="/project/backyards/adminpanel/staticpage" class="button white">Back to Static page</a></div> -->
    <form id="myForm"  name="myForm" action ="" class="uniform" method="post" enctype="multipart/form-data">    
        <fieldset>
            <legend>Edit Home Page</legend>
            <fieldset>
            <legend>Language & Name</legend>
            <dl class="inline" >
             <dt><label for="name">Language&nbsp;&nbsp;</label></dt>
                <dd >               
                    <select size="1" name="apiid" id="apiid"  onchange="changeurl(this.value)">         
                    <?php for($i=0;$i<count($apidetails);$i++) { ?>
                    <option value="<?php echo $apidetails[$i]['apiId']; ?>" <?php if(isset($_GET['id'])) if($_GET['id']==$apidetails[$i]['apiId']) echo "selected";  ?>><?php echo $apidetails[$i]['gatway']; ?></option>
                    <?php } ?>           
                    </select>							
                </dd>		
            	<dt><label for="name">Meta Key<span class="required">*</span></label></dt>
                <dd><input name="metakey" class="validate[required] text-input" id="metakey" type="text" size="50" value="<?php echo $apidetails_info[0]['metaKey']; ?>"/></dd>
                	<dt><label for="name">Meta Value<span class="required">*</span></label></dt>
                <dd><input name="metavalue" class="validate[required] text-input" id="metavalue" type="text" size="50" value="<?php echo $apidetails_info[0]['metaValue']; ?>"/></dd>
                               </dl>
                </fieldset>
               
           
            </fieldset>
                        <div class="buttons" >
                <input type="submit" class="button" id="submit_btn" value="Update" />				
            </div>
        </fieldset>
    </form>
</div>
</div>	
<script>
function changeurl(val)
{
	window.location="apiinfo?id="+val;
}
</script>		
</div>