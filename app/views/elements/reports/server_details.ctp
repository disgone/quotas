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
		<?php $proj_count = $tot_cons = $tot_quota = $avg_cons = $avg_quota = 0; ?>
		<?php if(count($usage) > 0): ?>
			<?php foreach($usage as $key => $item): ?>
				<?php
					$proj_count += round($item[0]['consumed']/$item[0]['average_consumed']);
				$tot_cons += $item[0]['consumed'];
				$tot_quota += $item[0]['allowance'];
				$avg_cons += $item[0]['average_consumed'];
				$avg_quota += $item[0]['average_quota'];
			?>
			<tr<?php echo $key%2 == 1 ? " class='alt'" : ''; ?>>
				<td><?php echo $item['Server']['name']; ?></td>
				<td><?php echo $html->link(round($item[0]['consumed']/$item[0]['average_consumed']), array('controller' => 'projects', 'action' => 'index', $item['Server']['name'])); ?></td>
				<td><?php echo $units->format($item[0]['consumed']); ?></td>
				<td><?php echo $units->format($item[0]['allowance']); ?></td>
				<td><?php echo $units->format($item[0]['average_consumed']); ?></td>
				<td><?php echo $units->format($item[0]['average_quota']); ?></td>
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
			<th><?php echo @$units->format($avg_cons/count($usage)); ?></th>
			<th><?php echo @$units->format($avg_quota/count($usage)); ?></th>
		</tr>
	</tfoot>
</table>