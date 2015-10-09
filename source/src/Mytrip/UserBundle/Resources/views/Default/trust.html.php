<?php $view->extend('::user.html.php');?>
<div class="container">
  <div class="about-banner pay-banner"><img src="<?php echo $view['assets']->getUrl('img/trust.jpg'); ?>"  alt="<?php echo $trust[0]['name']; ?>"></div>
  <div class="who-we payment">
    <h1><?php echo $trust[0]['name']; ?></h1>
    <img src="<?php echo $view['assets']->getUrl('img/icon2.png'); ?>" width="47" height="40">
     <div class="row clearfix"><?php echo $trust[0]['content']; ?></div></div>
</div>
