<?php $view->extend('::admin.html.php');
$paging=$pagination->getPaginationData();
?>
<div id="content" class="clearfix">
  <div class="container">
    <div class="mainheading">
      <div class="btnlink"></div>
      <div class="titletag">
        <h1>Manage Enquires</h1>
      </div>
    </div>
    <div class="tablefooter clearfix">
      <form name="searchfilters" action="" id="myForm1" method="post" style="width:1000px;float:left;padding: 5px 10px;">
        <table cellpadding="0" cellspacing="2">
          <tr>           
            <td>&nbsp;</td>
            <td><strong>&nbsp;From&nbsp;Date&nbsp;:&nbsp;</strong>&nbsp;</td>
            <td><input id="cdate" name="cdate" size="25"  type="text"value="<?php if(isset($_REQUEST['cdate'])){echo $_REQUEST['cdate'];}?>" /></td>
            <td><strong>&nbsp;To&nbsp;Date&nbsp;:&nbsp;</strong>&nbsp;</td>
            <td><input id="edate" name="edate" size="25"  type="text"value="<?php if(isset($_REQUEST['edate'])){echo $_REQUEST['edate'];}?>" /></td>
            <td><strong>&nbsp;Language&nbsp;:&nbsp;</strong>&nbsp;</td>
            <td><select name="lan" id="lang">
                <option value="" >All</option>
                <?php
				foreach($language as $lans){
					echo '<option value="'.$lans['lanCode'].'" '.((isset($_REQUEST['lan']) && ($_REQUEST['lan']==$lans['lanCode']))?'selected="selected"':'').'>'.$lans['language'].'</option>';
				}
				?>               
              </select></td>
            <td><input type="submit" name="searchbutton" class="button small" value="Search" /></td>
            <td>&nbsp;</td>
            <td><?php if(isset($_REQUEST['search'])){	
		?>
              <a href="<?php echo $view['router']->generate('mytrip_admin_contact');?>" class="button small" style="padding:3px 5px;">Cancel</a>
              <?php
		} ?></td>
          </tr>
        </table>
      </form>
    </div>
    <form action="<?php echo $view['router']->generate('mytrip_admin_deletecontact') ?>" id="myForm" method="post">
      <table class="gtable sortable">
        <thead>
          <tr>
            <th width="30" align="center"><input type="checkbox" id="checkAllAuto" name="action[]" class="validate[minCheckbox[1]] checkbox"  value="0" /></th>
            <th width="30" align="center">#</th>
            <th align="left" class="name"><a <?php if(isset($pagerequest['direction'])){ echo ($pagerequest['sort']=="p.name"?($pagerequest['direction']=="asc"?'class="asc"':'class="desc"'):"");}?> href="?<?php echo $sortingrequest;?>&sort=p.name&direction=<?php if(isset($pagerequest['direction'])){ echo ($pagerequest['direction']=="asc" ? 'desc' : 'asc');}else{ echo "asc";}?>&page=<?php echo $paging['current'];?>" title="Name">Name</a></th>
            <th align="left" class="email"><a <?php if(isset($pagerequest['direction'])){ echo ($pagerequest['sort']=="p.email"?($pagerequest['direction']=="asc"?'class="asc"':'class="desc"'):"");}?> href="?sort=p.email&direction=<?php if(isset($pagerequest['direction'])){ echo ($pagerequest['direction']=="asc"?'desc':'asc');}else{ echo "asc";}?>&page=<?php echo $paging['current'];?>" title="Email">Email</a></th>
            <th align="left" class="phone">Phone</th>
            <th align="left" class="subject">Subject</th>
            <th align="center">Date</th>
            <th width="30" align="center">View</th>
            <th width="30" align="center">Delete</th>
          </tr>
        </thead>
        <tbody>
          <?php if(!$pagination->getItems())
		echo '<tr><td colspan="10" align="center">No records found</td></tr>';
	else{
		$i=$paging['firstItemNumber'];
		foreach($pagination as $contact){			
			?>
          <tr >
            <td align="center"><input type="checkbox" name="action[]" rel="action" value="<?php echo $contact->getContactId(); ?>" /></td>
            <td align="center"><?php echo $i; ?></td>
            <td align="left"><?php echo $contact->getName();?></td>           
            <td align="left"><?php echo $contact->getEmail(); ?></td>
            <td align="left"><?php echo $contact->getPhone(); ?></td>
            <td align="left"><?php echo $contact->getSubject(); ?></td>
            <td align="center"><?php echo $this->container->get('mytrip_admin.helper.date')->viewformat($contact->getCreatedDate()->format('Y-m-d H:i:s'));?></td>
            <td align="center"><a href="<?php echo $view['router']->generate('mytrip_admin_viewcontact',array('id'=>$contact->getContactId()));?>"><img src="<?php echo $view['assets']->getUrl('img/icons/view.png');?>"/></a></td>
            <td align="center"><a href="<?php echo $view['router']->generate('mytrip_admin_deletecontact',array('id'=>$contact->getContactId()));?>" class="confirdel"><img src="<?php echo $view['assets']->getUrl('img/icons/cross.png');?>"/></a></td>
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
          <div class="pagenumber"> Page <?php echo $paging['current'];?> of <?php echo $paging['last'];?>, showing <?php echo $paging['currentItemCount'];?> records out of <?php echo $paging['totalCount'];?> </div>
          <div class="paging"> <span class="prev <?php echo ($paging['current']=="1"?"disabled":"");?>">
            <?php if($paging['current']>1){ echo '<a href="?'.$urlrequest.'&page='.($paging['current']-1).'">Previous</a>'; }else{ echo "Previous"; }?>
            </span>
            <?php for($i=1;$i<=$paging['last'];$i++){ ?>
            <span <?php echo ($paging['current']==$i?'class="current"':""); ?> ><?php echo ($paging['current']!=$i ? '<a href="?'.$urlrequest.'&page='.$i.'">'.$i.'</a>':$i);?></span>
            <?php }?>
            <span class="next <?php echo ($paging['current']==$paging['last']?"disabled":"");?>">
            <?php if($paging['current']< $paging['last']){ echo '<a href="?'.$urlrequest.'&page='.($paging['current']+1).'">Next</a>'; }else{ echo "Next"; }?>
            </span> </div>
        </div>
      </div>
    </form>
  </div>
</div>

<script type="text/javascript">
$(function() {
	$( "#cdate" ).datepicker({ dateFormat: '<?php echo $this->container->get('mytrip_admin.helper.date')->dateformat();?>' });
	$( "#edate" ).datepicker({ dateFormat: '<?php echo $this->container->get('mytrip_admin.helper.date')->dateformat();?>' });
});
</script>