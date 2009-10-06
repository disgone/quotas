<!DOCTYPE html>
<html> 
<head> 
	<meta http-equiv="content-type" content="text/html;charset=utf-8"/> 
	<title><?php echo $title_for_layout ? $title_for_layout . " | " : '';?> Quota Tracker</title>
	<link rel="shortcut icon" href="<?php echo $html->url('/favicon.ico'); ?>" />
	<?php echo $html->css('site.css', 'stylesheet', array('media' => 'screen,projection')); ?>
	<?php echo $html->css('jquery.autocomplete'); ?>
	<?php echo Configure::read('debug') > 1 ? $html->css('cake.debug') : ''; ?>
	<?php 
		if(isset($javascript)) {
			echo $javascript->link('jquery-1.3.2.min');
			echo $javascript->link('jquery.autocomplete.min.js');
			echo $javascript->link('search');
		}
	?>
	<?php echo $scripts_for_layout ?>
</head>
<body>
	<?php echo $this->element('top_nav_bar'); ?>
	<div id="body" class="wrap">
		<div id="head">
			<div id="logo">
				<h1><?php echo $html->link('Quota Tracker', array('controller' => 'projects', 'action' => 'index'), array('title' => 'HKS Quota Tracker')); ?></h1>
			</div>
			<div id="search">
				<?php echo $form->create('Search', array('url' => '/search/results/', 'type' => 'get', 'id' => 'search-form')); ?>
					<input type="text" name="q" id="search-term" class="em light field" value="Project name or number" />
					<input type="submit" name="search_btn" id="search_btn" class="sub_btn" value="Search" />
				</form>
			</div>
			<?php echo $this->element('main_navigation'); ?>
		</div>
		<div id="content">
			<?php $session->check('Message.flash') ? $session->flash() : ''; ?>
			<?php echo $content_for_layout ?>
		</div>
	</div>
</body>
</html>