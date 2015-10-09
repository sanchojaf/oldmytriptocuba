<?php $view->extend('::admin.html.php');
$paging=$pagination->getPaginationData();
//print_r($pagination->getItems());exit;
?>
<div id="content" class="clearfix"> 
	<div class="container">
    <div class="mainheading">   
    <div class="btnlink"><a href="<?php echo $view['router']->generate('mytrip_admin_addhostal');?>" class="button">Add Hostal</a></div> 		
        <div class="titletag"><h1>Hostal</h1></div>
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
        <a href="<?php echo $view['router']->generate('mytrip_admin_hostal');?>" class="button small" style="padding:3px 5px;">Cancel</a>
        <?php
		} ?></td>
        </tr></table></form>        
  </div>
<form action="<?php echo $view['router']->generate('mytrip_admin_deletehostal') ?>" id="myForm" method="post">
    <table class="gtable sortable">
        <thead>
        <tr>
            <th width="30" align="center"><input type="checkbox" id="checkAllAuto" name="action[]" class="validate[minCheckbox[1]] checkbox"  value="0" /></th>
            <th width="30" align="center">#</th>
            <th align="left" class="name"><a <?php if(isset($pagerequest['direction'])){ echo ($pagerequest['sort']=="h.name"?($pagerequest['direction']=="asc"?'class="asc"':'class="desc"'):"");}?> href="?<?php echo $sortingrequest;?>&sort=h.name&direction=<?php if(isset($pagerequest['direction'])){ echo ($pagerequest['direction']=="asc" ? 'desc' : 'asc');}else{ echo "asc";}?>&page=<?php echo $paging['current'];?>" title="Hostal">Hostal</a></th> 
            <th align="center">Destination</th>
            <th align="center">Status</th>
            <th align="center">Action</th>
            <th align="center">Banner</th>
            <th align="center">Cancel Details</th>
            <th width="30" align="center">Edit</th>  
            <th width="30" align="center">Delete</th>                       
        </tr>
        </thead>
        <tbody>  
		<?php if(!$pagination->getItems())
		echo '<tr><td colspan="9" align="center">No records found</td></tr>';
	else{
		$i=$paging['firstItemNumber'];
		foreach($pagination as $hostals){
			$hostal=$hostals[0];			
			?>
      <tr >
       <td align="center"><input type="checkbox" name="action[]" rel="action" value="<?php echo $hostal->getHostalId(); ?>" /></td>
       <td align="center"><?php echo $i; ?></td>
       <td align="left"><?php echo $hostal->getName();?></td>
       <td align="center"><?php echo $hostals['destination'];?></td>
       <td align="center"><?php echo $hostal->getStatus();?></td>
       <td align="center">
		<?php echo ($hostal->getStatus()=="Active" ? '<a href="'.$view['router']->generate('mytrip_admin_status',array('page'=>'hostal','status'=>'inactive','id'=>$hostal->getHostalId())).'" class="confirdel1" rel="Inactive">Click to Deactive</a>':'<a href="'.$view['router']->generate('mytrip_admin_status',array('page'=>'hostal','status'=>'active','id'=>$hostal->getHostalId())).'" class="confirdel1" rel="Active">Click to Active</a>'); ?></td> 
        <td align="center"><a href="<?php echo $view['router']->generate('mytrip_admin_banner',array('type'=>'hostal','id'=>$hostal->getHostalId()));?>"><img src="<?php echo $view['assets']->getUrl('img/icons/add.png');?>"/></a></td> 
         <td align="center"><a href="<?php echo $view['router']->generate('mytrip_admin_hostal_cancel',array('id'=>$hostal->getHostalId()));?>"><img src="<?php echo $view['assets']->getUrl('img/icons/view.png');?>"/></a></td> 
        <td align="center"><a href="<?php echo $view['router']->generate('mytrip_admin_edithostal',array('id'=>$hostal->getHostalId(),'lan'=>'en'));?>"><img src="<?php echo $view['assets']->getUrl('img/icons/edit.png');?>"/></a></td>
        <td align="center"><a href="<?php echo $view['router']->generate('mytrip_admin_deletehostal',array('id'=>$hostal->getHostalId()));?>" class="confirdel"><img src="<?php echo $view['assets']->getUrl('img/icons/cross.png');?>"/></a></td>
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