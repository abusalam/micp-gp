<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

	<?php \helper('form'); ?>
	<?php \helper('html'); ?>

	<div class="container-fluid">
		<div class="row">
			<div class="col-md-10 offset-md-1">

				<div class="card">
					<h2 class="card-header">
						<?=$booking->getPassenger() ?? lang('app.booking.notFound')?>
						<span class="text-monospace text-primary">
							Ticket No: <?=$pg_resp->cf_order_id?>
						</span>
					</h2>
					<div class="card-body">
						<?= view('Myth\Auth\Views\_message_block') ?>
						<p>
							<span class="badge badge-success badge-pill py-5 m-5 pull-right">
								<strong>Status: </strong><?=$booking->getStatus() ?? ''?>
							</span>
						</p>
						<h4>Rent Fees ₹<?=$booking->getAmount() ?? ''?></h4>
						<p><strong>Mobile: </strong><?=$booking->getMobile() ?? ''?></p>
						<p><strong><?=lang('app.booking.address')?>: </strong><?=$booking->getAddress() ?? ''?></p>
						<p><strong><?=lang('app.booking.purpose')?>: </strong><?=$booking->getPurpose() ?? ''?></p>
						<h3>Slot: <?=$booking->getBookedSlot() ?? ''?></h3>
						<pre><?php //var_dump($pg_resp ?? '')?></pre>
						<pre><?php //var_dump(session()->get('post_data') ?? '')?></pre>

						<a href="<?=$pg_resp->payment_link?>" class="btn btn-success btn-lg">Pay ₹<?=$booking->getAmount() ?? ''?></a>
					</div>
				</div>

			</div>
		</div>
	</div>

<?= $this->endSection() ?>
