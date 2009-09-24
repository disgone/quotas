<h2><?php echo $pageTitle; ?></h2>
<table class="records">
	<tr>
		<th colspan="4">New Projects <em class="sm">(24hrs)</em></th>
	</tr>
	<tr class="subhead">
		<th>Project</th>
		<th>Server</th>
		<th>Date Added</th>
	</tr>
	<?php if(count($projects)): ?>
		<?php foreach($projects as $key => $project): ?>
			<tr<?php echo $key%2 == 1 ? " class='alt'" : ''; ?>>
				<td><?php echo $html->link($project['Project']['number'] . ' ' . $project['Project']['name'], array('controller' => 'projects', 'action' => 'details', $project['Project']['id'])); ?></td>
				<td><?php echo $project['Server']['name']; ?></td>
				<td><?php echo $time->nice($project['Project']['created']); ?></td>
			</tr>
		<?php endforeach; ?>
	<?php else: ?>
		<tr>
			<td colspan="3" class="aCenter">No new projects were found.</td>
		</tr>
	<?php endif; ?>
</table>