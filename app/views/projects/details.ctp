<?php $javascript->link('swfobject', false); ?>
<div class="project">
	<div class="project-header">
		<h1><?php echo $project['Project']['number']; ?> <?php echo $project['Project']['name']; ?></h1>
		<p class="update">
			Last Update: <?php echo date('F d, Y H:i:s', strtotime($project['Quota'][count($project['Quota'])-1]['Quota']['created'])); ?>
		</p>
	</div>
	<div class="float-container">
		<div class="stats-controls">
			<dl>
				<dt>Period:</dt>
				<dd>
					<ul>
						<li class="selected"><a href="#">Today</a></li>
						<li><a href="#">3d</a></li>
						<li><a href="#">10d</a></li>
						<li><a href="#">30d</a></li>
						<li><a href="#">6m</a></li>
					</ul>
				</dd>
			</dl>
		</div>
	</div>
	<table class="stats-panel">
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
		<?php echo $this->element('amstock', array('cache' => array('time' => '+1 day', 'key' => $project['Project']['id']), 'project_id' => $project['Project']['id'], 'project' => $project)); ?>
	</div>
</div>

<!-- <pre>
<?php print_R($project); ?>
</pre> -->
