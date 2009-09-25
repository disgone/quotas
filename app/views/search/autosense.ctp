<h2>Search Results</h2>
<?php if(count($results) > 0): ?>
	<p>Search returned <?php echo count($results); ?> results.</p>
	<table class="records">
		<tr>
			<th>Project</th>
			<th>Server</th>
		</tr>
		<?php foreach($results as $key => $project): ?>
		<tr<?php echo $key%2 == 0 ? " class='alt'" : ''; ?>>
			<td><?php echo $html->link(trim($project['Project']['number'] . ' ' . $project['Project']['name']), array('controller' => 'projects', 'action' => 'details', $project['Project']['id'])); ?></td>
			<td><?php echo $project['Server']['name']; ?></td>
		</tr>
		<?php endforeach; ?>
	</table>
<?php else: ?>
	<p>No projects were found that matched your search term.</p>
<?php endif; ?>