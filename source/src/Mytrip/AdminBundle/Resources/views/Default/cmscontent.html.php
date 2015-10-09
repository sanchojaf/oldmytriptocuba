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
                <?php $cms_id=''; if(isset($cmscontent[0]['lan'])) { $cms_id=$cmscontent[0]['lan']; } else if(isset($_GET['lan'])){ $cms_id=$_GET['lan']; }   ?>
                    <select size="1" name="lang" id="lang"  onchange="changeurl(this.value)">  
                    <?php for($i=0;$i<count($languages);$i++) { ?>  
                          <option value="<?php echo  $languages[$i]['lanCode']; ?>" <?php if($cms_id==$languages[$i]['lanCode']) echo "selected"; ?>><?php echo  $languages[$i]['language']; ?></option>
                          <?php } ?>
                    </select>							
                </dd>		
            	<dt><label for="name">Name<span class="required">*</span></label></dt>
                <dd><input name="name" class="validate[required] text-input" id="name" type="text" size="50" value="<?php if(isset($cmscontent[0]['name'])) echo  $cmscontent[0]['name'] ?>"/></dd>
                               </dl>
                </fieldset>
                <fieldset>
            <legend>SEO</legend>
            <dl class="inline" >                             
            
            	<dt><label for="name">Title<span class="required">*</span></label></dt>
                <dd><input name="title" type="text" class="validate[required] text-input" id="title" size="28" value="<?php if(isset($cmscontent[0]['pageTitle'])) echo $cmscontent[0]['pageTitle'] ?>"/></dd>
                <dt><label for="image">Meta description</label></dt>
                <dd><textarea name="metadescription" class="text-input" id="metadescription" rows="4" cols="50"><?php if(isset($cmscontent[0]['metaDescription'])) echo $cmscontent[0]['metaDescription'] ?></textarea></dd>
                <dt><label for="image">Meta keyword</label></dt>
                <dd><textarea name="metakeyword" class="text-input" rows="3" id="metakeyword" cols="50"><?php if(isset($cmscontent[0]['metaKeyword'])) echo $cmscontent[0]['metaKeyword'] ?></textarea></dd>
                              
               </dl></fieldset>
                               <fieldset>
            <legend>Content</legend>
            <dl class="inline" > 
                <dt><label for="name">Content<span class="required">*</span></label></dt>
                <dd ><?php if(isset($cmscontent[0]['content'])) { $content_new=$cmscontent[0]['content']; } else { $content_new =''; }  $ckeditor->editor('contentvalue',$content_new); ?></dd>
               
               
            </dl>
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
	window.location="cmscontent?id=<?php echo $_GET['id']; ?>&lan="+val;
}
</script>		
</div>