<h2>Search Results</h2>
<?php if(count($results)): ?>
	<p>Your search for "<strong><?php echo $term; ?></strong>" matched <strong><?php echo count($results); ?></strong> projects.</p>
	<table class="records">
		<thead>
			<tr>
				<th>Project Number</th>
				<th>Project Name</th>
				<th>Server</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($results as $key => $project): ?>
			<tr<?php echo $key%2 == 0 ? " class='alt'" : ''; ?>>
				<td><?php echo $html->link($lighter->hl($project['Project']['number'],$term), array('controller' => 'projects', 'action' => 'details', $project['Project']['id']), array('class' => 'match'), false, false); ?></td>
				<td><?php echo $project['Project']['name'] ? $html->link($lighter->hl($project['Project']['name'],$term), array('controller' => 'projects', 'action' => 'details', $project['Project']['id']), array('class' => 'match'), false, false) : ''; ?></td>
				<td><?php echo $project['Server']['name']; ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php else: ?>
	<?php if($term == "" || $term == null): ?>
		<p class="message error">You fool, you forgot to add search terms!</p>
	<?php else: ?>
		<p class="message notice">No results were found for your search for "<strong><?php echo $term; ?></strong>".</p>
		<h3>Search Tips &amp; Instructions</h3>
		<ul class="bullets">
			<li>Terms can include either the project name or number</li>
			<li>Partial terms are accepted, for example "Hil" will match "<span class="match"><strong>Hil</strong></span>ton" or "Dr P<span class="match"><strong>hil</strong></span>lips"</li>
			<li>Spelling counts but capitialization doesnt</li>
			<li>If you are getting too many results, try to be more specific with your keywords or vise-versa</li>
		</ul>
	<?php endif; ?>
<?php endif; ?>