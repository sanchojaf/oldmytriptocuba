<?php $view->extend('::admin.html.php');?>
<div id="content"  class="clearfix">
  <div class="container">    
    <div align="right" style="padding: 10px 10px 0px;">
    <a class="button" href="<?php echo $view['router']->generate('mytrip_admin_list_story');?>">Back to Story</a>
    </div>
  <form action="" method="post" enctype="multipart/form-data">
    <fieldset>
      <legend>Mange Banner</legend>
      <dl class="inline">
      
       <dt><label for="name">Destination <span class="required"></span></label></dt>
        <dd><?php echo $story[0]['name'] ?>
        <input type="hidden" name="story" id="story" value="<?php  echo $story[0]['storyId'];  ?>" /></dd>
        
       <dt><label for="name">Upload New Image <span class="required"></span></label></dt>
       <dd> <input type="file" name="img[]" multiple class="validate[custom[image]]" ></dd>
     
          <div class="buttons">
<input id="submit" class="button" type="submit" value="Save" name="save">
</div>
	  
      </dl>
      
    </fieldset>
    </form>
    
    
     <form enctype="multipart/form-data" method="post" action="<?php echo $view['router']->generate('mytrip_admin_delete_story_banner');?>">
            <fieldset style="padding:20px 0;">
						<legend>Banner</legend>
                          <input type="hidden" name="story" id="story" value="<?php  echo $story[0]['storyId'];  ?>" />
                        <label style="margin-left:20px"><div class="checker" id="uniform-checkAllAuto"><span><input type="checkbox" class="validate[minCheckbox[1]] checkbox" value="0" name="action[]" id="checkAllAuto" style="opacity: 0;"></span></div> Select All</label>
                          <div style="float:left; width:100%;">
				<ul style="padding:10px 0;" id="gallery">
                
                       <?php if(count($bannerstroyimage) >0) for($i=0;$i<count($bannerstroyimage);$i++) { ?>      
                	<li style="position:relative;margin:15px 8px;min-height:110px;width:160px;">
                  <img width="150" height="150" alt="" class="img" src="<?php echo $view['assets']->getUrl('img/story_banner/').$bannerstroyimage[$i]['image']?>"> 
                    <br>
                    <div align="center" style="width:160px;font-size:12px;padding:10px 0;text-align:center;"><?php echo $story[0]['name'] ?></div>
                    <div> 
                    <table width="100%" cellspacing="0" cellpadding="0">
                    <tbody><tr><td>
                       </td><td align="center"><div class="checker" id="uniform-undefined"><span>
                       <input type="checkbox" rel="action"  name="action[]" style="opacity: 0;" value="<?php echo $bannerstroyimage[$i]['bannerId'];  ?>"></span></div></td><td><a  href="deletestorybanner?id=<?php echo $bannerstroyimage[$i]['bannerId']; ?>&did=<?php  echo $story[0]['storyId'];  ?>"><img alt="" style="float:right;" src="/mytripcuba/web/img/icons/cross.png"></a></td></tr></tbody></table></div>
                   
                                        
                    </li>
                             <?php } ?>                    
                                
                
                                </ul>
                </div>
                <div class="tablefooter clearfix">
    <div class="actions">    <input id="submit" class="button" type="submit" value="Delete" name="save">   
    </div>
    
  </div>
			</fieldset>	
         </form>
  </div>
</div>
