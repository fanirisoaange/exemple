<div class="wrapper">
    <nav class="main-header navbar navbar-expand navbar-dark">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <!--        <li class="nav-item d-none d-sm-inline-block">
                        <a href="/" class="nav-link">Home</a>
                    </li>
                    <li class="nav-item d-none d-sm-inline-block">
                        <a href="#" class="nav-link">Contact</a>
                    </li>-->
        </ul>

        <!-- SEARCH FORM -->
        <!--    <form class="form-inline ml-3">
                <div class="input-group input-group-sm">
                    <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                    <div class="input-group-append">
                        <button class="btn btn-navbar" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>-->

        <!-- Dropdown companies -->
        <div class=" col-sm-4 ml-2" id="userMainCompaniesHeader" data-url="<?= route_to('user_main_companies_header',$mainCompaniesOverride ?? 0); ?>"></div>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">           
            <!-- Notifications Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-bell"></i>
                    <span class="badge badge-warning navbar-badge all_notif">0</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-item dropdown-header"><span class="all_notif">0</span> Notifications</span>
                    <div class="dropdown-divider"></div>
                    <a href="/inbox" class="dropdown-item d-none">
                        <i class="fas fa-envelope mr-2"></i> <span class="msg_count">0</span> new messages
                        <span class="float-right text-muted text-xs ago_txt_msg"></span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="/order/list" class="dropdown-item d-none">
                        <i class="fas fa-users mr-2 "></i> <span class="order_notif">0</span> new Commande
                        <span class="float-right text-muted text-xs ago_txt_order"></span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="/Notification/all" class="dropdown-item dropdown-footer"><?php trad('See All ', 'global'); ?></a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="flag-icon flag-icon-<?= session('lang') ?>"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right p-0">
                    <?php foreach ($languages as $lang_abbr => $lang): ?>
                        <a href="/functions/changeLanguage/<?= $lang_abbr ?>" class="dropdown-item">
                            <i class="flag-icon flag-icon-<?= $lang_abbr ?> mr-2"></i> <?= trad($lang['label'], 'global') ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= route_to('logout') ?>">
                    <i class="fas fa-unlock-alt"></i>
                </a>
            </li>
        </ul>
    </nav>
