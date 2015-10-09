<?php $view->extend('::user.html.php');
$bucketurl=$this->container->get('mytrip_admin.helper.amazon')->getOption('url');
$imgurl=str_replace(array("/app_dev.php","/app.php"),"",$this->container->get('router')->getContext()->getBaseUrl())."/timthumb.php";
$formatter = new \NumberFormatter($view['session']->get('language'),\NumberFormatter::DECIMAL);
?>
<script src="<?php echo $view['assets']->getUrl('js/slippry.js'); ?>" type="text/javascript"></script>
<script src="<?php echo $view['assets']->getUrl('js/slider_des.js'); ?>" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('css/slider.css'); ?>" >
<?php 
if(!empty($banner)){?>
<section>
<ul id="slslide">
<?php 
foreach($banner as $banners){ ?>
  <li><a><img src="<?php echo $imgurl."?src=".$bucketurl.$banners['image']."&w=1457&h=491"; ?>" /></a></li>
  <?php
}
?> 
</ul>
</section>
<?php }?>
<div class="story-container">
  <div class="container">
    <div class="story-left-container">
      <ul class="breadcumbs">
        <li ><a href="<?php echo $view['router']->generate('mytrip_user_story');?>" class="first"><?php echo $view['translator']->trans('Our Stories');?> </a></li>
        <li ><a href="#"><i class="fa fa-chevron-right"></i><?php echo $story_content[0]['name'];?> <span>(<?php
        $em = $this->container->get('doctrine')->getManager();
		$count=$em->createQuery("SELECT SUM(p.count) AS visitcount FROM MytripAdminBundle:Visits p  WHERE  p.visitType='Story' AND p.typeId='".$story[0]['storyId']."'")->getArrayResult();
		echo $count[0]['visitcount'];
		?> <?php echo $view['translator']->trans('visits');?>)</span></a></li>
      </ul>
      <div class="story-left">
      <?php echo $story_content['0']['content'];?>        
      </div>      
    </div>
    <div class="story-right-container">
      <div class="story-description row-one">
        <h1><?php echo $hostals['1']['name'];?></h1>
        <img src="<?php echo $view['assets']->getUrl('img/icon.png'); ?>" width="48" height="41" alt="hospitality">
        <p><?php echo $hostals['1']['description'];?></p>
        <!--<span class="readmore"><a href="#">Read more</a></span>--> </div>
      <div class="story-description row-one">
        <h1><?php echo $view['translator']->trans('How to get here?');?></h1>
        <img src="<?php echo $view['assets']->getUrl('img/icon.png'); ?>" width="48" height="41" alt="hospitality">
        <p><?php echo $hostals['1']['locationDesc'];?></p>
        <div class="map">
          <div id="gmap_canvas" style="height:150px;width:100%;"></div> 
        </div>
      </div>
      <div class="  story-description row-one">
      <?php
	  $rating=$em->createQuery("SELECT d, SUM(d.rating) AS rating, COUNT(d) AS counts FROM MytripAdminBundle:Review d WHERE d.status='Active' AND d.reviewType='Hostal' AND d.typeId='".$hostals[0]['hostalId']."' GROUP BY d.typeId")->getArrayResult();
if(!empty($rating)){
	$ratings=($rating[0]['rating']/$rating[0]['counts'])*20;
	$reviewcount=$rating[0]['counts'];
}else{
	$ratings=0;
	$reviewcount=0;
}
	  ?>
        <h1><?php echo $view['translator']->trans('Reviews');?> </h1>
        <img src="<?php echo $view['assets']->getUrl('img/icon2.png'); ?>" width="47" height="40" alt="icon"> <span class="com-txt">(<?php echo $reviewcount;?>)</span>
        <div class="row clearfix">
          <?php
	$reviews=$em->createQuery("SELECT d,IDENTITY(d.user) AS user FROM MytripAdminBundle:Review d WHERE d.status='Active' AND d.reviewType='Hostal' AND d.typeId='".$hostals[0]['hostalId']."' ORDER BY d.reviewId DESC")->setMaxResults(2)->getArrayResult();
	if(!empty($reviews)){
	foreach($reviews as $reviews){
		$ruser=$em->createQuery("SELECT d FROM MytripAdminBundle:User d WHERE d.userId='".$reviews['user']."'")->getArrayResult();
		$pimg=$view['assets']->getUrl('img/bigprofileimage.jpg');
		if($ruser[0]['image']!=''){
			$pimg=$view['assets']->getUrl('img/user/'.$ruser[0]['image']);
		}else{
			$proimg=$em->createQuery("SELECT u, (RAND()) AS HIDDEN r FROM MytripAdminBundle:UserSocialLink u WHERE u.user='".$reviews['user']."' AND u.image !='' ORDER BY r ")->setMaxResults(1)->getArrayResult();
			if(!empty($proimg)){
				$pimg=$proimg[0]['image'];
			}
		}	
	?>
      <div class="row-right">        
          <div class="review">
            <div class="review-fiq"><img src="<?php echo $imgurl.'?src='.$pimg.'&w=54&h=53';?>" width="54" height="53"></div>
            <div class="review-left"><div class="reviewstar"><span class="star-review"><span class="userreviews" style="width:<?php echo $reviews[0]['rating']*20;?>%"></span></span></div>
              <h2><?php echo $ruser[0]['email'];?></h2>
            </div>
          </div>
          <p><?php echo $reviews[0]['review'];?></p>
        
      </div>
     <?php }
	}else{
		?>
        <div class="row-right">
        <div class="row-one row-right-bor" style="border:none;">
          <div class="review">
          <h2><?php echo $view['translator']->trans('No reviews found in this hostal');?></h2>
          </div>
          </div>
          </div>
        <?php
	}?> 
        </div>
      </div>
      <a href="<?php echo $this->container->get('router')->getContext()->getBaseUrl()."/".$destination[0]['url'].'/'.$hostals[0]['url'];?>">
      <div class="submit-review"><?php echo $view['translator']->trans('Visit the Hostals');?></div>
      </a> </div>
  </div>
</div>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script> 
<script type="text/javascript" src="<?php echo $view['assets']->getUrl('js/google_map.js'); ?>"></script> 
<script type="text/javascript">
google.maps.event.addDomListener(window, 'load', init_map(<?php echo $hostals[0]['latitude'];?>,<?php echo $hostals[0]['longitude'];?>,"<b><?php echo $hostals['1']['name'];?></b><br/><?php echo $hostals['1']['address'];?>"));
</script>