<div class="column-layout clearfix">
	<div class="column fLeft double">
		<h2>Log In</h2>
		<?php echo $form->create('User', array('type' => 'post', 'action' => 'login', 'class' => 'dataForm')); ?>
			<?php echo $form->input('email'); ?>
			<?php echo $form->input('password'); ?>
			<?php echo $form->input('remember_me', array('label' => 'Remember Me', 'type' => 'checkbox')); ?>
		<?php echo $form->end('Submit'); ?>
	</div>
	<div class="column fLeft double endcol">
		<h3>Need an account?</h3>
		<p>
			If you create an account you will be able to set up a personal dashboard so you will see only the projects you work on
			when you log in.
		</p>
		<p>
			<?php echo $html->link('Create an Account', '/register'); ?>
		</p>
		
		<h3>Can't login?</h3>
		<p>
			If you create an account you will be able to set up a personal dashboard so you will see only the projects you work on
			when you log in.
		</p>
		<p>
			<?php echo $html->link('Reset your password', '/reset'); ?>
		</p>
	</div>
</div>