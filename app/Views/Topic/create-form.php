<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

	<?php \helper('form'); ?>
	<?php \helper('html'); ?>

	<div class="container-fluid">
		<div class="row">
			<div class="col-md-10 offset-md-1">

				<div class="card">
					<h2 class="card-header"><?=lang('app.topic.topicTitle')?></h2>
					<div class="card-body">

						<?= view('Myth\Auth\Views\_message_block') ?>

						<p><?=lang('app.topic.topicHelp')?></p>

						<?= form_open_multipart(base_url(route_to('create-topic'))) ?>

							<fieldset <?php // session('has_no_profile') ? '' : 'disabled="disabled"'?>>
								<pre><?php //var_dump($post ?? '')?></pre>
								<div>
									<div class="form-row row-eq-spacing-md">
										<div class="col-md-5 <?php if(session('errors.class_id')) : ?>is-invalid<?php endif ?>">
											<label for="class" class="required"><?=lang('app.topic.classTitle')?></label>
											<select class="form-control" id="class" required="required"
													name="class_id">
												<option value="" <?= (old('class_id') === null) ? 'selected="selected"' : '' ?>
														disabled="disabled"><?=lang('app.topic.classTitle')?></option>
												<?php foreach($classes as $class) : ?>
												<option value="<?= $class['id'] ?>" 
														<?= (old('class_id', $topic->getClassId()) === $class['id']) ? 'selected="selected"' : '' ?>
													><?= $class['class'] ?>
												</option>
												<?php endforeach ?>
											</select>
										</div>
										<!-- Subject Selection -->
										<div class="col-md-7 <?php if(session('errors.subject_id')) : ?>is-invalid<?php endif ?>">
											<label for="subject" class="required"><?=lang('app.topic.subjectTitle')?></label>
											<select class="form-control" id="subject" required="required" 
													name="subject_id">
												<option value="" <?= (old('subject_id') === null) ? 'selected="selected"' : '' ?>
														disabled="disabled"><?=lang('app.topic.subjectTitle')?></option>
												<?php foreach($subjects as $subject) : ?>
												<option value="<?= $subject['id'] ?>" 
														<?= (old('subject_id', $topic->getSubjectId()) === $subject['id']) ? 'selected="selected"' : '' ?>
													><?= $subject['subject'] ?>
												</option>
												<?php endforeach ?>
											</select>
										</div>
									</div>
								</div>
								<div class="form-group <?php if(session('errors.topic')) : ?>is-invalid<?php endif ?>">
									<label for="topic" class="required"><?=lang('app.topic.topicTitle')?></label>
									<input type="text" class="form-control" id="topic" required="required"
											name="topic" placeholder="<?=lang('app.topic.topicTitle')?>"
											value="<?= old('topic', $topic->topic) ?>">
								</div>
								<div class="form-group <?php if(session('errors.detail')) : ?>is-invalid<?php endif ?>">
									<label for="if-8-textarea" class="required"><?=lang('app.topic.detailTitle')?></label>
									<textarea class="form-control" placeholder="<?=lang('app.topic.topicHelp')?>" required="required"
											name="detail" id="if-8-textarea"><?= old('detail', $topic->detail) ?></textarea>
									<small id="descHelp" class="form-text text-muted"><?=lang('app.topic.detailHelp')?></small>
								</div>

							</fieldset>
							<button type="submit" class="btn btn-primary btn-block"><?=lang('app.topic.topicSave')?></button>
						<?=form_close()?>

					</div>
				</div>

			</div>
		</div>
	</div>

<?= $this->endSection() ?>
