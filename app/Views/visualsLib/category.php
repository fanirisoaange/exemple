<div class="card">
    <div class="card-header bg-primary">
        <h3 class="card-title">
            <?= trad('Manage categories') ?>
        </h3>
    </div>
    <div class="card-body">
        <div class="card shadow-none bg-primary">
            <div class="card-body">
                <?= form_open('', false, ['id_parent' => '0']) ?>
                <div class="row">
                    <div class="col-md-4 col-6">
                        <div class="form-group mb-0">
                            <label class="col-form-label-sm mb-0 p-0"><?= trad('Name') ?></label>
                            <?= form_input('name', '', 'class="form-control" required') ?>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group mb-0">
                            <label class="col-form-label-sm mb-0 p-0"><?= trad('Comment') ?></label>
                            <?= form_input('comment', '', 'class="form-control"') ?>
                        </div>
                    </div>
                    <div class="col-md-2 col-12">
                        <?=
                        form_button([
                            'name' => 'add_cat',
                            'value' => 1,
                            'type' => 'submit',
                            'content' => '<i class="far fa-plus-square"></i> ' . trad('Add Category'),
                            'class' => 'btn btn-success btn-block border border-white mt-2 mt-md-4'
                        ])
                        ?>
                    </div>
                </div>
                <?= form_close() ?>
            </div>
        </div>
        <?php if ($categories): ?>
            <div id="accordion">
                <?php foreach ($categories as $id_cat => $cat): ?>
                    <div class="card shadow-none border">
                        <div class="card-header">
                            <h5 class="font-weight-bold mb-0">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?= $id_cat ?>" class="collapsed d-block text-dark" aria-expanded="false">
                                    <?php $nb_sc = isset($cat['subCategory']) && is_array($cat['subCategory']) ? count($cat['subCategory']) : 0; ?>            
                                    <?= $cat['name'] ?> <em class='text-sm'>(<?= $nb_sc. ' '. trad($nb_sc > 1 ? 'sub categories' : 'sub category') ?> )</em>
                                    <i class="fas fa-chevron-down float-right"></i>
                                </a>
                            </h5>
                        </div>
                        <div id="collapse<?= $id_cat ?>" class="panel-collapse collapse<?= ((!empty($post['id_category']) && $post['id_category'] == $cat['id_category']) ? ' show' : '') ?>">
                            <div class="card-body">
                                <?= form_open('', false, ['id_category' => $cat['id_category']]) ?>
                                <div class="form-row align-items-center mb-3">
                                    <div class="col-md-4 col-6">
                                        <div class="form-group mb-0">
                                            <label class="col-form-label-sm mb-0 p-0"><?= trad('Name') ?></label>
                                            <?= form_input('name', $cat['name'], 'class="form-control" required') ?>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group mb-0">
                                            <label class="col-form-label-sm mb-0 p-0"><?= trad('Comment') ?></label>
                                            <?= form_input('comment', $cat['comment'], 'class="form-control"') ?>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-12">
                                        <?=
                                        form_button([
                                            'name' => 'edit_cat',
                                            'value' => 1,
                                            'type' => 'submit',
                                            'content' => '<i class="far fa-edit"></i> ' . trad('Edit'),
                                            'class' => 'btn btn-warning btn-block border border-white mt-2 mt-md-4'
                                        ])
                                        ?>
                                    </div>
                                </div>
                                <?= form_close(); ?>
                                <h5 class="mb-3"><i class="fas fa-level-down-alt"></i> <?= trad('Sub Categories') ?></h5>
                                <div class="border-left border-primary pl-4 ml-2" style="border-left-width:5px!important">
                                    <div class="card shadow-none bg-primary">
                                        <div class="card-body">
                                            <?= form_open('', false, ['id_parent' => $cat['id_category']]) ?>
                                            <div class="form-row align-items-center">
                                                <div class="col-md-4 col-6">
                                                    <div class="form-group mb-0">
                                                        <label class="col-form-label-sm mb-0 p-0"><?= trad('Name') ?></label>
                                                        <?= form_input('name', '', 'class="form-control" required') ?>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-5">
                                                    <div class="form-group mb-0">
                                                        <label class="col-form-label-sm mb-0 p-0"><?= trad('Comment') ?></label>
                                                        <?= form_input('comment', '', 'class="form-control"') ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-12">
                                                    <?=
                                                    form_button([
                                                        'name' => 'add_cat',
                                                        'value' => 1,
                                                        'type' => 'submit',
                                                        'content' => '<i class="far fa-plus-square"></i> ' . trad('Add Sub Category'),
                                                        'class' => 'btn btn-success btn-block border border-white mt-2 mt-md-4'
                                                    ])
                                                    ?>
                                                </div>
                                                <?= form_close() ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php if (isset($cat['subCategory']) && is_array($cat['subCategory'])): ?>
                                    <?php foreach ($cat['subCategory'] as $id_sc => $sc): ?>
                                        <div class="card shadow-none border bg-light">
                                            <div class="card-body">
                                                <?= form_open('', false, ['id_parent' => $cat['id_category'], 'id_category' => $sc['id_category']]) ?>
                                                <div class="row">
                                                    <div class="col-md-4 col-6">
                                                        <div class="form-group mb-0">
                                                            <label class="col-form-label-sm mb-0 p-0"><?= trad('Name') ?></label>
                                                            <?= form_input('name', $sc['name'], 'class="form-control" required') ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group mb-0">
                                                            <label class="col-form-label-sm mb-0 p-0"><?= trad('Comment') ?></label>
                                                            <?= form_input('comment', $sc['comment'], 'class="form-control"') ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 col-12">
                                                        <?=
                                                        form_button([
                                                            'name' => 'edit_cat',
                                                            'value' => 1,
                                                            'type' => 'submit',
                                                            'content' => '<i class="far fa-edit"></i> ' . trad('Edit'),
                                                            'class' => 'btn btn-warning btn-block mt-2 mt-md-4'
                                                        ])
                                                        ?>

                                                    </div>
                                                </div>
                                                <?= form_close() ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>

        <?php endif; ?>
    </div>
</div>
