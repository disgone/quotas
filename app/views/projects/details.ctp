<?php $javascript->link('swfobject', false); ?>
<?php $javascript->link('project', false); ?>
<div class="project" project_record="<?php echo $project['Project']['id']; ?>">
	<div class="project-header float-container">
		<div class="project-title fLeft">
			<h1><span class="hover_target"><?php echo $project['Project']['number']; ?> <?php echo $project['Project']['name'] != null ? $project['Project']['name'] : "<em class='missing-detail'>Project Name</em>"; ?></span></h1>
			<p class="location">
				<strong>Direct Link:</strong> <a href="file:///<?php echo $project['Project']['path']; ?>" title="Explore this project"><?php echo $project['Project']['path']; ?></a>
			</p>
		</div>
		<div class="project-meta fRight">
			<ul>
				<li><strong>Status:</strong> <span class='value'><?php echo $changed[0][0]['days'] > 14 ? 'Inactive' : 'Active'; ?></span></li>
				<li><strong>Last Update:</strong> <span class='value'><?php echo $time->timeAgoInWords(strtotime($project['Quota'][count($project['Quota'])-1]['Quota']['created'])); ?> (<?php echo date('m/d g:ia', strtotime($project['Quota'][count($project['Quota'])-1]['Quota']['created'])); ?>)</span></li>
				<li><strong>Last Change:</strong> <span class='value'><?php echo $time->timeAgoInWords(strtotime($changed[0]['Quota']['created'])); ?> (<?php echo date('m/d g:ia', strtotime($changed[0]['Quota']['created'])); ?>)</span></li>
			</ul>
		</div>
	</div>
	<table class="clear stats-panel">
		<tbody>
			<tr>
				<td class="first">
					<div class="stat">
						<h2>Usage</h2>
						<p class="stat-focus">
							<?php echo $units->format($quota['current']); ?>
						</p>
						<p class="stat-meta">
							Capacity Filled: <span class="value"><?php echo round(($quota['current']/$quota['allowed'])*100,3); ?>%</span>
						</p>
					</div>
				</td>
				<td>
					<div class="stat">
						<h2>Allotment</h2>
						<p class="stat-focus">
							<?php echo $units->format($quota['allowed']); ?>
						</p>
						<p class="stat-meta">
							Quota Remaining: <span class="value"><?php echo $units->format($quota['allowed'] - $quota['current']); ?></span>
						</p>
					</div>
				</td>
				<td>
					<div class="stat">
						<h2>Change</h2>
						<p class="stat-focus">
							<?php printf('%s', $units->format($quota['change'])); ?>
						</p>
						<p class="stat-meta">
							% Change: <span class="value"><?php echo round(($quota['change']/$quota['allowed'])*100, 3); ?>%</span>
						</p>
					</div>
				</td>
				<td>
					<div class="stat">
						<h2>Cost/Month</h2>
						<p class="stat-focus">
							$<?php echo number_format(($quota['current']*(30/1073741824)),2); ?>
						</p>
						<p class="stat-meta">
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
	<div class="chart">
		<?php echo $this->element('amstock', array('key' => $project['Project']['id'], 'project_id' => $project['Project']['id'], 'project' => $project)); ?>
	</div>
</div>