<?php $view->extend('::admin.html.php');?>
<div id="content"  class="clearfix">
  <div class="container">
    <div align="right" style="padding: 10px 10px 0px;"><a href="<?php echo $view['router']->generate('mytrip_admin_adminusers');?>" class="button">Back to Admin Users</a></div>
    <?php echo $view['form']->start($addadmin) ?>
    <fieldset>
      <legend>Add New Admin</legend>
      <dl class="inline">
        <dt><label for="name">Name <span class="required">*</span></label></dt>
        <dd><?php echo $view['form']->widget($addadmin['name']) ?></dd>
        <dt><label for="name">Username <span class="required">*</span></label></dt>
        <dd><?php echo $view['form']->widget($addadmin['username']) ?></dd>
         <dt><label for="name">Password <span class="required">*</span></label></dt>
        <dd><?php echo $view['form']->widget($addadmin['password']) ?></dd>
        <dt><label for="name">Email <span class="required">*</span></label></dt>
        <dd><?php echo $view['form']->widget($addadmin['email']) ?></dd>
        <dt><label for="name">Mobile <span class="required">*</span></label></dt>
        <dd><?php echo $view['form']->widget($addadmin['cmcode']) ?>&nbsp;<?php echo $view['form']->widget($addadmin['mobile']) ?></dd>
        <div class="buttons" ><?php echo $view['form']->widget($addadmin['save']) ?></div>
      </dl>
    </fieldset>
    <?php echo $view['form']->end($addadmin) ?> </div>
</div>
