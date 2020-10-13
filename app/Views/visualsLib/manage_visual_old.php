<hr class="mt-0" />
<h2><?= $action == 'add' ? trad('Add visual') : trad('Edit visual') ?></h2>
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary"><?= trad('Visual informations') ?></div>
            <div class="card-body">
                <?php $hiddens = $action == 'add' ? ['id_user' => session()->get('user_id'), 'visibility' => 0, $formVisualCode['id_parent_category']['field'] => $formVisualCode['id_parent_category']['post']] : ['id_user' => session()->get('user_id')] ?>
                <?= form_open('', false, $hiddens) ?>
                <?= custom_dropdown($form_visual['id_company'], ['class' => 'select2 custom-select']) ?>
                <?= custom_input($form_visual['name'], ['class' => 'form-control']) ?>
                <?php if ($action == 'add'): ?>
                    <?= custom_dropdown($formVisualCode['id_category'], ['id' => 'visual-id_category', 'class' => 'select2 custom-select']) ?>
                    <?php
                    if ($formVisualCode['visual']['type'] == 'textarea'):
                        $display_textarea = '';
                        $display_text = ' style="display:none"';
                        $disabled_textarea = [];
                        $disabled_text = ['disabled' => 'disabled'];
                    else:
                        $display_textarea = ' style="display:none"';
                        $display_text = '';
                        $disabled_textarea = ['disabled' => 'disabled'];
                        $disabled_text = [];
                    endif;

                    ?>
                    <div id="visual-field-textarea" class="visual-field"<?= $display_textarea ?>>
                        <div class="form-group">
                            <?php
                            $classError = '';
                            if (isset($post[$formVisualCode['visual']['field']])):
                                $classError = $validation->showError($formVisualCode['visual']['field']) ? ' is-invalid' : ' is-valid';
                            endif;

                            ?>
                            <?= form_label(trad($formVisualCode['visual']['label'])); ?>
                            <?= form_textarea($formVisualCode['visual']['field'], $formVisualCode['visual']['post'], ['class' => 'form-control' . $classError] + $disabled_textarea + $formVisualCode['visual']['addID']) ?>
                            <?= $validation->showError($formVisualCode['visual']['field']); ?> 
                            <small class="form-text text-muted"><span id="sms-remaining"></span> <span id="sms-message"></span></small>
                        </div>
                    </div>
                    <div id="visual-field-text" class="visual-field"<?= $display_text ?>>
                        <?= custom_input($formVisualCode['visual'], ['class' => 'form-control'] + $disabled_text) ?>
                    </div>
                <?php else: ?>
                <?php endif; ?>
                <?= custom_textarea($form_visual['comment'], ['class' => 'form-control']) ?>
                <div class="text-right">
                    <?=
                    form_button([
                        'name' => $action == 'add' ? 'add_visual' : 'edit_visual',
                        'value' => 1,
                        'type' => 'submit',
                        'content' => $action == 'add' ? '<i class="far fa-plus-square"></i> ' . trad('Add visual') : '<i class="far fa-edit"></i> ' . trad('Edit visual'),
                        'class' => 'btn btn-success'
                    ]);

                    ?>
                </div>
                <?= form_close() ?>
            </div>
        </div>
        <?php if ($action == 'edit'): ?>
            <div class="card">
                <div class="card-header bg-primary"><?= trad('Visual Code / Url') ?></div>
                <div class="card-body">
                    <?= form_open('', false, [$formVisualCode['id_parent_category']['field'] => $formVisualCode['id_parent_category']['post']]) ?>
                    <?= custom_dropdown($formVisualCode['id_category'], ['id' => 'visual-id_category', 'class' => 'select2 custom-select']) ?>
                    <?php
                    if ($formVisualCode['visual']['type'] == 'textarea'):
                        $display_textarea = '';
                        $display_text = ' style="display:none"';
                        $disabled_textarea = [];
                        $disabled_text = ['disabled' => 'disabled'];
                    else:
                        $display_textarea = ' style="display:none"';
                        $display_text = '';
                        $disabled_textarea = ['disabled' => 'disabled'];
                        $disabled_text = [];
                    endif;

                    ?>
                    <div id="visual-field-textarea" class="visual-field"<?= $display_textarea ?>>
                        <?= custom_textarea($formVisualCode['visual'], ['class' => 'form-control'] + $disabled_textarea) ?>
                    </div>
                    <div id="visual-field-text" class="visual-field"<?= $display_text ?>>
                        <?= custom_input($formVisualCode['visual'], ['class' => 'form-control'] + $disabled_text) ?>
                    </div>
                    <div class="text-right">
                        <?=
                        form_button([
                            'name' => 'edit_code',
                            'value' => 1,
                            'type' => 'submit',
                            'content' => '<i class="far fa-edit"></i> ' . trad('Edit code / url'),
                            'class' => 'btn btn-success'
                        ]);

                        ?>
                    </div>

                    <?= form_close() ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <div class="col-md-4">
        <div id="visual-preview" class="card">
            <div class="card-header bg-secondary"><?= trad('Preview') ?></div>
            <div class="card-body">
                <?=
                form_open('', ['id' => 'form-preview'], [
                    'name' => '',
                    'id_visual' => '',
                    'id_category' => '',
                    'visual' => ''
                ]);

                ?>
                <?php form_close(); ?>
                <div class="preview">
                    <?php if (!empty($visual['thumbnail'])) : ?>
                        <a href="/visuals/thumbnails/<?= $visual['thumbnail'] ?>" data-fancybox>
                            <img src="/visuals/thumbnails/<?= $visual['thumbnail'] ?>" class="img-fluid" />
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php if ($action == 'edit'): ?>
            <div class="card">
                <div class="card-header bg-secondary"><?= trad('Visibility') ?></div>
                <div class="card-body">
                    <?= form_open('', false, ['id_user' => '']) ?>
                    <?= custom_dropdown($form_visibility['visibility'], ['class' => 'custom-select']) ?>
                    <?= form_close() ?>
                </div>
            </div>
            <?php if (!empty($form_features) && is_array($form_features) && count($form_features) > 0): ?>
                <div class="card">
                    <div class="card-header bg-secondary"><?= trad('Features') ?></div>
                    <div class="card-body">
                        <?php
                        echo form_open('', false, ['id_visual' => $id_visual]); //ajout id_visual_feature pour la modif
                        foreach ($form_features as $k => $v):
                            echo form_hidden('id_client_feature', $k);
                            echo custom_input($v, ['class' => 'form-control']);
                        endforeach;
                        echo form_close();

                        ?> 
                    </div>
                </div>
            <?php endif; ?>

        <?php endif; ?>
    </div>
</div>