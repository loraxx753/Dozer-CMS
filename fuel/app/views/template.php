<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?php echo $title; ?></title>
	<?php if($current_css = \Config::get("portfolio.bootswatch")) { 
		echo Asset::css(array('bootswatch/'.$current_css.'/bootstrap.min.css'), array("class" => "bootstrap")); 	
		$options = array("class" => "custom_css", "disabled" => true);
	} else {
		echo Asset::css(array('bootstrap.css'), array("class" => "bootstrap")); 
		$options = array("class" => "custom_css");
	}

	?>
	<?php 
		echo Asset::css(array('styles.css'), $options); 
		echo Asset::css(array('base.css')); 
	?>
</head>
<body>
	<div class="navbar">
		<div class="navbar-inner">
			<a class="brand" href="/"><?=\Config::get("portfolio.profile.name")?></a>
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
	<div class="container">
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
	<script>
	<?php
		$current_css = (\Config::get("portfolio.bootswatch")) ? \Config::get("portfolio.bootswatch") : "default";
	?>
		var current_css="<?=$current_css?>";
	</script>
	<?php echo Asset::js(array(
		'//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js',
		'bootstrap.js', 
		'html5sortable/jquery.sortable.js', 
		'init.js',
		"jquery-markdown/markdown/Markdown.Converter.js",
		"jquery-markdown/markdown/Markdown.Sanitizer.js",
		"jquery-markdown/markdown/Markdown.Editor.js",
		"jquery-markdown/markdown/jquery.markdown.js"
	)); ?>

</body>
</html>
