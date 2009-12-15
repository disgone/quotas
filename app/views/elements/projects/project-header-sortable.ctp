<?php $paginator->options(array('url' => $this->passedArgs)); ?>
<tr>
	<th><?php echo $paginator->sort('Number', 'Project.number'); ?></th>
	<th><?php echo $paginator->sort('Name', 'Project.name', array('sort' => 'Project.number')); ?></th>
	<th><?php echo $paginator->sort('Server', 'Server.name'); ?></th>
	<th class="aRight">Quota Usage</th>
	<th class="aRight">Quota Allowance</th>
	<th class="aRight">% Used</th>
	<th class="aRight">Last Update</th>
</tr>