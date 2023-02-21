<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

	<?php \helper('form'); ?>
	<?php \helper('html'); ?>

	<div class="container-fluid">
	<div class="row">
		<div class="col-xl-6 offset-xl-3 col-lg-8 offset-lg-2 col-md-10 offset-md-1">

			<div class="card">
				<h2 class="card-header"><?=lang('app.profile.formTitle')?></h2>
				<div class="card-body">

					<?= view('Myth\Auth\Views\_message_block') ?>
					
					<?php if (! session('has_no_profile')) : ?>
						<div class="alert alert-success">
							<?=lang('app.profile.notRequired')?>
						</div>
					<?php endif ?>

					<?= form_open_multipart(route_to('profile')) ?>
					<fieldset <?= session('has_no_profile') ? '' : 'disabled="disabled"'?>>
						<pre><?php //var_dump($post ?? '')?></pre>
						<div class="form-group <?php if(session('errors.full_name')) : ?>is-invalid<?php endif ?>">
							<label for="full_name" class="required"><?=lang('app.profile.fullName')?></label>
							<input type="text" class="form-control" required="required"
									name="full_name" placeholder="<?=lang('app.profile.fullName')?>"
									value="<?= old('full_name', $user->getFullName()) ?>">
						</div>

						<div class="form-group <?php if(session('errors.mobile')) : ?>is-invalid<?php endif ?>">
							<label for="mobile" class="required"><?=lang('Auth.mobile')?></label>
							<input type="text" class="form-control" required="required"
										name="mobile" aria-describedby="mobileHelp" 
										placeholder="<?=lang('Auth.mobile')?>"
										value="<?= old('mobile', $user->mobile) ?>">
							<small id="mobileHelp" class="form-text text-muted">
								<?=lang('Auth.weNeverShareMobile')?>
							</small>
						</div>

						<div class="form-group <?php if(session('errors.description')) : ?>is-invalid<?php endif ?>">
							<label for="if-8-textarea"><?=lang('app.profile.aboutMe')?></label>
							<textarea class="form-control" placeholder="<?=lang('app.profile.aboutMe')?>"
									name="description" id="if-8-textarea"><?= old('description', $user->description) ?></textarea>
							<small id="descHelp" class="form-text text-muted"><?=lang('app.profile.descHelp')?></small>
						</div>
						
						<!-- <div class="form-group <?php if(session('errors.photo')) : ?>is-invalid<?php endif ?>">
							<div class="custom-file">
								<input type="file" id="if-8-file-input" name="photo">
								<label for="if-8-file-input">Choose photo</label>
							</div>
						</div> -->
						<br>

						<button type="submit" class="btn btn-primary"><?=lang('app.profile.updateTitle')?></button>
					</fieldset>
					<?= form_close() ?>
				</div>
			</div>

		</div>
	</div>
</div>
<?= $this->endSection() ?>
