<div class="sidebar-menu">
	<div class="sidebar-divider"></div>
	<div class="hidden-md-and-up">
		<div class="sidebar-link">
			<?='';//view('layout/parts/YouTube', [], ['cache' => 3600])?>
		</div>
		<div class="sidebar-divider"></div>
	</div>
	
	<?php \helper('auth'); ?>
	<?php
	if (! function_exists('add_class'))
	{
		function add_class($routeAlias, $class = 'active')
		{
			return current_url() === base_url(route_to($routeAlias)) ? $class : '';
		}
	}
	?>

	<?php if (logged_in()) : ?>
	
		<h5 class="sidebar-title font-weight-bold">
			<?=user()->full_name?>
			(<?=(user_id() === '1') ? 'Super Admin' : ((ENVIRONMENT !== 'production') ? join(',', user()->getRoles()) : '')?>)
		</h5>
		<div class="sidebar-divider"></div>

		<?php if(in_groups('admins')): ?>
			<a href="<?= base_url(route_to('accounts'))?>"
				class="sidebar-link sidebar-link-with-icon <?= add_class('accounts')?>">
				<span class="sidebar-icon">
					<i class="fa fa-users" aria-hidden="true"></i>
				</span>
				<?=lang('app.menu.userManagement')?>
			</a>
		<?php endif ?>
		<a href="<?= base_url(route_to('create-booking'))?>"
			class="sidebar-link sidebar-link-with-icon <?= add_class('create-booking')?>">
			<span class="sidebar-icon">
				<i class="fa fa-ship" aria-hidden="true"></i>
			</span>
			<?=lang('app.menu.booking')?>
		</a>
		<a href="<?= base_url(route_to('profile'))?>"
			class="sidebar-link sidebar-link-with-icon <?= add_class('profile')?>">
			<span class="sidebar-icon">
				<i class="fa fa-user" aria-hidden="true"></i>
			</span>
			<?=lang('app.menu.updateProfile')?>
		</a>
		<a href="<?= base_url(route_to('logout'))?>"
			class="sidebar-link sidebar-link-with-icon">
			<span class="sidebar-icon">
				<i class="fa fa-sign-out" aria-hidden="true"></i>
			</span>
			<?=lang('app.menu.logout')?>
		</a>

	<?php else : ?>
		<a href="<?= base_url(route_to('login'))?>"
			class="sidebar-link sidebar-link-with-icon <?= add_class('login')?>">
			<span class="sidebar-icon">
				<i class="fa fa-sign-in" aria-hidden="true"></i>
			</span>
			<?=lang('app.menu.login')?>
		</a>
	<?php endif ?>
</div>

<div class="flex-grow-1"></div> <!-- push the below items to the bottom -->

<div class="sidebar-menu">
	<?php if(ENVIRONMENT !== 'production'): ?>
	<div class="sidebar-link">
		<span class="badge badge-secondary badge-pill m-5">Mode: <?= ENVIRONMENT ?></span>
	</div>
	<?php endif ?>
	<div class="sidebar-link">
		<span class="badge badge-success badge-pill m-5">Response Time: {elapsed_time}s</span>
	</div>
</div>