<?php $pager->setSurroundCount(2) ?>
<nav class="hidden-md-and-down" aria-label="<?= lang('Pager.pageNavigation') ?>">
	<ul class="pagination pagination-rounded text-center">
		<?php if ($pager->hasPreviousPage()) : ?>
			<li class="page-item">
				<a href="<?= $pager->getFirst() ?>" class="page-link" aria-label="<?= lang('Pager.first') ?>">
					<i class="fa fa-angle-double-left" aria-hidden="true"></i>
					<span class="sr-only" aria-hidden="true"><?= lang('Pager.first') ?></span>
				</a>
			</li>
			<li class="page-item">
				<a href="<?= $pager->getPreviousPage() ?>" class="page-link" aria-label="<?= lang('Pager.previous') ?>">
					<i class="fa fa-angle-left" aria-hidden="true"></i>
					<span class="sr-only" aria-hidden="true"><?= lang('Pager.previous') ?></span>
				</a>
			</li>
		<?php endif ?>

		<?php foreach ($pager->links() as $link) : ?>
			<li  class="page-item <?= $link['active'] ? 'active' : '' ?>">
				<a href="<?= $link['uri'] ?>" class="page-link">
					<?= $link['title'] ?>
				</a>
			</li>
		<?php endforeach ?>

		<?php if ($pager->hasNextPage()) : ?>
			<li class="page-item">
				<a href="<?= $pager->getNextPage() ?>" class="page-link" aria-label="<?= lang('Pager.next') ?>">
					<i class="fa fa-angle-right" aria-hidden="true"></i>
					<span class="sr-only" aria-hidden="true"><?= lang('Pager.next') ?></span>
				</a>
			</li>
			<li class="page-item">
				<a href="<?= $pager->getLast() ?>" class="page-link" aria-label="<?= lang('Pager.last') ?>">
				<i class="fa fa-angle-double-right" aria-hidden="true"></i>
					<span class="sr-only" aria-hidden="true"><?= lang('Pager.last') ?></span>
				</a>
			</li>
		<?php endif ?>
	</ul>
</nav>

<?php $pager->setSurroundCount(0); ?>
<nav class="hidden-lg-and-up" aria-label="<?= lang('Pager.pageNavigation') ?>">
	<ul class="pagination pagination-rounded text-center">
		<li class="page-item <?= $pager->hasPrevious() ? '' : 'disabled' ?>">
			<a href="<?= $pager->getPrevious() ?? '#' ?>" class="page-link" aria-label="<?= lang('Pager.previous') ?>">
				<i class="fa fa-angle-left" aria-hidden="true"></i>
				<span class="sr-only" aria-hidden="true"><?= lang('Pager.newer') ?></span>
			</a>
		</li>
		<li class="page-item <?= $pager->hasNext() ? '' : 'disabled' ?>">
			<a href="<?= $pager->getnext() ?? '#' ?>" class="page-link" aria-label="<?= lang('Pager.next') ?>">
				<i class="fa fa-angle-right" aria-hidden="true"></i>
				<span class="sr-only" aria-hidden="true"><?= lang('Pager.older') ?></span>
			</a>
		</li>
	</ul>
</nav>