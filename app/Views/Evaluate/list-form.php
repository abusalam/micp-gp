<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

	<?php \helper('form'); ?>
	<?php \helper('html'); ?>

	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">

				<div class="card">
					<div class='font-size-24 text-right pull-right'>
						<span class='text-success'>
							<?=lang('app.assignment.marksTitle') . ': ' . $assignment->marks?>
						</span>
						<br/>
						<span class='text-secondary'>
							<?=lang('app.assignment.questions') . ': ' . $assignment->questions?>
						</span>
					</div>
					<h2 class="card-header"><?=$title ?? lang('app.eval.listTitle')?>
					<span class="text-monospace">
						<a href="<?=base_url(route_to('view-assignment-files', $id))?>">
							#<?=$id?>
						</a>
					</span>
					</h2>
					<div class="card-body">
					<h4><?=$assignment->getTopic()->getTitle()?></h4>
						<hr/>
						<?= view('Myth\Auth\Views\_message_block') ?>
						<?= view('layout/parts/table') ?>
					</div>
				</div>

			</div>
		</div>
	</div>

<?= $this->endSection() ?>
