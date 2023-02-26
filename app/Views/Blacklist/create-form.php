<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

  <?php \helper('form'); ?>
  <?php \helper('html'); ?>

  <div class="container-fluid">
    <div class="row">
      <div class="col-md-10 offset-md-1">

        <div class="card">
          <h2 class="card-header"><?=lang('app.blacklist.blacklistTitle')?></h2>
          <div class="card-body">

            <?= view('Myth\Auth\Views\_message_block') ?>

            <p><?=lang('app.blacklist.blacklistHelp')?></p>

            <?= form_open_multipart(base_url(route_to('create-blacklist'))) ?>
              <fieldset <?php // session('has_no_profile') ? '' : 'disabled="disabled"'?>>
                <pre><?php //var_dump($blacklist->getTimeSlots() ?? '')?></pre>
                <div class="form-row row-eq-spacing-md">
                  <div class="col-md-6">
                    <div class="form-group <?php if(session('errors.blacklist_no')) : ?>is-invalid<?php endif ?>">
                      <label for="blacklist_no" class="required"><?=lang('app.blacklist.blacklist')?></label>
                      <input type="text" class="form-control" id="blacklist_no" required="required"
                            name="blacklist_no" placeholder="<?=lang('app.blacklist.blacklist')?>"
                            value="<?=old('blacklist_no', $blacklist->blacklist_no) ?>">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group <?php if(session('errors.reason')) : ?>is-invalid<?php endif ?>">
                      <label for="reason" class="required"><?=lang('app.blacklist.reasonTitle')?></label>
                      <input type="text" class="form-control" id="reason" required="required"
                            name="reason" placeholder="<?=lang('app.blacklist.reasonTitle')?>"
                            value="<?=old('reason', $blacklist->reason) ?>">
                    </div>
                  </div>
                </div>
                <pre id="debug"><?php //var_dump($blacklist ?? '')?></pre>
                        <button type="submit" class="btn btn-danger"  disabled="disabled"><?=lang('app.blacklist.btnAddTitle')?></button>
                        <button type="submit" class="btn btn-success" disabled="disabled"><?=lang('app.blacklist.btnRemoveTitle')?></button>
                        <a href="<?=base_url(route_to('view-blacklists'))?>" class="btn btn-primary"><?=lang('app.blacklist.btnListTitle')?></a>
              </fieldset>
            <?=form_close()?>

          </div>
        </div>

      </div>
    </div>
  </div>
<?= $this->endSection() ?>
