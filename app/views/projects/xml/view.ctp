<result>
	<request>
		<time><?php echo date('Y-m-d H:i:s T'); ?></time>
	</request>
<?php echo $xml->serialize($project, array('format' => 'tags')); ?>
</result>