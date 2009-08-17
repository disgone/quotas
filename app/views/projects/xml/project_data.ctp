<chart>
	<series>
		<?php foreach($data['Quota'] as $key => $project): ?>
		<value xid="<?php echo $key; ?>"><?php echo date('M d H:i', strtotime($project['Quota']['created'])); ?></value>
		<?php endforeach; ?>
	</series>
	<graphs>
		<graph gid="1" title="Usage">
			<?php foreach($data['Quota'] as $key => $project): ?>
			<value xid="<?php echo $key; ?>" description="<?php echo $units->format($project['Quota']['consumed'], true, 4); ?>"><?php echo $units->convertTo($project['Quota']['consumed'], $data['Meta']['unit']['index']); ?></value>
			<?php endforeach; ?>
		</graph><?php echo date('H:i', strtotime($project['Quota']['created'])); ?>
		<graph gid="2" title="Allowance">
			<?php foreach($data['Quota'] as $key => $project): ?>
			<value xid="<?php echo $key; ?>" description="<?php echo $units->format($project['Quota']['allowance'], true, 4); ?>"><?php echo $units->convertTo($project['Quota']['allowance'], $data['Meta']['unit']['index']); ?></value>
			<?php endforeach; ?>
		</graph>
	</graphs>
	<labels>
		<label lid="0">
			<text><![CDATA[<b>Quota Usage</b>]]></text>
			<y>10</y>
			<text_size>14</text_size>
			<align>center</align>
		</label>
		<label lid="1">
			<text><![CDATA[<?php echo $data['Project']['number'] . ' ' . $data['Project']['name'];?>]]></text>
			<y>25</y>
			<text_size>12</text_size>
			<align>center</align>
		</label>
	</labels> 
</chart>
<!--
<?php print_r($data); ?>
-->