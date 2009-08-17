<table class="records">
	<thead>
		<tr>
			<th>Project Number</th>
			<th>Project Name</th>
			<th>Server</th>
			<th class="aRight">Quota Allowance</th>
			<th class="aRight">Quota Usage</th>
			<th class="aRight">% Used</th>
			<th class="aRight">Last Update</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($projects as $key => $project): ?>
		<tr<?php echo $key%2 == 0 ? " class='alt'" : ''; ?>>
			<td><?php echo $html->link($project['Project']['number'], array('controller' => 'projects', 'action' => 'details', $project['Project']['id'])); ?></td>
			<td><?php echo $project['Project']['name'] ? $html->link($project['Project']['name'], array('controller' => 'projects', 'action' => 'details', $project['Project']['id'])) : ''; ?></td>
			<td><?php echo $project['Project']['server']; ?></td>
			<td class="aRight"><?php echo $units->format($project['Project']['Quota']['allowance']); ?></td>
			<td class="aRight"><?php echo $units->format($project['Project']['Quota']['consumed'], true, 3); ?></td>
			<td class="aRight"><?php echo round(($project['Project']['Quota']['consumed']/$project['Project']['Quota']['allowance'])*100, 2); ?>%</td>
			<td class="aRight"><?php echo date('m/d/y H:i:s', strtotime($project['Project']['Quota']['created'])); ?>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?php echo $paginator->numbers(); ?>