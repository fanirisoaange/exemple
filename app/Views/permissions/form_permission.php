
<div class="card m-0 no-border">
    <?= form_open(route_to('groups_permissions_list'), 'id="formPermissionAdd"', ['form_action' => $form['form_action']['post'], 'id' => $form['id']['post']]) ?>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group ">
                    <label for="<?= $form['module']['field']; ?>"><?= $form['module']['label']; ?></label>
                    <?= form_input($form['module']['field'], $form['module']['post'], 'class="form-control"'); ?>
                    <?= $validation->showError($form['module']['field']) ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group ">
                    <label for="<?= $form['action']['field']; ?>"><?= $form['action']['label']; ?></label>
                    <?= form_input($form['action']['field'], $form['action']['post'], 'class="form-control"'); ?>
                    <?= $validation->showError($form['action']['field']) ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group ">
                    <label for="<?= $form['var']['field']; ?>"><?= $form['var']['label']; ?></label>
                    <?= form_input($form['var']['field'], $form['var']['post'], 'class="form-control"'); ?>
                    <?= $validation->showError($form['var']['field']) ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group ">
                    <label><?= $form['comments']['label']; ?></label>
                    <?= form_textarea($form['comments']['field'], $form['comments']['post'], 'class="form-control"'); ?>
                    <?= $validation->showError($form['comments']['field']) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-success float-right"><?= trad('Save', 'global'); ?></button>
    </div>
    <?= form_close() ?>
</div>