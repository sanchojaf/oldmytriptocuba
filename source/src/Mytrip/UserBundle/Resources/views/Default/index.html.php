<?php $view->extend('::user.html.php');
$bucketurl=$this->container->get('mytrip_admin.helper.amazon')->getOption('url');
$imgurl=str_replace(array("/app_dev.php","/app.php"),"",$this->container->get('router')->getContext()->getBaseUrl())."/timthumb.php";
$em = $this->container->get('doctrine')->getManager();
$formatter = new \NumberFormatter($view['session']->get('language'),\NumberFormatter::DECIMAL);
$j=0;
while($j==0){
	$home_destination=$em->createQuery("SELECT d, (RAND()) AS HIDDEN r FROM MytripAdminBundle:Destination d WHERE d.status='Active' ORDER BY r ")->setMaxResults(1)->getArrayResult();
	$home_banner=$em->createQuery("SELECT d, (RAND()) AS HIDDEN r FROM MytripAdminBundle:Banner d WHERE d.bannerType='Destination' AND d.typeId='".$home_destination[0]['destinationId']."' AND d.status='Active' ORDER BY r ")->setMaxResults(1)->getArrayResult();
	if(!empty($home_banner)){
		$j=1;
	}
}

$banner_hostal=$em->createQuery("SELECT d.hostalId, (RAND()) AS HIDDEN r FROM MytripAdminBundle:Hostal d WHERE d.destination='".$home_destination[0]['destinationId']."' AND d.status='Active' ORDER BY r ")->getArrayResult();
?>
<div class="banner-section"> <img src="<?php echo $imgurl."?src=".$bucketurl.$home_banner[0]['image']."&w=1457&h=491";?><?php //echo $view['imageresizer']->url($bucketurl.$home_banner[0]['image'], 'homothetic', 'banner') ?><?php //echo $bucketurl.$home_banner[0]['image'];?>" alt="banner" >
<?php
if(!empty($banner_hostal)){
	$tarray= $this->container->get('mytrip_admin.helper.date')->array_column($banner_hostal, 'hostalId');
	$banner_hostal_review=$em->createQuery("SELECT d.typeId, (SUM(d.rating)/COUNT(d)) AS rate, (RAND()) AS HIDDEN r FROM MytripAdminBundle:Review d WHERE d.reviewType='Hostal' AND d.status='Active' AND d.typeId IN ('".implode(",",$tarray)."')  GROUP BY d.typeId ORDER BY r ")->setMaxResults(1)->getArrayResult();	
	if(!empty($banner_hostal_review)){
		$hostal_id=$banner_hostal_review[0]['typeId'];
	}else{
		$hostal_id=$tarray[0];
	}
	$banner_hostals=$em->createQuery("SELECT d FROM MytripAdminBundle:Hostal d WHERE d.hostalId='".$tarray[0]."'")->getArrayResult();
	$banner_hostal_content=$em->createQuery("SELECT d FROM MytripAdminBundle:HostalContent d WHERE d.hostal='".$tarray[0]."'")->getArrayResult();
	$banner_hostal_image=$em->createQuery("SELECT d FROM MytripAdminBundle:HostalImage d WHERE d.hostal='".$tarray[0]."'")->getArrayResult();
	?>
    <div class="banner-info">
      <div class="banner-info-left">
        <p><?php echo substr($banner_hostal_content[0]['smallDesc'],0,45);?>........ <a href="<?php echo $this->container->get('router')->getContext()->getBaseUrl()."/".$home_destination[0]['url']."/".$banner_hostals[0]['url'];?>"><?php echo $view['translator']->trans('Read more');?></a> </p>
        <span><small><?php echo substr($banner_hostal_content[0]['name'],0,30);?>&nbsp; &nbsp;</small>
        <?php if(!empty($banner_hostal_review)){ ?>
         <span class="star-review"><span class="userreviews" style="width:<?php echo $banner_hostal_review[0]['rate']*20;?>%"></span></span>
         <?php }?>
         </span> </div>
      <div class="banner-info-img"><img src="<?php echo $imgurl."?src=".$bucketurl.$banner_hostal_image[0]['image']."&w=89&h=51";?><?php //echo $bucketurl.$banner_hostal_image[0]['image'];?>" width="89" height="51" alt="info"></div>
    </div>
    <?php
}
?>    
    <div>
      <div class="banner-cover">
        <div class=" banner-contaniner">
          <h1><?php echo $view['translator']->trans('Plan your Perfect trip to Cuba');?>....</h1>
          <form class="search-bar" action="" method="post" name="homesearch" id="homesearch">
            <input type="text" placeholder="<?php echo $view['translator']->trans('Choose Destination');?>" id="hdes" name="hdes" class="validate[required]" data-prompt-position="topLeft:20,5">
            <input type="text" id="checkin" class="date validate[required]" placeholder="<?php echo $view['translator']->trans('Check in');?>" name="checkin" data-prompt-position="topLeft:20,5">
            <input type="text" id="checkout" class="date validate[required]" placeholder="<?php echo $view['translator']->trans('Check out');?>" name="checkout" data-prompt-position="topLeft:20,5">
            <select name="guest" id="guest">
            <?php for($i=1;$i<=5;$i++){?>
              <option value="<?php echo $i;?>"><?php echo  $i;?> <?php echo $view['translator']->trans('Guest');?></option>
            <?php }?>
              <option value="6">6+ <?php echo $view['translator']->trans('Guest');?></option>
            </select>
            <input type="submit" class="search-button" name="homesearchbutton" id="homesearchbutton" value="<?php echo $view['translator']->trans('Search');?>">
          </form>
          <div class="check-in">
            <p> <?php echo $view['translator']->trans('Check Availability');?></p>
            <a href="<?php echo $view['router']->generate('mytrip_user_availability');?>" ><i class="fa fa-plus-square open"></i></a> </div>
        </div>
        <span class="bannner-caption">&nbsp;</span></div>
    </div>
  </div>
    <div class="t-destination">
    <div class="container">     
        <h1><?php echo $view['translator']->trans('Top Destinations');?>. &nbsp; &nbsp;<span><?php echo $view['translator']->trans("Not sure where to stay? we've created handy guides for cities all around the island");?></span></h1>
       <?php if(!empty($destination)){?>
        <div class="des-contain">
        <?php 
		$j=0;$k=1;
		$dcount=count($destination);
		foreach($destination as $destination){
			if(!empty($destination['typeId'])){
				$destinations=$em->createQuery("SELECT p FROM MytripAdminBundle:Destination p  WHERE p.destinationId='".$destination['typeId']."'")->getArrayResult();			
				$count=$em->createQuery("SELECT SUM(p.count) AS visitcount FROM MytripAdminBundle:Visits p  WHERE  p.visitType='Destination' AND p.typeId='".$destination['typeId']."'")->getArrayResult();
				$destinations_content=$em->createQuery("SELECT p FROM MytripAdminBundle:DestinationContent p  WHERE  p.destination='".$destination['typeId']."' AND p.lan='".$view['session']->get('language')."'")->getArrayResult();
				if(empty($destinations_content)){
					 $destinations_content=$em->createQuery("SELECT p FROM MytripAdminBundle:DestinationContent p  WHERE p.destination='".$destination['typeId']."' AND p.lan='en'")->getArrayResult();
				}			
				$destinations_image=$em->createQuery("SELECT p FROM MytripAdminBundle:DestinationImage  p  WHERE  p.destination='".$destination['typeId']."'")->getArrayResult();
				if(!empty($destinations_image)){
					$dimg=$imgurl."?src=".$bucketurl.$destinations_image[0]['image']."&w=673&h=369";
				}else{
					$dimg=$view['assets']->getUrl('img/no-des.jpg');
				}
			}else{
				$destinations=$em->createQuery("SELECT p FROM MytripAdminBundle:Destination p  WHERE p.destinationId='".$destination['destinationId']."'")->getArrayResult();			
				$count=$em->createQuery("SELECT SUM(p.count) AS visitcount FROM MytripAdminBundle:Visits p  WHERE  p.visitType='Destination' AND p.typeId='".$destination['destinationId']."'")->getArrayResult();
				$destinations_content=$em->createQuery("SELECT p FROM MytripAdminBundle:DestinationContent p  WHERE  p.destination='".$destination['destinationId']."' AND p.lan='".$view['session']->get('language')."'")->getArrayResult();
				if(empty($destinations_content)){
					 $destinations_content=$em->createQuery("SELECT p FROM MytripAdminBundle:DestinationContent p  WHERE p.destination='".$destination['destinationId']."' AND p.lan='en'")->getArrayResult();
				}			
				$destinations_image=$em->createQuery("SELECT p FROM MytripAdminBundle:DestinationImage  p  WHERE  p.destination='".$destination['destinationId']."'")->getArrayResult();
				if(!empty($destinations_image)){
					$dimg=$imgurl."?src=".$bucketurl.$destinations_image[0]['image']."&w=673&h=369";
				}else{
					$dimg=$view['assets']->getUrl('img/no-des.jpg');
				}
				
			}
			if($j==0){
		?>
        <div class="destination-sec">
            <div class="destination-fig">
            <span class="top-corner"></span><a href="<?php echo $this->container->get('router')->getContext()->getBaseUrl()."/".$destinations[0]['url'];?>" ><img src="<?php echo $dimg;?><?php //echo $bucketurl.$destinations_image[0]['image']?>" alt="<?php echo $destinations_content[0]['name'];?>" onerror="this.src ='<?php echo $view['assets']->getUrl('img/no-des.jpg')?>'"></a></div>
            <h2><a href="<?php echo $this->container->get('router')->getContext()->getBaseUrl()."/".$destinations[0]['url'];?>" ><?php echo $destinations_content[0]['name'];?></a></h2>
            <p><?php echo $formatter->format($count[0]['visitcount']);?> <?php echo $view['translator']->trans('visits');?></p>
          </div>
        <?php }else{ 
		if($k==1){
		?>          
        <div class="destination-small"> 
        <?php }?>
         <div class="destination-sec destination-small-fig ">         
            <div class="destination-fig  destination-fig-pad">
            <span class="top-corner"></span>
            <a href="<?php echo $this->container->get('router')->getContext()->getBaseUrl()."/".$destinations[0]['url'];?>" ><img src="<?php echo $dimg;?><?php //echo $bucketurl.$destinations_image[0]['image'];?>" alt="<?php echo $destinations_content[0]['name']; ?>"></a></div>
            <h2><a href="<?php echo $this->container->get('router')->getContext()->getBaseUrl()."/".$destinations[0]['url'];?>" ><?php echo $destinations_content[0]['name']; ?></a></h2>
            <p><?php echo $formatter->format($count[0]['visitcount']);?> <?php echo $view['translator']->trans('visits');?></p>
          </div>
           <?php if($k==2) {  ?>
           </div>
		    <div class="destination-small">
		    <?php }
						
			if(($k+1)==$dcount){
			?>
         </div>  
          <?php }?>
          <?php $k++;
		  } 
		  $j++;
		}?>
         </div>      
      <?php }?>
      </div>
        <p><a href="<?php echo $view['router']->generate('mytrip_user_destination');?>"><?php echo $view['translator']->trans('All Destination Guide');?></a></p>
      </div> 

    <div class="container">
    <?php 
	$hospitality_menus = $em->createQuery("SELECT p FROM MytripAdminBundle:Staticpage p  WHERE p.status='Active' AND p.staticpageId=8 ")->getArrayResult();
	$trust_menus = $em->createQuery("SELECT p FROM MytripAdminBundle:Staticpage p  WHERE p.status='Active' AND p.staticpageId=10 ")->getArrayResult();
	?>
    <div class="row">
    <?php
	if(!empty($hospitality_menus)){?>
        <div class="row-cover">
        <div class="row-one">
            <h1><?php echo $hospitality[0]['name']; ?></h1>
            <img src="<?php echo $view['assets']->getUrl('img/icon.png')?>" width="48" height="41" alt="hospitality">
            <p><?php echo substr(strip_tags($hospitality[0]['content']),0,524); ?></p>
            <span class="readmore"><a href="<?php echo $view['router']->generate('mytrip_user_hospitality');?>"><?php echo $view['translator']->trans('Read more');?></a></span> </div>
      </div>
      <?php }?>
      
        <div class="row-cover">
        <div class="row-one">
            <h1><?php echo $stories[0]['name']; ?></h1>
            <i class="fa fa-heart row-icon"></i>
            <p><?php echo substr(strip_tags($stories[0]['content']),0,524); ?></p>
            <span class="readmore"><a href="<?php echo $view['router']->generate('mytrip_user_story');?>"><?php echo $view['translator']->trans('Read more');?></a></span> </div>
      </div>
     <?php if(!empty($trust_menus)){?> 
        <div class="row-cover">
        <div class="row-one border-none">
            <h1><?php echo $trust[0]['name']; ?></h1>
            <img src="<?php echo $view['assets']->getUrl('img/icon2.png')?>" width="48" height="41" alt="hospitality">
            <p><?php echo substr(strip_tags($trust[0]['content']),0,524); ?></p>
            <span class="readmore"><a href="<?php echo $view['router']->generate('mytrip_user_trust');?>"><?php echo $view['translator']->trans('Read more');?></a></span> </div>
      </div>
      <?php }?>
      </div>
  </div>  