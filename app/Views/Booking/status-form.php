<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

	<?php \helper('form'); ?>
	<?php \helper('html'); ?>

	<div class="container-fluid">
		<div class="row">
			<div class="col-md-10 offset-md-1">

				<div class="card">
					<h2 class="card-header">
						<span class="text-monospace text-primary">
							Gate Pass No: <?=$id ?? ''?>
						</span>
					</h2>
					<div class="card-body">
						<?= view('Myth\Auth\Views\_message_block') ?>
						<p><strong><?=lang('app.booking.vehicleNo')?>: </strong><?=$booking->getVehicleNo() ?? ''?></p>
						<p><strong><?=lang('app.booking.purpose')?>: </strong><?=$booking->getPurpose() ?? ''?></p>
						<p><strong><?=lang('app.booking.validFrom')?>: </strong><?=$booking->getIssueDate() ?? ''?></p>
						<p><strong><?=lang('app.booking.validTill')?>: </strong><?=$booking->getValidTill() ?? ''?></p>
						<a href="<?=base_url(route_to('print', $id))?>" target="_blank" class="btn btn-success btn-lg">Print Receipt</a>
					</div>
				</div>

			</div>
		</div>
	</div>

<?= $this->endSection() ?>
