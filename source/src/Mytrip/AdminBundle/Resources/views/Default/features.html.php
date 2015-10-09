<?php $view->extend('::admin.html.php');
$paging=$pagination->getPaginationData();
?>
<div id="content" class="clearfix"> 
	<div class="container">
    <div class="mainheading">   
    <div class="btnlink"><a href="<?php echo $view['router']->generate('mytrip_admin_addfeature');?>" class="button">Add Feature</a></div> 	
        <div class="titletag"><h1>Features</h1></div>
    </div>
  <div class="tablefooter clearfix">
 <form name="searchfilters" action="" id="myForm1" method="post" style="width:800px;float:left;padding: 5px 10px;">  
        <table cellpadding="0" cellspacing="2">
        <tr><td><strong>Feature Name : </strong>&nbsp;</td>
        <td><input id="searchname" name="searchname" type="text" class="validate[groupRequired[payments]] text-input" autocomplete="off" value="<?php if(isset($_REQUEST['feature'])){echo $_REQUEST['feature'];}?>" /></td><td>&nbsp;</td>         
        <td><input type="submit" name="searchbutton" class="button small" value="Search" /></td>
            <td>&nbsp;</td><td>
		<?php if(isset($_REQUEST['search'])){	
		?>
        <a href="<?php echo $view['router']->generate('mytrip_admin_features');?>" class="button small" style="padding:3px 5px;">Cancel</a>
        <?php
		} ?></td>
        </tr></table></form>        
  </div>
<form action="<?php echo $view['router']->generate('mytrip_admin_deletefeature') ?>" id="myForm" method="post">
    <table class="gtable sortable">
        <thead>
        <tr>
            <th width="30" align="center"><input type="checkbox" id="checkAllAuto" name="action[]" class="validate[minCheckbox[1]] checkbox"  value="0" /></th>
            <th width="30" align="center">#</th>
            <th align="left" class="name"><a <?php if(isset($pagerequest['direction'])){ echo ($pagerequest['sort']=="p.feature"?($pagerequest['direction']=="asc"?'class="asc"':'class="desc"'):"");}?> href="?<?php echo $sortingrequest;?>&sort=p.feature&direction=<?php if(isset($pagerequest['direction'])){ echo ($pagerequest['direction']=="asc" ? 'desc' : 'asc');}else{ echo "asc";}?>&page=<?php echo $paging['current'];?>" title="Feature">Feature</a></th>           
            <th width="30" align="center">Edit</th>
            <th width="30" align="center">Delete</th>           
        </tr>
        </thead>
        <tbody>  
		<?php if(!$pagination->getItems())
		echo '<tr><td colspan="5" align="center">No records found</td></tr>';
	else{
		$i=$paging['firstItemNumber'];
		foreach($pagination as $feature){	
			?>
      <tr >
       <td align="center"><input type="checkbox" name="action[]" rel="action" value="<?php echo $feature->getFeatureId(); ?>" /></td>
    
        <td align="center"><?php echo $i; ?></td>
        <td align="left"><?php echo $feature->getFeature();?></td>      
        <td align="center"><a href="<?php echo $view['router']->generate('mytrip_admin_editfeature',array('id'=>$feature->getFeatureId()));?>"><img src="<?php echo $view['assets']->getUrl('img/icons/edit.png');?>"/></a></td>
        <td align="center"><a href="<?php echo $view['router']->generate('mytrip_admin_deletefeature',array('id'=>$feature->getFeatureId()));?>" class="confirdel"><img src="<?php echo $view['assets']->getUrl('img/icons/cross.png');?>"/></a></td>
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