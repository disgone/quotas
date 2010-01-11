<div id="navigation" class="navigation">
	<ul>
		<li<?php echo $this->params['controller'] == 'dashboard' ? " class='current'" : ''; ?>><?php echo $html->link('Dashboard', array('admin' => false, 'controller' => 'dashboard', 'action' => 'index'), array('title' => 'View your dashboard')); ?></li>
		<li<?php echo $this->params['controller'] == 'projects' ? " class='current'" : ''; ?>><?php echo $html->link('Projects', array('admin' => false, 'controller' => 'projects', 'action' => 'index'), array('title' => 'Go to the main project index')); ?></li>
		<li<?php echo $this->params['controller'] == 'reports' ? " class='current'" : ''; ?>><?php echo $html->link('Reports', array('admin' => false, 'controller' => 'reports', 'action' => 'index'), array('title' => 'View the project report dashboard')); ?></li>
	</ul>
</div>
<?php if($this->params['controller'] == 'reports'): ?>
<div id="subnavigation" class="navigation">
	<ul>
		<li<?php echo $this->params['action'] == 'index' ? " class='current'" : ''; ?>><?php echo $html->link('Main', array('admin' => false, 'controller' => 'reports', 'action' => 'index')); ?></li>
		<li<?php echo $this->params['action'] == 'new_projects' ? " class='current'" : ''; ?>><?php echo $html->link('New Projects', array('admin' => false, 'controller' => 'reports', 'action' => 'new_projects')); ?></li>
		<li<?php echo $this->params['action'] == 'duplicates' ? " class='current'" : ''; ?>><?php echo $html->link('Duplicates', array('admin' => false, 'controller' => 'reports', 'action' => 'duplicates')); ?></li>
	</ul>
</div>
<?php endif; ?>