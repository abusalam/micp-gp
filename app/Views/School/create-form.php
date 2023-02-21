<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

	<?php \helper('form'); ?>
	<?php \helper('html'); ?>

	<div class="container-fluid">
		<div class="row">
			<div class="col-md-10 offset-md-1">

				<div class="card">
					<h2 class="card-header"><?=lang('app.school.createTitle')?></h2>
					<div class="card-body">

						<?= view('Myth\Auth\Views\_message_block') ?>

						<?= form_open_multipart(base_url(route_to('create-school'))) ?>

							<fieldset <?php // session('has_no_profile') ? '' : 'disabled="disabled"'?>>
								<pre><?php //var_dump($post ?? '')?></pre>
								<div class="form-row row-eq-spacing-md">
									<div class="col-md-4 form-group <?php if(session('errors.udise')) : ?>is-invalid<?php endif ?>">
										<label for="udise" class="required"><?=lang('app.school.diseTitle')?></label>
										<input type="text" class="form-control" id="udise" required="required"
												name="udise" placeholder="<?=lang('app.school.diseTitle')?>"
												value="<?= old('udise', $school->udise) ?>">
									</div>
									<div class="col-md-8 form-group <?php if(session('errors.school')) : ?>is-invalid<?php endif ?>">
										<label for="school" class="required"><?=lang('app.school.schoolTitle')?></label>
										<input type="text" class="form-control" id="school" required="required"
												name="school" placeholder="<?=lang('app.school.schoolTitle')?>"
												value="<?= old('school', $school->school) ?>">
									</div>
								</div>
							</fieldset>
							<button type="submit" class="btn btn-primary btn-block"><?=lang('app.school.schoolSave')?></button>
						<?=form_close()?>

					</div>
				</div>

			</div>
		</div>
	</div>

<?= $this->endSection() ?>
