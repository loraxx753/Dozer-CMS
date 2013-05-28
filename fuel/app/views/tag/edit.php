<h2>Editing <span class='muted'>Language</span></h2>
<br>

<?php echo render('tag/_form'); ?>
<p>
	<?php echo Html::anchor('/admin/tag/view/'.$tag->id, 'View'); ?> |
	<?php echo Html::anchor('/admin/tag', 'Back'); ?></p>
