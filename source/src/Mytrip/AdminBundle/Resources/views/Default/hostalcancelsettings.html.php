<?php $view->extend('::admin.html.php');
$paging=$pagination->getPaginationData();
?>
<div id="content" class="clearfix"> 
	<div class="container">
    <div class="mainheading">   
    <div class="btnlink"><a href="<?php echo $view['router']->generate('mytrip_admin_hostal_add_cancel_setting',array('id'=>$_REQUEST['id']));?>" class="button">Add Cancel Settings</a></div> 	
        <div class="titletag"><h1><?php echo $hostal[0]['name'];?> - Cancel Settings</h1></div>
    </div>
  <div class="tablefooter clearfix">
     &nbsp;   
  </div>
<form action="<?php echo $view['router']->generate('mytrip_admin_hostal_delete_cancel_settings',array('hostalid'=>$_REQUEST['id'])) ?>" id="myForm" method="post">
    <table class="gtable sortable">
        <thead>
        <tr>
            <th width="30" align="center"><input type="checkbox" id="checkAllAuto" name="action[]" class="validate[minCheckbox[1]] checkbox"  value="0" /></th>
            <th width="30" align="center">#</th>
            <th align="left" class="name">Days</th>
            <th align="left" class="username">Percentage</th>
            <th width="30" align="center">Edit</th>
            <th width="30" align="center">Delete</th>           
        </tr>
        </thead>
        <tbody>  
		<?php if(!$pagination->getItems())
		echo '<tr><td colspan="6" align="center">No records found</td></tr>';
	else{
		$i=$paging['firstItemNumber'];
		foreach($pagination as $settings){	
			?>
      <tr >
       <td align="center"><?php if($i==1){?><img src="<?php echo $view['assets']->getUrl('img/icons/arrow.jpg');?>"/><?php }else{ ?><input type="checkbox" name="action[]" rel="action" value="<?php echo $settings->getHostalCancelId(); ?>" /><?php }?></td>
        <td align="center"><?php echo $i; ?></td>
        <td align="left"><?php echo $settings->getDays();?> days</td>
        <td align="left"><?php echo $settings->getPercentage();?> %</td>      
        <td align="center"><a href="<?php echo $view['router']->generate('mytrip_admin_hostal_edit_cancel_settings',array('hostalid'=>$_REQUEST['id'],'id'=>$settings->getHostalCancelId()));?>"><img src="<?php echo $view['assets']->getUrl('img/icons/edit.png');?>"/></a></td>
        <td align="center"><?php if($i>1){?><a href="<?php echo $view['router']->generate('mytrip_admin_hostal_delete_cancel_settings',array('hostalid'=>$_REQUEST['id'],'id'=>$settings->getHostalCancelId()));?>" class="confirdel"><img src="<?php echo $view['assets']->getUrl('img/icons/cross.png');?>"/></a><?php }?></td>
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