<?php $view->extend('::user.html.php');
$em = $this->container->get('doctrine')->getManager();
$bucketurl=$this->container->get('mytrip_admin.helper.amazon')->getOption('url');
$imgurl=str_replace(array("/app_dev.php","/app.php"),"",$this->container->get('router')->getContext()->getBaseUrl())."/timthumb.php";
$rating=$em->createQuery("SELECT d, SUM(d.rating) AS rating, COUNT(d) AS counts FROM MytripAdminBundle:Review d WHERE d.status='Active' AND d.reviewType='Hostal' AND d.typeId='".$hostals[0]['hostalId']."' GROUP BY d.typeId")->getArrayResult();
if(!empty($rating)){
	$ratings=($rating[0]['rating']/$rating[0]['counts'])*20;
	$reviewcount=$rating[0]['counts'];
}else{
	$ratings=0;
	$reviewcount=0;
}
$price=$em->createQuery("SELECT MIN(d.price) AS min_price, MAX(d.price) AS max_price FROM MytripAdminBundle:HostalRooms d WHERE d.hostal='".$hostals[0]['hostalId']."'")->getArrayResult();
?>
<link rel="stylesheet" href="<?php echo $view['assets']->getUrl('css/jquery.mCustomScrollbar.css'); ?>" type="text/css">
<script src="<?php echo $view['assets']->getUrl('js/slippry.js'); ?>" type="text/javascript"></script>
<script src="<?php echo $view['assets']->getUrl('js/slider_des.js'); ?>" type="text/javascript"></script>

<link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('css/slider.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('css/dateTimePicker.css'); ?>" />

<section class="hostal-border">
<?php if(!empty($banner)){?>
<div class="hostal-sec">
    <section class="hostal-banner">
        <ul id="slslide">
          <?php
        foreach($banner as $banner){
            echo '<li><a><img src="'.$imgurl."?src=".$bucketurl.$banner['image'].'&w=1240&h=471'.'" alt="'.$hostal_content['0']['name'].'"></a></li>';
        }
        ?>
        </ul>
    </section>
</div>
<?php }?>
<input type="hidden" id="minprice" value="<?php echo $price[0]['min_price']*$view['session']->get('conversionrate');?> 
<?php echo $view['session']->get('currency');?>"/>
<input type="hidden" id="maxprice" value="<?php echo $price[0]['max_price']*$view['session']->get('conversionrate');?> 
<?php echo $view['session']->get('currency');?>"/>
<div class="t-destination">
  <div class="container">
    <div class="hostal-info">
      <h1><?php echo $hostal_content['0']['name'];?></h1>
      <div class="rate_star"><span class="b-star"><span class="rstar" style="width:<?php echo $ratings;?>%"></span></span><br/><h6><?php echo $view['translator']->trans('Price');?> : <?php echo $price[0]['min_price']*$view['session']->get('conversionrate');?> - <?php echo $price[0]['max_price']*$view['session']->get('conversionrate');?> <?php echo $view['session']->get('currency');?></h6></div>
      <div class="chk-bl"> <a href="#availabilitycalender"  name="availabilitycalender" rel="leanModal" id="checkavail" class="go">
        <div class="chk-button"><?php echo $view['translator']->trans('Check Availability');?></div></a>
        <a href="<?php echo $this->container->get('router')->getContext()->getBaseUrl()."/booking/".$destinations[0]['url']."/".$hostals[0]['url'];?>">
        <div class="chk-button make-b"><?php echo $view['translator']->trans('Make Booking Now');?></div>
        </a></div>
    </div>
    <div class="row">
      <div class="row-cover">
        <div class="row-one  destination-x">
          <h1><?php echo $view['translator']->trans('Description');?></h1>
          <img src="<?php echo $view['assets']->getUrl('img/icon3.png');?>" width="48" height="41" alt="<?php echo $view['translator']->trans('Description');?>">
          <p><?php echo $hostal_content['0']['description'];?></p>
        </div>
      </div>
      <div class="row-cover">
        <div class="row-one  destination-x">
          <h1><?php echo $view['translator']->trans('How to get here?');?></h1>
          <img src="<?php echo $view['assets']->getUrl('img/icon5.png');?>" width="26" height="40" alt="<?php echo $view['translator']->trans('How to get here?');?>">
          <p><?php echo $hostal_content['0']['locationDesc'];?></p>
          <div class="map">
            <div id="gmap_canvas" style="height:150px;width:100%;"></div>
          </div>
        </div>
      </div>
      <div class="row-cover">
        <div class="row-one  destination-x border-none">
          <h1><?php echo $view['translator']->trans('Features');?></h1>
          <img src="<?php echo $view['assets']->getUrl('img/icon6.png');?>" alt="<?php echo $view['translator']->trans('Features');?>">
           <?php if(!empty($hostal_feature)){?>
          <ul class="site clearfix">
          <?php 
		  foreach($hostal_feature as $hostal_features){
			   $hostal_feature_content=$em->createQuery("SELECT fc FROM MytripAdminBundle:FeatureContent fc WHERE fc.lan='".$view['session']->get('language')."' AND fc.feature2='".$hostal_features['featureId']."'")->getArrayResult();
			  if(empty($hostal_feature_content)){
				  $hostal_feature_content=$em->createQuery("SELECT fc FROM MytripAdminBundle:FeatureContent fc WHERE fc.lan='en' AND fc.feature2='".$hostal_features['featureId']."'")->getArrayResult();
			  }
		  ?>
            <li><a><img src="<?php echo $view['assets']->getUrl(($hostal_features['icon']!=''?'img/feature_icon/'.$hostal_features['icon']:'img/site.png'));?>" width="24" height="20"/>&nbsp;&nbsp;<?php echo $hostal_feature_content[0]['feature'];?></a></li>
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
    <h1><?php echo $view['translator']->trans('Snapshots');?></h1>
    <img src="<?php echo $view['assets']->getUrl('img/tv.png');?>" alt="<?php echo $view['translator']->trans('Snapshoots');?>">
    <div class="clearfix video-sec">
     <?php 
	if(preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$hostals[0]['video'])){
		list($web,$param)=explode("=",$hostals[0]['video']);
		echo '<iframe width="420" height="315" src="//www.youtube.com/embed/'.$param.'" frameborder="0" allowfullscreen></iframe>';
	}else{
		 echo $hostals[0]['video'];
	}
	?> 
     <?php //echo $hostals[0]['video'];?>
    </div>
    <div class="row">
      <div class="row clearfix" > <span class="destination-right-head border-none destination-left-heder">
        <h1><?php echo $view['translator']->trans('Write a Review');?></h1>
        <img src="<?php echo $view['assets']->getUrl('img/review.png');?>" alt="<?php echo $view['translator']->trans('Write a Review');?>"> </span>
        <form class="write-review write-review-margin" name="reviewform" id="reviewform">
          <textarea name="hostalreview" id="hostalreview" class="reviewtext"></textarea>
          <input type="hidden" name="ratings" id="ratings"/>
          <input type="hidden" name="type" id="reviewtype" value="Hostal"/>
          <input type="hidden" name="hostal" id="reviewhostal" value="<?php echo $hostals[0]['url'];?>"/>
          <input type="hidden" name="destination" id="reviewdestination" value="<?php echo $destinations[0]['url'];?>"/>
          <h2><?php echo $view['translator']->trans('How would you rate the hostal?');?></h2><div class="stars">
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
  <div class=" review-sec destination-right"> <span class="destination-right-head">
    <h1><?php echo $view['translator']->trans('Reviews');?> </h1>
    <img src="<?php echo $view['assets']->getUrl('img/icon2.png');?>" width="47" height="40" alt="<?php echo $view['translator']->trans('Reviews');?>"> <span class="com-txt">(<?php echo $reviewcount;?>)</span> </span>
    <div class="row clearfix  content mCustomScrollbar" id="review_content_bar">
     <?php if($hostals[0]['tripadvisor']!=''){?>
   <div class="row-right">
        <div class="row-one row-right-bor">
          <div class="review"><?php echo $hostals[0]['tripadvisor'];?></div>
        </div>
   </div>
   <?php }?>
    <?php
	$reviews=$em->createQuery("SELECT d,IDENTITY(d.user) AS user FROM MytripAdminBundle:Review d WHERE d.status='Active' AND d.reviewType='Hostal' AND d.typeId='".$hostals[0]['hostalId']."'")->getArrayResult();
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
		if($hostals[0]['tripadvisor']==''){
		?>
        <div class="row-right">
        <div class="row-one row-right-bor">
          <div class="review">
          <h2><?php echo $view['translator']->trans('No reviews found in this hostal');?></h2>
          </div>
          </div>
          </div>
        <?php
		}
	}?>      
    </div>
  </div>
</div>

<div id="availabilitycalender" class="avail_cal">
<a class="modal_close"></a>
<div class="avail_cal_head">
<ul class="cal"><li><div class="avail"></div><?php echo $view['translator']->trans('Available');?></li><li><div class="navail"></div><?php echo $view['translator']->trans('Not Available');?></li><li><div class="to_conf"></div><?php echo $view['translator']->trans('To Confirm');?></li><li><div class="to_past"></div><?php echo $view['translator']->trans('Past');?></li></ul>
<ul class="cal_page"><li><div class="cal_prev"><img src="<?php echo $view['assets']->getUrl('img/cal_arrow_left.png');?>"> <?php echo $view['translator']->trans('Previous');?></div></li><li>|</li><li><div class="cal_next"><?php echo $view['translator']->trans('Next');?> <img src="<?php echo $view['assets']->getUrl('img/cal_arrow_right.png');?>"></div></li></ul>
</div>
 <div id="show-next-month" data-toggle="calendar"></div>
 <div class="avail_cal_head">
<p>* <?php echo $view['translator']->trans('The calender is updated every five minutes and is only an approximation of availability. We suggest that you contact us to confirm availability before submitting a reservation request. Some hosts set custom pricing for certain days on their calendar, like weekends or holidays. The rates listed are per day and do not include any cleaning fee or rates for extra people the host may have for this listing. Please refer to the listings Description tab for more details.');?></p>

</div>
  </div>

</section>
<script src="<?php echo $view['assets']->getUrl('js/jquery.mCustomScrollbar.concat.min.js'); ?>" type="text/javascript"></script> 
<script src="<?php echo $view['assets']->getUrl('js/scroll_bar.js'); ?>" type="text/javascript"></script> 
<script src="<?php echo $view['assets']->getUrl('js/dateTimePicker.js'); ?>" type="text/javascript"></script> 
<script src="<?php echo $view['assets']->getUrl('js/cal_script.js'); ?>" type="text/javascript"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script> 
<script type="text/javascript" src="<?php echo $view['assets']->getUrl('js/google_map.js'); ?>"></script> 
<script type="text/javascript">
google.maps.event.addDomListener(window, 'load', init_map(<?php echo $hostals[0]['latitude'];?>,<?php echo $hostals[0]['longitude'];?>,"<b><?php echo $hostal_content['0']['name'];?></b><br/><?php echo $hostal_content['0']['address'];?>"));
<?php
if(isset($_REQUEST['cal'])){
	?>
	$(document).ready(function(){
		$('#checkavail').click();
	});
	<?php
}
?>
</script>