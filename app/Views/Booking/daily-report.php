<?= $this->extend('App\Views\layout\report') ?>
<?= $this->section('main') ?>
  <h2>
    <?=$title ?? 'Report'?>
  </h2>
  <?= view('layout/parts/data') ?>
<?= $this->endSection() ?>
