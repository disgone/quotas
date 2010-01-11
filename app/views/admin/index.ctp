<div id="admin-home">
	<h2><?php echo $this->pageTitle; ?></h2>
	<div class="column-layout clearfix">
		<div class="row">
			<div class="column double fLeft">
				<h3>Projects</h3>
				<ul>
					<li>
						<a href="#">Delete Projects</a>
						<p>Delete old or invalid projects from the tracker.</p>
					</li>
					<li>
						<a href="#">Merge Projects</a>
						<p>Merge project data from one project to another project.</p>
					</li>
					<li>
						<a href="#">Hide Projects</a>
						<p>Hide projects so they do not appear on the project directory.</p>
					</li>
				</ul>
			</div>
			<div class="column double fLeft endcol">
				<h3>Users</h3>
				<ul>
					<li>
						<a href="#">User Index</a>
						<p>View a list of current users with accounts in the system.</p>
					</li>
					<li>
						<a href="#">Delete Users</a>
						<p>Delete old or invalid projects from the tracker.</p>
					</li>
					<li>
						<a href="#">Rename Users</a>
						<p>Merge project data from one project to another project.</p>
					</li>
					<li>
						<a href="#">Hide Projects</a>
						<p>Hide projects so they do not appear on the project directory.</p>
					</li>
				</ul>
			</div>
		</div>
		<div class="row">
			<div class="column double fLeft">
				<h3>Logs &amp; History</h3>
				<ul>
					<li>
						<?php echo $html->link('View Activity Log', array('controller' => 'action', 'action' => 'log', 'admin' => true)); ?>
						<p>View the most recent actions by system users.</p>
					</li>
					<li>
						<a href="#">Merge Projects</a>
						<p>Merge project data from one project to another project.</p>
					</li>
					<li>
						<a href="#">Hide Projects</a>
						<p>Hide projects so they do not appear on the project directory.</p>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>