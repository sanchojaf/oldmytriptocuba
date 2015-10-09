<?php $view->extend('::user.html.php');?>
<div class="body-sec about-bl">
    <div class="about-section">
    <div class="container">
        <div class="about-banner"><img src="<?php echo $view['assets']->getUrl('user/img/about-a.jpg') ?>"  alt="about"></div>
        <div class="who-we">
        <h1><?php echo $we[0]['name']; ?></h1> <img width="47" height="41" src="<?php echo $view['assets']->getUrl('user/img/icon4.png')?>">
       <?php echo $we[0]['content']; ?>
      </div>
        <div class="who-we" id="who_we">
        <h1><?php echo $do[0]['name']; ?></h1>
        <img src="<?php echo $view['assets']->getUrl('user/img/icon3.png') ?>">
        <div class="row clearfix"> 
          <?php echo $do[0]['content']; ?>
          </span>
          </div>
      </div>
        <div class="row">
        <div class="who-we offer-bl" id="who_offer">
            <h1><?php echo $offer[0]['name']; ?></h1>
            <img src="<?php echo $view['assets']->getUrl('user/img/icon4.png') ?>" width="47" height="41">
           <?php echo $offer[0]['content']; ?>
          </div>
        <div class="what-offer-image"><img src="<?php echo $view['assets']->getUrl('user/img/human-1.jpg') ?>" alt="what we offer"></div>
      </div>
      </div>
  </div>
    
    