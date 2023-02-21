<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

	<?php \helper('form'); ?>
	<?php \helper('html'); ?>

	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">

				<div class="card">
					<div class="dropdown pull-right">
						
					</div>
					<h2 class="card-header"><?=lang('app.assignment.listTitle')?></h2>
					<div class="card-body">
						<hr/>
						<?= view('Myth\Auth\Views\_message_block') ?>

						<form action="<?= base_url(route_to('bookings')) ?>" method="post">
							<?= csrf_field() ?>
						</form>
						<?= view('layout/parts/table') ?>
					</div>
				</div>

			</div>
		</div>
	</div>

<?= $this->endSection() ?>
