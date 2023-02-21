<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

	<?php \helper('form'); ?>
	<?php \helper('html'); ?>

	<div class="container-fluid">
		<div class="row">
			<div class="col-md-10 offset-md-1">

				<div class="card">
					<div class="dropdown pull-right">
						<button class="btn btn-success" data-toggle="dropdown" type="button" 
								id="dropdown-toggle-btn-1" aria-haspopup="true" aria-expanded="false">
								<?=lang('app.menu.menuTitle')?>
								<i class="fa fa-angle-down ml-5" aria-hidden="true"></i>
						</button>
						<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-toggle-btn-1">
							<h6 class="dropdown-header font-weight-bold">
							<i class="fa fa-file-text mr-5" aria-hidden="true"></i>
							<?=lang('app.assignment.menuTopics')?></h6>
							<div class="dropdown-divider"></div>
							<div class="dropdown-content">
								<a href="<?= base_url(route_to('create-topic'))?>" class="btn btn-primary ">
									<?=lang('app.topic.createTitle')?>
								</a>
							</div>
						</div>
					</div>
					<h2 class="card-header"><?=lang('app.assignment.createTitle')?></h2>
					<div class="card-body">

						<?= view('Myth\Auth\Views\_message_block') ?>

						<p><?=lang('app.assignment.createHelp')?></p>

						<?= form_open_multipart(base_url(route_to('create-assignment'))) ?>

							<fieldset <?php // session('has_no_profile') ? '' : 'disabled="disabled"'?>>
								<pre><?php //var_dump($topicId ?? '')?></pre>
								<div class="form-group">
									<label for="topic_id" class="required">
										<?=lang('app.topic.topicTitle')?>
										<a href="<?= base_url(route_to('create-topic'))?>" class="">(<?=lang('app.topic.topicNew')?>)</a>
									</label>
									<select class="form-control" id="topic_id" required="required" name="topic_id">
										<option value="" <?= (old('topic_id', $assignment->getTopic()->id ?? null) === null) ? 'selected="selected"' : '' ?>
												disabled="disabled"><?=lang('app.topic.topicTitle')?></option>
										<?php foreach($topics as $topic) : ?>
										<option value="<?= $topic['id'] ?>" 
												<?= (old('topic_id', explode(' ', session('message'))[3] ?? $assignment->getTopic()->id) === $topic['id']) ? 'selected="selected"' : '' ?>>
												<?= model('ClassModel')->find($topic['class_id'])['class']
														. ' - ' . model('SubjectModel')->find($topic['subject_id'])['subject'] . ' - ' . $topic['topic'] ?>
										</option>
										<?php endforeach ?>
									</select>
								</div>
								<div class="form-group <?php if(session('errors.title')) : ?>is-invalid<?php endif ?>">
									<label for="title" class="required"><?=lang('app.assignment.newTitle')?></label>
									<input type="text" class="form-control" id="title" required="required"
											name="title" placeholder="<?=lang('app.assignment.newTitle')?>"
											value="<?= old('title', $assignment->title) ?>">
								</div>
								<div>
									<div class="form-row row-eq-spacing-md">
										<div class="col-md-6 <?php if(session('errors.questions')) : ?>is-invalid<?php endif ?>">
											<div class="form-group">
												<label for="questions" class="required"><?=lang('app.assignment.questions')?></label>
												<input type="text" class="form-control" id="questions" required="required"
															name="questions" placeholder="<?=lang('app.assignment.questions')?>"
															value="<?= old('questions', $assignment->questions) ?>">
											</div>
										</div>
										<div class="col-md-6 <?php if(session('errors.marks')) : ?>is-invalid<?php endif ?>">
											<div class="form-group">
												<label for="marks" class="required"><?=lang('app.assignment.marksTitle')?></label>
												<input type="text" class="form-control" id="marks" required="required"
															name="marks" placeholder="<?=lang('app.assignment.marksTitle')?>"
															value="<?= old('marks', $assignment->marks) ?>">
											</div>
										</div>
									</div>
								</div>
							</fieldset>
							<button type="submit" class="btn btn-primary btn-block"><?=lang('app.assignment.btnCreateTitle')?></button>
						<?=form_close()?>

					</div>
				</div>

			</div>
		</div>
	</div>

<?= $this->endSection() ?>
