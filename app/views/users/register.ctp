<div class="column-layout clearfix">
	<div class="column fLeft double">
		<h2>Create an account</h2>
		<p>All we need are a few details.</p>
		<?php echo $form->create('User', array('type' => 'post', 'action' => 'register', 'class' => 'dataForm')); ?>
			<?php echo $form->input('displayname', array('label' => 'Name')); ?>
			<?php echo $form->input('email'); ?>
			<?php echo $form->input('password'); ?>
			<?php echo $form->input('confirm', array('label' => 'Confirm Password', 'type' => 'password')); ?>
		<?php echo $form->end('Submit'); ?>
	</div>
	<div class="column fLeft double endcol">
		<h3>Already have an account?</h3>
		<p>Head over to the <?php echo $html->link('login page', '/login'); ?> and log in.</p>
	</div>
</div>