<?php $view->extend('::admin.html.php');?>
    <div id="content" class="clearfix"> 
	<div class="container">
    <h1>Admin List</h1>
  <div class="tablefooter clearfix">
<!--  <form name="searchfilters" action="" id="myForm1" method="post" style="width:500px;float:left;padding: 5px 10px;"> 
        <table cellpadding="0" cellspacing="2">
        <tr><td><strong>Search : </strong>&nbsp;</td>
        <td><p id="auto"><input id="searchField" name="searchField" type="text" class="validate[required] text-input" autocomplete="off" value="" />&nbsp;</p></td>
        <td><input type="submit" class="button small" value="Search" /></td>
            <td></td>
        </tr></table></form>-->
        <div class="right"><a href="addfeatures" class="button">+ Add Feature</a></div>
  </div>
  <form action="deletefeaturemultiple" method="post" name="forum" id="myForm">
    <table class="gtable sortable">
        <thead>
        <tr>
            <th width="30" align="center"><input type="checkbox" id="checkAllAuto" name="action[]" value="0" class="validate[minCheckbox[1]] checkbox" /></th>
            <th width="30" align="center">#</th>           
            <th align="left" width="50" class="status">feature</th>                       
            <th width="30" align="center">Edit</th>
            <th width="30" align="center">Delete</th>
        </tr>
        </thead>
      <tbody>  		   
      <?php 	
	  for($i=0;$i<count($Feature);$i++) {  ?>
      <tr>
      <td align="center"><input type="checkbox" name="action[]" rel="action" value="<?php echo $Feature[$i]['featureId'] ?>" /></td>
       <td align="center"><?php echo $i+1; ?></td>
        <td align="left"><?php echo $Feature[$i]['feature']; ?></td>                    
        <td align="center"><a href="editfeature?id=<?php echo $Feature[$i]['featureId']; ?>"><img src="/project/backyards/img/icons/edit.png" border="0" alt="Edit" /></a></td>
        <td align="center"><a href="deletefeature?id=<?php echo $Feature[$i]['featureId']; ?>"><img src="/project/backyards/img/icons/cross.png" border="0" alt="Delete" onclick="return confirm(&quot;Are you sure want to delete this page?&quot;)" /></a></td>       
      </tr>
      <?php }  ?>
              </tbody>
    </table>
  <div class="tablefooter clearfix">
    <div class="actions"><input type="submit" id="action_btn"  class="button small" value="Delete" onclick="return confirm('Are you sure want to delete these pages?')" />
    </div>
  </div> 
  </form>
  </div>
</div></div>
<script type="text/javascript">
function undercons(){
	alert('Under construction');
}
</script>