<h2><?php echo $this->pageTitle; ?></h2>
<div class="column-layout clearfix">
	<div class="column triple fLeft">
		<h3>My Projects</h3>
		<table class="records">
			<tr>
				<th>Project</th>
				<th>Server</th>
				<th class="aRight">Allowance</th>
				<th class="aRight">Usage</th>
				<th class="aRight">Created</th>
			</tr>
			<?php if($session->check('User')): ?>
				<?php if(count($projects) > 0): ?>
					<?php foreach($projects as $key => $project): ?>
						<tr<?php echo $key%2 == 0 ? " class='alt'" : ''; ?>>
							<td><?php echo $html->link(trim($project['Project']['number'] . ' ' . $project['Project']['name']), array('controller' => 'projects', 'action' => 'details', $project['Project']['id'])); ?></td>
							<td><?php echo $project['Server']['name']; ?></td>
							<td class="aRight"><?php echo $units->format($project['Project']['Quota']['allowance']); ?></td>
							<td class="aRight"><?php echo $units->format($project['Project']['Quota']['consumed']); ?></td>
							<td class="aRight"><?php echo $time->niceShort($project['Project']['created']); ?></td>
						</tr>
					<?php endforeach; ?>
				<?php else: ?>
				<tr>
					<td colspan="4" class="aCenter">You aren't tracking any projects.</td>
				</tr>
				<?php endif; ?>
			<?php else: ?>
				<tr>
					<td colspan="4" class="aCenter">You must be logged in to track and view your projects.</td>
				</tr>
			<?php endif; ?>
		</table>
	</div>
	<div class="column single fLeft endcol">
		<h3>My Stats</h3>
		<dl class="aRight">
			<dt>Total Projects</dt>
			<dd><?php echo count($projects); ?></dd>
			
			<dt>Quota Usage</dt>
			<dd><?php echo $units->format($total['used']); ?></dd>
			
			<dt>Quota Allowance</dt>
			<dd><?php echo $units->format($total['allowance']); ?></dd>
			
			<dt>Average Project Size</dt>
			<dd><?php echo $units->format($total['allowance']/count($projects)); ?></dd>
		</dl>
	</div>
</div>