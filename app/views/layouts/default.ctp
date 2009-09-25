<!DOCTYPE html>
<html> 
<head> 
	<meta http-equiv="content-type" content="text/html;charset=utf-8"/> 
	<title><?php echo $title_for_layout ? $title_for_layout . " | " : '';?> Project Quota Monitor</title>
	<?php echo $html->css('site.css', 'stylesheet', array('media' => 'screen,projection')); ?>
	<?php echo Configure::read('debug') > 1 ? $html->css('cake.debug') : ''; ?>
	<?php 
		if(isset($javascript)) {
			echo $javascript->link('jquery-1.3.2.min');
			echo $javascript->link('search');
		}
	?>
	<?php echo $scripts_for_layout ?>
</head> 
<body>
	<div id="body" class="wrap">
		<div id="head">
			<div id="logo">
				<h1>Quota Tracker</h1>
			</div>
			<div id="search">
				<form>
					<label for="search"><input type="text" name="q" id="q" value="Search" /></label>
				</form>
			</div>
			<div id="navigation">
				<ul>
					<li><?php echo $html->link('Projects', array('controller' => 'projects', 'action' => 'index'), array('title' => 'Go to the main project index', 'class' => 'current')); ?></li>
					<li><?php echo $html->link('Reports', array('controller' => 'reports', 'action' => 'index'), array('title' => 'View the project report dashboard')); ?></li>
					<li><a href="#">Search</a></li>
				</ul>
			</div>
		</div>
		<div id="content">
			<?php $session->check('Message.flash') ? $session->flash() : ''; ?>
			<?php echo $content_for_layout ?>
		</div>
	</div>
</body>
</html>