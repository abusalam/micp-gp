<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

	<?php \helper('form'); ?>
	<?php \helper('html'); ?>

	<div class="container-fluid">
		<div class="row">
			<div class="col-md-10 offset-md-1">

				<div class="card">
					<h2 class="card-header"><?=lang('app.booking.createTitle')?></h2>
					<div class="card-body">

						<?= view('Myth\Auth\Views\_message_block') ?>

						<p><?=lang('app.booking.createHelp')?></p>

						<?= form_open_multipart(base_url(route_to('check'))) ?>
							<fieldset <?php // session('has_no_profile') ? '' : 'disabled="disabled"'?>>
								<pre><?php //var_dump($booking->getTimeSlots() ?? '')?></pre>
								<div>
									<div class="form-row row-eq-spacing-md">
											<div class="col-md-4 <?php if(session('errors.date')) : ?>is-invalid<?php endif ?>">
												<div class="form-group">
													<label for="date" class="required"><?=lang('app.booking.date')?></label>
													<input type="text" class="form-control" id="date" required="required"
																name="date" placeholder="<?=lang('app.booking.date')?>"
																value="<?=old('date', date('d/m/Y')) ?>">
												</div>
											</div>
									</div>
								</div>
								<pre id="debug"><?php //var_dump($booking->getTimeSlots() ?? '')?></pre>
							</fieldset>
							<button type="submit" class="btn btn-primary btn-block form-control"><?=lang('app.booking.btnCreateTitle')?></button>
						<?=form_close()?>

					</div>
				</div>

			</div>
		</div>
	</div>

	<script {csp-script-nonce}>
	$( function() {

		$( "#date" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
			changeMonth: true,
			minDate: 0, 
			maxDate: "+2M",
			dateFormat: 'dd/mm/yy',
			onSelect: function(date, ui){
				$.ajax({
						method: "POST",
						url: "<?=base_url(route_to('check'))?>",
						headers: {'X-Requested-With': 'XMLHttpRequest'},
						data: {
							 'date': date,
							 'csrf_test_name' : $("[name='csrf_test_name']").val(),
						 },
					}).done(function(resp){
						$("#debug").text(JSON.stringify(resp));
					});
				
			},
    });
	  
	} );
	</script>

<?= $this->endSection() ?>
