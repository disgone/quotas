<h2><?php echo $this->pageTitle; ?></h2>
<div class="column-layout clearfix">
	<div class="column triple fLeft">
		<h3>My Projects</h3>
		<table class="records">
			<tr>
				<th>Project</th>
				<th>Server</th>
				<th class="aRight">Usage</th>
				<th class="aRight">Allowance</th>
				<th class="aRight">Last Update</th>
			</tr>
			<?php if($session->check('User')): ?>
				<?php if(count($projects) > 0): ?>
					<?php foreach($projects as $key => $project): ?>
						<tr<?php echo $key%2 == 0 ? " class='alt'" : ''; ?>>
							<td><?php echo $html->link(trim($project['Project']['number'] . ' ' . $project['Project']['name']), array('controller' => 'projects', 'action' => 'details', $project['Project']['id'])); ?></td>
							<td><?php echo $project['Server']['name']; ?></td>
							<td class="aRight"><?php echo $units->format($project['Project']['Quota']['consumed']); ?></td>
							<td class="aRight"><?php echo $units->format($project['Project']['Quota']['allowance']); ?></td>
							<td class="aRight"><?php echo date('M d, Y h:ia', strtotime($project['Project']['Quota']['created'])); ?></td>
						</tr>
					<?php endforeach; ?>
				<?php else: ?>
				<tr>
					<td colspan="5" class="aCenter">You aren't tracking any projects.</td>
				</tr>
				<?php endif; ?>
			<?php else: ?>
				<tr>
					<td colspan="5" class="aCenter">You must be logged in to track and view your projects.</td>
				</tr>
			<?php endif; ?>
		</table>
	</div>
	<div class="column single fLeft endcol">
		<h3>My Stats</h3>
		<?php if($session->check('User')): ?>
			<?php if(count($projects) > 0): ?>
				<div class="stats-panel">
					<dl>
						<dt class="fRight">Total Projects</dt>
						<dd class="stat-focus"><?php echo count($projects); ?></dd>
						
						<dt class="fRight">Quota Usage</dt>
						<dd class="stat-focus"><?php echo $units->format($total['used']); ?></dd>
						
						<dt class="fRight">Quota Allowance</dt>
						<dd class="stat-focus"><?php echo $units->format($total['allowance']); ?></dd>
						
						<dt class="fRight">Average Project Size</dt>
						<dd class="stat-focus"><?php echo $units->format($total['allowance']/count($projects)); ?></dd>
					</dl>
				</div>
			<?php else: ?>
			<p>You must have projects being tracked to view your stats.</p>
			<?php endif; ?>
		<?php else: ?>
			<p>You must be logged in to track and view your project stats.</p>
		<?php endif; ?>
	</div>
</div>