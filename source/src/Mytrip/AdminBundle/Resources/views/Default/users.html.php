<?php $view->extend('::admin.html.php');
$paging=$pagination->getPaginationData();
$em = $this->container->get('doctrine')->getManager();
?>
<div id="content" class="clearfix"> 
	<div class="container">
    <div class="mainheading">   
    <div class="btnlink"></div> 		
        <div class="titletag"><h1>Users</h1></div>
    </div>
  <div class="tablefooter clearfix">
 <form name="searchfilters" action="" id="myForm1" method="post" style="width:800px;float:left;padding: 5px 10px;">  
        <table cellpadding="0" cellspacing="2">
        <tr><td><strong>Name : </strong>&nbsp;</td>
        <td><input id="searchname" name="searchname" type="text" class="validate[groupRequired[payments]] text-input" autocomplete="off" value="<?php if(isset($_REQUEST['name'])){echo $_REQUEST['name'];}?>" /></td><td>&nbsp;</td> 
        <td><strong>Email : </strong>&nbsp;</td>
        <td><input id="searchemail" name="searchemail" type="text" class="validate[groupRequired[payments]] text-input" autocomplete="off" value="<?php if(isset($_REQUEST['email'])){echo $_REQUEST['email'];}?>" /></td><td>&nbsp;</td>         
        <td><strong>Mobile : </strong>&nbsp;</td>
        <td><input id="searchmobile" name="searchmobile" type="text" class="validate[groupRequired[payments]] text-input" autocomplete="off" value="<?php if(isset($_REQUEST['mobile'])){echo $_REQUEST['mobile'];}?>" /></td><td>&nbsp;</td>       
        <td><input type="submit" name="searchbutton" class="button small" value="Search" /></td>
            <td>&nbsp;</td><td>
		<?php if(isset($_REQUEST['search'])){	
		?>
        <a href="<?php echo $view['router']->generate('mytrip_admin_users');?>" class="button small" style="padding:3px 5px;">Cancel</a>
        <?php
		} ?></td>
        </tr></table></form>        
  </div>
<form action="<?php echo $view['router']->generate('mytrip_admin_deleteusers') ?>" id="myForm" method="post">
    <table class="gtable sortable">
        <thead>
        <tr>
            <th width="30" align="center"><img src="<?php echo $view['assets']->getUrl('img/icons/arrow.jpg');?>"/></th>
            <th width="30" align="center">#</th>
            <th align="left" class="name">Name</th> 
            <th align="center" class="type">Email</th> 
            <th align="center" class="typename">Mobile</th>
            <th align="center" class="rating">Country</th>           
            <th align="center">Status</th>            
            <th align="center">Action</th> 
            <th align="center">View</th>
            <th width="30" align="center">Delete</th>                       
        </tr>
        </thead>
        <tbody>  
		<?php if(!$pagination->getItems())
		echo '<tr><td colspan="9" align="center">No records found</td></tr>';
	else{
		$i=$paging['firstItemNumber'];
		foreach($pagination as $user){					
			$country=$em->createQuery("SELECT c FROM MytripAdminBundle:Country c WHERE c.cid='".$user->getCountry()."'")->getArrayResult();
			
			?>
      <tr >
       <td align="center"><img src="<?php echo $view['assets']->getUrl('img/icons/arrow.jpg');?>"/></td>
       <td align="center"><?php echo $i; ?></td>
       <td align="left"><?php echo $user->getFirstname()." ".$user->getLastname(); ?></td>        
       <td align="center"><?php echo $user->getEmail(); ?></td>   
       <td align="center"><?php echo $user->getMobile(); ?></td>
       <td align="center"><?php if(!empty($country)){echo $country[0]['country'];}else{ echo '-';}?></td>
       <td align="center"><?php echo $user->getStatus();?></td>
       <td align="center">
		<?php echo ($user->getStatus()=="Active" ? '<a href="'.$view['router']->generate('mytrip_admin_status',array('page'=>'users','status'=>'inactive','id'=>$user->getUserId())).'" class="confirdel1" rel="Inactive">Click to Deactive</a>':'<a href="'.$view['router']->generate('mytrip_admin_status',array('page'=>'users','status'=>'active','id'=>$user->getUserId())).'" class="confirdel1" rel="Active">Click to Active</a>'); ?></td> 
        <td align="center"><a href="<?php echo $view['router']->generate('mytrip_admin_viewusers',array('id'=>$user->getUserId()));?>"><img src="<?php echo $view['assets']->getUrl('img/icons/view.png');?>"/></a></td>
        <td align="center"><a href="<?php echo $view['router']->generate('mytrip_admin_deleteusers',array('id'=>$user->getUserId()));?>" class="confirdel"><img src="<?php echo $view['assets']->getUrl('img/icons/cross.png');?>"/></a></td>
      </tr>
      <?php $i++;
	  }
	  }?>
        </tbody>
    </table>
  <div class="tablefooter clearfix">   
   
    
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
