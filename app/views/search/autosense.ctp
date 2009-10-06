<?php foreach($results as $key => $project): ?>
<?php echo trim($project['Project']['number'] . ' ' . $project['Project']['name']); ?> <em><?php echo $project['Server']['name']; ?></em>|<?php echo $project['Project']['id'] . "\n"; ?>
<?php endforeach; ?>