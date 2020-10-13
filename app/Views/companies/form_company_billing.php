<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="<?= $form['billing_fiscal_name']['field']; ?>"><?= $form['billing_fiscal_name']['label']; ?></label>
            <?= form_input($form['billing_fiscal_name']['field'], $form['billing_fiscal_name']['post'], 'class="form-control" '); ?>
            <?= $validation->showError($form['billing_fiscal_name']['field']) ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="form-group ">
            <label><?= $form['billing_address_1']['label']; ?></label>
            <?= form_textarea($form['billing_address_1']['field'], $form['billing_address_1']['post'], 'class="form-control" '); ?>
            <?= $validation->showError($form['billing_address_1']['field']) ?>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group ">
            <label><?= $form['billing_address_2']['label']; ?></label>
            <?= form_textarea($form['billing_address_2']['field'], $form['billing_address_2']['post'], 'class="form-control"'); ?>
            <?= $validation->showError($form['billing_address_2']['field']) ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-1">
        <div class="form-group">
            <label for="<?= $form['billing_zip_code']['field']; ?>"><?= $form['billing_zip_code']['label']; ?></label>
            <?= form_input($form['billing_zip_code']['field'], $form['billing_zip_code']['post'], 'class="form-control" '); ?>
            <?= $validation->showError($form['billing_zip_code']['field']) ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group ">
            <label for="<?= $form['billing_city']['field']; ?>"><?= $form['billing_city']['label']; ?></label>
            <?= form_input($form['billing_city']['field'], $form['billing_city']['post'], 'class="form-control" '); ?>
            <?= $validation->showError($form['billing_city']['field']) ?>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <label for="<?= $form['billing_country_id']['field']; ?>"><?= $form['billing_country_id']['label']; ?></label>
            <?= form_dropdown($form['billing_country_id']['field'], $form['billing_country_id']['options'], $form['billing_country_id']['post'], 'class="custom-select select2" '); ?>
            <?= $validation->showError($form['billing_country_id']['field']) ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-9">
        <div class="form-group ">
            <label for="<?= $form['billing_vat_number']['field']; ?>"><?= $form['billing_vat_number']['label']; ?></label>
            <?= form_input($form['billing_vat_number']['field'], $form['billing_vat_number']['post'], 'class="form-control"'); ?>
            <?= $validation->showError($form['billing_vat_number']['field']) ?>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <label for="<?= $form['billing_vat']['field']; ?>"><?= $form['billing_vat']['label']; ?></label>
            <?= form_dropdown($form['billing_vat']['field'], $form['billing_vat']['options'], $form['billing_vat']['post'], 'class="custom-select select2" '); ?>
            <?= $validation->showError($form['billing_vat']['field']) ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <label for="<?= $form['billing_phone_number']['field']; ?>"><?= $form['billing_phone_number']['label']; ?></label>
            <?= form_input($form['billing_phone_number']['field'], $form['billing_phone_number']['post'], 'class="form-control"'); ?>
            <?= $validation->showError($form['billing_phone_number']['field']) ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label for="<?= $form['billing_email']['field']; ?>"><?= $form['billing_email']['label']; ?></label>
            <?= form_input($form['billing_email']['field'], $form['billing_email']['post'], 'class="form-control"'); ?>
            <?= $validation->showError($form['billing_email']['field']) ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group ">
            <label><?= $form['billing_comments']['label']; ?></label>
            <?= form_textarea($form['billing_comments']['field'], $form['billing_comments']['post'], 'class="form-control"'); ?>
            <?= $validation->showError($form['billing_comments']['field']) ?>
        </div>
    </div>
</div>
<?php if (!empty($billing_created_date) || !empty($billing_updated_date)): ?>
    <div class="row p-2 text-muted text-right">
        <div class="col-sm-12">
            <small><b><?= trad('Created', 'global'); ?>:</b> <?= !empty($billing_created_date) ? format_date($billing_created_date, 'd/m/Y', true) : ''; ?> <b><?= trad('Updated', 'global'); ?>:</b> <?= !empty($billing_updated_date) ? format_date($billing_updated_date, 'd/m/Y', true) : ''; ?></small>
        </div>
    </div>
<?php endif; ?>