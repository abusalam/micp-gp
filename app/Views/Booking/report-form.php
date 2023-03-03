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
                <?=lang('app.booking.quickReports')?>
              </h6>
              <div class="dropdown-divider"></div>
              <div class="dropdown-content">
                <a href="<?= base_url(route_to('daily-report'))?>" target="_blank" class="btn btn-primary">
                  <?=lang('app.booking.dailyReport')?>
                </a>
              </div>
              <div class="dropdown-content">
                <a href="<?= base_url(route_to('blacklist-report'))?>" target="_blank" class="btn btn-primary">
                  <?=lang('app.booking.blacklistReport')?>
                </a>
              </div>
            </div>
          </div>
          <h2 class="card-header"><?=lang('app.booking.reports')?></h2>
          <div class="card-body">
            <?= view('Myth\Auth\Views\_message_block') ?>
            <?= form_open_multipart(base_url(route_to('reports')),['target' => '_blank']) ?>
                <pre><?php //var_dump($booking->getTimeSlots() ?? '')?></pre>
                <div class="form-group <?php if(session('errors.date')) : ?>is-invalid<?php endif ?>" id="reportrange">
                  <label for="date" class="required"><?=lang('app.booking.date')?></label>
                  <div class="input-group w-300">
                    <span class="form-control"></span>
                    <input type="hidden" id="dateRange" name="date">
                    <input type="hidden" id="dateFrom" name="dateFrom">
                    <input type="hidden" id="dateTo" name="dateTo">
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="submit"><?=lang('app.booking.btnReportTitle')?></button>
                    </div>
                  </div>
                </div>
                <!-- <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                    <i class="fa fa-calendar"></i>&nbsp;
                    <span></span> 
                    <i class="fa fa-caret-down"></i>
                </div> -->
                <pre id="debug"><?php //var_dump($booking ?? '')?></pre>
                <!-- <button type="submit" class="btn btn-primary"><?=lang('app.booking.btnReportTitle')?></button> -->
            <?=form_close()?>
          </div>
        </div>
      </div>
    </div>
  </div>

<script type="text/javascript" {csp-script-nonce}>
  $(function() {

      var start = moment().subtract(29, 'days');
      var end = moment();

      function cb(start, end) {
          $('#reportrange span').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
          $('#dateRange').val(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
          $('#dateFrom').val(start.format("YYYY-MM-DD"));
          $('#dateTo').val(end.format("YYYY-MM-DD"));
      }

      $('#reportrange').daterangepicker({
          startDate: start,
          endDate: end,
          "buttonClasses": "btn",
          ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          }
      }, cb);

      cb(start, end);

  });
</script>
<?= $this->endSection() ?>
