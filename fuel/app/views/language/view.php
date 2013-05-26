<h2>Viewing <span class='muted'>#<?php echo $language->id; ?></span></h2>

<p>
	<strong>Name:</strong>
	<?php echo $language->name; ?></p>

<?php echo Html::anchor('/admin/language/edit/'.$language->id, 'Edit'); ?> |
<?php echo Html::anchor('/admin/language', 'Back'); ?>