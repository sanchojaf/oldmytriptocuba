<?php $view->extend('::user.html.php');
$em = $this->container->get('doctrine')->getManager();
$bucketurl=$this->container->get('mytrip_admin.helper.amazon')->getOption('url');
$imgurl=str_replace(array("/app_dev.php","/app.php"),"",$this->container->get('router')->getContext()->getBaseUrl())."/timthumb.php";
$formatter = new \NumberFormatter($view['session']->get('language'),\NumberFormatter::DECIMAL);
$rating=$em->createQuery("SELECT d, SUM(d.rating) AS rating, COUNT(d) AS counts FROM MytripAdminBundle:Review d WHERE d.status='Active' AND d.reviewType='Destination' AND d.typeId='".$destination[0]['destinationId']."' GROUP BY d.typeId")->getArrayResult();
if(!empty($rating)){
	$ratings=($rating[0]['rating']/$rating[0]['counts'])*20;
	$reviewcount=$rating[0]['counts'];
}else{
	$ratings=0;
	$reviewcount=0;
}
$topreview=$em->createQuery("SELECT d.typeId,(SUM(d.rating)/COUNT(d)) AS HIDDEN rate FROM MytripAdminBundle:Review d  LEFT JOIN MytripAdminBundle:Destination r WITH r.destinationId=d.typeId WHERE r.status='Active' AND d.reviewType='Destination' AND d.status='Active' GROUP BY d.typeId ORDER BY rate DESC ")->setMaxResults('12')->getArrayResult(); 
$tarray= $this->container->get('mytrip_admin.helper.date')->array_column($topreview, 'typeId');
?>
<script src="<?php echo $view['assets']->getUrl('js/slippry.js'); ?>" type="text/javascript"></script>
<script src="<?php echo $view['assets']->getUrl('js/slider_des.js'); ?>" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo $view['assets']->getUrl('css/jquery.mCustomScrollbar.css'); ?>" type="text/css">
<link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('css/slider.css'); ?>" >
<?php if(!empty($banner)){?>
<section>
<ul id="slslide">
<?php
foreach($banner as $banner){
	echo '<li><a><img src="'.$imgurl."?src=".$bucketurl.$banner['image']."&w=1457&h=491".'" alt="'.$destination_content['0']['name'].'"></a></li>';
}
?>
</ul>
</section>
<?php }?>
<div class="t-destination">
  <div class="container">
    <div class="desti-x">
      <ul class="breadcumbs">
        <li ><a href="<?php echo $view['router']->generate('mytrip_user_destination');?>" class="first"><?php echo $view['translator']->trans('Destination Guides');?></a></li>
        <li ><a><i class="fa fa-chevron-right"></i> <?php echo $destination_content['0']['name'];?> <span>(<?php 
        $count=$em->createQuery("SELECT SUM(p.count) AS visitcount FROM MytripAdminBundle:Visits p  WHERE  p.visitType='Destination' AND p.typeId='".$destination[0]['destinationId']."'")->getArrayResult();
		echo $formatter->format($count[0]['visitcount']);
		?> 
		<?php echo $view['translator']->trans('visits');?>)</span></a></li>
        <li><span class="b-star"><span class="rstar" style="width:<?php echo $ratings;?>%"></span></span></li>
      </ul>
      <?php
	  if(in_array($destination[0]['destinationId'],$tarray)){
	  ?>
      <div class="chk-bl"><ul class="breadcumbs">
        <li><a><?php echo $view['translator']->trans('Top Destinations');?></a></li>
        <li> <img src="<?php echo $view['assets']->getUrl('img/star-1.png'); ?>" width="52" height="47" alt="star"></li>
      </ul></div>
      <?php }?>
    </div>
    <div class="row">
      <div class="row-cover">
        <div class="row-one  destination-x">
          <h1><?php echo $view['translator']->trans('Description');?></h1>
          <img src="<?php echo $view['assets']->getUrl('img/icon3.png'); ?>" width="48" height="41" alt="<?php echo $view['translator']->trans('Description');?>">
          <p><?php echo $destination_content[0]['description'];?></p>
        </div>
      </div>
      <div class="row-cover">
        <div class="row-one  destination-x">
          <h1><?php echo $view['translator']->trans('How to get here?');?></h1>
          <img src="<?php echo $view['assets']->getUrl('img/icon5.png'); ?>" width="26" height="40" alt="<?php echo $view['translator']->trans('How to get here?');?>">
          <p><?php echo $destination_content[0]['locationDesc'];?></p>
          <div class="map">
            <div id="gmap_canvas" style="height:150px;width:100%;"></div>   
          </div>
        </div>
      </div>
      <div class="row-cover">
        <div class="row-one  destination-x border-none">
          <h1><?php echo $view['translator']->trans('Sites of interest');?></h1>
          <img src="<?php echo $view['assets']->getUrl('img/icon6.png'); ?>" alt="<?php echo $view['translator']->trans('Sites of interest');?>">
          <?php if(!empty($destination_feature)){?>
          <ul class="site clearfix">
          <?php 
		  foreach($destination_feature as $destination_features){
			  $destination_feature_content=$em->createQuery("SELECT fc FROM MytripAdminBundle:FeatureContent fc WHERE fc.lan='".$view['session']->get('language')."' AND fc.feature2='".$destination_features['featureId']."'")->getArrayResult();
			  if(empty($destination_feature_content)){
				  $destination_feature_content=$em->createQuery("SELECT fc FROM MytripAdminBundle:FeatureContent fc WHERE fc.lan='en' AND fc.feature2='".$destination_features['featureId']."'")->getArrayResult();
			  }
		  ?>
            <li><a><img src="<?php echo $view['assets']->getUrl(($destination_features['icon']!=''?'img/feature_icon/'.$destination_features['icon']:'img/site.png'));?>" width="24" height="20"/>&nbsp;&nbsp;<?php echo $destination_feature_content[0]['feature'];?></a></li>
           <?php }?>            
          </ul>
          <?php }?>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container">
  <div class="destination-left review-sec">
    <h1><?php echo $view['translator']->trans('Snapshots');?> </h1>
    <img src="<?php echo $view['assets']->getUrl('img/tv.png'); ?>" alt="<?php echo $view['translator']->trans('Snapshoots');?>">
    <div class="clearfix video-sec">
    <?php 
	if(preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$destination[0]['video'])){
		list($web,$param)=explode("=",$destination[0]['video']);
		echo '<iframe width="420" height="315" src="//www.youtube.com/embed/'.$param.'" frameborder="0" allowfullscreen></iframe>';
	}else{
		 echo $destination[0]['video'];
	}
	?>     
    </div>
    <div class="row" id="hdetail"> <span class="destination-right-head destination-left-heder">
      <h1><?php echo $view['translator']->trans('Houses');?> </h1>
      
      <img src="<?php echo $view['assets']->getUrl('img/icon2.png'); ?>" width="47" height="40" alt="<?php echo $view['translator']->trans('Houses');?>"> <span class="com-txt">(<?php echo $hostal_count['0']['hostalcount'];?>)</span> </span>
      <?php 
	  if(!empty($hostals)){		
	  foreach($hostals as $hostals){		 
		  $hostal_image=$em->createQuery("SELECT p FROM MytripAdminBundle:HostalImage p where p.hostal='".$hostals['hostalId']."'")->getArrayResult();
		  $hostal_content=$em->createQuery("SELECT p FROM MytripAdminBundle:HostalContent p where p.hostal='".$hostals['hostalId']."' AND p.lan='".$view['session']->get('language')."'")->getArrayResult();
			if(empty($hostal_content)){
				$hostal_content=$em->createQuery("SELECT p FROM MytripAdminBundle:HostalContent p where p.hostal='".$hostals['hostalId']."' AND p.lan='en'")->getArrayResult();
			}
			$hostalreview=$em->createQuery("SELECT d, (SUM(d.rating)/COUNT(d)) AS rating FROM MytripAdminBundle:Review d WHERE d.status='Active' AND d.reviewType='Hostal' AND d.typeId='".$hostals['hostalId']."' GROUP BY d.typeId")->getArrayResult();
		  ?>
	  <div class="reviews-sec">
        <div class="cover">
          <div class="review-sec-left"><a href="<?php echo $this->container->get('router')->getContext()->getBaseUrl()."/".$destination[0]['url']."/".$hostals['url'];?>"><img src="<?php echo $imgurl."?src=".$bucketurl.$hostal_image[0]['image']."&w=228&h=125"; ?>" alt="<?php echo $hostal_content[0]['name'];?>"></a></div>
          <div class="review-sec-right">
            <h2><a href="<?php echo $this->container->get('router')->getContext()->getBaseUrl()."/".$destination[0]['url']."/".$hostals['url'];?>" title="<?php echo $hostal_content[0]['name'];?>"><?php echo substr($hostal_content[0]['name'],0,20).(strlen($hostal_content[0]['name'])>20?'...':'');?></a><span><?php echo $hostal_content[0]['city'];?> - <?php echo $hostal_content[0]['province'];?></span> </h2>
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
  <div class=" review-sec destination-right"> <span class="destination-right-head">
    <h1><?php echo $view['translator']->trans('Reviews');?> </h1>
    <img src="<?php echo $view['assets']->getUrl('img/icon2.png'); ?>" width="47" height="40" alt="<?php echo $view['translator']->trans('Reviews');?>"> <span class="com-txt">(<?php echo $reviewcount;?>)</span> </span>    
    <div class="row clearfix  content mCustomScrollbar" id="review_content_bar">
    <?php if($destination[0]['tripadvisor']!=''){?>
   <div class="row-right">
        <div class="row-one row-right-bor">
          <div class="review"><?php echo $destination[0]['tripadvisor'];?></div>
        </div>
   </div>
   <?php }?>
    <?php
	$reviews=$em->createQuery("SELECT d,IDENTITY(d.user) AS user FROM MytripAdminBundle:Review d WHERE d.status='Active' AND d.reviewType='Destination' AND d.typeId='".$destination[0]['destinationId']."'")->getArrayResult();
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
        <div class="row-one row-right-bor">
          <div class="review">
            <div class="review-fiq"><img src="<?php echo $imgurl.'?src='.$pimg.'&w=54&h=53';?>" width="54" height="53"></div>
            <div class="review-left"><div class="reviewstar"><span class="star-review"><span class="userreviews" style="width:<?php echo $reviews[0]['rating']*20;?>%"></span></span></div>
              <h2><?php echo $ruser[0]['email'];?></h2>
            </div>
          </div>
          <p><?php echo $reviews[0]['review'];?></p>
        </div>
      </div>
     <?php }
	}else{
		if($destination[0]['tripadvisor']==''){
		?>
        <div class="row-right">
        <div class="row-one row-right-bor">
          <div class="review">
          <h2><?php echo $view['translator']->trans('No reviews found in this destination');?></h2>
          </div>
          </div>
          </div>
        <?php
		}
	}?>      
    </div>
    <div class="destination-right-head"></div>
    <div class="row clearfix"> <span class="destination-right-head border-none destination-write-head">
      <h1><?php echo $view['translator']->trans('Write a Review');?></h1>
      <img src="<?php echo $view['assets']->getUrl('img/review.png'); ?>" alt="<?php echo $view['translator']->trans('Write a Review');?>"> </span>     
      <form class="write-review" name="reviewform" id="reviewform">
          <textarea name="hostalreview" id="hostalreview" class="reviewtext"></textarea>
          <input type="hidden" name="ratings" id="ratings"/>
          <input type="hidden" name="type" id="reviewtype" value="Destination"/>
          <input type="hidden" name="hostal" id="reviewhostal" value=""/>
          <input type="hidden" name="destination" id="reviewdestination" value="<?php echo $destination[0]['url'];?>"/>
          <h2><?php echo $view['translator']->trans('How would you rate the destination?');?></h2><div class="stars">
            <input type="radio" name="rating" class="star-1" id="star-1" value="1" />
            <label class="star-1" for="star-1">1</label>
            <input type="radio" name="rating" class="star-2" id="star-2" value="2"  />
            <label class="star-2" for="star-2">2</label>
            <input type="radio" name="rating" class="star-3" id="star-3" value="3"  />
            <label class="star-3" for="star-3">3</label>
            <input type="radio" name="rating" class="star-4" id="star-4" value="4"  />
            <label class="star-4" for="star-4">4</label>
            <input type="radio" name="rating" class="star-5" id="star-5" value="5"  />
            <label class="star-5" for="star-5">5</label>
            <span></span>
        </div>
          <input id="reviewsubmit" name="reviewsubmit" type="submit" value="<?php echo $view['translator']->trans('Submit review');?>" class="submit-review">
        </form>
    </div>
  </div>
</div>
<script src="<?php echo $view['assets']->getUrl('js/jquery.mCustomScrollbar.concat.min.js'); ?>" type="text/javascript"></script> 
<script src="<?php echo $view['assets']->getUrl('js/scroll_bar.js'); ?>" type="text/javascript"></script> 
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="<?php echo $view['assets']->getUrl('js/google_map.js'); ?>"></script>
<script type="text/javascript">
google.maps.event.addDomListener(window, 'load', init_map(<?php echo $destination[0]['latitude'];?>,<?php echo $destination[0]['longitude'];?>,"<b><?php echo $destination_content['0']['name'];?></b><br/><?php echo $destination_content['0']['address'];?>"));
</script>
<style>
#CDSWIDDMO{
	width:auto;
}
</style>