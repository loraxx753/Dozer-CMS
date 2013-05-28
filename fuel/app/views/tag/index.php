<h2>Listing <span class='muted'>Languages</span></h2>
<br>
<?php if ($tags): ?>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Name</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($tags as $tag): ?>		<tr>

			<td><?php echo $tag->name; ?></td>
			<td>
				<?php echo Html::anchor('/admin/tag/view/'.$tag->id, '<i class="icon-eye-open" title="View"></i>'); ?> |
				<?php echo Html::anchor('/admin/tag/edit/'.$tag->id, '<i class="icon-wrench" title="Edit"></i>'); ?> |
				<?php echo Html::anchor('/admin/tag/delete/'.$tag->id, '<i class="icon-trash" title="Delete"></i>', array('onclick' => "return confirm('Are you sure?')")); ?>

			</td>
		</tr>
<?php endforeach; ?>	</tbody>
</table>

<?php else: ?>
<p>No Tags.</p>

<?php endif; ?><p>
	<?php echo Html::anchor('/admin/tag/create', 'Add new Tag', array('class' => 'btn btn-success')); ?>

</p>
