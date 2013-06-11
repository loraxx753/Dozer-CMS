	<div class="navbar">
		<div class="navbar-inner">
			<a class="brand" href="/"><?=\Config::get("portfolio.profile.name")?></a>
			<ul class="nav">
				<?php 
				foreach($pages as $page) { ?>
				<li<?=($currentPage == $page->name)?" class='active'":''?>><a href="/<?=$page->clean_name?>"><?=ucwords($page->name)?><?=(count($page->sub_pages)) ? '<b class="caret"></b>' : ''?></a>
					<?php if(count($page->sub_pages)) { ?>
					<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
					  <?php foreach($page->sub_pages as $subpage) { ?>
					  <li><a tabindex="-1" href="/<?=$page->clean_name."/".$subpage->clean_name?>"><?=$subpage->name?></a></li>
					  <?php } ?>
					</ul>
					<?php } ?>
			</li>
				<?php } ?>
				<?php if(\Auth::member(100)) { ?>
				<li><a href="#" class="newpage">New Page+</a></li>
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