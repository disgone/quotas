<ul class="project-bar" style="margin: 3px 0pt 15px; font-size: 11px; font-family: Arial; text-align: right;">
	<li class="fLeft"><a href="file:///<?php echo $project['Project']['path']; ?>" title="Open this project's folder: <?php echo $project['Project']['path']; ?>">Open Project Folder</a></li>
	<li class="fLeft"><?php echo $html->link($project['Server']['name'], array('action' => 'index', $project['Server']['name'])); ?></li>
	<li class="fLeft"><span style="padding: 3px 5px;"><strong>Last Update:</strong> <?php echo $time->timeAgoInWords(strtotime($project['Quota'][count($project['Quota'])-1]['Quota']['created'])); ?> (<?php echo date('m/d g:ia', strtotime($project['Quota'][count($project['Quota'])-1]['Quota']['created'])); ?>)</span></li>
	<?php if($session->check('User')): ?>
		<li>
			<?php if(!$following): ?>
				<?php echo $html->link('Add To My Projects', array('action' => 'track', "add", $project['Project']['id']), array("title" => "Add to My Projects list", "class" => "estar icon fav")); ?>
			<?php else: ?>
				<?php echo $html->link('Remove From My Projects', array('action' => 'track', "remove", $project['Project']['id']), array("title" => "Remove from My Projects list", "class" => "star icon fav")); ?>
			<?php endif; ?>
		</li>
		<?php if($isAdmin): ?>
			<li><a href="#">Hide</a></li>
			<li><?php echo $html->link("Merge", array('controller' => 'projects', 'action' => 'merge', 'admin' => true, $project['Project']['id'])); ?></li>
			<li><?php echo $html->link("Delete", array('controller' => 'projects', "action" => 'delete', $project['Project']['id']), array('title' => "Delete this project"), "Are you sure you want to delete this project?  This cannot be undone."); ?></li>
		<?php endif; ?>
	<?php endif; ?>
</ul>