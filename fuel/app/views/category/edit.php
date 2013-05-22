<h2>Editing <span class='muted'>Category</span></h2>
<br>

<?php echo render('category/_form'); ?>
<p>
	<?php echo Html::anchor('/admin/category/view/'.$category->id, 'View'); ?> |
	<?php echo Html::anchor('/admin/category', 'Back'); ?></p>
