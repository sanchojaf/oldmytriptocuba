<?php $view->extend('::admin.html.php');
$em = $this->container->get('doctrine')->getManager();
$mainmenu= $em->createQuery("SELECT p FROM MytripAdminBundle:Staticpage p WHERE p.mainMenu= 'Yes'")->getArrayResult();
?>
<script src="<?php echo $view['assets']->getUrl('js/ckeditor/ckeditor.js');?>" type="text/javascript"></script>
<div id="content"  class="clearfix">
  <div class="container">
     <div align="right" style="padding: 10px 10px 0px;"><a href="<?php echo $view['router']->generate('mytrip_admin_staticpage');?>" class="button">Back to Content Page</a></div>
    <form action="" id="myForm" method="post">
    <fieldset>
      <legend>Add Content page</legend>
      <dl class="inline"> 
     	<dt><label for="name">Main Menu <span class="required">*</span></label></dt>
        <dd><select id="mainmenu" name="mainmenu" class="validate[required]">
        <?php
		foreach($mainmenu as $mainmenu){
			echo '<option value="'.$mainmenu['staticpageId'].'">'.$mainmenu['pagename'].'</option>';
		}
		?>
        </select></dd>      
        <dt><label for="name">Page Name <span class="required">*</span></label></dt>
        <dd><input type="text" id="pagename" name="pagename" class="validate[required]"  size="50"  /></dd>
        <!--<dt><label for="name">SEO <span class="required">*</span></label></dt>
        <dd><input type="radio" id="seo" name="seo" class="validate[required]"  value="Yes"  /> Yes&nbsp;&nbsp;<input type="radio" id="seo1" name="seo" class="validate[required]"  value="No"  /> No</dd>
        <dt><label for="name">Content <span class="required">*</span></label></dt>
        <dd><input type="radio" id="content1" name="content" class="validate[required]"  value="Yes"  /> Yes&nbsp;&nbsp;<input type="radio" id="content1" name="content" class="validate[required]"  value="No"  /> No</dd>-->
        <dt><label for="name">Page Title <span class="required">*</span></label></dt>
        <dd><textarea  id="pagetitle" name="pagetitle"  rows="5" cols="60" class="validate[required]" ></textarea></dd>
        <dt><label for="name">Meta Description </label></dt>
        <dd><textarea  id="metadescription" name="metadescription"  rows="5" cols="60"></textarea></dd>
        <dt><label for="name">Meta Keyword </label></dt>
        <dd><textarea  id="metakeyword" name="metakeyword"  rows="5" cols="60"></textarea></dd>
        <dt><label for="name">Page Content <span class="required">*</span></label></dt>
        <dd><div style="width:710px"><textarea  id="pagecontent" name="pagecontent" class="validate[required] ckeditor"  rows="5" cols="60"></textarea></div></dd>
        <div class="buttons" ><input type="submit" name="save" id="submit" class="button" value="Save"/></div>
      </dl>
    </fieldset>
    </form>
     </div>
</div>

