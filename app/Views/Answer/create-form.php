<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

	<?php \helper('form'); ?>
	<?php \helper('html'); ?>

	<div class="container-fluid">
		<div class="row">
			<div class="col-md-10 offset-md-1">

				<div class="card">
					<h2 class="card-header"><?=$title?></h2>
					<div class="card-body">

						<?= view('Myth\Auth\Views\_message_block') ?>

						<?= form_open_multipart(base_url(route_to('create-answer', $id))) ?>
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
										<img class="img-fluid border rounded m-10" 
												src="<?=base_url(route_to('show-file', $file['id']))?>"
										/>
										<div class="text-center">
											<a class="btn btn-secondary" 
													href="<?=base_url(route_to('rotate-file', $file['id'], 270, 'create-answer', $id))?>">
												<i class="fa fa-repeat" aria-hidden="true"></i>
											</a>
											<a class="btn btn-secondary" 
													href="<?=base_url(route_to('rotate-file', $file['id'], 90, 'create-answer', $id))?>">
												<i class="fa fa-undo" aria-hidden="true"></i>
											</a>
											<a class="btn btn-danger" 
													href="<?=base_url(route_to('del-file', $file['id'], 'create-answer', $id))?>">
												<i class="fa fa-trash" aria-hidden="true"></i>
											</a>
										</div>
									</div>
								<?php endforeach ?>
							</div>
							<br/><hr/>
							<?php if ($answer):?>
							<a class="btn btn-success float-right" href="<?=base_url(route_to('lock-answer', $answer->id))?>">
								<i class="fa fa-lock" aria-hidden="true"></i>
								<?=lang('app.answer.btnCreateTitle')?>
							</a>
							<?php endif ?>
							<div class="<?php if(session('errors.imageFile')) : ?>is-invalid<?php endif ?>">
								<div class="form-group">
									<label for="imageFile" class="required">
										<?=lang('app.file.newTitle')?>
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
						<?=form_close()?>

					</div>
				</div>

			</div>
		</div>
	</div>

<?= $this->endSection() ?>
