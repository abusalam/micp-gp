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

		<style {csp-script-nonce}>
			.img-edit {
				position: relative;
				top: 0.5rem;
				right: 0.5rem;
			}
		</style>
	</head>
	<body class="with-custom-webkit-scrollbars with-custom-css-scrollbars"
				data-dm-shortcut-enabled="true"
				data-sidebar-shortcut-enabled="true"
				data-set-preferred-theme-onload="true">
		<!-- Modals go here -->
		<!-- Reference: https://www.gethalfmoon.com/docs/modal -->

		<!-- Page wrapper start -->
		<div class="page-wrapper with-navbar with-sidebar with-navbar-fixed-bottom" 
					data-sidebar-type="overlayed-sm-and-down">

			<!-- Sticky alerts (toasts), empty container -->
			<!-- Reference: https://www.gethalfmoon.com/docs/sticky-alerts-toasts -->
			<div class="sticky-alerts"></div>

			<!-- Navbar start -->
			<nav class="navbar">
				<!-- Reference: https://www.gethalfmoon.com/docs/navbar -->
				<div class="navbar-content">
					<!-- Navbar content (with toggle sidebar button) -->
					<button class="btn btn-action" type="button" id="toggleSidebar">
						<i class="fa fa-bars" aria-hidden="true"></i>
						<span class="sr-only">Toggle sidebar</span> <!-- sr-only = show only on screen readers -->
					</button>
				</div>
				<!-- Navbar brand -->
				<a href="<?=base_url()?>" class="navbar-brand">
					<div class="branding">
						<div class="large"><?= APP_TITLE ?></div>
						<!-- <div><?= getenv('AUTHORITY')?></div> -->
					</div>
				</a>
				<!-- Navbar text -->
				<span class="navbar-text text-monospace badge"><?= VERSION ?></span> <!-- text-monospace = font-family shifted to monospace -->
				<div class="navbar-content ml-auto">
					<!-- Navbar content (with toggle darkmode button) -->
					<button class="btn btn-action mr-5" type="button" id="toggleDarkMode">
						<i class="fa fa-moon-o" aria-hidden="true"></i>
						<span class="sr-only">Toggle sidebar</span> <!-- sr-only = show only on screen readers -->
					</button>
				</div>
			</nav>
			<!-- Navbar end -->

			<!-- Sidebar overlay -->
			<div class="sidebar-overlay" id="toggleSidebar-overlay"></div>

			<!-- Sidebar start -->
			<div class="sidebar d-flex flex-column">
				<!-- Reference: https://www.gethalfmoon.com/docs/sidebar -->
				<?=view('layout/parts/sidebar')?>
			</div>
			<!-- Sidebar end -->

			<!-- Content wrapper start -->
			<div class="content-wrapper">
				<!--
					Add your page's main content here
					Examples:
					1. https://www.gethalfmoon.com/docs/content-and-cards/#building-a-page
					2. https://www.gethalfmoon.com/docs/grid-system/#building-a-dashboard
				-->
				<!-- Content -->
				<?= $this->renderSection('main') ?>
			</div>
			<!-- Content wrapper end -->

			<!-- Navbar fixed bottom start -->
			<nav class="navbar navbar-fixed-bottom">
				<!-- Reference: https://www.gethalfmoon.com/docs/navbar#navbar-fixed-bottom -->
				<!-- FOOTER: DEBUG INFO + COPYRIGHTS -->
				<span class="navbar-text mr-auto hidden-md-and-down"><?= lang('app.home.disclaimer') . getenv('AUTHORITY')?></span>
					<span class="navbar-text">
						&copy; Copyright <?= date('Y') ?>
						<span class="hidden-md-and-up pl-5">NIC</span>
						<span class="hidden-sm-and-down pl-5"> National Informatics Centre.</span>
					</span>
			</nav>
			<!-- Navbar fixed bottom end -->
		</div>
		<!-- Page wrapper end -->

		<!-- Halfmoon 1.1.1 JS -->
		<script src="<?=base_url('/js/halfmoon.min.js')?>"></script>
		<script {csp-script-nonce} type="text/javascript">
			document.getElementById("toggleDarkMode").addEventListener("click", function(){
				halfmoon.toggleDarkMode();
			});
			document.getElementById("toggleSidebar").addEventListener("click", function(){
				halfmoon.toggleSidebar();
			});
			document.getElementById("toggleSidebar-overlay").addEventListener("click", function(){
				halfmoon.toggleSidebar();
			});
		</script>

	</body>
</html>