<h2><?php echo $this->pageTitle; ?></h2>
<table class="records">
	<tr>
		<th colspan="6">Potential Duplicates</th>
	</tr>
	<tr class="subhead">
		<th>Project</th>
		<th>Server</th>
		<th>Created</th>
		<th>Usage</th>
		<th>Allowance</th>
		<th>Last Update</th>
	</tr>
	<?php foreach($dupes as $key => $project): ?>
	<tr<?php echo $key%2 == 1 ? " class='alt'" : ''; ?>>
		<td><?php echo $html->link($project['Project']['number'] . ' ' . $project['Project']['name'], array('controller' => 'projects', 'action' => 'details', $project['Project']['id'])); ?></td>
		<td><?php echo $project['Server']['name']; ?></td>
		<td><?php echo $time->niceShort($project['Project']['created']); ?></td>
		<td><?php echo $units->format($project['Project']['Quota']['consumed']); ?></td>
		<td><?php echo $units->format($project['Project']['Quota']['allowance']); ?></td>
		<td><?php echo $time->niceShort($project['Project']['Quota']['created']); ?></td>
	</tr>
	<?php endforeach; ?>
</table>