<?php $view->extend('::user.html.php');?>
 <div class="about-section">
    <div class="container">
        <div class="about-banner"><img src="<?php echo $view['assets']->getUrl('img/hospitality.jpg'); ?>"  alt="<?php echo $staticpage[0]['name']; ?>"></div>
        <div class="who-we hospitality">
        <h1><?php echo $staticpage[0]['name']; ?></h1> <img src="<?php echo $view['assets']->getUrl('img/icon.png'); ?>">
        <?php echo $staticpage[0]['content']; ?>
      </div>
      </div>
  </div>

    
    