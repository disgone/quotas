<?php $javascript->link('swfobject', false); ?>
<?php $javascript->link('jquery.editinplace.packed', false); ?>
<?php $javascript->link('projects/project', false); ?>
<div class="project" project_record="<?php echo $project['Project']['id']; ?>">
	<div class="project-header float-container">
		<div class="project-title fLeft">
			<h2><?php echo $project['Project']['number']; ?> <span class="title editable"><?php echo $project['Project']['name']; ?></span></h2>
			<p class="sm">
				<?php echo $time->nice($project['Quota'][0]['Quota']['created']); ?> - <?php echo $time->nice($project['Quota'][count($project['Quota'])-1]['Quota']['created']); ?>
			</p>
			<p class="location">
				<strong>Direct Link:</strong> <a href="file:///<?php echo $project['Project']['path']; ?>" title="Explore this project"><?php echo $project['Project']['path']; ?></a>
			</p>
		</div>
		<div class="project-status fRight">
			<ul>
				<li><strong>Status:</strong> <span class='value'><?php echo $changed[0][0]['days'] > 14 ? 'Perceived Inactive' : 'Active'; ?></span></li>
				<li><strong>Last Update:</strong> <span class='value'><?php echo $time->timeAgoInWords(strtotime($project['Quota'][count($project['Quota'])-1]['Quota']['created'])); ?> (<?php echo date('m/d g:ia', strtotime($project['Quota'][count($project['Quota'])-1]['Quota']['created'])); ?>)</span></li>
				<li><strong>Last Change:</strong> <span class='value'><?php echo $time->timeAgoInWords(strtotime($changed[0]['Quota']['created']), array('format' => 'n/j/Y')); ?> (<?php echo date('m/d g:ia', strtotime($changed[0]['Quota']['created'])); ?>)</span></li>
			</ul>
		</div>
	</div>
	<div class="stats">
		<table class="clear stats-panel">
			<tbody>
				<tr>
					<td class="first">
						<div class="stat">
							<h3>Current Usage</h3>
							<p class="stat-focus">
								<?php echo $units->format($quota['current']); ?>
							</p>
							<p class="stat-supp">
								Capacity Filled: <span class="value"><?php echo round(($quota['current']/$quota['allowed'])*100,3); ?>%</span>
							</p>
						</div>
					</td>
					<td>
						<div class="stat">
							<h3>Allotment</h3>
							<p class="stat-focus">
								<?php echo $units->format($quota['allowed']); ?>
							</p>
							<p class="stat-supp">
								Quota Remaining: <span class="value"><?php echo $units->format($quota['allowed'] - $quota['current']); ?></span>
							</p>
						</div>
					</td>
					<td>
						<div class="stat">
							<h3>Change</h3>
							<p class="stat-focus">
								<?php printf('%s', $units->format($quota['change'])); ?>
							</p>
							<p class="stat-supp">
								% Change: <span class="value"><?php echo round(($quota['change']/$quota['allowed'])*100, 3); ?>%</span>
							</p>
						</div>
					</td>
					<td>
						<div class="stat">
							<h3>Cost/Month</h3>
							<p class="stat-focus">
								$<?php echo number_format(($quota['current']*(30/1073741824)),2); ?>
							</p>
							<p class="stat-supp">
								Cost/Day: <span class="value">$<?php echo number_format($quota['current']*(30/1073741824)/date('t'),2); ?></span>
							</p>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
		<table class="stats-ticker">
			<tbody>
				<tr>
					<td>Range: <span class="value"><?php echo $units->format($quota['min'], true, 3) . ' - ' . $units->format($quota['max'], true, 3); ?></td>
					<td>Open: <span class="value"><?php echo $units->format($quota['start'], true, 3); ?></td>
					<td>High: <span class="value"><?php echo $units->format($quota['max'], true, 3); ?></td>
					<td>Low: <span class="value"><?php echo $units->format($quota['min'], true, 3); ?></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="controls clear">
		<div class="admin-controls">
			<?php echo $html->link('Delete Project', array('action' => 'delete', 'id' => $project['Project']['id']), array('class' => 'delete'), 'Deleting this project will remove all quota data associated with it as well.  Are you sure you wish to remove this project, this cannot be undone?')?>
		</div>
	</div>
	<div class="chart">
		<div class="title">
			<h3>Quota Usage Over Time</h3>
		</div>
		<?php echo $this->element('amstock', array('key' => $project['Project']['id'], 'project_id' => $project['Project']['id'], 'project' => $project)); ?>
	</div>
</div>