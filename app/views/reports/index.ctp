<h2><?php echo $pageTitle; ?></h2>
<div class="column-layout">
	<div class="column full">
		<table class="records">
			<tr>
				<th colspan="6">Server Details</th>
			</tr>
			<tr class="subhead">
				<th>Server</th>
				<th>Disk Usage (est)</th>
				<th>Project Count</th>
				<th>Quota Allowance (est)</th>
				<th>Average Project Size</th>
				<th>Average Project Quota</th>
			</tr>
			<?php foreach($usage as $key => $item): ?>
			<tr<?php echo $key%2 == 1 ? " class='alt'" : ''; ?>>
				<td><?php echo $item['Server']['name']; ?></td>
				<td><?php echo $units->format($item[0]['consumed']); ?></td>
				<td><?php echo round($item[0]['consumed']/$item[0]['average_consumed']); ?></td>
				<td><?php echo $units->format($item[0]['allowance']); ?></td>
				<td><?php echo $units->format($item[0]['average_consumed']); ?></td>
				<td><?php echo $units->format($item[0]['average_quota']); ?></td>
			</tr>
			<?php endforeach; ?>
		</table>
	</div>
	<div class="column double fLeft">
		<table class="records">
			<tr>
				<th colspan="2">Largest Increase <em class="sm">(24hrs)</em></th>
			</tr>
			<?php foreach($gainers as $key => $project): ?>
			<tr<?php echo $key%2 == 1 ? " class='alt'" : ''; ?>>
				<td><?php echo $html->link($project['projects']['number'] . ' ' . $project['projects']['name'], array('controller' => 'projects', 'action' => 'details', $project['projects']['id'])); ?></td>
				<td><?php echo $units->format($project[0]['movement']); ?> </td>
			</tr>
			<?php endforeach; ?>
		</table>
	</div>
	<div class="column double endcol">
		<table class="records">
			<tr>
				<th colspan="2">Largest Decrease <em class="sm">(24hrs)</em></th>
			</tr>
			<?php foreach($losers as $key => $project): ?>
			<tr<?php echo $key%2 == 1 ? " class='alt'" : ''; ?>>
				<td><?php echo $html->link($project['projects']['number'] . ' ' . $project['projects']['name'], array('controller' => 'projects', 'action' => 'details', $project['projects']['id'])); ?></td>
				<td><?php echo $units->format($project[0]['movement']); ?> </td>
			</tr>
			<?php endforeach; ?>
		</table>
	</div>
	<div class="column full">
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
					<?php if($key < 10): ?>
					<tr<?php echo $key%2 == 1 ? " class='alt'" : ''; ?>>
						<td><?php echo $html->link($project['Project']['number'] . ' ' . $project['Project']['name'], array('controller' => 'projects', 'action' => 'details', $project['Project']['id'])); ?></td>
						<td><?php echo $project['Server']['name']; ?></td>
						<td><?php echo $time->nice($project['Project']['created']); ?></td>
					</tr>
					<?php endif; ?>
				<?php endforeach; ?>
				<?php if(count($projects) >= 10): ?>
					<tr class="subhead">
						<th colspan="3" class="aCenter">Wow, busy day.  There are too many new projects to list, <?php echo $html->link('view the full list of new additions', array('controller' => 'reports', 'action' => 'new_projects')); ?></a>.</th>
					</tr>
				<?php endif; ?>
			<?php else: ?>
				<tr>
					<td colspan="3" class="aCenter">There are no new projects (yet) today.</td>
				</tr>
			<?php endif; ?>
		</table>
	</div>
</div>