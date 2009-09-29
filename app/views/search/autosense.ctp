<?php foreach($results as $key => $project): ?>
<?php echo trim($project['Project']['number'] . ' ' . $project['Project']['name']) . "|" . $project['Project']['id'] . "\n"; ?>
<?php endforeach; ?>