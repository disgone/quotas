<?php 
	$paginator->options(array('url' => $this->passedArgs));
	$sort_col = isset($this->passedArgs['sort']) ? $this->passedArgs['sort'] : 'Project.number';
	$sort_dir = isset($this->passedArgs['direction']) ? $this->passedArgs['direction'] : 'desc'; 
?>
<tr>
	<th<?php echo $sort_col == 'Project.number' ? " class='sort" . ucwords($sort_dir) . "'" : ''; ?>><?php echo $paginator->sort('Number', 'Project.number'); ?></th>
	<th<?php echo $sort_col == 'Project.name' ? " class='sort" . ucwords($sort_dir) . "'" : ''; ?>><?php echo $paginator->sort('Name', 'Project.name'); ?></th>
	<th<?php echo $sort_col == 'Server.name' ? " class='sort" . ucwords($sort_dir) . "'" : ''; ?>><?php echo $paginator->sort('Server', 'Server.name'); ?></th>
	<th class="aRight<?php echo $sort_col == 'Quota.consumed' ? " sort" . ucwords($sort_dir) : ''; ?>"><?php echo $paginator->sort('Quota Usage', 'Quota.consumed'); ?></th>
	<th class="aRight<?php echo $sort_col == 'Quota.allowance' ? " sort" . ucwords($sort_dir) : ''; ?>"><?php echo $paginator->sort('Quota Allowance', 'Quota.allowance'); ?></th>
	<th class="aRight">% Used</th>
	<th class="aRight<?php echo $sort_col == 'Quota.created' ? " sort" . ucwords($sort_dir) : ''; ?>"><?php echo $paginator->sort('Last Update', 'Quota.created'); ?></th>
</tr>