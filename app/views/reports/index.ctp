<h2>Project Reports</h2>
<div class="column-layout">
	<div class="4col double">
		<table class="records">
			<tr>
				<th colspan="2">Largest Increase (24hrs)</th>
			</tr>
			<?php foreach($gainers as $key => $project): ?>
			<tr<?php echo $key%2 == 1 ? " class='alt'" : ''; ?>>
				<td><?php echo $html->link($project['projects']['number'] . ' ' . $project['projects']['name'], array('controller' => 'projects', 'action' => 'details', $project['projects']['id'])); ?></td>
				<td><?php echo $units->format($project[0]['movement']); ?> </td>
			</tr>
			<?php endforeach; ?>
		</table>
	</div>
	<div class="4col double endcol">
		<table class="records">
			<tr>
				<th colspan="2">Largest Decrease (24hrs)</th>
			</tr>
			<?php foreach($losers as $key => $project): ?>
			<tr<?php echo $key%2 == 1 ? " class='alt'" : ''; ?>>
				<td><?php echo $html->link($project['projects']['number'] . ' ' . $project['projects']['name'], array('controller' => 'projects', 'action' => 'details', $project['projects']['id'])); ?></td>
				<td><?php echo $units->format($project[0]['movement']); ?> </td>
			</tr>
			<?php endforeach; ?>
		</table>
	</div>
	<div>
		<table class="records">
			<tr>
				<th colspan="4">Server Details</th>
			</tr>
			<tr class="subhead">
				<th>Server</th>
				<th>Disk Usage (est)</th>
				<th>Quota Allowance (est)</th>
				<th>Average Usage</th>
			</tr>
			<?php foreach($usage as $key => $item): ?>
			<tr<?php echo $key%2 == 1 ? " class='alt'" : ''; ?>>
				<td><?php echo $item['Server']['name']; ?></td>
				<td><?php echo $units->format($item[0]['consumed']); ?></td>
				<td><?php echo $units->format($item[0]['allowance']); ?></td>
				<td><?php echo $units->format($item[0]['average_consumed']); ?></td>
			</tr>
			<?php endforeach; ?>
		</table>
	</div>
</div>