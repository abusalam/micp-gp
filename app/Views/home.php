<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

	<div class="container">
		<div class="row">
			<div class="col-lg-10 offset-lg-1">
				<div class="card">
					<h2 class="card-title"><?=lang('app.home.welcome')?></h2>
					<div class="card-body">
						<?= view('Myth\Auth\Views\_message_block') ?>
						<?=view('layout/parts/YouTube', [], ['cache' => 3600])?>
						<div class="table-responsive">
							<pre>
								<?php use CodeIgniter\CLI\CLI;?>
								<?=logged_in() && (ENVIRONMENT !== 'production') ? CLI::table($tbody, $thead) : ''?>
							</pre>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<?= $this->endSection() ?>
