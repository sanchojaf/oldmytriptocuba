<?php $view->extend('::adminlogin.html.php');?>
<div id="loginBox" class="loginBox clearfix" <?php if(isset($result)){ if($result!='') echo ' style="display:none;"'; } ?>>
    <h2>Admin Secure Login</h2>
   <?php echo $view['form']->start($login) ?> 
    <div id="login">
        <dl>
        <dt><?php echo $view['form']->label($login['username']) ?></dt><dd><?php echo $view['form']->widget($login['username']) ?></dd>
        <dt><?php echo $view['form']->label($login['password']) ?></dt><dd><?php echo $view['form']->widget($login['password']) ?></dd>        
        </dl>
        <div id="loginDiv">
            <div class="loginbtn"> <?php echo $view['form']->widget($login['save']) ?></div>
            <div class="forgottab loginlink">Can't access your account?</div>
        </div>
    </div>
    <?php echo $view['form']->end($login) ?>
</div>
<div id="loginBox" class="forgotBox clearfix" <?php if(isset($result)){ if($result!='') echo ' style="display:block;"'; else echo ' style="display:none;"';}  else echo ' style="display:none;"';?>>
    <h2>Admin Forgot Password</h2>
     <?php echo $view['form']->start($forgot) ?>
    <div id="login">
    	<p style="padding:10px;width:90%;">Forgot your username or password? No worries, enter your email address below and we will hook you up.</p>
        <dl>
       <dt><?php echo $view['form']->label($forgot['email']) ?></dt><dd><?php echo $view['form']->widget($forgot['email']) ?></dd>                 
        </dl>
        <div id="loginDiv">
            <div class="loginbtn"><?php echo $view['form']->widget($forgot['save']) ?></div>
            <div class="logintab loginlink">Back to login page</div>
        </div>
    </div>
     <?php echo $view['form']->end($forgot) ?>
</div>
