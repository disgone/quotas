<h2><?php echo $pageTitle; ?></h2>
<div class="column-layout">
	<div class="row">
		<div class="column full">
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
					<?php foreach($usage as $key => $item): ?>
					<tr<?php echo $key%2 == 1 ? " class='alt'" : ''; ?>>
						<td><?php echo $item['Server']['name']; ?></td>
						<td><?php echo $html->link(round($item[0]['consumed']/$item[0]['average_consumed']), array('controller' => 'projects', 'action' => 'index', $item['Server']['name'])); ?></td>
						<td><?php echo $units->format($item[0]['consumed']); ?></td>
						<td><?php echo $units->format($item[0]['allowance']); ?></td>
						<td><?php echo $units->format($item[0]['average_consumed']); ?></td>
						<td><?php echo $units->format($item[0]['average_quota']); ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="row">
		<div class="column double fLeft">
			<table class="records">
				<thead>
					<tr>
						<th colspan="3">Largest Increase <em class="sm">(Today)</em></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($gainers as $key => $project): ?>
					<tr<?php echo $key%2 == 1 ? " class='alt'" : ''; ?>>
						<td><?php echo $html->link($project['projects']['number'] . ' ' . $project['projects']['name'], array('controller' => 'projects', 'action' => 'details', $project['projects']['id']), null, null, false); ?></td>
						<td><?php echo $project['Server']['name']; ?></td>
						<td nowrap="true"><?php echo $units->format($project[0]['movement']); ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<div class="column double endcol">
			<table class="records">
				<thead>
					<tr>
						<th colspan="3">Largest Decrease <em class="sm">(Today)</em></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($losers as $key => $project): ?>
					<tr<?php echo $key%2 == 1 ? " class='alt'" : ''; ?>>
						<td><?php echo $html->link($project['projects']['number'] . ' ' . $project['projects']['name'], array('controller' => 'projects', 'action' => 'details', $project['projects']['id']), null, null, false); ?></td>
						<td><?php echo $project['Server']['name']; ?></td>
						<td nowrap="true"><?php echo $units->format($project[0]['movement']); ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="row">
		<div class="column full">
			<table class="records">
				<thead>
					<tr>
						<th colspan="4">New Projects <em class="sm">(24hrs)</em></th>
					</tr>
					<tr class="subhead">
						<th>Project</th>
						<th>Server</th>
						<th>Path</th>
						<th>Date Created</th>
					</tr>
				</thead>
				<tbody>
					<?php if(count($projects)): ?>
						<?php foreach($projects as $key => $project): ?>
							<?php if($key < 10): ?>
							<tr<?php echo $key%2 == 1 ? " class='alt'" : ''; ?>>
								<td><?php echo $html->link($project['Project']['number'] . ' ' . $project['Project']['name'], array('controller' => 'projects', 'action' => 'details', $project['Project']['id']), null, null, false); ?></td>
								<td><?php echo $project['Server']['name']; ?></td>
								<td><?php echo $project['Project']['path']; ?></td>
								<td><?php echo $time->nice($project['Project']['created']); ?></td>
							</tr>
							<?php endif; ?>
						<?php endforeach; ?>
						<?php if(count($projects) >= 10): ?>
							<tr class="subhead">
								<th colspan="4" class="aCenter">Wow, busy day.  There are too many new projects to list, <?php echo $html->link('view the full list of new additions', array('controller' => 'reports', 'action' => 'new_projects')); ?></a>.</th>
							</tr>
						<?php endif; ?>
					<?php else: ?>
						<tr>
							<td colspan="4" class="aCenter">There are no new projects (yet) today.</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>