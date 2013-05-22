<h2>New <span class='muted'>Project</span></h2>
<br>

<?php echo View::forge('project/_form', array("categories" => $categories))->render(); ?>


<p><?php echo Html::anchor('/admin/project', 'Back'); ?></p>
