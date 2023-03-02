
<table class="table table-striped table-hover table-outer-bordered table-inner-bordered">
	<thead>
	<tr class="text-primary">
<?php foreach($heads as $key => $head) : ?>
		<th><?=$head?></th>
<?php endforeach ?>
	</tr>
	</thead>
	<tbody>
<?php foreach($rows as $row) : ?>
		<tr>
<?php foreach($row as $key => $value) : ?>
			<th><?= $value?></th>
<?php endforeach ?>
		</tr>
<?php endforeach ?>
	</tbody>
</table>

