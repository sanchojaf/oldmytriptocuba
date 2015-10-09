<?php $view->extend('::user.html.php');
$bucketurl=$this->container->get('mytrip_admin.helper.amazon')->getOption('url');
$imgurl=str_replace(array("/app_dev.php","/app.php"),"",$this->container->get('router')->getContext()->getBaseUrl())."/timthumb.php";
$em = $this->container->get('doctrine')->getManager();
?>
<script src="<?php echo $view['assets']->getUrl('js/slippry.js'); ?>" type="text/javascript"></script>
<script src="<?php echo $view['assets']->getUrl('js/slider_des.js'); ?>" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('css/slider.css'); ?>" >
<?php 
if(!empty($banner)){?>
<section>
<ul id="slslide">
<?php 
foreach($banner as $banners){ 
$sdetails=$em->createQuery("SELECT s,IDENTITY(s.destination) AS destination,IDENTITY(s.hostal) AS hostal FROM MytripAdminBundle:Story s  WHERE s.storyId='".$banners['typeId']."'")->getArrayResult();
$sdetail_content=$em->createQuery("SELECT s FROM MytripAdminBundle:StoryContent s  WHERE s.story='".$banners['typeId']."' AND s.lan='".$view['session']->get('language')."'")->getArrayResult();
$sdetail_image=$em->createQuery("SELECT p FROM MytripAdminBundle:StoryImage p where p.story='". $sdetails[0][0]['storyId'] ."'")->getArrayResult();

if(empty($sdetail_content)){
	$sdetail_content=$em->createQuery("SELECT s FROM MytripAdminBundle:StoryContent s  WHERE s.story='".$banners['typeId']."' AND s.lan='en'")->getArrayResult();
}
$destination=$em->createQuery("SELECT p FROM MytripAdminBundle:Destination p where  p.destinationId='".$sdetails[0]['destination']."'")->getArrayResult();
$hostal=$em->createQuery("SELECT p FROM MytripAdminBundle:Hostal p  where  p.hostalId='".$sdetails[0]['hostal']."'")->getArrayResult();

?>
  <li><a href="<?php echo $this->container->get('router')->getContext()->getBaseUrl()."/stories/".$destination[0]['url']."/".$hostal[0]['url']."/".$sdetails[0][0]['url'];?>"><img src="<?php echo $imgurl.'?src='.$bucketurl.$banners['image']."&w=1457&h=491"; ?>" /></a>
  <div class="banner-info">
  <div class="banner-info-left">
      <p><?php echo $sdetail_content[0]['name'];?></p>
          <span>"<?php echo $sdetail_content[0]['subHead'];?>" &nbsp;&nbsp; <a href="<?php echo $this->container->get('router')->getContext()->getBaseUrl()."/stories/".$destination[0]['url']."/".$hostal[0]['url']."/".$sdetails[0][0]['url'];?>"><?php echo $view['translator']->trans('Full Story');?></a></span>
   </div>
			<div class="banner-info-img">
				<a href="<?php echo $this->container->get('router')->getContext()->getBaseUrl()."/stories/".$destination[0]['url']."/".$hostal[0]['url']."/".$sdetails[0][0]['url'];?>"><img src="<?php echo $imgurl."?src=".$bucketurl.$sdetail_image[0]['image']."&w=89&h=51";?>" width="89" height="51" alt="info" border="0"></a>
           </div>
	</div>
</li>
  <?php
}
?> 
</ul>
</section>
<?php }?>
<div class="t-destination">
  <div class="container">
    <h1 class="our-bl"><?php echo $view['translator']->trans('Our Stories');?><img src="<?php echo $view['assets']->getUrl('img/icon4.png'); ?>" width="47" height="41" alt="<?php echo $view['translator']->trans('Our Stories');?>" class="our-icon"><br/>
      <span><?php echo $view['translator']->trans('Behind every great host there is always a great story');?>.</span></h1>
      <span class="w-destination">
      <?php echo $story_content['0']['content'];?>
      </span>
    <div class="our-story-bl">
    <?php	
	if(!empty($story)){
		$k=0; foreach($story as $storys) {		
		$destination=$em->createQuery("SELECT p FROM MytripAdminBundle:Destination p  where  p.destinationId='".$storys['destination']."'")->getArrayResult();
		$story_content=$em->createQuery("SELECT p FROM MytripAdminBundle:StoryContent p  where  p.story='".$storys[0]['storyId']."' AND p.lan='".$view['session']->get('language')."'")->getArrayResult();
		if(empty($story_content)){
			$story_content=$em->createQuery("SELECT p FROM MytripAdminBundle:StoryContent p  where  p.story='".$storys[0]['storyId']."' AND p.lan='en'")->getArrayResult();
		}
		$story_image=$em->createQuery("SELECT p FROM MytripAdminBundle:StoryImage p where p.story='".$storys[0]['storyId']."'")->getArrayResult();
		$hostal=$em->createQuery("SELECT p FROM MytripAdminBundle:Hostal p  where  p.hostalId='".$storys['hostal']."'")->getArrayResult();			
		 ?>	
      <div class="our-story-sec">
        <div class="our-story-img"><a href="<?php echo $this->container->get('router')->getContext()->getBaseUrl()."/stories/".$destination[0]['url']."/".$hostal[0]['url']."/".$storys[0]['url'];?>"><img src="<?php echo $imgurl.'?src='.$bucketurl.$story_image[0]['image'].'&w=358&h=324'; ?>" alt="<?php echo $story_content[0]['name'];?>"></a></div>
        <h2><a href="<?php echo $this->container->get('router')->getContext()->getBaseUrl()."/stories/".$destination[0]['url']."/".$hostal[0]['url']."/".$storys[0]['url'];?>"><?php echo $story_content[0]['name'];?></a></h2>
        <p><?php echo $story_content[0]['subHead'];?></p>
      </div>
      <?php $k++;  if($k%3==0) {  ?>
       </div>
       <div class="our-story-bl">
        <?php } 
       }?>
    <?php }else{?>
    <h1><?php echo $view['translator']->trans('No story found');?>.</h1>
    <?php }?>     
    </div>   
  </div>
</div>
<?php
if(!empty($storyofthemonth)){
$storyofthemonth_destination=$em->createQuery("SELECT p FROM MytripAdminBundle:Destination p  where  p.destinationId='".$storyofthemonth[0]['destination']."'")->getArrayResult();
$storyofthemonth_content=$em->createQuery("SELECT p FROM MytripAdminBundle:StoryContent p  where  p.story='".$storyofthemonth[0][0]['storyId']."' AND p.lan='".$view['session']->get('language')."'")->getArrayResult();
if(empty($storyofthemonth_content)){
	$storyofthemonth_content=$em->createQuery("SELECT p FROM MytripAdminBundle:StoryContent p  where  p.story='".$storyofthemonth[0][0]['storyId']."' AND p.lan='en'")->getArrayResult();
}
$storyofthemonth_image=$em->createQuery("SELECT p FROM MytripAdminBundle:StoryImage p where p.story='".$storyofthemonth[0][0]['storyId']."'")->getArrayResult();
$storyofthemonth_hostal=$em->createQuery("SELECT p FROM MytripAdminBundle:Hostal p  where  p.hostalId='".$storyofthemonth[0]['hostal']."'")->getArrayResult();
?>
<div class="container">
  <div class="row payment">
    <h1 class="storyof-month"><?php echo $view['translator']->trans('Story of the month');?>.<img src="<?php echo $view['assets']->getUrl('img/icon-7.png'); ?>" alt="<?php echo $view['translator']->trans('Story of the month');?>" class="our-icon"><br/>
      <span><?php echo $storyofthemonth_content[0]['name'];?>  -  "<?php echo $storyofthemonth_content[0]['subHead'];?>"</span></h1>
    <div class="row story-content-sec">
      <div class="our-story-img st-img"><img src="<?php echo $imgurl.'?src='.$bucketurl.$storyofthemonth_image[0]['image'].'&w=358&h=324'; ?>" alt="<?php echo $storyofthemonth_content[0]['name'];?>"></div>
      <div class="story-our-bl">
        <p><?php echo substr(strip_tags($storyofthemonth_content[0]['content']),0,1350);?></p>
        <span class="readmore" style="float:right;"><a href="<?php echo $this->container->get('router')->getContext()->getBaseUrl()."/stories/".$storyofthemonth_destination[0]['url']."/".$storyofthemonth_hostal[0]['url']."/".$storyofthemonth[0][0]['url'];?>"><?php echo $view['translator']->trans('Read more');?></a></span></div>
    </div>
  </div>
</div>
<?php }?>
