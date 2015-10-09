<?php $view->extend('::admin.html.php');
$paging=$pagination->getPaginationData();
$em = $this->container->get('doctrine')->getManager();
?>
<div id="content" class="clearfix"> 
	<div class="container">
    <div class="mainheading">   
    <div class="btnlink"></div> 		
        <div class="titletag"><h1>Reviews</h1></div>
    </div>
  <div class="tablefooter clearfix">
 <form name="searchfilters" action="" id="myForm1" method="post" style="width:800px;float:left;padding: 5px 10px;">  
        <table cellpadding="0" cellspacing="2">
        <tr><td><strong>User : </strong>&nbsp;</td>
        <td><input id="searchname" name="searchname" type="text" class="validate[custom[email]] text-input" autocomplete="off" value="<?php if(isset($_REQUEST['name'])){echo $_REQUEST['name'];}?>" /></td><td>&nbsp;</td> 
        <td><strong>Type : </strong>&nbsp;</td>
        <td><select name="searchtype" id="searchtype" class="validate[groupRequired[payments]] text-input">
         <option value="">[-Select-]</option>
         <option value="Destination" <?php if(isset($_REQUEST['type'])){echo $_REQUEST['type']=="Destination"?'selected="selected"':'';}?>>Destination</option>
         <option value="Hostal" <?php if(isset($_REQUEST['type'])){echo $_REQUEST['type']=="Hostal"?'selected="selected"':'';}?>>Hostal</option>
        </select></td><td>&nbsp;</td>
        <td><strong>Type Name : </strong>&nbsp;</td>
        <td><input id="searchtypename" name="searchtypename" type="text" class="validate[groupRequired[payments]] text-input" autocomplete="off" value="<?php if(isset($_REQUEST['typename'])){echo $_REQUEST['typename'];}?>" /></td><td>&nbsp;</td>       
        <td><input type="submit" name="searchbutton" class="button small" value="Search" /></td>
            <td>&nbsp;</td><td>
		<?php if(isset($_REQUEST['search'])){	
		?>
        <a href="<?php echo $view['router']->generate('mytrip_admin_comments');?>" class="button small" style="padding:3px 5px;">Cancel</a>
        <?php
		} ?></td>
        </tr></table></form>        
  </div>
<form action="<?php echo $view['router']->generate('mytrip_admin_deletecomments') ?>" id="myForm" method="post">
    <table class="gtable sortable">
        <thead>
        <tr>
            <th width="30" align="center"><input type="checkbox" id="checkAllAuto" name="action[]" class="validate[minCheckbox[1]] checkbox"  value="0" /></th>
            <th width="30" align="center">#</th>
            <th align="left" class="name">Username</th> 
            <th align="center" class="type">Type</th> 
            <th align="center" class="typename">Type Name</th>
            <th align="center" class="rating">Rating</th> 
            <th align="left" class="comments">Reviews</th>           
            <th align="center">Status</th>            
            <th align="center">Action</th> 
            <th align="center">View</th>
            <th width="30" align="center">Delete</th>                       
        </tr>
        </thead>
        <tbody>  
		<?php if(!$pagination->getItems())
		echo '<tr><td colspan="10" align="center">No records found</td></tr>';
	else{
		$i=$paging['firstItemNumber'];
		foreach($pagination as $comment){	
		$comments=$comment[0];	
		//print_r($comment->getUser());exit;	
			$user=$em->createQuery("SELECT p FROM MytripAdminBundle:User p WHERE p.userId='".$comment['user']."'")->getArrayResult();	
			if($comments->getReviewType()=="Destination"){
				$type=$em->createQuery("SELECT p FROM MytripAdminBundle:Destination p WHERE p.destinationId='".$comments->getTypeId()."'")->getArrayResult();		
			}elseif($comments->getReviewType()=="Hostal"){
				$type=$em->createQuery("SELECT p FROM MytripAdminBundle:Hostal p WHERE p.hostalId='".$comments->getTypeId()."'")->getArrayResult();	
			}
			?>
      <tr >
       <td align="center"><input type="checkbox" name="action[]" rel="action" value="<?php echo $comments->getReviewId(); ?>" /></td>
       <td align="center"><?php echo $i; ?></td>
       <td align="left"><?php echo $user[0]['email'];?></td>        
       <td align="center"><?php echo $comments->getReviewType();?></td>   
       <td align="center"><?php echo $type[0]['name'];?></td>
       <td align="center"><?php echo $comments->getRating();?></td>
       <td align="left"><?php echo substr($comments->getReview(),0,50);?>...</td>   
       <td align="center"><?php echo $comments->getStatus();?></td>
       <td align="center">
		<?php echo ($comments->getStatus()=="Active" ? '<a href="'.$view['router']->generate('mytrip_admin_status',array('page'=>'comments','status'=>'inactive','id'=>$comments->getReviewId())).'" class="confirdel1" rel="Inactive">Click to Deactive</a>':'<a href="'.$view['router']->generate('mytrip_admin_status',array('page'=>'comments','status'=>'active','id'=>$comments->getReviewId())).'" class="confirdel1" rel="Active">Click to Active</a>'); ?></td> 
        <td align="center"><a href="<?php echo $view['router']->generate('mytrip_admin_viewcomments',array('id'=>$comments->getReviewId()));?>"><img src="<?php echo $view['assets']->getUrl('img/icons/view.png');?>"/></a></td>
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
