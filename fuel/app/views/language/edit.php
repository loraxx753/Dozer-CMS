<h2>Editing <span class='muted'>Language</span></h2>
<br>

<?php echo render('language/_form'); ?>
<p>
	<?php echo Html::anchor('/admin/language/view/'.$language->id, 'View'); ?> |
	<?php echo Html::anchor('/admin/language', 'Back'); ?></p>
