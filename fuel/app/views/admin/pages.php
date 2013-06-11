<table class="table table-striped page_table">
	<thead>
		<tr>
			<th>Name</th>
			<th>Url</th>
			<th>Parent</th>
			<th>Published</th>
		</tr>
	</thead>
	<tbody>
<?php foreach($pages as $page) {?>
		<tr class="page_row" data-id="<?=$page->id?>">
			<td><?=$page->name?></td>
			<td><a href="<?=$page->url?>"><?=$page->url?></a></td>
			<td>
				<?php if($page->name != "Main Page") { ?>
				<select class="parent_id">
					<option <?=($page->parent_id == -1) ? "selected='selected' " : ''?>value="-1">None</option>
					<option <?=($page->parent_id == 0) ? "selected='selected' " : ''?>value="0">Top Menu</option>
					<?php foreach($pages as $parent_page) { ?>
						<?php if($parent_page->id != $page->id && $parent_page->name != "Main Page") { ?>
					<option <?=($page->parent_id == $parent_page->id) ? "selected='selected' " : ''?>value="<?=$parent_page->id?>"><?=$parent_page->name?></option>
						<?php } ?>
					<?php } ?>
				</select>
				<?php } else { ?>
				<p>This is the main page</p>
				<?php } ?>
			</td>
			<td>
				<?php if($page->name != "Main Page") { ?>
				<select class="published">
					<option <?=($page->published==0) ? "selected='selected' " : ''?>value='0'>No</option>
					<option <?=($page->published==1) ? "selected='selected' " : ''?>value='1'>Yes</option>
				</select>
				<?php } else { ?>
				<p>This is the main page</p>
				<?php } ?>
			</td>
		</tr>
<?php } ?>
	</tbody>
</table>

<button class="btn btn-primary update_pages" data-loading-text="Updating...">Update</button>
<button class="btn btn-primary admin_newpage">New Page</button>