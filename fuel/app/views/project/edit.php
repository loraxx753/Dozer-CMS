<h2>Editing <span class='muted'>Project</span></h2>
<br>

<?php echo View::forge('project/_form', $options)->render(); ?>
<p>
	<?php echo Html::anchor('/admin/project/view/'.$project->id, 'View'); ?> |
	<?php echo Html::anchor('/admin/project', 'Back'); ?></p>
