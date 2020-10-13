<?php
$router = service('router');
?>
<?php if ($edit): ?>
    <?php if (isset($traductions[$token])): ?>
        <?php if (isset($alert) && is_array($alert)): ?>
            <div class="alert alert-<?= $alert['class'] ?> fade show" role="alert">
                <?= trad($alert['content']) ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <div class="card">
            <h5 class="card-header bg-primary">
                #<?= $traductions[$token]['token'] ?>
            </h5>
            <div class="card-body">
                <?= form_open('', '', ['token' => $token, 'id_zone' => $traductions[$token]['id_zone']]) ?>
                <div class="row">
                    <?php foreach ($traductions[$token]['traductions'] as $lang => $trad) : ?>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label><?= trad($lang) ?></label>
                                <?php $content = isset($trad['content']) ? $trad['content'] : '' ?>
                                <?php $content = isset($post['traductions'][$lang]) ? $post['traductions'][$lang] : $content; ?>
                                <?= form_textarea('traductions[' . $lang . ']', $content, 'class="form-control" rows="3"') ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>


                <?=
                form_button([
                    'type' => 'submit',
                    'name' => 'edit_trad',
                    'content' => trad('Edit traductions'),
                    'value' => 1,
                    'class' => 'btn btn-success float-right'
                ]);
                ?>
                <?= form_close() ?>
                <?= form_open('/administration/traductions', false, ['id_zone' => $traductions[$token]['id_zone']]); ?>
                <?=
                form_button([
                    'type' => 'submit',
                    'name' => 'submit_zone',
                    'content' => trad('Back', 'global'),
                    'value' => 1,
                    'class' => 'btn btn-secondary'
                ]);
                ?>
                                                <!--<a href="/administration/traductions" class="btn btn-secondary"><?= trad('Back', 'global') ?></a>-->
                <?= form_close() ?>
            </div>
        </div>

    <?php endif; ?>
<?php else: ?>

    <div class="card mb-3">               
        <div class="card-body">
            <a href="/administration/traductions_gen" class="btn btn-danger float-right"><i class="fas fa-file-code"></i> <?= trad('Generate traductions files') ?></a>

            <?= form_open('/administration/traductions', ['class' => 'form-row']); ?>
            <div class="col">
                <?= form_dropdown($form_select['id_zone']['field'], $form_select['id_zone']['options'], $form_select['id_zone']['post'], 'class="custom-select"') ?>
                <?= $validation->showError('id_zone') ?>
            </div>
            <div class="col">
                <?=
                form_button([
                    'type' => 'submit',
                    'name' => 'submit_zone',
                    'class' => 'btn btn-primary',
                    'value' => 1,
                    'content' => 'Submit'
                ])
                ?>
            </div>

            <?= form_close(); ?>

        </div>
    </div>

    <div class="card">
        <?php if ($traductions): ?>
            <h5 class="card-header bg-primary">
                <?= isset($form_select['id_zone']['options'][$post['id_zone']]) ? $form_select['id_zone']['options'][$post['id_zone']] : '' ?>
            </h5>
        <?php endif; ?>
        <div class="card-body">
            <?php if ($traductions): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th><?= trad('Content') ?></th>
                            <th><?= trad('Languages') ?></th>
                            <th><?= trad('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($traductions as $token => $default): ?>
                            <tr>
                                <td><?= character_limiter($default['content'], 50, '...') ?></td>
                                <td>
                                    <?php
                                    foreach ($default['traductions'] as $lang => $trad) :
                                        echo '<span class="mr-3">';
                                        echo (!empty($trad['content'])) ? '<i class="fa fa-check-circle text-success"></i> ' . $lang : '<i class="fa fa-times-circle text-danger"></i> ' . $lang;
                                        echo '</span>';
                                    endforeach;
                                    ?>
                                </td>
                                <td><a href="/administration/traductions/<?= $token ?>" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> <?= trad('Edit') ?></a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-warning mb-0" role="alert">
                    <?= trad('No traduction available for the current selection') ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>