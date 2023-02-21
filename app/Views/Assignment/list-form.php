<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

	<?php \helper('form'); ?>
	<?php \helper('html'); ?>

	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">

				<div class="card">
					<div class="dropdown pull-right">
						<button class="btn btn-success" data-toggle="dropdown" type="button" 
								id="dropdown-toggle-btn-1" aria-haspopup="true" aria-expanded="false">
								<?=lang('app.menu.menuTitle')?>
								<i class="fa fa-angle-down ml-5" aria-hidden="true"></i>
						</button>
						<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-toggle-btn-1">
							<h6 class="dropdown-header font-weight-bold">
							<i class="fa fa-wrench mr-5" aria-hidden="true"></i>
							<?=lang('app.assignment.menuTitle')?></h6>
							<a href="<?= base_url(route_to('list-files'))?>" class="dropdown-item">
								<?=lang('app.assignment.menuComplete')?>
							</a>
							<?php if (! in_groups(env('auth.defaultUserGroup', 'students'))):?>
							<a href="<?= base_url(route_to('create-topic'))?>" class="dropdown-item">
								<?=lang('app.assignment.menuTopics')?>
							</a>
							<div class="dropdown-divider"></div>
							<div class="dropdown-content">
								<a href="<?= base_url(route_to('create-assignment'))?>" class="btn btn-primary ">
									<?=lang('app.assignment.createTitle')?>
								</a>
							</div>
							<?php endif ?>
						</div>
					</div>
					<h2 class="card-header"><?=lang('app.assignment.listTitle')?></h2>
					<div class="card-body">
						<hr/>
						<?= view('Myth\Auth\Views\_message_block') ?>

						<form action="<?= base_url(route_to('accounts')) ?>" method="post">
							<?= csrf_field() ?>
						</form>
						<?= view('layout/parts/table') ?>
					</div>
				</div>

			</div>
		</div>
	</div>

<?= $this->endSection() ?>
