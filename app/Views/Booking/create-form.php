<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

	<?php \helper('form'); ?>
	<?php \helper('html'); ?>
	<div class="modal" id="modal-1" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
			<h5 class="modal-title">Modal title</h5>
			<video id="webCam" required="required" name="webCam" autoplay controls></video>
			<div class="text-right mt-20"> <!-- text-right = text-align: right, mt-20 = margin-top: 2rem (20px) -->
				<a href="#" class="btn mr-5" role="button">Close</a>
				<a href="#" class="btn btn-primary" role="button">I understand</a>
			</div>
			</div>
		</div>
	</div>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-10 offset-md-1">

				<div class="card">
					<h2 class="card-header"><?=lang('app.booking.createTitle')?></h2>
					<div class="card-body">

						<?= view('Myth\Auth\Views\_message_block') ?>

						<p><?=lang('app.booking.createHelp')?></p>

						<?= form_open_multipart(base_url(route_to('create-booking'))) ?>

							<fieldset <?php // session('has_no_profile') ? '' : 'disabled="disabled"'?>>
								<pre><?php //var_dump($booking->getTimeSlots() ?? '')?></pre>
								<div class="form-row row-eq-spacing-md">
									<div class="col-md-6">
										<div class="form-group <?php if(session('errors.passenger')) : ?>is-invalid<?php endif ?>">
											<label for="passenger" class="required">
												<?=lang('app.booking.vehicleNo')?>
											</label>
											<input type="text" class="form-control" id="passenger" required="required"
													name="passenger" placeholder="<?=lang('app.booking.vehicleNo')?>"
													value="<?=old('passenger', $booking->passenger) ?>">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group <?php if(session('errors.purpose')) : ?>is-invalid<?php endif ?>">
											<label for="purpose" class="required"><?=lang('app.booking.purpose')?></label>
											<input type="text" class="form-control" id="purpose" required="required"
													name="purpose" placeholder="<?=lang('app.booking.purpose')?>"
													value="<?=old('purpose', $booking->purpose) ?>">
										</div>
									</div>
								</div>
								<div class="form-row row-eq-spacing-md">
								<div class="col-md-6 float-left"> <!-- w-400 = width: 40rem (400px), mw-full = max-width: 100% -->
									<div class="card p-0"> <!-- p-0 = padding: 0 -->
										<!-- Card header -->
										<div class="px-card py-10 border-bottom"> <!-- py-10 = padding-top: 1rem (10px) and padding-bottom: 1rem (10px), border-bottom: adds a border on the bottom -->
										<h2 class="card-title font-size-18 m-0"> <!-- font-size-18 = font-size: 1.8rem (18px), m-0 = margin: 0 -->
										<?=lang('app.booking.driverTitle')?>
										</h2>
										</div>
										<!-- Content -->
										<div class="content">
											<div class="form-row row-eq-spacing-md">
												<div class="col-md-6">
													<div class="card p-0 m-0 <?php if(session('errors.mobile')) : ?>is-invalid<?php endif ?>" data-toggle="tooltip" data-title="Click to Capture Photo" data-placement="bottom">
														<a href="#modal-1" role="button">													
															<canvas id="canvas" width="180" height="200"></canvas>
														</a>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group <?php if(session('errors.passenger')) : ?>is-invalid<?php endif ?>">
														<label for="passenger" class="required">
															<?=lang('app.booking.driverLicense')?>
														</label>
														<input type="text" class="form-control" id="passenger" required="required"
																name="passenger" placeholder="<?=lang('app.booking.driverLicense')?>"
																value="<?=old('passenger', $booking->passenger) ?>">
													</div>
													<div class="form-group <?php if(session('errors.passenger')) : ?>is-invalid<?php endif ?>">
														<label for="passenger" class="required">
															<?=lang('app.booking.driverName')?>
														</label>
														<input type="text" class="form-control" id="passenger" required="required"
																name="passenger" placeholder="<?=lang('app.booking.driverName')?>"
																value="<?=old('passenger', $booking->passenger) ?>">
													</div>
												</div>

											</div>
											<div class="form-row row-eq-spacing-md">
												<div class="col-md-6">
													<div class="form-group <?php if(session('errors.mobile')) : ?>is-invalid<?php endif ?>">
														<label for="mobile" class="required"><?=lang('app.booking.driverMobile')?></label>
														<input type="text" class="form-control" id="mobile" required="required"
																	name="mobile" placeholder="<?=lang('app.booking.driverMobile')?>"
																	value="<?=old('mobile', $booking->mobile) ?>">
													</div>
												</div>
												<div class="col-md-6 ">
													<div class="form-group <?php if(session('errors.address')) : ?>is-invalid<?php endif ?>">
														<label for="address" class="required"><?=lang('app.booking.driverAddress')?></label>
														<input type="text" class="form-control" id="address" required="required"
																name="address" placeholder="<?=lang('app.booking.driverAddress')?>"
																value="<?=old('address', $booking->address) ?>">
													</div>
												</div>


											</div>
										</div>
										<!-- Card footer -->
									</div>
								</div>
								<div class="col-md-6 float-left"> <!-- w-400 = width: 40rem (400px), mw-full = max-width: 100% -->
									<div class="card p-0"> <!-- p-0 = padding: 0 -->
										<!-- Card header -->
										<div class="px-card py-10 border-bottom"> <!-- py-10 = padding-top: 1rem (10px) and padding-bottom: 1rem (10px), border-bottom: adds a border on the bottom -->
										<h2 class="card-title font-size-18 m-0"> <!-- font-size-18 = font-size: 1.8rem (18px), m-0 = margin: 0 -->
										<?=lang('app.booking.crewTitle')?>
										</h2>
										</div>
										<!-- Content -->
										<div class="content">
											<div class="form-row row-eq-spacing-md">
												<div class="col-md-6">
													<div class="card p-0 m-0 <?php if(session('errors.mobile')) : ?>is-invalid<?php endif ?>" data-toggle="tooltip" data-title="Click to Capture Photo" data-placement="bottom">
														<a href="#modal-1" role="button">													
															<canvas id="canvas" width="180" height="200"></canvas>
														</a>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group <?php if(session('errors.mobile')) : ?>is-invalid<?php endif ?>">
														<label for="mobile" class="required"><?=lang('app.booking.crewMobile')?></label>
														<input type="text" class="form-control" id="mobile" required="required"
																	name="mobile" placeholder="<?=lang('app.booking.crewMobile')?>"
																	value="<?=old('mobile', $booking->mobile) ?>">
													</div>
													<div class="form-group <?php if(session('errors.passenger')) : ?>is-invalid<?php endif ?>">
														<label for="passenger" class="required">
															<?=lang('app.booking.crewName')?>
														</label>
														<input type="text" class="form-control" id="passenger" required="required"
																name="passenger" placeholder="<?=lang('app.booking.crewName')?>"
																value="<?=old('passenger', $booking->passenger) ?>">
													</div>
												</div>
											</div>
											<div class="form-row row-eq-spacing-md">
												<div class="col-md-6">
												<div class="form-group <?php if(session('errors.address')) : ?>is-invalid<?php endif ?>">
														<label for="address" class="required"><?=lang('app.booking.crewIdCardType')?></label>
														<input type="text" class="form-control" id="address" required="required"
																name="address" placeholder="<?=lang('app.booking.crewIdCardType')?>"
																value="<?=old('address', $booking->address) ?>">
													</div>

												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label for="passenger" class="required">
															<?=lang('app.booking.crewIdCardNo')?>
														</label>
														<input type="text" class="form-control" id="passenger" required="required"
																name="passenger" placeholder="<?=lang('app.booking.crewIdCardNo')?>"
																value="<?=old('passenger', $booking->passenger) ?>">
													</div>

												</div>													
											</div>
											<div class="form-group <?php if(session('errors.address')) : ?>is-invalid<?php endif ?>" >
												<label for="address" class="required"><?=lang('app.booking.crewAddress')?></label>
												<input type="text" class="form-control" id="address" required="required"
														name="address" placeholder="<?=lang('app.booking.crewAddress')?>"
														value="<?=old('address', $booking->address) ?>">
											</div>
										</div>
										<!-- Card footer -->
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

		// Grab elements, create settings, etc.
		var video = document.getElementById('webCam');

		// Elements for taking the snapshot
		var canvas = document.getElementById('canvas');
		var context = canvas.getContext('2d');

		// Trigger photo take
		document.getElementById("snap").addEventListener("click", function() {
			context.drawImage(video, 0, 0, 320, 240);
			//alert(canvas.toDataURL("image/png"));
			if (video.paused) 
				video.play(); 
			else {
				video.srcObject.getTracks().forEach(function(track) {
					if (track.readyState == 'live') {
						track.stop();
					}
				});;
			}
		});
		// Get access to the camera!
		if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
			// Not adding `{ audio: true }` since we only want video now
			navigator.mediaDevices.getUserMedia({ video: true }).then(function(stream) {
				//video.src = window.URL.createObjectURL(stream);
				video.srcObject = stream;
				video.play();
			});
		}

		/* Legacy code below: getUserMedia */
		else if(navigator.getUserMedia) { // Standard
			navigator.getUserMedia({ video: true }, function(stream) {
				video.src = stream;
				video.play();
			}, errBack);
		} else if(navigator.webkitGetUserMedia) { // WebKit-prefixed
			navigator.webkitGetUserMedia({ video: true }, function(stream){
				video.src = window.webkitURL.createObjectURL(stream);
				video.play();
			}, errBack);
		} else if(navigator.mozGetUserMedia) { // Mozilla-prefixed
			navigator.mozGetUserMedia({ video: true }, function(stream){
				video.srcObject = stream;
				video.play();
			}, errBack);
		}

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
							 //'csrf_test_name' : $("[name='csrf_test_name']").val(),
						 },
					}).done(function(resp){
						//$("#debug").text(resp);
					});
				
			},
    });
	  
	} );
	</script>

<?= $this->endSection() ?>
