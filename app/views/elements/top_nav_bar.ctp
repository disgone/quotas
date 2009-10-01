<div id="top-nav">
<?php if($session->check('User')): ?>
	<?php echo $session->read('User.displayname'); ?>
	|
	<?php echo $html->link(__('My Dashboard', true), array('controller' => 'dashboard', 'action' => 'index'), array('title' => 'View your dashboard')); ?>
	|
	<?php echo $html->link(__('Administration', true), array('controller' => 'projects', 'action' => 'index'), array('title' => 'Admin control panel')); ?>
	|
	<?php echo $html->link(__('Log Out', true), '/logout', array('title' => 'Log out of Quota Tracker')); ?>
<?php else: ?>
	Welcome Guest
	|
	<?php echo $html->link(__('Log In', true), '/login', array('title' => 'Log in to Quota Tracker')); ?>
<?php endif; ?>
</div>