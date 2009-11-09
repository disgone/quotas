<div class="column-layout clearfix">
	<div class="column fLeft double">
		<h2><?php echo $this->pageTitle; ?></h2>
		<?php echo $form->create('User', array('type' => 'post', 'action' => 'login', 'class' => 'dataForm')); ?>
			<?php echo $form->input('username'); ?>
			<?php echo $form->input('password'); ?>
			<?php echo $form->input('remember_me', array('label' => 'Remember Me', 'type' => 'checkbox')); ?>
		<?php echo $form->end('Submit'); ?>
	</div>
	<div class="column fLeft double endcol">
		
	</div>
</div>