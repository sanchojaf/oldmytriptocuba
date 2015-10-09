<?php $view->extend('::user.html.php');?>
<div class="container">
  <div class="about-banner pay-banner"><img src="<?php echo $view['assets']->getUrl('img/faq.jpg'); ?>"  alt="<?php echo $faq[0]['name']; ?>"></div>
  <div class="who-we payment ">
    <div class="faq">
      <h1><?php echo $faq[0]['name']; ?></h1>
      <img src="<?php echo $view['assets']->getUrl('img/faq-icon.png'); ?>"  alt="faq"></div>
    <?php echo $faq[0]['content']; ?> </div>
</div>
