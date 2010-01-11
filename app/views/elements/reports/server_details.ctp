<table id="servers" class="records">
	<thead>
		<tr>
			<th colspan="6">Server Details <em class="sm">(Estimated)</em></th>
		</tr>
		<tr class="subhead">
			<th>Server</th>
			<th>Project Count</th>
			<th>Disk Usage</th>
			<th>Quota Allowance</th>
			<th>Average Project Size</th>
			<th>Average Project Quota</th>
		</tr>
	</thead>
	<tbody>
		<?php if(count($usage) > 0): ?>
			<?php $proj_count = $tot_cons = $tot_quota = 0; ?>
			<?php foreach($usage as $key => $item): ?>
				<?php 
					$proj_count += $item['server_stats']['projects'];
					$tot_cons += $item['server_stats']['consumed']; 
					$tot_quota += $item['server_stats']['allowance'];
				?>
			<tr<?php echo $key%2 == 1 ? " class='alt'" : ''; ?>>
				<td><?php echo $item['server_stats']['name']; ?></td>
				<td><?php echo $html->link($item['server_stats']['projects'], array('controller' => 'projects', 'action' => 'index', $item['server_stats']['name'])); ?></td>
				<td><?php echo $units->format($item['server_stats']['consumed']); ?></td>
				<td><?php echo $units->format($item['server_stats']['allowance']); ?></td>
				<td><?php echo $units->format($item['server_stats']['project_size']); ?></td>
				<td><?php echo $units->format($item['server_stats']['project_quota']); ?></td>
			</tr>
			<?php endforeach; ?>
		<?php else: ?>
			<tr>
				<td class="aCenter" colspan="6">No servers have been scanned yet.</td>
			</tr>
		<?php endif; ?>
	</tbody>
	<tfoot>
		<tr class="subhead">
			<th>Totals:</th>
			<th><?php echo $proj_count; ?></th>
			<th><?php echo $units->format($tot_cons); ?></th>
			<th><?php echo $units->format($tot_quota); ?></th>
			<th><?php echo @$units->format($tot_cons/$proj_count); ?></th>
			<th><?php echo @$units->format($tot_quota/$proj_count); ?></th>
		</tr>
	</tfoot>
</table>