<!DOCTYPE html>
<html> 
<head> 
	<meta http-equiv="content-type" content="text/html;charset=utf-8"/> 
	<title><?php echo $title_for_layout ? $title_for_layout . " | " : '';?> Project Quota Monitor</title>
	<?php echo $html->css('reset.css', 'stylesheet', array('media' => 'screen')); ?>
	<?php echo $html->css('style.css', 'stylesheet', array('media' => 'screen')); ?>
	<?php echo Configure::read('debug') > 1 ? $html->css('cake.debug') : ''; ?>
	<?php echo $javascript->link('jquery-1.3.2.min'); ?>
	<?php echo $scripts_for_layout ?>
</head> 
<body>
	<div id="head">
		<div id="navigation">
			<ul>
				<li><?php echo $html->link('Projects', array('controller' => 'projects', 'action' => 'index'), array('title' => 'Go to the main project index')); ?></li>
			</ul>
		</div>
	</div>
	<div id="wrap">
		<div id="body">
			<?php echo $content_for_layout ?>
		</div>
	</div>
</body>
</html>