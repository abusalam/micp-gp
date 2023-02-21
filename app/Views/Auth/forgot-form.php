<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

	<?php \helper('form'); ?>
	<?php \helper('html'); ?>

	<div class="container-fluid">
		<div class="row">
			<div class="col-xl-6 offset-xl-3 col-lg-8 offset-lg-2 col-md-10 offset-md-1">

				<div class="card">
					<h2 class="card-header"><?=lang('Auth.forgotPassword')?></h2>
					<div class="card-body">

						<?= view('Myth\Auth\Views\_message_block') ?>

						<p><?=lang('Auth.enterEmailForInstructions')?></p>

						<form action="<?= base_url(route_to('forgot')) ?>" method="post">
							<?= csrf_field() ?>

							<div class="form-group">
								<label for="email"><?=lang('Auth.emailAddress')?></label>
								<input type="email" class="form-control <?php if(session('errors.email')) : ?>is-invalid<?php endif ?>"
											name="email" aria-describedby="emailHelp" placeholder="<?=lang('Auth.email')?>">
								<div class="invalid-feedback">
									<?= session('errors.email') ?>
								</div>
							</div>

							<br>

							<button type="submit" class="btn btn-primary btn-block"><?=lang('Auth.sendInstructions')?></button>
						</form>

					</div>
				</div>

			</div>
		</div>
	</div>

<?= $this->endSection() ?>
