<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

	<?php \helper('form'); ?>
	<?php \helper('html'); ?>

	<div class="container-fluid">
		<div class="row">
			<div class="col-md-10 offset-md-1">

				<div class="card">
					<h2 class="card-header"><?=lang('app.booking.searchTitle')?></h2>
					<div class="card-body">

						<?= view('Myth\Auth\Views\_message_block') ?>

						<p><?=lang('app.booking.searchHelp')?></p>

						<?= form_open_multipart(base_url(route_to('search'))) ?>
							<fieldset <?php // session('has_no_profile') ? '' : 'disabled="disabled"'?>>
								<pre><?php //var_dump($booking->getTimeSlots() ?? '')?></pre>
								<?php if ($booking ?? 0): ?>
								<div class="card bg-light-lm bg-dark-light-dm ">
									<h3 class="card-header">
										<?=$booking->getPassenger() ?? lang('app.booking.notFound')?>
										<span class="text-monospace text-primary">
											Ticket No: <?=$booking->ticket?>
										</span>
									</h3>
									<div class="card-body">
										<p>
											<span class="badge badge-success badge-pill py-5 m-5 pull-right">
												<strong>Status: </strong><?=$booking->getStatus() ?? ''?>
											</span>
										</p>
										<h4>Rent Fees ₹<?=$booking->getAmount() ?? ''?></h4>
										<p><strong>Mobile: </strong><?=$booking->getMobile() ?? ''?></p>
										<p><strong><?=lang('app.booking.address')?>: </strong><?=$booking->getAddress() ?? ''?></p>
										<p><strong><?=lang('app.booking.purpose')?>: </strong><?=$booking->getPurpose() ?? ''?></p>
										<h4>Slot: <?=$booking->getBookedSlot() ?? ''?></h4>
										<pre><?php //var_dump($pg_resp ?? '')?></pre>
										<pre><?php //var_dump(session()->get('post_data') ?? '')?></pre>
									</div>
								</div>
								<?php endif ?>
								<div class="<?php if(session('errors.ticket')) : ?>is-invalid<?php endif ?>">
									<div class="form-group">
										<label for="ticket" class="required"><?=lang('app.booking.ticket')?></label>
										<input type="text" class="form-control" id="ticket" required="required"
													name="ticket" placeholder="<?=lang('app.booking.ticket')?>"
													value="<?=old('ticket') ?>">
									</div>
								</div>
								<pre id="debug"><?php //var_dump($booking ?? '')?></pre>
                <button type="submit" class="btn btn-primary"><?=lang('app.booking.btnSearchTitle')?></button>
							</fieldset>
						<?=form_close()?>

					</div>
				</div>

			</div>
		</div>
	</div>
<?= $this->endSection() ?>
