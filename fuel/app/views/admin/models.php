<ul>
	<li><button id="admin_newmodel" class="btn btn-success">Create Model</button></li>
</ul>

<table class="table table-striped page_table">
	<thead>
		<tr>
			<th></th>
			<th>Name</th>
			<th>Fields</th>
			<th></th>
			<th>Options</th>
		</tr>
	</thead>
	<tbody>
<?php foreach($models as $model) {?>
		<tr class="page_row" data-id="<?=$model->id?>">
			<td><a href="#" title="Show Entries"><i class="icon-chevron-right"></i></a></td>
			<td class="model_name"><?=$model->name?></td>
			<td class="field_list">
				<ul class="inline comma">
					<?php foreach ($model->fields as $key => $value) { ?>
					<li data-type="<?=$value?>"><?=$key?></li>
					<?php } ?>
				</ul>
			</td>
			<td></td>
			<td>
				<ul class="inline">
					<li><a href="#" class="add_entry" title="Add New"><i class="icon-plus"></i></a></li>
					<li><a href="#" title="Edit Model"><i class="icon-edit"></i></a></li>
					<li><a href="#" title="Remove Model"><i class="icon-remove"></i></a></li>
				</ul>
			</td>
		</tr>
<?php } ?>
	</tbody>
</table>
