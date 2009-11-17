<tbody>
	<?php foreach($movers as $key => $project): ?>
	<tr<?php echo $key%2 == 1 ? " class='alt'" : ''; ?>>
		<td><?php echo $html->link($project['projects']['number'] . ' ' . $project['projects']['name'], array('controller' => 'projects', 'action' => 'details', $project['projects']['id']), null, null, false); ?></td>
		<td><?php echo $project['Server']['name']; ?></td>
		<td nowrap="true"><?php echo $units->format($project[0]['movement']); ?></td>
	</tr>
	<?php endforeach; ?>
</tbody>