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

						<?= form_open_multipart(base_url(route_to('save-answer-file', $id, $file->id))) ?>
								<h4 class="text-secondary text-monospace">
									<?=lang('app.answer.checkAnswer')?>
									<span class="text-monospace">
										<a href="<?=base_url(route_to('view-answer-files', $id))?>">
											#<?=$id?>
										</a>
									</span>
								</h4>
								<input type="hidden" id="imgData" name="assessment" value="<?=old('assessment')?>"/>
								<hr/>
								<div id="wrapper" class="wrapper text-center">
									<div class="layered overflow-auto w-full h-full text-center py-10">
										<img id="AnswerFile" <?=$image['size_str']?>
												class="border rounded" 
												src="<?= base_url(route_to('show-file', $file->id))?>"/>
										<canvas id="signature-pad" <?=$image['size_str']?>
												class="signature-pad rounded">
											<?=lang('app.answer.canvasNotSupported')?>
										</canvas>
									</div>
									<br/>
									<a class="btn btn-danger" id="clear">Clear</a>
									<a class="btn btn-secondary" id="undo">Undo</a>
									<a class="btn btn-primary" id="change-color" data-action="change-color">
										<i class="fa fa-spinner" aria-hidden="true"></i> Color
									</a>
									<?php if($isMobile):?>
										<a class="btn btn-primary" id="change-mode" data-action="change-mode">
											<i class="fa fa-hand-paper-o" aria-hidden="true"></i> Mode
										</a>
									<?php endif ?>
									<button type="submit" class="btn btn-success" id="save-svg">
										<?=lang('app.answer.checkSave')?>
									</button>
								</div>
						<?=form_close()?>

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

  /* Set horizontal alignment of items in, case they have a diffrent width. */
  /* justify-items: start | end | center | stretch (default);*/
  justify-items: center;

  /* Set vertical alignment of items, in case they have a diffrent height. */
  /* align-items: start | end | center | stretch (default); */
  align-items: center;
}

.layered > * {
  grid-column-start: 1;
  grid-row-start: 1;
}


/* for demonstration purposes only */
/* .layered > * {
  outline: 1px solid red;
  background-color: rgba(255, 255, 255, 0.3)
} */
</style>

<script {csp-script-nonce}>
var wrapper = document.getElementById('wrapper');
wrapper.style.cssText = 'cursor:crosshair';
var imgData = document.getElementById('imgData');
var image = document.getElementById('AnswerFile');
var canvas = document.getElementById('signature-pad');
const changeColorButton = wrapper.querySelector("[data-action=change-color]");

// canvas.width = image.width;
// canvas.height = image.height;
imgData.value = <?=json_encode($assessment->assessment)?>;

const updateField = (event) => {imgData.value = JSON.stringify(signaturePad.toData())};

var signaturePad = new SignaturePad(canvas, {
  backgroundColor: 'rgba(128, 0, 128, 0)',
	maxWidth: 1,
	throttle: 0,
	penColor: 'rgba(255,0,0,0.5)',
	onEnd: updateField,
});

signaturePad.fromData(JSON.parse(imgData.value));

document.getElementById('save-svg').addEventListener('click', function () {
  if (signaturePad.isEmpty()) {
		return alert("Please provide a signature first.");
  }
	imgData.value = signaturePad ? JSON.stringify(signaturePad.toData()) : null;
});

document.getElementById('clear').addEventListener('click', function (event) {
	event.preventDefault();
  signaturePad.clear();
	updateField();
});

document.getElementById('undo').addEventListener('click', function (event) {
	event.preventDefault();
	var data = signaturePad.toData();
  if (data) {
	data.pop(); // remove the last dot or line
	signaturePad.fromData(data);
	updateField();
  }
});

changeColorButton.addEventListener("click", function (event) {
	event.preventDefault();
  const r = Math.round(Math.random() * 255)
  const g = Math.round(Math.random() * 255)
  const b = Math.round(Math.random() * 255)
  const color = "rgba(" + r + "," + g + "," + b +", 0.8)"

  signaturePad.penColor = color
});

<?php if($isMobile):?>
	var canvasMode = true;
	const changeModeButton = wrapper.querySelector("[data-action=change-mode]");
	changeModeButton.addEventListener("click", function (event) {
		event.preventDefault();
		if(canvasMode)
		{
			signaturePad.off();
			changeModeButton.innerHTML = '<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Mode';
			wrapper.style.cssText = 'cursor:move';
		}
		else
		{
			signaturePad.on();
			changeModeButton.innerHTML = '<i class="fa fa-hand-paper-o" aria-hidden="true"></i> Mode';
			wrapper.style.cssText = 'cursor:crosshair';
		}
		canvasMode = !canvasMode;
	});
<?php endif ?>
</script>

<?= $this->endSection() ?>
