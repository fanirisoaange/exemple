<?= form_open('', ['id' => 'formCampaign'], ['action' => 'createCampaign']) ?>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <label for="<?= $form['name']['field']; ?>"><?= $form['name']['label']; ?></label>
            <?= form_input($form['name']['field'], $form['name']['post'], 'class="form-control"'); ?>
            <?= form_hidden('error', trad('Please fill all field')) ?>
        </div>
    </div>
</div>
<div class="row">
    <!-- <div class="col-sm-12"> -->
        <!-- <div class="form-group"> -->
            <!-- <label for="<?//= $form['type']['field']; ?>"><?//= $form['type']['label']; ?></label> -->
            <!-- <?//= form_dropdown($form['type']['field'], $form['type']['options'], $form['type']['post'], 'class="custom-select" id="dopdownCampaignType" required'); ?> -->
<!--            <div style="display:none" id="checkboxAdhesion">-->
<!--                <br>-->
<!--                <label for="adhesion">--><?//= trad('Compulsory membership', 'campaign'); ?><!--</label>-->
<!--                <input type="checkbox" name="adhesion" value="1" data-bootstrap-switch data-off-color="danger" data-on-color="success" data-on-text="--><?//= trad('Yes', 'global'); ?><!--" data-off-text="--><?//= trad('No', 'global'); ?><!--">-->
<!--            </div>-->
        <!-- </div> -->
    <!-- </div> -->
    <!-- <div class="col-sm-12"> -->
        <!-- <div class="form-group"> -->
            <!-- <label for=""><?//= $form['model_economique']['label']; ?></label> -->
            <!-- <?//= form_dropdown($form['model_economique']['field'], $form['model_economique']['options'], $form['model_economique']['post'], 'class="custom-select select2" required'); ?> -->
        <!-- </div> -->
    <!-- </div> -->
    <div class="col-sm-12">
        <div class="form-group">
            <?php companySelector(true,'col-12', true,false); ?>
            <?= form_hidden('company', $companyId ? $companyId : '') ?>
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

<div class="row pt-4">
    <div class="col-sm-12 pt-2 border-top">
        <button type="submit" class="btn btn-success btnSubmitCampaign float-right"><?= trad('Next', 'global'); ?></button>
    </div>
</div>

<?= form_close() ?>