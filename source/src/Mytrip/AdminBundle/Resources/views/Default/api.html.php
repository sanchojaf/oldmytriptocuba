<?php $view->extend('::admin.html.php');
$paging=$pagination->getPaginationData();
?>
<div id="content" class="clearfix"> 
	<div class="container">
    <div class="mainheading">   
    <div class="btnlink"></div> 	
        <div class="titletag"><h1>API</h1></div>
    </div>
  <div class="tablefooter clearfix">
 <form name="searchfilters" action="" id="myForm1" method="post" style="width:800px;float:left;padding: 5px 10px;">  
        <table cellpadding="0" cellspacing="2">
        <tr><td><strong>API Name : </strong>&nbsp;</td>
        <td><input id="searchname" name="searchname" type="text" class="validate[required] text-input" autocomplete="off" value="<?php if(isset($_REQUEST['name'])){echo $_REQUEST['name'];}?>" /></td><td>&nbsp;</td>
        <td><input type="submit" name="searchbutton" class="button small" value="Search" /></td>
            <td>&nbsp;</td><td>
		<?php if(isset($_REQUEST['search'])){	
		?>
        <a href="<?php echo $view['router']->generate('mytrip_admin_api');?>" class="button small" style="padding:3px 5px;">Cancel</a>
        <?php
		} ?></td>
        </tr></table></form>        
  </div>
<form action="" id="myForm" method="post" >
    <table class="gtable sortable">
        <thead>
        <tr>
            <th width="30" align="center"><img src="<?php echo $view['assets']->getUrl('img/icons/arrow.jpg');?>"/></th>
            <th width="30" align="center">#</th>
            <th align="left" class="name"><a <?php if(isset($pagerequest['direction'])){ echo ($pagerequest['sort']=="p.gateway"?($pagerequest['direction']=="asc"?'class="asc"':'class="desc"'):"");}?> href="?<?php echo $sortingrequest;?>&sort=p.gateway&direction=<?php if(isset($pagerequest['direction'])){ echo ($pagerequest['direction']=="asc" ? 'desc' : 'asc');}else{ echo "asc";}?>&page=<?php echo $paging['current'];?>" title="Gateway">API Names</a></th>           
            <th align="center">Action</th>            
            <th align="center" class="status"><a <?php if(isset($pagerequest['direction'])){ echo ($pagerequest['sort']=="p.status"?($pagerequest['direction']=="asc"?'class="asc"':'class="desc"'):"");}?> href="?sort=p.status&direction=<?php if(isset($pagerequest['direction'])){ echo ($pagerequest['direction']=="asc"?'desc':'asc');}else{ echo "asc";}?>&page=<?php echo $paging['current'];?>" title="Status">Status</a></th>
            <th width="30" align="center">Edit</th>                   
        </tr>
        </thead>
        <tbody>  
		<?php if(!$pagination->getItems())
		echo '<tr><td colspan="6" align="center">No records found</td></tr>';
	else{
		$i=$paging['firstItemNumber'];
		foreach($pagination as $api){		
			?>
      <tr >
       <td align="center"><img src="<?php echo $view['assets']->getUrl('img/icons/arrow.jpg');?>"/></td>
    
        <td align="center"><?php echo $i; ?></td>
        <td align="left"><?php echo $api->getGateway();?></td>
        <td align="center">
		<?php echo ($api->getStatus()=="Active" ? '<a href="'.$view['router']->generate('mytrip_admin_status',array('page'=>'api','status'=>'inactive','id'=>$api->getApiId())).'" class="confirdel1" rel="Inactive">Click to Deactive</a>':'<a href="'.$view['router']->generate('mytrip_admin_status',array('page'=>'api','status'=>'active','id'=>$api->getApiId())).'" class="confirdel1" rel="Active">Click to Active</a>'); ?></td>
        <td align="center"><?php echo $api->getStatus(); ?></td>        
        <td align="center"><a href="<?php echo $view['router']->generate('mytrip_admin_editapi',array('id'=>$api->getApiId()));?>"><img src="<?php echo $view['assets']->getUrl('img/icons/edit.png');?>"/></a></td>
      </tr>
      <?php $i++;
	  }
	}?>
        </tbody>
    </table>
  <div class="tablefooter clearfix">   
   <div class="actions">      
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