<?php if (!empty(Session()->get('current_main_company'))): ?>
    <?php //if (Session()->get('current_main_company') == $form_visual['main_company_id']['post']): ?>
        <hr class="mt-0" />
        <h2><?= $action == 'add' ? trad('Add visual') : trad('Edit visual') ?></h2>
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary"><?= trad('Visual informations') ?></div>
                    <div class="card-body">
                        <?php
                        $hiddens = [
                            'id_user' => session()->get('user_id'),
                            'visibility' => 0,
                            $formVisualCode['parent_category_id']['field'] => $formVisualCode['parent_category_id']['post'],
                            $form_visual['main_company_id']['field'] => $form_visual['main_company_id']['post']
                            ]

                        ?>
                        <?php
                        if ($action == 'edit'):
                            $hiddens += ['id_visual' => $visual['id_visual']];
                        endif;

                        ?>
                        <?= form_open('', ['id' => 'visual-form'], $hiddens) ?>
                        <?= custom_input($form_visual['name'], ['class' => 'form-control']) ?>
                        <?php //if ($action == 'add'): ?>
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
                        <?= custom_input($formVisualCode['sms_url'], ['class' => 'form-control']) ?>
                        <div id="visual-field-textarea" class="visual-field"<?= $display_textarea ?>>

                            <div class="form-group">
                                <?php
                                $classError = '';
                                if (isset($post[$formVisualCode['visual']['field']])):
                                    $classError = $validation->showError($formVisualCode['visual']['field']) ? ' is-invalid' : ' is-valid';
                                endif;

                                ?>
                                <?= form_label(trad($formVisualCode['visual']['label'])); ?>
                                <?=
                                form_button([
                                    'name' => 'fullscreen',
                                    'value' => 1,
                                    'type' => 'button',
                                    'content' => '<i class="fas fa-expand-arrows-alt"></i>',
                                    'class' => 'btn btn-light float-right'
                                ]);

                                ?>
                                <span class="clearfix"></span>
                                <?= form_textarea($formVisualCode['visual']['field'], $formVisualCode['visual']['post'], ['id' => 'visual', 'class' => 'form-control' . $classError] + $disabled_textarea + $formVisualCode['visual']['addID']) ?>
                                <?= $validation->showError($formVisualCode['visual']['field']); ?> 
                                <small class="form-text text-muted"><span id="sms-remaining"></span> <span id="sms-message"></span></small>
                            </div>
                        </div>
                        <div id="visual-field-text" class="visual-field"<?= $display_text ?>>
                            <?= custom_input($formVisualCode['visual'], ['class' => 'form-control'] + $disabled_text) ?>
                        </div>

                        <?php // endif; ?>
                        <?= custom_textarea($form_visual['comment'], ['class' => 'form-control']) ?>
                        <div class="text-right">
                            <?=
                            form_button([
                                'name' => 'add_visual',
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

            </div>
            <div class="col-md-4">
                <div id="visual-preview" class="card">
                    <div class="card-header bg-secondary"><?= trad('Preview') ?></div>
                    <div class="card-body">
                        <div class="preview">
                            <?= ($thumbnail) ? $thumbnail : false; ?>
                        </div>
                    </div>
                    <div class="card-footer">
                        <?=
                        form_button([
                            'name' => 'enlarge-thumb',
                            'id' => 'enlarge-thumb',
                            'type' => 'button',
                            'class' => 'btn btn-secondary',
                            'content' => '<i class="fas fa-search-plus"></i> ' . trad('Enlarge')
                        ]);

                        ?> 
                        <?=
                        form_button([
                            'name' => 'refresh-thumb',
                            'id' => 'refresh-thumb',
                            'type' => 'button',
                            'class' => 'btn btn-warning float-right',
                            'content' => '<i class="fas fa-sync-alt"></i> ' . trad('Refresh')
                        ]);

                        ?> 
                    </div>
                </div>
                <?php if ($action == 'edit'): ?>
                    <div class="card">
                        <div class="card-header bg-secondary"><?= trad('Permissions') ?></div>
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
    <?php //else: ?>
        <div class="card card-danger card-outline">
            <div class="card-body text-danger">
                <?= trad('Forbidden') ?>
            </div>
        </div>
    <?php //endif; ?>
<?php else: ?>
    <div class="card card-primary card-outline">
        <div class="card-body">
            <?= trad('Please select the company for which to create or edit a visual') ?>
        </div>
    </div>
<?php endif; ?>
