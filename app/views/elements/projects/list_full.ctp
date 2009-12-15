<table class="records">
	<thead>
		<?php if(isset($paginator)): ?>
			<?php echo $this->element('projects/project-header-sortable'); ?>
		<?php else: ?>
			<?php echo $this->element('projects/project-header'); ?>
		<?php endif; ?>
	</thead>
	<tbody>
		<?php foreach($projects as $key => $project): ?>
		<tr<?php echo $key%2 == 0 ? " class='alt'" : ''; ?>>
			<td>
				<?php if($session->check('User') && isset($favs)): ?>
					<?php if(in_array($project['Project']['id'], $favs)): ?>
						<a class="favorite star pointer" href="<?php echo $html->url(array('controller' => 'projects', 'action' => 'track', 'remove', $project['Project']['id'])); ?>" title="Remove from My Projects list."></a>
					<?php else: ?>
						<a class="favorite estar pointer" href="<?php echo $html->url(array('controller' => 'projects', 'action' => 'track', 'add', $project['Project']['id'])); ?>" title="Add to My Projects list."></a>
					<?php endif; ?>
				<?php endif; ?>
				<?php echo $html->link($project['Project']['number'], array('controller' => 'projects', 'action' => 'details', $project['Project']['id']), null, null, false); ?>
			</td>
			<td><?php echo $project['Project']['name'] ? $html->link($project['Project']['name'], array('controller' => 'projects', 'action' => 'details', $project['Project']['id']), null, null, false) : ''; ?></td>
			<td><?php echo $project['Server']['name']; ?></td>
			<td class="aRight"><?php echo $units->format($project['Quota'][0]['consumed'], true, 3); ?></td>
			<td class="aRight"><?php echo $units->format($project['Quota'][0]['allowance']); ?></td>
			<td class="aRight"><?php echo round(($project['Quota'][0]['consumed']/$project['Quota'][0]['allowance'])*100, 2); ?>%</td>
			<td class="aRight nowrap"><?php echo date('m/d/Y h:ia', strtotime($project['Quota'][0]['created'])); ?></td>
		</tr>
		<?php endforeach; ?>
		<?php if(count($projects) < 1): ?>
		<tr>
			<td colspan="7" class="aCenter">No projects are available.</td>
		</tr>
		<?php endif; ?>
	</tbody>
</table>