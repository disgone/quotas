<h2><?php echo $this->pageTitle; ?></h2>
<div class="column-layout clearfix">
	<div class="column triple fLeft">
		<h3>My Projects</h3>
			<?php if($session->check('User')): ?>
				<?php if(count($projects) > 0): ?>
					<?php echo $this->element('projects/list_full', array('projects' => $projects)); ?>
				<?php else: ?>
					<p>You aren't tracking any projects.</p>
				<?php endif; ?>
			<?php else: ?>
				<p class="message info">
					You must be logged in to be able to track projects.
				</p>
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