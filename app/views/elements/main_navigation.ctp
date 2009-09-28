<div id="navigation">
	<ul>
		<li<?php echo $controller == 'projects' ? " class='current'" : ''; ?>><?php echo $html->link('Projects', array('controller' => 'projects', 'action' => 'index'), array('title' => 'Go to the main project index')); ?></li>
		<li<?php echo $controller == 'reports' ? " class='current'" : ''; ?>><?php echo $html->link('Reports', array('controller' => 'reports', 'action' => 'index'), array('title' => 'View the project report dashboard')); ?></li>
		<li<?php echo $controller == 'search' ? " class='current'" : ''; ?>><?php echo $html->link('Search', array('controller' => 'search', 'action' => 'index'), array('title' => 'Project search.')); ?></li>
</div>