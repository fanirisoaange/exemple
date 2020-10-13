<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <label for="<?= $form['main_id']['field']; ?>"><?= $form['main_id']['label']; ?></label>
            <?= form_dropdown($form['main_id']['field'], $form['main_id']['options'], $form['main_id']['post'], 'class="custom-select select2" id="dopdownMainID" data-url="' . route_to('children_companies') . '"'); ?>
            <?= $validation->showError($form['main_id']['field']) ?>
        </div>
    </div>
    <div class="col-sm-8" id="parentCompany">
        <div class="form-group">
            <label for="<?= $form['parent_id']['field']; ?>"><?= $form['parent_id']['label']; ?></label>
            <?= form_dropdown($form['parent_id']['field'], $form['parent_id']['options'], $form['parent_id']['post'], 'class="custom-select select2" id="dopdownParentID" '); ?>
            <?= $validation->showError($form['parent_id']['field']) ?>
        </div>
    </div>

</div>
<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="<?= $form['fiscal_name']['field']; ?>"><?= $form['fiscal_name']['label']; ?></label>
            <?= form_input($form['fiscal_name']['field'], $form['fiscal_name']['post'], 'class="form-control"'); ?>
            <?= $validation->showError($form['fiscal_name']['field']) ?>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group ">
            <label for="<?= $form['commercial_name']['field']; ?>"><?= $form['commercial_name']['label']; ?></label>                    
            <?= form_input($form['commercial_name']['field'], $form['commercial_name']['post'], 'class="form-control"'); ?>
            <?= $validation->showError($form['commercial_name']['field']) ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="form-group ">
            <label><?= $form['address_1']['label']; ?></label>
            <?= form_textarea($form['address_1']['field'], $form['address_1']['post'], 'class="form-control" required'); ?>
            <?= $validation->showError($form['address_1']['field']) ?>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group ">
            <label><?= $form['address_2']['label']; ?></label>
            <?= form_textarea($form['address_2']['field'], $form['address_2']['post'], 'class="form-control"'); ?>
            <?= $validation->showError($form['address_2']['field']) ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-1">
        <div class="form-group">
            <label for="<?= $form['zip_code']['field']; ?>"><?= $form['zip_code']['label']; ?></label>
            <?= form_input($form['zip_code']['field'], $form['zip_code']['post'], 'class="form-control" required'); ?>
            <?= $validation->showError($form['zip_code']['field']) ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group ">
            <label for="<?= $form['city']['field']; ?>"><?= $form['city']['label']; ?></label>
            <?= form_input($form['city']['field'], $form['city']['post'], 'class="form-control" required'); ?>
            <?= $validation->showError($form['city']['field']) ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group ">
            <label for="<?= $form['city_display']['field']; ?>"><?= $form['city_display']['label']; ?></label>
            <?= form_input($form['city_display']['field'], $form['city_display']['post'], 'class="form-control"'); ?>
            <?= $validation->showError($form['city_display']['field']) ?>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <label for="<?= $form['country_id']['field']; ?>"><?= $form['country_id']['label']; ?></label>
            <?= form_dropdown($form['country_id']['field'], $form['country_id']['options'], $form['country_id']['post'], 'class="custom-select select2" required'); ?>
            <?= $validation->showError($form['country_id']['field']) ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-9">
        <div class="form-group ">
            <label for="<?= $form['vat_number']['field']; ?>"><?= $form['vat_number']['label']; ?></label>
            <?= form_input($form['vat_number']['field'], $form['vat_number']['post'], 'class="form-control"'); ?>
            <?= $validation->showError($form['vat_number']['field']) ?>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <label for="<?= $form['vat']['field']; ?>"><?= $form['vat']['label']; ?></label>
            <?= form_dropdown($form['vat']['field'], $form['vat']['options'], $form['vat']['post'], 'class="custom-select select2" required'); ?>
            <?= $validation->showError($form['vat']['field']) ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <label for="<?= $form['phone_number']['field']; ?>"><?= $form['phone_number']['label']; ?></label>
            <?= form_input($form['phone_number']['field'], $form['phone_number']['post'], 'class="form-control"'); ?>
            <?= $validation->showError($form['phone_number']['field']) ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label for="<?= $form['email']['field']; ?>"><?= $form['email']['label']; ?></label>
            <?= form_input($form['email']['field'], $form['email']['post'], 'class="form-control"'); ?>
            <?= $validation->showError($form['email']['field']) ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label for="<?= $form['website']['field']; ?>"><?= $form['website']['label']; ?></label>
            <?= form_input($form['website']['field'], $form['website']['post'], 'class="form-control" placeholder="HTTP://"'); ?>
            <?= $validation->showError($form['website']['field']) ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <label for="<?= $form['dealer_ship_id']['field']; ?>"><?= $form['dealer_ship_id']['label']; ?></label>
            <?= form_input($form['dealer_ship_id']['field'], $form['dealer_ship_id']['post'], 'class="form-control"'); ?>
            <?= $validation->showError($form['dealer_ship_id']['field']) ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label for="<?= $form['site_number']['field']; ?>"><?= $form['site_number']['label']; ?></label>
            <?= form_input($form['site_number']['field'], $form['site_number']['post'], 'class="form-control"'); ?>
            <?= $validation->showError($form['site_number']['field']) ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label for="<?= $form['orias']['field']; ?>"><?= $form['orias']['label']; ?></label>
            <?= form_input($form['orias']['field'], $form['orias']['post'], 'class="form-control"'); ?>
            <?= $validation->showError($form['orias']['field']) ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <div class="form-check">
                <input type="checkbox" value="1" name ="billing"  class="form-check-input" id="companyBilling" <?= !empty($form['billing']['post']) ? 'checked' : ''; ?>>
                <label class="form-check-label"><?= trad('Use as billing address', 'company'); ?></label>
            </div>
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
<div class="row">
    <div class="col-sm-3">
        <div class="form-group">
            <label for="<?= $form['status']['field']; ?>"><?= $form['status']['label']; ?></label>
            <?= form_dropdown($form['status']['field'], $form['status']['options'], $form['status']['post'], 'class="custom-select select2"'); ?>
            <?= $validation->showError($form['status']['field']) ?>
        </div>
    </div>
</div>
<?php if (!empty($created_date) || !empty($updated_date)): ?>
    <div class="row p-2 text-muted text-right">
        <div class="col-sm-12">
            <small><b><?= trad('Created', 'global'); ?>:</b> <?= !empty($created_date) ? format_date($created_date, 'd/m/Y', true) : ''; ?> <b><?= trad('Updated', 'global'); ?>:</b> <?= !empty($updated_date) ? format_date($updated_date, 'd/m/Y', true) : ''; ?></small>
        </div>
    </div>
<?php endif; ?>