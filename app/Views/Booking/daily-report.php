<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>
  <h2>
    <?=$title ?? 'Report'?>
  </h2>
  <?= view('layout/parts/data') ?>
<?= $this->endSection() ?>
