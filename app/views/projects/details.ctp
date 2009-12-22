<?php $javascript->link('lib/swfobject', false); ?>
<?php $javascript->link('lib/jquery.editinplace.packed', false); ?>
<?php $javascript->link('projects/project', false); ?>
<div class="project" project_record="<?php echo $project['Project']['id']; ?>">
	<div class="project-head">
		<h2>
			<?php echo $project['Project']['number']; ?>
			<?php if($session->read('User.Group.name') == 'Admin'): ?>
				<span class="title editable"><?php echo $project['Project']['name']; ?></span>
			<?php else: ?>
				<?php echo $project['Project']['name']; ?>
			<?php endif; ?>
		</h2>
		<?php echo $this->element('projects/project-bar'); ?>
	</div>
	<div class="stats">
		<table class="stats-panel">
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
	<!-- Quota Graph -->
	<div class="chart">
		<div class="title">
			<h3>Quota Usage Over Time</h3>
		</div>
		<?php echo $this->element('amstock', array('key' => $project['Project']['id'], 'project_id' => $project['Project']['id'], 'project' => $project)); ?>
	</div>
	<!-- End Quota Graph -->
</div>