<h2><?php echo $this->pageTitle; ?></h2>
<table class="records">
	<thead>
		<tr>
			<th>User</th>
			<th>Action</th>
			<th>Date</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($activities as $key => $item): ?>
		<tr<?php echo $key%2 == 0 ? " class='alt'" : ''; ?>>
			<td><?php echo $item['User']['displayname']; ?></td>
			<td><?php echo $item['Action']['action']; ?></td>
			<td><?php echo date('m/d/Y h:ia', strtotime($item['Action']['created'])); ?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>