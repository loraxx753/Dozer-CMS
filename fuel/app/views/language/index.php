<h2>Listing <span class='muted'>Languages</span></h2>
<br>
<?php if ($languages): ?>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Name</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($languages as $language): ?>		<tr>

			<td><?php echo $language->name; ?></td>
			<td>
				<?php echo Html::anchor('/admin/language/view/'.$language->id, '<i class="icon-eye-open" title="View"></i>'); ?> |
				<?php echo Html::anchor('/admin/language/edit/'.$language->id, '<i class="icon-wrench" title="Edit"></i>'); ?> |
				<?php echo Html::anchor('/admin/language/delete/'.$language->id, '<i class="icon-trash" title="Delete"></i>', array('onclick' => "return confirm('Are you sure?')")); ?>

			</td>
		</tr>
<?php endforeach; ?>	</tbody>
</table>

<?php else: ?>
<p>No Languages.</p>

<?php endif; ?><p>
	<?php echo Html::anchor('/admin/language/create', 'Add new Language', array('class' => 'btn btn-success')); ?>

</p>
