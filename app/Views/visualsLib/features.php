<div class="row">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="card-title">
                    <?= trad('Manage features') ?>
                </h3>
            </div>
            <div id="edit-feature" class="card-body">
                <?php if (!empty($features) && is_array($features) && count($features) > 0): ?>
                    <?php foreach ($features as $k => $v): ?>
                        <?= view('visualsLib/features_form', $v); ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="alert alert-warning" role="alert">
                        <?= trad('There is no feature available') ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card">
            <div class="card-header bg-success">
                <h3 class="card-title">
                    <?= trad('Add feature') ?>
                </h3>
            </div>
            <div class="card-body">
                <?= form_open('', ['class' => 'ajax-form-features']); ?>
                <div class="form-group">
                    <?= form_dropdown($new_feature['id_company']['field'], ['' => trad('Select company')] + $new_feature['id_company']['options'], $new_feature['id_company']['post'], 'class="custom-select select2"') ?>
                    <?= $validation->showError($new_feature['id_company']['field']) ?>
                </div>
                <div class="form-group">
                    <?= form_input($new_feature['name']['field'], $new_feature['name']['post'], 'class="form-control" placeholder="Name"') ?>
                    <?= $validation->showError($new_feature['name']['field']) ?>
                </div>
                <div class="form-group">
                    <?= form_textarea($new_feature['comment']['field'], $new_feature['comment']['post'], 'class="form-control" placeholder="Comment"') ?>
                    <?= $validation->showError($new_feature['comment']['field']) ?>
                </div>
                <?=
                form_button([
                    'name' => 'add_feature',
                    'value' => 1,
                    'type' => 'submit_feature',
                    'content' => '<i class="far fa-plus-square"></i> ' . trad('Add Feature'),
                    'class' => 'btn btn-success btn-block'
                ])
                ?>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>