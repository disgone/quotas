<h1>Project Reports</h1>
<div class="column-layout">
	<div class="4col double">
		<table class="records">
			<tr>
				<th colspan="2">Largest Increase (24hrs)</th>
			</tr>
			<?php foreach($gainers as $key => $project): ?>
			<tr<?php echo $key%2 == 0 ? " class='alt'" : ''; ?>>
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
			<tr<?php echo $key%2 == 0 ? " class='alt'" : ''; ?>>
				<td><?php echo $html->link($project['projects']['number'] . ' ' . $project['projects']['name'], array('controller' => 'projects', 'action' => 'details', $project['projects']['id'])); ?></td>
				<td><?php echo $units->format($project[0]['movement']); ?> </td>
			</tr>
			<?php endforeach; ?>
		</table>
	</div>
</div>