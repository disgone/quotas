<h2><?php echo $this->pageTitle; ?></h2>
<div class="column-layout">
	<div class="row">
		<div class="column full">
			<?php echo $this->element('reports/server_details', array('usage' => $usage)); ?>
		</div>
	</div>
	<div class="row">
		<div class="column double fLeft">
			<table class="records" id="largest-increase">
				<thead>
					<tr>
						<th colspan="3">Largest Increase <em class="sm">(Today)</em></th>
					</tr>
				</thead>
				<tbody>
					<?php echo $this->element('reports/movers', array("movers" => $gainers)); ?>
				</tbody>
			</table>
		</div>
		<div class="column double endcol">
			<table class="records" id="largest-decrease">
				<thead>
					<tr>
						<th colspan="3">Largest Decrease <em class="sm">(Today)</em></th>
					</tr>
				</thead>
				<tbody>
					<?php echo $this->element('reports/movers', array("movers" => $losers)); ?>
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