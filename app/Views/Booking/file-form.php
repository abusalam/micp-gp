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
							<?=lang('app.file.menuTitle')?></h6>
							<a href="<?= base_url(route_to('list-files'))?>" class="dropdown-item"><?=lang('app.file.menuShow')?></a>
							<div class="dropdown-divider"></div>
							<div class="dropdown-content">
								<a href="<?= base_url(route_to('create-assignment'))?>" class="btn btn-primary ">
									<?=lang('app.file.btnFileTitle')?>
								</a>
							</div>
						</div>
					</div>
					<h2 class="card-header"><?=lang('app.file.createTitle')?></h2>
					<div class="card-body">

						<?= view('Myth\Auth\Views\_message_block') ?>

						<?= form_open_multipart(base_url(route_to('add-assignment-file', $id))) ?>
							<div class="form-group">
								<h4>
									<?= lang('app.answer.newTitle') . ' : ' . $assignment->title?>
									(<?=$assignment->marks . ' ' . lang('app.answer.marksTitle')?>)
								</h4>
								<h5>
									<?=$assignment->getTopic()->getTitle()?>
								</h5>
							</div>
							<div class="text-center">
								<?php foreach($files as $file): ?>
								<div class="mb-20">
									<img class="img-fluid border rounded m-5 p-10" 
											src="<?=base_url(route_to('show-file', $file['id']))?>"
									/>
									<div class="text-center">
										<a class="btn btn-danger" 
												href="<?=base_url(route_to('del-file', $file['id'], 'add-assignment-file', $id))?>">
											<i class="fa fa-trash" aria-hidden="true"></i>
										</a>
										<a class="btn btn-secondary" 
												href="<?=base_url(route_to('rotate-file', $file['id'], 270, 'add-assignment-file', $id))?>">
											<i class="fa fa-repeat" aria-hidden="true"></i>
										</a>
										<a class="btn btn-secondary" 
												href="<?=base_url(route_to('rotate-file', $file['id'], 90, 'add-assignment-file', $id))?>">
											<i class="fa fa-undo" aria-hidden="true"></i>
										</a>
										<!-- <a class="btn btn-success" 
												href="<?=base_url(route_to('del-file', $file['id'], 'add-assignment-file', $id))?>">
											<i class="fa fa-arrow-up" aria-hidden="true"></i>
										</a>
										<a class="btn btn-success" 
												href="<?=base_url(route_to('del-file', $file['id'], 'add-assignment-file', $id))?>">
											<i class="fa fa-arrow-down" aria-hidden="true"></i>
										</a> -->
									</div>
								</div>
								<?php endforeach ?>
							</div>
							<br/>
							<hr/>
							<a class="btn btn-success float-right" href="<?=base_url(route_to('lock-assignment', $id))?>">
									<i class="fa fa-lock" aria-hidden="true"></i>
									<?=lang('app.assignment.btnLockTitle')?>
							</a>
							<div class="form-row row-eq-spacing-md">
								<div class="<?php if(session('errors.imageFile')) : ?>is-invalid<?php endif ?>">
									<div class="form-group">
										<label for="imageFile" class="required">
											<?=$targetDims?>
										</label>
										<div class="custom-file">
											<label for="imageFile"><?=lang('app.file.lblUpload')?></label>
											<input type="file" accept=".jpg, .jpeg" class="form-control"
														name="imageFile" id="imageFile" required>
											<button type="submit" class="btn btn-primary"><?=lang('app.file.btnUpload')?></button>
										</div>
										<div class="invalid-feedback">
											<?= session('errors.imageFile') ?>
										</div>
									</div>
								</div>
							</div>
						<?=form_close()?>
					</div>
				</div>

			</div>
		</div>
	</div>

<?= $this->endSection() ?>
