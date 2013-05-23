<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?php echo $title; ?></title>
	<?php echo Asset::css(array('bootstrap.css', 'styles.css')); ?>
	<style>
		body { margin: 40px; }
	</style>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="span12">
				<div class="navbar">
					<div class="navbar-inner">
						<a class="brand" href="/">Kevin Baugh</a>
						<ul class="nav">
							<?php foreach($pages as $page) { ?>
							<li<?=($currentPage == $page)?" class='active'":''?>><a href="/<?=$page?>"><?=ucwords($page)?></a></li>
							<?php } ?>
						</ul>
						<?php if(\Auth::member(100)) { ?>
						<ul class="nav pull-right">
							<li><a class="pull-right right-pipe" href="/admin">Admin Panel</a></li>
							<li><a class="pull-right" href="/logout">Logout</a></li>
						</ul>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<div class="span12">
<?php if (Session::get_flash('success')): ?>
			<div class="alert alert-success">
				<strong>Success</strong>
				<p>
				<?php echo implode('</p><p>', e((array) Session::get_flash('success'))); ?>
				</p>
			</div>
<?php endif; ?>
<?php if (Session::get_flash('error')): ?>
			<div class="alert alert-error">
				<strong>Error</strong>
				<p>
				<?php echo implode('</p><p>', e((array) Session::get_flash('error'))); ?>
				</p>
			</div>
<?php endif; ?>
		</div>
		<div class="span12">
<?php echo $content; ?>
		</div>
		<footer>
			<p class="muted">Copyright &copy; 2013 Kevin Baugh</p>
		</footer>
	</div>
	<?php echo Asset::js(array('//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js','bootstrap.js')); ?>

</body>
</html>
