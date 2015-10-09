<?php $view->extend('::admin.html.php');?>
    <div id="content" class="clearfix"> 
	<div class="container">
    <h1>Cms Page List</h1> 
    <table class="gtable sortable">
        <thead>
        <tr>           
            <th width="30" align="center">#</th>           
            <th align="left" width="50" class="status">Name</th>
            <th align="left" width="50" class="status">Seo</th>
            <th align="left" width="50" class="status">Content</th>
            <th width="30" align="center">Edit</th>
        </tr>
        </thead>
      <tbody>  		   
      <?php 	
	  for($i=0;$i<count($cmslist);$i++) {  ?>
      <tr>
      
       <td align="center"><?php echo $i+1; ?></td>
        <td align="left"><?php echo $cmslist[$i]['pagename']; ?></td>     
        <td align="left"><?php echo $cmslist[$i]['seo']; ?></td>     
        <td align="left"><?php echo $cmslist[$i]['content']; ?></td>     
        <td align="center"><a href="cmscontent?id=<?php echo $cmslist[$i]['staticpageId']; ?>"><img src="/project/backyards/img/icons/edit.png" border="0" alt="Edit" /></a></td>      </tr>
      <?php }  ?>
              </tbody>
    </table>
  </div>
</div>
