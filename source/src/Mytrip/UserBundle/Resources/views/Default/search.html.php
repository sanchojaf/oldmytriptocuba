<?php $view->extend('::user.html.php');
$bucketurl=$this->container->get('mytrip_admin.helper.amazon')->getOption('url');
$imgurl=str_replace(array("/app_dev.php","/app.php"),"",$this->container->get('router')->getContext()->getBaseUrl())."/timthumb.php";
$em = $this->container->get('doctrine')->getManager();
?>
<div class="container">
  <div class="about-banner pay-banner"><img src="<?php echo $view['assets']->getUrl('img/search-result.jpg') ?>"  alt="about"></div>
  <div class="faq">
    <h1>Search results for "<?php echo $_REQUEST['search'];?>"....</h1>
  </div>
  <div class="review-sec" style="overflow:hidden;">
    <div class="row">
      <?php 
	  if(!empty($hostals)){		
	  foreach($hostals as $hostals){		 
		  $hostal_image=$em->createQuery("SELECT p FROM MytripAdminBundle:HostalImage p where p.hostal='".$hostals[0]['hostalId']."'")->getArrayResult();
		  $destination=$em->createQuery("SELECT p FROM MytripAdminBundle:Destination p where p.destinationId='".$hostals['destinations']."'")->getArrayResult();
		  $hostal_content=$em->createQuery("SELECT p FROM MytripAdminBundle:HostalContent p where p.hostal='".$hostals[0]['hostalId']."' AND p.lan='".$view['session']->get('language')."'")->getArrayResult();
			if(empty($hostal_content)){
				$hostal_content=$em->createQuery("SELECT p FROM MytripAdminBundle:HostalContent p where p.hostal='".$hostals[0]['hostalId']."' AND p.lan='en'")->getArrayResult();
			}
			$hostalreview=$em->createQuery("SELECT d, (SUM(d.rating)/COUNT(d)) AS rating FROM MytripAdminBundle:Review d WHERE d.status='Active' AND d.reviewType='Hostal' AND d.typeId='".$hostals[0]['hostalId']."' GROUP BY d.typeId")->getArrayResult();
		  ?>
	  <div class="reviews-sec">
        <div class="cover">
          <div class="review-sec-left"><a href="<?php echo $this->container->get('router')->getContext()->getBaseUrl()."/".$destination[0]['url']."/".$hostals[0]['url'];?>"><img src="<?php echo $imgurl."?src=".$bucketurl.$hostal_image[0]['image']."&w=225&h=123"; ?>" alt="<?php echo $hostal_content[0]['name'];?>"></a></div>
          <div class="review-sec-right">
            <h2><a href="<?php echo $this->container->get('router')->getContext()->getBaseUrl()."/".$destination[0]['url']."/".$hostals[0]['url'];?>" title="<?php echo $hostal_content[0]['name'];?>"><?php echo substr($hostal_content[0]['name'],0,20).(strlen($hostal_content[0]['name'])>20?'...':'');?></a><span><?php echo $hostal_content[0]['city'];?> - <?php echo $hostal_content[0]['province'];?></span> </h2>
             <div class="reviewstar"><span class="star-review"><span class="userreviews" style="width:<?php if(!empty($hostalreview)){echo $hostalreview[0]['rating']*20;}else{ echo '0';}?>%"></span></span></div>
            <p class="clearfix"><?php echo $hostal_content[0]['smallDesc'];?></p>
          </div>
        </div>
      </div>
      <?php }}else{?>      
      <h2><?php echo $view['translator']->trans('No Houses found');?>.</h2>
      <?php }?>
    </div>
  </div>
  <!--<ul class="pagination">
    <li class="prev">&lsaquo;&lsaquo; </li>
    <li class="current">1</li>
    <li><a href="#">2</a></li>
    <li><a href="#">3</a></li>
    <li>...</li>
    <li><a href="#">13</a></li>
    <li class="last"><a href="#"> &raquo;</a></li>
  </ul>-->
</div>
