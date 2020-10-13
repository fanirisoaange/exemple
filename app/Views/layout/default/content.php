<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="head-title"><?= $page_title ?></h1>
                </div>
                <div class="col-sm-6">
                    <?php if ($breadcrumb && is_array($breadcrumb) && count($breadcrumb) > 0): ?>
                        <ol class="breadcrumb float-sm-right">
                            <?php
                            $i = 1;
                            $last = count($breadcrumb);

                            ?>
                            <?php foreach ($breadcrumb as $link => $item) : ?>
                                <?php if ($i == $last): ?>
                                    <li class="breadcrumb-item active"><?= $item ?></li>
                                <?php else : ?>
                                    <li class="breadcrumb-item"><a href="<?= $link ?>"><?= $item ?></a></li>
                                <?php endif; ?>
                                <?php $i++; ?>
                        <?php endforeach; ?> 
                        </ol>
                        <?php endif; ?>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <?php if ($view): ?>
                <?= view($view) ?>
<?php endif; ?>
        </div>
    </section>
    <!-- /.content -->
</div>