<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

	<?php \helper('form'); ?>
	<?php \helper('html'); ?>

	<div class="container-fluid">
	<div class="row">
		<div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1">

			<div class="card">
				<h2 class="card-header"><?=lang('app.account.createTitle')?></h2>
				<div class="card-body">

					<?= view('Myth\Auth\Views\_message_block') ?>

					<?= form_open_multipart(base_url(route_to('create'))) ?>
					<!-- <pre><?= var_dump($roles ?? 'get')?></pre> -->
						<div class="form-row row-eq-spacing-md">
							<div class="col-lg-8 col-md-6 <?php if(session('errors.full_name')) : ?>is-invalid<?php endif ?>">
								<label for="full_name" class="required"><?=lang('app.profile.fullName')?></label>
								<input type="text" class="form-control" required="required"
										name="full_name" placeholder="<?=lang('app.profile.fullName')?>"
										value="<?= old('full_name', $user->getFullName()) ?>">
							</div>
							<div class="col-lg-4 col-md-6 <?php if(session('errors.type')) : ?>is-invalid<?php endif ?>">
									<label for="type" class="required"><?=lang('app.account.roleTitle')?></label>
									<select class="form-control" id="type" name="type" required="required">
										<option value="" selected="selected" disabled="disabled">
											<?=lang('app.account.roleOptTitle')?>
										</option>
										<?php foreach($roles as $role) : ?>
											<?php $roleId = (ENVIRONMENT !== 'production') ? $roles[array_rand($roles, 1)]['name'] : ''; ?>
											<option value="<?= $role['name'] ?>" 
												<?=(old('type', $roleId) === $role['name']) ? 'selected="selected"' : ''?>>
												<?= $role['description'] ?>
											</option>
										<?php endforeach ?>
									</select>
							</div>
						</div>

						<div class="form-row row-eq-spacing-md">
							<div class="col-md-6 <?php if(session('errors.mobile')) : ?>is-invalid<?php endif ?>">
								<label for="mobile" class="required"><?=lang('Auth.mobile')?></label>
								<input type="text" class="form-control" required="required"
											name="mobile" aria-describedby="mobileHelp" 
											placeholder="<?=lang('Auth.mobile')?>"
											value="<?= old('mobile', $user->mobile) ?>">
							</div>
							<div class="col-md-6">
								<label for="email" class="required"><?=lang('Auth.email')?></label>
								<input type="email" name="email" 
									class="form-control <?php if(session('errors.email')) : ?>is-invalid<?php endif ?>"
									aria-describedby="emailHelp" placeholder="<?=lang('Auth.email')?>" 
									value="<?= old('email', $user->email) ?>">
							</div>
								<small id="mobileHelp" class="form-text text-muted">
									<?=lang('app.account.weNeverShare')?>
								</small>
						</div>

						<div>
							<label for="school" class="required"><?=lang('app.account.schoolTitle')?></label>
							<div class="form-row row-eq-spacing-md">
								<div class="col-md-4 <?php if(session('errors.class_id')) : ?>is-invalid<?php endif ?>">
									<select class="form-control" id="class" 
											name="class_id">
										<option value="" <?= (old('class_id') === null) ? 'selected="selected"' : '' ?>
												disabled="disabled"><?=lang('app.account.classTitle')?></option>
										<?php foreach($classes as $class) : ?>
										<option value="<?= $class['id'] ?>" 
												<?= (old('class_id', $user->class) === $class['id']) ? 'selected="selected"' : '' ?>
											><?= $class['class'] ?>
										</option>
										<?php endforeach ?>
									</select>
								</div>
								<div class="col-md-8 <?php if(session('errors.school_id')) : ?>is-invalid<?php endif ?>">
									<select class="form-control" id="school" 
											name="school_id" required="required">
										<option value="" <?= (old('school_id') === null) ? 'selected="selected"' : '' ?>
												disabled="disabled"><?=lang('app.account.schoolTitle')?></option>
										<?php foreach($schools as $school) : ?>
										<option value="<?= $school->id ?>" 
												<?= (old('school_id', $user->getSchoolId()) === $school->id) ? 'selected="selected"' : '' ?>
											><?= $school->school ?>
										</option>
										<?php endforeach ?>
									</select>
								</div>
								<small id="mobileHelp" class="form-text text-muted">
									<?=lang('app.account.classNotRequired')?>
								</small>
							</div>
						</div>
						<br>

						<button type="submit" class="btn btn-primary btn-block"><?=lang('app.account.createTitle')?></button>
					<?= form_close() ?>

				</div>
			</div>

		</div>
	</div>
</div>
<?= $this->endSection() ?>
