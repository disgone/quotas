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
				<h1><?php echo $html->link('Quota Tracker', array('controller' => 'projects', 'action' => 'index')); ?></h1>
			</div>
			<div id="search">
				<?php echo $form->create('Search', array('url' => '/search/results/', 'type' => 'get', 'id' => 'search-form')); ?>
					<input type="text" name="q" id="search-term" class="em light field" value="Project name or number" />
					<input type="submit" name="search_btn" id="search_btn" value="Search" />
				</form>
			</div>
			<?php echo $this->element('main_navigation', array('controller' => $this->params['controller'])); ?>
		</div>
		<div id="content">
			<?php $session->check('Message.flash') ? $session->flash() : ''; ?>
			<?php echo $content_for_layout ?>
		</div>
	</div>
</body>
</html>