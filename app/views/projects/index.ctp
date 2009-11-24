<?php $javascript->link('projects/listing', false); ?>
<?php if($this->layout != 'ajax'): ?>
	<div class="clearfix">
		<h2 class="fLeft"><?php echo $this->pageTitle; ?></h2>
		<select class="selector fRight">
			<option value="">All</option>
			<?php foreach($servers as $curServer): ?>
				<option value="<?php echo $curServer['Server']['name']; ?>"<?php echo $curServer['Server']['name'] == $server['Server']['name'] ? " selected='true'" : '' ?>><?php echo $curServer['Server']['name']; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
<?php endif; ?>
<div id="project-listing">
	<?php echo $this->element('projects/list_full', array('projects' => $projects, 'favs' => $favs)); ?>
	<?php echo $this->element('pagination'); ?>
</div>