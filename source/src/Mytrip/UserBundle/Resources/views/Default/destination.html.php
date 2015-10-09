<?php $view->extend('::user.html.php');
$em = $this->container->get('doctrine')->getManager();
$bucketurl=$this->container->get('mytrip_admin.helper.amazon')->getOption('url');
$imgurl=str_replace(array("/app_dev.php","/app.php"),"",$this->container->get('router')->getContext()->getBaseUrl())."/timthumb.php";
$formatter = new \NumberFormatter($view['session']->get('language'),\NumberFormatter::DECIMAL);
?>
<?php 
if(!empty($banner)){?>
<section>
<ul id="slslide">
<?php 
foreach($banner as $banners){ 
$bdestination=$em->createQuery("SELECT p FROM MytripAdminBundle:Destination p  where  p.destinationId='".$banners['typeId']."'")->getArrayResult();
$bdestination_content=$em->createQuery("SELECT p FROM MytripAdminBundle:DestinationContent p  where  p.destination='".$banners['typeId']."' AND p.lan='".$view['session']->get('language')."'")->getArrayResult();
if(empty($bdestination_content)){
	$bdestination_content=$em->createQuery("SELECT p FROM MytripAdminBundle:DestinationContent p where p.destination='".$banners['typeId']."' AND p.lan='en'")->getArrayResult();
}
?>
  <li><a href="<?php echo $this->container->get('router')->getContext()->getBaseUrl()."/".$bdestination[0]['url'];?>"><img src="<?php echo $imgurl."?src=".$bucketurl.$banners['image']."&w=1457&h=491"; ?>" alt="<?php echo $bdestination_content['0']['name'];?>" /></a></li>
  <?php
}
?> 
</ul>
</section>
<?php }?>

    <div class="t-destination">
    <div class="container">
        <h1><?php echo $view['translator']->trans('Our Destinations Guides');?>.&nbsp; &nbsp;<span><?php echo $view['translator']->trans('Take a look to our handy guides for cities all around the island');?></span></h1>
        <span class="w-destination">
      <?php echo $destination_content['0']['content'];?>
      </span>
        <div class="des-contain">
        <div class="destination">
         <?php		 
		 $k=0; foreach($destination as $destinations) {			
			 $count=$em->createQuery("SELECT SUM(p.count) AS visitcount FROM MytripAdminBundle:Visits p  WHERE  p.visitType='Destination' AND p.typeId='".$destinations['destinationId']."'")->getArrayResult();
			 $destinations_content=$em->createQuery("SELECT p FROM MytripAdminBundle:DestinationContent p  WHERE  p.destination='".$destinations['destinationId']."' AND p.lan='".$view['session']->get('language')."'")->getArrayResult();
			 if(empty($destinations_content)){
				  $destinations_content=$em->createQuery("SELECT p FROM MytripAdminBundle:DestinationContent p  WHERE p.destination='".$destinations['destinationId']."' AND p.lan='en'")->getArrayResult();
			 }			
			 $destinations_image=$em->createQuery("SELECT p FROM MytripAdminBundle:DestinationImage  p  WHERE  p.destination='".$destinations['destinationId']."'")->getArrayResult();
			 if(!empty($destinations_image)){
				$dimg=$imgurl."?src=".$bucketurl.$destinations_image[0]['image']."&w=225&h=123";
			}else{
				$dimg=$view['assets']->getUrl('img/no-des.jpg');
			}
			 ?>
            <div class="destination-sec destination-small-fig destination-grid">
            <div class="destination-fig destination-fig-pad"><a href="<?php echo $this->container->get('router')->getContext()->getBaseUrl()."/".$destinations['url'];?>" ><img src="<?php echo $dimg;?>" alt="<?php echo $destinations_content[0]['name']; ?>"></a></div>
            <h2><a href="<?php echo $this->container->get('router')->getContext()->getBaseUrl()."/".$destinations['url'];?>" ><?php echo substr($destinations_content[0]['name'],0,28); ?></a></h2>
            <p><?php echo $formatter->format($count[0]['visitcount']);?> <?php echo $view['translator']->trans('visits');?></p>
          </div>
          <?php  $k++;  if($k%4==0) {  ?>
           </div>
		   <div class="destination">
		    <?php } 
			  } ?>
         
          </div>
      </div>
        <p><a href="<?php echo $view['router']->generate('mytrip_user_topdestination');?>"><?php echo $view['translator']->trans('Top Destinations');?> </a></p>
      </div>
  </div>
    <div class="container">
    <div class="row review-sec">
        <h1><?php echo $view['translator']->trans('Reviews about our Destination Guides?');?>&nbsp;&nbsp;&nbsp;</h1>
        <img src="<?php echo $view['assets']->getUrl('img/icon2.png'); ?>" width="47" height="40" alt="icon" align="left" style="float:left;">
        <div class="row clearfix">
        <?php
		$review=$em->createQuery("SELECT d,IDENTITY(d.user) AS user FROM MytripAdminBundle:Review d LEFT JOIN MytripAdminBundle:Destination r WITH r.destinationId=d.typeId WHERE r.status='Active' AND d.reviewType='Destination' AND d.status='Active' ORDER BY d.reviewId DESC ")->setMaxResults('3')->getArrayResult(); 
		if(!empty($review)){
			foreach($review as $reviews){
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
        <div class="row-cover">
            <div class="row-one">
            <div class="review">
                <div class="review-fiq"><img src="<?php echo $imgurl.'?src='.$pimg.'&w=54&h=53';?>" width="54" height="53"></div>
                <div class="review-left"> <div class="reviewstar"><span class="star-review"><span class="userreviews" style="width:<?php echo $reviews[0]['rating']*20;?>%"></span></span></div>
                <h2><?php echo $ruser[0]['email'];?></h2>
              </div>
              </div>
           <p><?php  echo $reviews[0]['review'];?></p>
          </div>
          </div>
        <?php
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
  <script src="<?php echo $view['assets']->getUrl('js/slippry.js'); ?>" type="text/javascript"></script>
<script src="<?php echo $view['assets']->getUrl('js/slider_des.js'); ?>" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('css/slider.css'); ?>" >  
    