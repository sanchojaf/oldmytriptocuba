<?php $view->extend('::admin.html.php');?>
<div id="content"  class="clearfix">
  <div class="container">
    <div align="right" style="padding-right:50px;"></div>
    <?php echo $view['form']->start($profile) ?>
    <fieldset>
      <legend>Edit Profile</legend>
      <dl class="inline">
        <dt><label for="name">Name <span class="required">*</span></label></dt>
        <dd><?php echo $view['form']->widget($profile['name']) ?></dd>
        <dt><label for="name">Username <span class="required">*</span></label></dt>
        <dd><?php echo $view['form']->widget($profile['username']) ?></dd>
        <dt><label for="name">Email <span class="required">*</span></label></dt>
        <dd><?php echo $view['form']->widget($profile['email']) ?></dd>
         <dt><label for="name">Mobile <span class="required">*</span></label></dt>
        <dd><?php echo $view['form']->widget($profile['cmcode']) ?>&nbsp;<?php echo $view['form']->widget($profile['mobile']) ?></dd>
        <div class="buttons" ><?php echo $view['form']->widget($profile['save']) ?></div>
      </dl>
    </fieldset>
    <?php echo $view['form']->end($profile) ?> </div>
</div>
