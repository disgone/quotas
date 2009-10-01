<h2>Merge Project Data</h2>
<p class="message notice">This is intended to merge quota for a project when the pathname is updated.  <strong>This will delete the old project and once run, this cannot be undone, so please use with caution.</strong></p>
<?php echo $form->create('Project', array('controller' => 'projects', 'action' => 'merge', 'class' => 'dataForm')); ?>
<?php echo $form->end('Submit'); ?>