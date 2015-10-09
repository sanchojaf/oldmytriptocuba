<?php $view->extend('::user.html.php');
$em = $this->container->get('doctrine')->getManager();
$what_do_we_do_menus = $em->createQuery("SELECT p FROM MytripAdminBundle:Staticpage p WHERE  p.staticpageId=3 AND p.status='Active'")->getArrayResult();
$who_we_are_menus = $em->createQuery("SELECT p FROM MytripAdminBundle:Staticpage p WHERE p.staticpageId=2 AND p.status='Active'")->getArrayResult();
$what_do_we_offer_menus = $em->createQuery("SELECT p FROM MytripAdminBundle:Staticpage p WHERE  p.staticpageId=4 AND p.status='Active'")->getArrayResult();
?>
<div class="about-section">
    <div class="container">
        <div class="about-banner"><img src="<?php echo $view['assets']->getUrl('img/about-a.jpg') ?>"  alt="<?php echo $view['translator']->trans('About Us');?>"></div>
        <?php if(!empty($who_we_are_menus)){?>
        <div class="who-we">
        <h1><?php echo $who_we_are[0]['name']; ?></h1> <img width="47" height="41" src="<?php echo $view['assets']->getUrl('img/icon4.png')?>" alt="<?php echo $who_we_are[0]['name']; ?>">
       <?php echo $who_we_are[0]['content']; ?>
      </div>
      <?php }?>
       <?php if(!empty($what_do_we_do_menus)){?>
        <div class="who-we" id="who_we">
        <h1><?php echo $what_do_we_do[0]['name']; ?></h1>
        <img src="<?php echo $view['assets']->getUrl('img/icon3.png') ?>" alt="<?php echo $what_do_we_do[0]['name']; ?>">
        <div class="row clearfix"> 
          <?php echo $what_do_we_do[0]['content']; ?>
          </span>
          </div>
      </div>
      <?php }?>
       <?php if(!empty($what_do_we_offer_menus)){?>
        <div class="row">
        <div class="who-we offer-bl" id="who_offer">
            <h1><?php echo $what_do_we_offer[0]['name']; ?></h1>
            <img src="<?php echo $view['assets']->getUrl('img/icon4.png') ?>" width="47" height="41" alt="<?php echo $what_do_we_offer[0]['name']; ?>">
           <?php echo $what_do_we_offer[0]['content']; ?>
          </div>
        <div class="what-offer-image"><img src="<?php echo $view['assets']->getUrl('img/human-1.jpg') ?>" alt="<?php echo $what_do_we_offer[0]['name']; ?>"></div>
      </div>
      <?php }?>
      </div>
  </div>
   <script type="text/javascript">
 <?php if(isset($_REQUEST['term'])){
	 if($_REQUEST['term']=="what_do_we_do"){
	 ?>
 $(document).ready(function(){
	  document.getElementById('who_we').scrollIntoView(); //$('#terms').focus();
 });
 <?php 
	 }elseif($_REQUEST['term']=="what_do_we_offer"){
		  ?>
 $(document).ready(function(){
	  document.getElementById('who_offer').scrollIntoView(); //$('#terms').focus();
 });
 <?php 
	 }
	}?>
 </script> 
     