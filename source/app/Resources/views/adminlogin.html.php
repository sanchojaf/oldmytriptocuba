<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>My trip Cuba - Login</title>
<meta name="description" content="My trip Cuba - Login" />
<meta name="keywords" content="My trip Cuba - Login" />
<meta name="author" content="My trip Cuba" />
<meta name="copyright" content="My trip Cuba" />
<link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('css/style.css') ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('css/reset.css') ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('css/uniform/uniform.default.css') ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('css/jQuery.validation/validationEngine.jquery.css') ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('css/jquery.confirm/jquery.confirm.css') ?>"/>
<script src="<?php echo $view['assets']->getUrl('js/jquery-1.8.3.js') ?>" type="text/javascript"></script>
<script src="<?php echo $view['assets']->getUrl('js/uniform/jquery.uniform.js') ?>" type="text/javascript"></script>
<script src="<?php echo $view['assets']->getUrl('js/jQuery.validation/jquery.validationEngine.js') ?>" type="text/javascript"></script>
<script src="<?php echo $view['assets']->getUrl('js/jQuery.validation/languages/jquery.validationEngine-en.js') ?>" type="text/javascript"></script>
<script src="<?php echo $view['assets']->getUrl('js/confirm/jquery.confirm.js') ?>" type="text/javascript"></script>
<script src="<?php echo $view['assets']->getUrl('js/adminlogin.js') ?>" type="text/javascript"></script>
</head>
<body style="background:#E4E4E4">
<div class="helpfade"></div>
<div class="helptips">
  <div class="loader_block">
    <div class="loader_block_inner"></div>
    <div class="loader_text">Please wait...</div>
  </div>
</div>
<div class="dismsg" id="msginfo" style="top:10%;">
<?php 
	foreach ($view['session']->getFlash('error') as $message){ 
		echo $message.'<div class="close"> Click to close.</div>';
	} 
?>
</div>
<div id="mainContainer">
  <div id="header" class="clearfix">
    <div id="topHeader">
      <div id="logo"><img src="<?php echo $view['assets']->getUrl('img/admin-logo.png');?>"/></div>
    </div>
  </div>
  <?php $view['slots']->output('_content'); ?>
</div>
</body>
</html>