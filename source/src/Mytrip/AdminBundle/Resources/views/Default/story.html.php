<?php $view->extend('::admin.html.php');
$paging=$pagination->getPaginationData();
?>
<div id="content" class="clearfix"> 
	<div class="container">
    <div class="mainheading">   
    <div class="btnlink"><a href="<?php echo $view['router']->generate('mytrip_admin_addstory');?>" class="button">Add Story</a></div> 		
        <div class="titletag"><h1>Story</h1></div>
    </div>
  <div class="tablefooter clearfix">
 <form name="searchfilters" action="" id="myForm1" method="post" style="width:800px;float:left;padding: 5px 10px;">  
        <table cellpadding="0" cellspacing="2">
        <tr><td><strong>Name : </strong>&nbsp;</td>
        <td><input id="searchname" name="searchname" type="text" class="validate[groupRequired[payments]] text-input" autocomplete="off" value="<?php if(isset($_REQUEST['name'])){echo $_REQUEST['name'];}?>" /></td><td>&nbsp;</td>        
        <td><input type="submit" name="searchbutton" class="button small" value="Search" /></td>
            <td>&nbsp;</td><td>
		<?php if(isset($_REQUEST['search'])){	
		?>
        <a href="<?php echo $view['router']->generate('mytrip_admin_story');?>" class="button small" style="padding:3px 5px;">Cancel</a>
        <?php
		} ?></td>
        </tr></table></form>        
  </div>
<form action="<?php echo $view['router']->generate('mytrip_admin_deletestory') ?>" id="myForm" method="post">
    <table class="gtable sortable">
        <thead>
        <tr>
            <th width="30" align="center"><input type="checkbox" id="checkAllAuto" name="action[]" class="validate[minCheckbox[1]] checkbox"  value="0" /></th>
            <th width="30" align="center">#</th>
            <th align="left" class="name"><a <?php if(isset($pagerequest['direction'])){ echo ($pagerequest['sort']=="s.name"?($pagerequest['direction']=="asc"?'class="asc"':'class="desc"'):"");}?> href="?<?php echo $sortingrequest;?>&sort=s.name&direction=<?php if(isset($pagerequest['direction'])){ echo ($pagerequest['direction']=="asc" ? 'desc' : 'asc');}else{ echo "asc";}?>&page=<?php echo $paging['current'];?>" title="Story">Story Name</a></th> 
            <th align="center">Hostal</th>
            <th align="center">Status</th>
            <th align="center">Action</th>
            <th align="center">Banner</th>
            <th align="center">Story of the month</th>
            <th width="30" align="center">Edit</th>  
            <th width="30" align="center">Delete</th>                       
        </tr>
        </thead>
        <tbody>  
		<?php if(!$pagination->getItems())
		echo '<tr><td colspan="9" align="center">No records found</td></tr>';
	else{
		$i=$paging['firstItemNumber'];
		foreach($pagination as $stories){
			$story=$stories[0];			
			?>
      <tr >
       <td align="center"><input type="checkbox" name="action[]" rel="action" value="<?php echo $story->getStoryId(); ?>" /></td>
       <td align="center"><?php echo $i; ?></td>
       <td align="left"><?php echo $story->getName();?></td>
       <td align="center"><?php echo $stories['hostal'];?></td>
       <td align="center"><?php echo $story->getStatus();?></td>
       <td align="center">
		<?php echo ($story->getStatus()=="Active" ? '<a href="'.$view['router']->generate('mytrip_admin_status',array('page'=>'story','status'=>'inactive','id'=>$story->getStoryId())).'" class="confirdel1" rel="Inactive">Click to Deactive</a>':'<a href="'.$view['router']->generate('mytrip_admin_status',array('page'=>'story','status'=>'active','id'=>$story->getStoryId())).'" class="confirdel1" rel="Active">Click to Active</a>'); ?></td> 
        <td align="center"><a href="<?php echo $view['router']->generate('mytrip_admin_banner',array('type'=>'story','id'=>$story->getStoryId()));?>"><img src="<?php echo $view['assets']->getUrl('img/icons/add.png');?>"/></a></td>  
        <td align="center"><input type="checkbox" name="topstory" class="topstory" id="<?php echo $story->getStoryId(); ?>" value="1" <?php if($story->getTopStory()=="Yes"){echo 'checked="checked"';} ?>/></td>  
        <td align="center"><a href="<?php echo $view['router']->generate('mytrip_admin_editstory',array('id'=>$story->getStoryId(),'lan'=>'en'));?>"><img src="<?php echo $view['assets']->getUrl('img/icons/edit.png');?>"/></a></td>
        <td align="center"><a href="<?php echo $view['router']->generate('mytrip_admin_deletestory',array('id'=>$story->getStoryId()));?>" class="confirdel"><img src="<?php echo $view['assets']->getUrl('img/icons/cross.png');?>"/></a></td>
      </tr>
      <?php $i++;
	  }
	  }?>
        </tbody>
    </table>
  <div class="tablefooter clearfix">   
   <div class="actions">
      <input type="submit" id="action_btn"  class="button small" value="Delete"/>
    </div>
    
    <div class="pagination">
    <div class="pagenumber">
    Page <?php echo $paging['current'];?> of <?php echo $paging['last'];?>, showing <?php echo $paging['currentItemCount'];?> records out of <?php echo $paging['totalCount'];?>	
    </div>
        <div class="paging">
         <span class="prev <?php echo ($paging['current']=="1"?"disabled":"");?>"><?php if($paging['current']>1){ echo '<a href="?'.$urlrequest.'&page='.($paging['current']-1).'">Previous</a>'; }else{ echo "Previous"; }?></span>
         <?php for($i=1;$i<=$paging['last'];$i++){ ?>
         <span <?php echo ($paging['current']==$i?'class="current"':""); ?> ><?php echo ($paging['current']!=$i ? '<a href="?'.$urlrequest.'&page='.$i.'">'.$i.'</a>':$i);?></span>
		 <?php }?>
         <span class="next <?php echo ($paging['current']==$paging['last']?"disabled":"");?>"><?php if($paging['current']< $paging['last']){ echo '<a href="?'.$urlrequest.'&page='.($paging['current']+1).'">Next</a>'; }else{ echo "Next"; }?></span> 
        </div>
    </div>
  </div> 
</form>
  </div>
</div>
<script type="text/javascript">
$('.topstory').live('click',function(){	
	var thisvar=$(this);
	var ids=$(this).attr('id');	
	if(thisvar.is(':checked')){
		var result = $.confirm({
				'title'		: 'Message',
				'message'	: 'Are you sure want to set this story to story of the month?',
				'buttons'	: {
					'Yes'	: {
						'class'	: 'blue',
						'action': function(){
							$('.topstory').removeAttr('checked');
							$('.topstory').parent().removeAttr('class');
							thisvar.attr('checked','checked');
							thisvar.parent().addClass('checked');		
							$.ajax({
								type: "POST",
								url: "<?php echo $this->container->get('request')->getSchemeAndHttpHost().$view['router']->generate('mytrip_admin_topstory');?>",
								data: "sid="+ids,
								success: function(msg){ 
															
								}
							});
						}
					},
					'No'	: {
						'class'	: 'gray',
						'action': function(){
							thisvar.removeAttr('checked');
							thisvar.parent().removeAttr('class');		
							return false;
						}	// Nothing to do in this case. You can as well omit the action property.
					}
				}
			});
			if(!result)		
			return false;				
		
	}else{
		alert('Please select other commentary.');
		thisvar.attr('checked','checked');
		thisvar.parent().addClass('checked');
	}
	
});
</script>