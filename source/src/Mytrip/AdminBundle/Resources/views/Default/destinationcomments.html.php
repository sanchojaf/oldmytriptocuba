<?php $view->extend('::admin.html.php');
$paging=$pagination->getPaginationData();
$em = $this->container->get('doctrine')->getManager();
?><table width="100%" cellspacing="0" cellpadding="0" border="0">
<tbody><tr>
<td width="230" valign="top" align="right" class="sidepromenu">
<ul class="projectmenu">
	<li <?php echo (in_array($this->container->get('request')->get('_route'),array('mytrip_admin_editdestination'))?'class="active"':'') ?>><a href="<?php echo $view['router']->generate('mytrip_admin_editdestination',array('id'=>$_REQUEST['id'],'lan'=>$_REQUEST['lan']));?>">Destination details</a></li>
    <li <?php echo (in_array($this->container->get('request')->get('_route'),array('mytrip_admin_destinationhostals'))?'class="active"':'') ?>><a href="<?php echo $view['router']->generate('mytrip_admin_destinationhostals',array('id'=>$_REQUEST['id'],'lan'=>$_REQUEST['lan']));?>">Destination Hostals</a></li>
    <li <?php echo (in_array($this->container->get('request')->get('_route'),array('mytrip_admin_destinationcomments'))?'class="active"':'') ?>><a href="<?php echo $view['router']->generate('mytrip_admin_destinationcomments',array('id'=>$_REQUEST['id'],'lan'=>$_REQUEST['lan']));?>">Destination Comments</a></li>
</ul></td>
<td valign="top">
<div id="content" class="clearfix"> 
	<div class="container">
    <div class="mainheading">   
    <div class="btnlink"></div> 		
        <div class="titletag"><h1><?php echo $destination[0]['name'];?> - Comments</h1></div>
    </div>
  <div class="tablefooter clearfix">
 <form name="searchfilters" action="" id="myForm1" method="post" style="width:800px;float:left;padding: 5px 10px;">  
        <table cellpadding="0" cellspacing="2">
        <tr><td><strong>User : </strong>&nbsp;</td>
        <td><input id="searchname" name="searchname" type="text" class="validate[groupRequired[payments]] text-input" autocomplete="off" value="<?php if(isset($_REQUEST['name'])){echo $_REQUEST['name'];}?>" /></td><td>&nbsp;</td>        
        <td><input type="submit" name="searchbutton" class="button small" value="Search" /></td>
            <td>&nbsp;</td><td>
		<?php if(isset($_REQUEST['search'])){	
		?>
        <a href="<?php echo $view['router']->generate('mytrip_admin_destinationcomments',array('did'=>$_REQUEST['id'],'lan'=>$_REQUEST['lan']));?>" class="button small" style="padding:3px 5px;">Cancel</a>
        <?php
		} ?></td>
        </tr></table></form>        
  </div>
<form action="<?php echo $view['router']->generate('mytrip_admin_deletedestinationcomments',array('did'=>$_REQUEST['id'],'lan'=>$_REQUEST['lan'])) ?>" id="myForm" method="post">
    <table class="gtable sortable">
        <thead>
        <tr>
            <th width="30" align="center"><input type="checkbox" id="checkAllAuto" name="action[]" class="validate[minCheckbox[1]] checkbox"  value="0" /></th>
            <th width="30" align="center">#</th>
            <th align="left" class="name">Username</th>  
            <th align="left" class="name">Rating</th> 
            <th align="left" class="name">Comments</th>           
            <th align="center">Status</th>
            <th align="center">Action</th> 
            <th width="30" align="center">Delete</th>                       
        </tr>
        </thead>
        <tbody>  
		<?php if(!$pagination->getItems())
		echo '<tr><td colspan="8" align="center">No records found</td></tr>';
	else{
		$i=$paging['firstItemNumber'];
		foreach($pagination as $comments){			
			$user=$em->createQuery("SELECT p FROM MytripAdminBundle:User p WHERE p.userId='".$comments->getUserId()."'")->getArrayResult();			
			?>
      <tr >
       <td align="center"><input type="checkbox" name="action[]" rel="action" value="<?php echo $comments->getReviewId(); ?>" /></td>
       <td align="center"><?php echo $i; ?></td>
       <td align="left"><?php echo $user[0]['email'];?></td>    
       <td align="center"><?php echo $comments->getRating();?></td>
       <td align="center"><?php echo substr($comments->getReview(),0,100);?></td>   
       <td align="center"><?php echo $comments->getStatus();?></td>
       <td align="center">
		<?php echo ($comments->getStatus()=="Active" ? '<a href="'.$view['router']->generate('mytrip_admin_status',array('page'=>'comments','status'=>'inactive','id'=>$comments->getReviewId())).'" class="confirdel1" rel="Inactive">Click to Deactive</a>':'<a href="'.$view['router']->generate('mytrip_admin_status',array('page'=>'comments','status'=>'active','id'=>$comments->getReviewId())).'" class="confirdel1" rel="Active">Click to Active</a>'); ?></td> 
        <td align="center"><a href="<?php echo $view['router']->generate('mytrip_admin_deletecomments',array('id'=>$comments->getReviewId()));?>" class="confirdel"><img src="<?php echo $view['assets']->getUrl('img/icons/cross.png');?>"/></a></td>
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
</td>
</tr></tbody></table>