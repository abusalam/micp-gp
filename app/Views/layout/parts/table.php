<div class="table-responsive">
	<table class="table table-striped table-hover table-outer-bordered">
		<thead>
		<tr>
<?php foreach($heads as $key => $head) : ?>
			<th>
				<?=$head?>
				<a href="?sort=<?=$key?>&order=<?=$order ?? 'asc'?>">
					<i class="fa fa-sort-amount-<?=$order ?? 'asc'?>" aria-hidden="true"></i>
				</a>
			</th>
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
</div>
<hr/><br/>
<?= $pager->links('default', 'pager_full') ?>

<!-- DataTables 1.10.21 JS -->
<script type="text/javascript" src="<?=base_url('/js/datatables.min.js')?>"></script>
<script {csp-script-nonce} type="text/javascript">
	$(document).ready( function () {
		$('.dataTable').DataTable();
	});
</script>
