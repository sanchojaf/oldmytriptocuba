<?php $view->extend('::user.html.php');
$bucketurl=$this->container->get('mytrip_admin.helper.amazon')->getOption('url');
$imgurl=str_replace(array("/app_dev.php","/app.php"),"",$this->container->get('router')->getContext()->getBaseUrl())."/timthumb.php";
$em = $this->container->get('doctrine')->getManager();
$formatter = new \NumberFormatter($view['session']->get('language'),\NumberFormatter::DECIMAL);
?>
<div class="t-destination check-bl">
<div class="container clearfix">
    <h1><?php echo $view['translator']->trans('Check Availability');?></h1>
    <div>
        <div id="signup-ct">
        <form  action="<?php echo $view['router']->generate('mytrip_user_homepage');?>" method="post" name="homesearch" id="homesearch" class="pop-bar">
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
      </div>
      </div>
  </div>
</div>
<script type="text/javascript">
$(window).on('resize',function(){
	if($(window).width() >800){
		window.location='<?php echo $this->container->get('router')->getContext()->getBaseUrl()."/";?>';
	}
});
</script>