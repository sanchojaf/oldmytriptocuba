<?php $view->extend('::user.html.php');
$em = $this->container->get('doctrine')->getManager();
$payment_menu = $em->createQuery("SELECT p FROM MytripAdminBundle:Staticpage p  WHERE p.staticpageId=5")->getArrayResult();
$terms_menu = $em->createQuery("SELECT p FROM MytripAdminBundle:Staticpage p  WHERE p.staticpageId=6")->getArrayResult();
?>
 <div class="container">
        <div class="about-banner pay-banner"><img src="<?php echo $view['assets']->getUrl('img/about-b.jpg'); ?>"  alt="<?php echo $payment[0]['name']; ?>"></div>
        <?php if($payment_menu[0]['status']=="Active"){?>
        <div class="who-we payment">
        <h1><?php echo $payment[0]['name']; ?></h1>
        <img src="<?php echo $view['assets']->getUrl('img/icon2.png')?>" width="47" height="40" alt="<?php echo $payment[0]['name']; ?>">
        <?php echo $payment[0]['content']; ?>
      </div>
      <?php }?>
        <?php if($terms_menu[0]['status']=="Active"){?>
        <div class="who-we payment" id="terms">
        <h1><?php echo $terms[0]['name']; ?></h1>
        <img src="<?php echo $view['assets']->getUrl('img/icon2.png')?>" width="47" height="40" alt="<?php echo $terms[0]['name']; ?>">
        <div class="row clearfix">
            <?php echo $terms[0]['content']; ?>
          </div>
      </div>
       <?php }?>
      </div>

  <script type="text/javascript">   
 <?php if(isset($_REQUEST['terms'])){?>
 $(document).ready(function(){
	  document.getElementById('terms').scrollIntoView(); //$('#terms').focus();
 });
 <?php }?>
 </script>   
    