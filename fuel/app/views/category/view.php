<h2>Viewing <span class='muted'>#<?php echo $category->id; ?></span></h2>

<p>
	<strong>Name:</strong>
	<?php echo $category->name; ?></p>

<?php echo Html::anchor('/admin/category/edit/'.$category->id, 'Edit'); ?> |
<?php echo Html::anchor('/admin/category', 'Back'); ?>