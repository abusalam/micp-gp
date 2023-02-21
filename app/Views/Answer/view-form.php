<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

	<?php \helper('form'); ?>
	<?php \helper('html'); ?>

	<div class="container-fluid">
		<div class="row">
			<div class="col-md-10 offset-md-1">

				<div class="card">
					<h2 class="card-header">
						<?=$title ?? lang('app.assignment.notFound')?>
						<span class="text-monospace">
							<a href="<?=base_url(route_to('view-assignment-files', $assnId))?>">
								#<?=$assnId?>
							</a>
						</span>
					</h2>
					<div class="card-body">
						<h4><?=$topic?></h4>
						<?= view('Myth\Auth\Views\_message_block') ?>

						<p><?=$detail ?? ''?></p>

								<pre><?php //var_dump($post ?? '')?></pre>
								<button id="marks" class="btn btn-primary pull-right"><?=lang('app.answer.markSheet')?></button>
								<h3>
									<?php if(in_groups('teachers')): ?>
										<?=lang('app.answer.solvedAnswers')?>
									<?php else:?>
										<?=lang('app.answer.myMarks') . ': ' . ($answer->marks ?? '--') . ' / ' . $marks?>
									<?php endif ?>
								</h3>
								<hr/>

								<!-- Precompiled alert with a complex design -->
								<div class="alert alert-success" role="alert" id="add-marks">
									<h4 class="alert-heading">
										<span class="font-weight-bold"><?=lang('app.answer.markSheet')?></span>
									</h4>
									<?=form_open_multipart(base_url(route_to('save-marks', $id)))?>
										<?=lang('app.answer.question')?>
										<?php $assessment = unserialize($answer->assessment);?>
										<!-- <pre></pre> -->
										<fieldset <?= in_groups('teachers') ? '' : 'disabled="disabled"'?>>
										<div class="marks overflow-auto">
											<?php for($i = 1; $i <= $questions; $i++): ?>
											<!-- Input group with prepended text -->
											<div class="input-group">
												<div class="input-group-prepend">
													<span class="input-group-text text-monospace">
														&nbsp;<?=str_pad('#' . $i, 3, ' ', STR_PAD_LEFT)?>
													</span>
												</div>
												<input type="text" id="q<?=$i?>" name="q[]" class="form-control text-right"
														value="<?=old('q.' . ($i - 1), $assessment[$i - 1] ?? '0')?>" placeholder="0">
											</div>
											<?php endfor ?>
										</div>
										<div class="p-5"></div>
										<!-- Input group with appended and prepended text -->
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text text-primary text-weight-bold"><?=lang('app.answer.marksObtained')?></span>
											</div>
											<input type="text" name="marks" id="total" class="form-control text-right"
													value="<?=old('marks', $answer->marks)?>" placeholder="0">
											<div class="input-group-append">
												<span class="input-group-text text-success">/ <?=$marks?></span>
											</div>
											<?php if(in_groups('teachers')): ?>
											<div class="input-group-append">
												<button type="submit" id="saveMarks" class="btn btn-secondary"><?=lang('app.answer.saveMarks')?></button>
											</div>
											<?php endif ?>
										</div>
										</fieldset>
										<div class="mb-20"></div>
									<?=form_close()?>
								</div>


									<?php foreach($files as $file): ?>
										<?php if (! in_groups(env('auth.defaultUserGroup', 'students'))): ?>
										<a href="<?=site_url(route_to('check-answer-file', $id, $file['id']))?>">
										<?php endif ?>
											<?php $image = model('FileModel')->find($file['id'])->loadFile()->getImageInfo();?>
											<div id="wrapper-<?=$file['id']?>" class="wrapper text-center">
												<div class="layered overflow-auto w-full h-full text-center py-10">
													<img id="AnswerFile-<?=$file['id']?>" <?=$image['size_str']?>
															class="img-fluid border rounded" 
															src="<?= base_url(route_to('show-file', $file['id']))?>"/>
													<canvas id="signature-pad-<?=$file['id']?>" <?=$image['size_str']?>
															class="img-fluid signature-pad rounded">
														<?=lang('app.answer.canvasNotSupported')?>
													</canvas>
												</div>
											</div>
										<?php if (! in_groups(env('auth.defaultUserGroup', 'students'))): ?>
										</a>
										<?php endif ?>
									<?php endforeach ?>
							<!-- <button type="submit" class="btn btn-primary btn-block"><?=lang('app.file.btnCreateTitle')?></button> -->

					</div>
				</div>

			</div>
		</div>
	</div>

<!-- Signature Pad JS -->
<script type="text/javascript" src="<?=base_url('/js/signature_pad.min.js')?>"></script>

<style {csp-style-nonce}>
.layered {
  display: grid;

  /* Set horizontal alignment of items in, case they have a different width. */
  /* justify-items: start | end | center | stretch (default);*/
  justify-items: center;

  /* Set vertical alignment of items, in case they have a different height. */
  /* align-items: start | end | center | stretch (default); */
  align-items: center;
}

.layered > * {
  grid-column-start: 1;
  grid-row-start: 1;
}

.marks {
	max-height: 20rem;
}
/* for demonstration purposses only */
/* .layered > * {
  outline: 1px solid red;
  background-color: rgba(255, 255, 255, 0.3)
} */
</style>

<!-- JavaScript -->
<script {csp-script-nonce}>
	<?php foreach($files as $file): ?>
		<?php $assessment = model('AnswerModel')->getCheckedFile($file['id']);?>
		var wrapper<?=$file['id']?> = document.getElementById('wrapper-<?=$file['id']?>');
		var image<?=$file['id']?> = document.getElementById('AnswerFile-<?=$file['id']?>');
		var canvas<?=$file['id']?> = document.getElementById('signature-pad-<?=$file['id']?>');
		var signaturePad<?=$file['id']?> = new SignaturePad(canvas<?=$file['id']?>, {
			backgroundColor: 'rgba(0, 0, 0, 0)',
			maxWidth: 1,
		});
		signaturePad<?=$file['id']?>.fromData(JSON.parse(<?=json_encode($assessment->assessment)?>));
		signaturePad<?=$file['id']?>.off();
	<?php endforeach ?>

	const marksButton     = document.getElementById('marks');
	const marksAlert      = document.getElementById('add-marks');
	const saveMarksButton = document.getElementById('saveMarks');
	const markDetails     = document.getElementsByName('q[]');
	const markTotal       = document.getElementById('total');
	
	// Remove DOM Elements so that it can be added to sticky-alerts when required
	marksAlert.parentNode.removeChild(marksAlert);

	// Update the total marks
	function addMarks(event) {
		var num=0;
		for(var i=0;i<markDetails.length;i++)
		{
			num += parseFloat(markDetails[i].value);
		}
		markTotal.value=num.toFixed(2);
	};
	
	// Check if marks are more than total marks and show alert
	saveMarksButton.addEventListener('click', function(event){
		if (markTotal.value > <?=$marks?>) {
			event.preventDefault();
			halfmoon.initStickyAlert({
				content: "<?=lang('app.answer.tooMuchMarks')?>",
				title: "Error",
				alertType: "alert-danger",
				fillType: "filled-dm",
				hasDismissButton: true,
			});
		}
	});

	// Update total if typed incorrectly
	markTotal.addEventListener("blur", addMarks);
	
	marksButton.addEventListener("click", function (event) {
		event.preventDefault();
		
		// Add the DOM elements to sticky-alert
		halfmoon.stickyAlerts.insertBefore(marksAlert, halfmoon.stickyAlerts.childNodes[0]);

		// Register the event listeners for all the questions to update the marks
		for(var i=0;i<markDetails.length;i++) {
			markDetails[i].addEventListener("blur", addMarks);
		}

		// Show the marksheet form
		halfmoon.toastAlert('add-marks', 99999999999999);

		// Remove the marksheet button
		marksButton.parentNode.removeChild(marksButton);
	});
</script>

<?= $this->endSection() ?>
