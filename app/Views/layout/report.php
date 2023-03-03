<!DOCTYPE html>
<html>
	<head>
		<!-- Meta tags -->
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
		<meta name="viewport" content="width=device-width" />

		<!-- Favicon and title -->
		<link rel="icon" href="<?=base_url('/favicon.ico')?>">
		<title><?= $title ?? 'Home' ?> - <?= APP_TITLE ?></title>

		<!-- Halfmoon 1.1.1 CSS -->
		<link href="<?=base_url('/css/halfmoon.min.css')?>" rel="stylesheet" />
		<!-- Font Awesome 4.7.0 CSS -->
		<link rel="stylesheet" href="<?=base_url('/css/font-awesome.min.css')?>" />
		<!-- DataTables 1.10.21 CSS -->
		<link rel="stylesheet" type="text/css" href="<?=base_url('/css/datatables.min.css')?>"/>

		
		<link rel="stylesheet" type="text/css" href="<?=base_url('/css/jquery-ui.min.css')?>">
		<script src="<?=base_url('/js/jquery-1.12.4.min.js')?>"></script>
		<script src="<?=base_url('/js/jquery-ui.min.js')?>"></script>
	</head>
	<body>
		<!--
			Add your page's main content here
			Examples:
			1. https://www.gethalfmoon.com/docs/content-and-cards/#building-a-page
			2. https://www.gethalfmoon.com/docs/grid-system/#building-a-dashboard
		-->
		<!-- Content -->
		<?= $this->renderSection('main') ?>
	</body>
</html>