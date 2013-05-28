<h2>Listing <span class='muted'>Projects</span></h2>
<br>
<?php if ($projects): ?>
<table class="table table-striped projects">
	<thead>
		<tr>
			<th>&nbsp;</th>
			<th>Name</th>
			<th>Description</th>
			<th>Category</th>
			<th>Tags</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($projects as $project): ?>		<tr>

			<td><?php echo $project->thumbnail(); ?></td>
			<td><?php echo $project->name; ?></td>
			<td><?php echo $project->description; ?></td>
			<td><?php echo $project->category->name; ?></td>
			<td>
				<ul>
					<?php foreach ($project->tags as $tag) { ?>
						<li><?php echo $tag->name; ?></li>
					<?php } ?>
				</ul>
			</td>
			<td>
				<?php echo Html::anchor('/admin/project/view/'.$project->id, '<i class="icon-eye-open" title="View"></i>'); ?> |
				<?php echo Html::anchor('/admin/project/edit/'.$project->id, '<i class="icon-wrench" title="Edit"></i>'); ?> |
				<?php echo Html::anchor('/admin/project/delete/'.$project->id, '<i class="icon-trash" title="Delete"></i>', array('onclick' => "return confirm('Are you sure?')")); ?>

			</td>
		</tr>
<?php endforeach; ?>	</tbody>
</table>

<?php else: ?>
<p>No Projects.</p>

<?php endif; ?><p>
	<?php echo Html::anchor('/admin/project/create', 'Add new Project', array('class' => 'btn btn-success')); ?>

</p>
