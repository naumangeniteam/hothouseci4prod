<?php if ($pager->getPageCount() > 1) : ?>
<nav>
    <ul class="pagination">
        <!-- First Page Arrow -->
        <?php if ($pager->hasPrevious()) : ?>
            <li class="page-item">
                <a class="page-link" href="<?= $pager->getFirstPage() ?>" aria-label="First">
                    <span aria-hidden="true">«</span>
                </a>
            </li>
        <?php endif; ?>

        <!-- Page Numbers -->
        <?php foreach ($pager->links() as $link) : ?>
            <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
                <a class="page-link" href="<?= $link['uri'] ?>"><?= $link['title'] ?></a>
            </li>
        <?php endforeach; ?>

        <!-- Last Page Arrow -->
        <?php if ($pager->hasNext()) : ?>
            <li class="page-item">
                <a class="page-link" href="<?= $pager->getLastPage() ?>" aria-label="Last">
                    <span aria-hidden="true">»</span>
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>
<?php endif; ?>
