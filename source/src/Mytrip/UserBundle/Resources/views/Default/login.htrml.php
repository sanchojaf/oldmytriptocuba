<?php $view->extend('::user.html.php');?>
<div class="body-sec about-bl">
    <div class="">
    <div class="container">
        <div class="about-banner pay-banner"><img src="<?php echo $view['assets']->getUrl('user/img/about-b.jpg'); ?>"  alt="about"></div>
        <div class="who-we payment">
        <h1><?php echo $login[0]['name']; ?></h1>
        <img src="<?php echo $view['assets']->getUrl('user/img/icon2.png')?>" width="47" height="40" alt="heom">
        <?php echo $login[0]['content']; ?>
      </div>
      
      </div>
  </div>
  