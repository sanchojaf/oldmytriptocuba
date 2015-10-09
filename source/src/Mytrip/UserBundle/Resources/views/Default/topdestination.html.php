<?php $view->extend('::user.html.php');
$bucketurl=$this->container->get('mytrip_admin.helper.amazon')->getOption('url');
$imgurl=str_replace(array("/app_dev.php","/app.php"),"",$this->container->get('router')->getContext()->getBaseUrl())."/timthumb.php";
$formatter = new \NumberFormatter($view['session']->get('language'),\NumberFormatter::DECIMAL);
$em = $this->container->get('doctrine')->getManager();
 $topreview=$em->createQuery("SELECT d.typeId,(SUM(d.rating)/COUNT(d)) AS HIDDEN rate FROM MytripAdminBundle:Review d  LEFT JOIN MytripAdminBundle:Destination r WITH r.destinationId=d.typeId WHERE r.status='Active' AND d.reviewType='Destination' AND d.status='Active' GROUP BY d.typeId ORDER BY rate DESC ")->setMaxResults('12')->getArrayResult(); 
 if(empty($topreview)){
	 $topreview=$em->createQuery("SELECT d FROM MytripAdminBundle:Destination d WHERE d.status='Active'")->setMaxResults('12')->getArrayResult();
 }
?>
<script src="<?php echo $view['assets']->getUrl('js/slippry.js'); ?>" type="text/javascript"></script>
<script src="<?php echo $view['assets']->getUrl('js/slider_des.js'); ?>" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('css/slider.css'); ?>" >
<?php 
if(!empty($topreview)){
	?>
<section>
<ul id="slslide">
<?php 
  foreach($topreview as $topreviews){
	  if(empty($topreviews['typeId'])){
		  $topreviews['typeId']=$topreviews['destinationId'];
	  }
	  $bdestination=$em->createQuery("SELECT p FROM MytripAdminBundle:Destination p  where  p.destinationId='".$topreviews['typeId']."'")->getArrayResult();
	  $banners=$em->createQuery("SELECT p FROM MytripAdminBundle:Banner p WHERE p.bannerType='Destination' AND p.status='Active' AND p.typeId='".$topreviews['typeId']."' GROUP BY p.typeId")->getArrayResult();	
	  $bdestination_content=$em->createQuery("SELECT p FROM MytripAdminBundle:DestinationContent p  where  p.destination='".$topreviews['typeId']."' AND p.lan='".$view['session']->get('language')."'")->getArrayResult();
		if(empty($bdestination_content)){
			$bdestination_content=$em->createQuery("SELECT p FROM MytripAdminBundle:DestinationContent p where p.destination='".$topreviews['typeId']."' AND p.lan='en'")->getArrayResult();
		}
	 if(!empty($banners)){
	  ?>
     <li><a href="<?php echo $this->container->get('router')->getContext()->getBaseUrl()."/".$bdestination[0]['url'];?>">
     <img src="<?php echo $imgurl."?src=".$bucketurl.$banners[0]['image']."&w=1457&h=491"; ?>" alt="<?php echo $bdestination_content['0']['name'];?>" /></a></li>
    <?php 
	 }
  }
  ?>    
</ul>
</section>
<?php 
}
?>
<div class="t-destination">
  <div class="container">
    <h1><?php echo $view['translator']->trans('Top Destinations');?>.&nbsp; &nbsp;<span><?php echo $view['translator']->trans('Not sure where to stay?');?> ...<?php echo $view['translator']->trans('Let us suggest the best of the best');?></span></h1>
    <div class="des-contain">
        <div class="destination">
         <?php 
		 $k=0; 
		 foreach($topreview as $topreviews) { $k++;	
			if(empty($topreviews['typeId'])){
				$topreviews['typeId']=$topreviews['destinationId'];
			}
			 $destination=$em->createQuery("SELECT c FROM MytripAdminBundle:Destination c WHERE  c.destinationId='".$topreviews['typeId']."'")->getArrayResult();	
			 $destination_contents=$em->createQuery("SELECT c FROM MytripAdminBundle:DestinationContent c WHERE c.lan='".$view['session']->get('language')."' AND c.destination='".$destination[0]['destinationId']."'")->getArrayResult();
			 if(empty($destination_contents)){
				  $destination_contents=$em->createQuery("SELECT c FROM MytripAdminBundle:DestinationContent c WHERE c.lan='en' AND c.destination='".$destination[0]['destinationId']."'")->getArrayResult();
				  
			  }
			 $destination_image=$em->createQuery("SELECT c FROM MytripAdminBundle:DestinationImage c WHERE c.destination='".$destination[0]['destinationId']."'")->getArrayResult();
			 if(!empty($destination_image)){
				$dimg=$imgurl."?src=".$bucketurl.$destination_image[0]['image']."&w=225&h=123";
			}else{
				$dimg=$view['assets']->getUrl('img/no-des.jpg');
			}
			 $count=$em->createQuery("SELECT SUM(p.count) AS visitcount FROM MytripAdminBundle:Visits p  WHERE  p.visitType='Destination' AND p.typeId='".$destination[0]['destinationId']."'")->getArrayResult(); ?>
            <div class="destination-sec destination-small-fig destination-grid">
            <div class="destination-fig destination-fig-pad">
            <span class="top-corner"></span><a href="<?php echo $this->container->get('router')->getContext()->getBaseUrl()."/".$destination[0]['url'];?>" ><img src="<?php echo $dimg;?>" alt="<?php echo $destination_contents[0]['name']; ?>"></a></div>
            <h2><a href="<?php echo $this->container->get('router')->getContext()->getBaseUrl()."/".$destination[0]['url'];?>" ><?php echo substr($destination_contents[0]['name'],0,28); ?></a></h2>
            <p><?php echo $formatter->format($count[0]['visitcount']);?> <?php echo $view['translator']->trans('visits');?></p>
          </div>
          <?php   if($k%4==0) {  ?>
           </div>
		   <div class="destination">
		    <?php } 
			  } ?>
         
          </div>
      </div>
    <span class="w-destination">
    <h1><?php echo $view['translator']->trans('What is top Destination for us?');?></h1>
    <img width="47" height="41" src="<?php echo $view['assets']->getUrl('img/star-1.png'); ?>">
    <?php echo $destination_content['0']['content'];?>
    </span> <span class="border-story"></span>
    <p><a href="<?php echo $view['router']->generate('mytrip_user_destination');?>"><?php echo $view['translator']->trans('All the destination guides');?> </a></p>
  </div>
</div>
<div class="container">
  <div class="row review-sec">
  <?php
  $tarray= $this->container->get('mytrip_admin.helper.date')->array_column($topreview, 'typeId');
  $topdestinationreviews=$em->createQuery("SELECT d,IDENTITY(d.user) AS user FROM MytripAdminBundle:Review d WHERE d.reviewType='Destination' AND d.status='Active' AND d.typeId IN ('".implode("','",$tarray)."') ORDER BY d.reviewId DESC")->setMaxResults('6')->getArrayResult();
  ?>
    <h1><?php echo $view['translator']->trans('Top Destination Reviews');?></h1>
    <img src="<?php echo $view['assets']->getUrl('img/star-2.png'); ?>" width="47" height="40" alt="icon" align="left" style="float:left;">
    <div class="row clearfix">
      <div class="top-review">
      <?php
	  $r=0;
	  if(!empty($topdestinationreviews)){
		  foreach($topdestinationreviews as $topdestinationreview){
			  $tdestination=$em->createQuery("SELECT c FROM MytripAdminBundle:Destination c WHERE  c.destinationId='".$topdestinationreview[0]['typeId']."'")->getArrayResult();
			  $topdestination_content=$em->createQuery("SELECT c FROM MytripAdminBundle:DestinationContent c WHERE c.lan='".$view['session']->get('language')."' AND c.destination='".$tdestination[0]['destinationId']."'")->getArrayResult();
			  if(empty($topdestination_content)){
				  $topdestination_content=$em->createQuery("SELECT c FROM MytripAdminBundle:DestinationContent c WHERE c.lan='en' AND c.destination='".$tdestination[0]['destinationId']."'")->getArrayResult();
			  }
			  $ruser=$em->createQuery("SELECT d FROM MytripAdminBundle:User d WHERE d.userId='".$topdestinationreview['user']."'")->getArrayResult();
			  $count=$em->createQuery("SELECT SUM(p.count) AS visitcount FROM MytripAdminBundle:Visits p  WHERE  p.visitType='Destination' AND p.typeId='".$topdestinationreview[0]['typeId']."'")->getArrayResult();	
			  $pimg=$view['assets']->getUrl('img/bigprofileimage.jpg');
				if($ruser[0]['image']!=''){
					$pimg=$view['assets']->getUrl('img/user/'.$ruser[0]['image']);
				}else{
					$proimg=$em->createQuery("SELECT u, (RAND()) AS HIDDEN r FROM MytripAdminBundle:UserSocialLink u WHERE u.user='".$topdestinationreviews[0]['user']."' AND u.image !='' ORDER BY r ")->setMaxResults(1)->getArrayResult();
					if(!empty($proimg)){
						$pimg=$proimg[0]['image'];
					}
				}		  		
	  ?>
        <div class="row-one story-review">
          <h2><?php echo $topdestination_content[0]['name'];?></h2>
          <p><?php echo $formatter->format($count[0]['visitcount']);?> <?php echo $view['translator']->trans('Visits');?></p>
          <p><?php  echo $topdestinationreview[0]['review'];?></p>
          <div class="review">
            <div class="review-fiq"><img src="<?php echo $imgurl.'?src='.$pimg.'&w=54&h=53';?>" width="54" height="53"></div>
            <div class="review-left"> <div class="reviewstar"><span class="star-review"><span class="userreviews" style="width:<?php echo $topdestinationreview[0]['rating']*20;?>%"></span></span></div>
              <h2><?php echo $ruser[0]['email'];?></h2>
            </div>
          </div>
        </div>
       <?php
	   $r++;
	   if($r%3==0){
		   echo '</div>
      <div class="top-review">';
	   }
		  }
	  }else{
	  ?>
      <h2><?php echo $view['translator']->trans('No reviews found');?></h2>
      <?php
	  }
	  ?>        
      </div>
    </div>
  </div>
</div>
