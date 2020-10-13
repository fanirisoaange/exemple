<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/" class="brand-link elevation-2 text-center">
        <?= img(ASSETS . 'img/logo-cardata.png', false, 'alt="Cardata" class="img-fluid"') ?>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <?php if (isLoggedIn()): ?>
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="/library/theme-lte/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
                </div>

                <div class="info">
                    <a href="<?= route_to('user_detail', session()->get('user_id')) ?>" class="d-block"><?= session()->get('currentUser_first_name').' '.session()->get('currentUser_last_name')?></a>
                </div>
            </div>
        <?php endif; ?>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <?php if ($sidebar_nav && is_array($sidebar_nav) && count($sidebar_nav) > 0): ?>
                    <?php foreach ($sidebar_nav as $k_nav => $nav): ?>
                        <?php $active = getSegment(1) == $k_nav ? ' active' : ''; ?>
                        <?php if (isset($nav['sub_nav'])): ?>
                            <li class="nav-item has-treeview">
                                <a href="#" class="nav-link<?= isset($nav['class']) ? ' ' . $nav['class'] : '' ?><?= $active ?>">
                                    <?= $nav['icon'] ?>
                                    <p>
                                        <?= trad($nav['name'], 'global') ?>
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview sub-nav rounded-bottom">
                                    <?php foreach ($nav['sub_nav'] as $k_sub_nav => $sub_nav) : ?>
                                        <li class="nav-item">
                                            <a href="<?= $sub_nav['url'] ?>" class="nav-link<?= isset($sub_nav['class']) ? ' ' . $sub_nav['class'] : '' ?>">
                                                <?= $sub_nav['icon'] ?>
                                                <p><?= trad($sub_nav['name'], 'global') ?></p>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        <?php else: ?>
                            <li class="nav-item has-treeview<?= $active ?>">
                                <a href="<?= $nav['url'] ?>" class="nav-link<?= isset($nav['class']) ? ' ' . $nav['class'] : '' ?><?= $active ?>">
                                    <?= $nav['icon'] ?>
                                    <p><?= trad($nav['name'], 'global') ?></p>
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>