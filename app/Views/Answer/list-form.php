<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

	<?php \helper('form'); ?>
	<?php \helper('html'); ?>

	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">

				<div class="card">
					<h2 class="card-header"><?=$title ?? lang('app.answer.listTitle')?></h2>
					<div class="card-body">
						<hr/>
						<?= view('Myth\Auth\Views\_message_block') ?>
						<?= view('layout/parts/table') ?>
					</div>
				</div>

			</div>
		</div>
	</div>

<?= $this->endSection() ?>
