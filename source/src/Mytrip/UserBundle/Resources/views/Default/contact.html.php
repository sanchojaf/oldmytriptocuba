<?php $view->extend('::user.html.php');?>
<?php
    $usersession=$view['session']->get('user');
   
    if (!empty($usersession))
    {
        $_REQUEST['name'] = $usersession['firstname'].' '.$usersession['lastname'];
        $_REQUEST['email'] = $usersession['email'];
        $_REQUEST['phone'] = $usersession['phone'];
    }
?>
<div class="container">
  <div class="about-banner pay-banner"><img src="<?php echo $view['assets']->getUrl('img/contact-us.jpg') ?>"  alt="<?php echo $contact[0]['name']; ?>"></div>
  <div class="who-we payment ">
    <div class="faq">
      <h1><?php echo $contact[0]['name']; ?></h1>
      <img src="<?php echo $view['assets']->getUrl('img/contact-us.png') ?>"  alt="<?php echo $contact[0]['name']; ?>"></div>
    <div class="que">
      <h2><?php echo $view['translator']->trans('All fields with "*" are required');?></h2>
      <form class="contact" id="contactform" name="contactform" action="" method="post">
        <div class="input">
          <label><?php echo $view['translator']->trans('Name');?><span class="required">*</span></label>
          <input type="text" name="name" id="name" class="validate[required]" maxlength="25"  data-prompt-position="bottomLeft:20,5" value="<?php echo (isset($_REQUEST['name'])?$_REQUEST['name']:'')?>" />
        </div>
        <div class="input">
          <label><?php echo $view['translator']->trans('Email');?><span class="required">*</span></label>
          <input type="text" class="validate[required,custom[email]]" name="email" id="email" maxlength="100" data-prompt-position="bottomLeft:20,5" value="<?php echo (isset($_REQUEST['email'])?$_REQUEST['email']:'')?>"   />
        </div>
        <div class="input">
          <label><?php echo $view['translator']->trans('Phone');?>.<span class="required">*</span></label>
          <input type="text" class="validate[required,custom[phone]]" name="phone" id="phone" maxlength="26" data-prompt-position="bottomLeft:20,5" value="<?php echo (isset($_REQUEST['phone'])?$_REQUEST['phone']:'')?>"   />
        </div>
        <div class="input">
          <label><?php echo $view['translator']->trans('Subject');?><span class="required">*</span></label>
          <input type="text" class="validate[required,max[50]]" name="subject" id="subject" maxlength="50"  data-prompt-position="bottomLeft:20,5" value="<?php echo (isset($_REQUEST['subject'])?$_REQUEST['subject']:'')?>"  />
        </div>
        <div class="input">
          <label><?php echo $view['translator']->trans('Message');?><span class="required">*</span></label>
          <textarea name="messages" id="messages" class="validate[required,min[10],max[250]]  text-input" data-prompt-position="bottomLeft:20,5" ><?php echo (isset($_REQUEST['messages'])?$_REQUEST['messages']:'')?></textarea>
        </div>
         <div class="input">
         <label>&nbsp;</label>
        <?php
		echo recaptcha_get_html($this->container->get('mytrip_admin.helper.recaptcha')->getOption('publickey'),null);?>
        </div>
        <div class="con-send">
          <input type="submit" name="contactsubmit" id="contactsubmit" class="submit-review" value="<?php echo $view['translator']->trans('Send Email');?>">
        </div>
      </form>
      <?php echo $contact[0]['content']; ?>     
    </div>
  </div>
</div>
<script type="text/javascript">
$(window).bind("load", function() {
	if($('#recaptcha_response_field').length >0){
		$('#recaptcha_response_field').addClass('validate[required]').attr('data-prompt-position',"bottomLeft:20,5");
	}
});
</script>
