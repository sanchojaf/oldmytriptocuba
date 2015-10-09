<?php $view->extend('::admin.html.php');?>
<div id="content"  class="clearfix">
  <div class="container">
    <div align="right" style="padding: 10px 10px 0px;"><a href="<?php echo $view['router']->generate('mytrip_admin_adminusers');?>" class="button">Back to Admin Users</a></div>
    <?php echo $view['form']->start($editadmin) ?>
    <fieldset>
      <legend>Edit New Admin</legend>
      <dl class="inline">
        <dt><label for="name">Name <span class="required">*</span></label></dt>
        <dd><?php echo $view['form']->widget($editadmin['name']) ?></dd>
        <dt><label for="name">Username <span class="required">*</span></label></dt>
        <dd><?php echo $view['form']->widget($editadmin['username']) ?></dd>
        <dt><label for="name">Email <span class="required">*</span></label></dt>
        <dd><?php echo $view['form']->widget($editadmin['email']) ?></dd>
         <dt><label for="name">Mobile <span class="required">*</span></label></dt>
        <dd><?php echo $view['form']->widget($editadmin['cmcode']) ?>&nbsp;<?php echo $view['form']->widget($editadmin['mobile']) ?></dd>
        <div class="buttons" ><?php echo $view['form']->widget($editadmin['save']) ?></div>
      </dl>
    </fieldset>
    <?php echo $view['form']->end($editadmin) ?> </div>
</div>
