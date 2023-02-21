<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

	<?php \helper('form'); ?>
	<?php \helper('html'); ?>

	<div class="container-fluid">
		<div class="row">
			<div class="col-md-10 offset-md-1">

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
								<?=lang('app.assignment.manageTitle')?>
							</h6>
							<!-- User is student and assignment is locked show upload answer menu -->
							<?php if (in_groups(env('auth.defaultUserGroup', 'students')) && $locked):?>
									<div class="dropdown-divider"></div>
									<div class="dropdown-content">
										<a href="<?=base_url(route_to('create-answer', $id))?>" 
												class="btn btn-primary">
												<?=lang('app.assignment.menuAnswer')?>
										</a>
									</div>
							<?php	else: ?>
								<!-- User is not student and assignment is not locked show add file and lock menus-->
								<?php if(! $locked): ?> 
									<a href="<?=base_url(route_to('lock-assignment', $id))?>" class="dropdown-item">
											<i class="fa fa-lock" aria-hidden="true"></i>
											<?=lang('app.assignment.btnLockTitle')?>
									</a>
									<div class="dropdown-divider"></div>
									<div class="dropdown-content">
										<a href="<?= base_url(route_to('add-assignment-file', $id))?>" 
												class="btn btn-primary">
											<?=lang('app.assignment.btnFileTitle')?>
										</a>
									</div>
									<!-- User is not student and assignment is locked show check answers menu-->
								<?php	else: ?>
									<a href="<?= base_url(route_to('check-answers', $id))?>" class="dropdown-item">
										<i class="fa fa-check-square-o" aria-hidden="true"></i>
										<?=lang('app.answer.checkAnswers')?>
									</a>
								<?php endif ?>
							<?php endif ?>
						</div>
					</div>
					<h2 class="card-header">
						<?=$title ?? lang('app.assignment.notFound')?>
						<span class="text-monospace text-primary">
							#<?=$id?>
						</span>
					</h2>
					<div class="card-body">
						<h4><?=$topic?></h4>
						<?= view('Myth\Auth\Views\_message_block') ?>

						<p><?=$detail ?? ''?></p>

						<div class="text-center">
							<?php foreach($files as $file): ?>
								<img class="img-fluid rounded" 
										src="<?= base_url(route_to('show-file', $file['id']))?>"
								/>
							<?php endforeach ?>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>

<?= $this->endSection() ?>
