<div class="column-layout clearfix">
	<?php $session->flash('auth', 'flash/error'); ?>
	<div class="column fLeft double">
		<h2>Log In</h2>
		<?php echo $form->create('User', array('type' => 'post', 'action' => 'login', 'class' => 'dataForm')); ?>
			<?php echo $form->input('email'); ?>
			<?php echo $form->input('password'); ?>
		<?php echo $form->end('Submit'); ?>
	</div>
	<div class="column fLeft double endcol">
		<h3>Create Account</h3>
		<p>
			If you create an account you will be able to set up a personal dashboard so you will see only the projects you work on
			when you log in.
		</p>
	</div>
</div>