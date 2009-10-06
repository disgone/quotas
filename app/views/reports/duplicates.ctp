<h2><?php echo $this->pageTitle; ?></h2>
<table class="records">
	<tr>
		<th colspan="6">Potential Duplicates</th>
	</tr>
	<tr class="subhead">
		<th>Project</th>
		<th>Server</th>
		<th>Created</th>
	</tr>
	<?php $cur = null; ?>
	<?php foreach($dupes as $key => $project): ?>
	<?php if($cur != $project['Project']['number']): ?>
	<tr>
		<th colspan="3"><?php echo $cur = $project['Project']['number']; ?></th>
	</tr>
	<?php endif; ?>
	<tr<?php echo $key%2 == 1 ? " class='alt'" : ''; ?>>
		<td><?php echo $html->link($project['Project']['number'] . ' ' . $project['Project']['name'], array('controller' => 'projects', 'action' => 'details', $project['Project']['id'])); ?></td>
		<td><?php echo $project['Server']['name']; ?></td>
		<td><?php echo $time->niceShort($project['Project']['created']); ?></td>
	</tr>
	<?php endforeach; ?>
</table>