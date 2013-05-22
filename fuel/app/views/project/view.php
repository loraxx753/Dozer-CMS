<h2>Viewing <span class='muted'>#<?php echo $project->id; ?></span></h2>

<p>
	<strong>Name:</strong>
	<?php echo $project->name; ?></p>
<p>
	<strong>Description:</strong>
	<?php echo $project->description; ?></p>
<p>
	<strong>Category:</strong>
	<?php echo $project->category; ?></p>
<p>
	<strong>Order:</strong>
	<?php echo $project->order; ?></p>

<?php echo Html::anchor('/admin/project/edit/'.$project->id, 'Edit'); ?> |
<?php echo Html::anchor('/admin/project', 'Back'); ?>