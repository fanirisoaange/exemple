<?= form_open(route_to('campaign_save'), 'id="formVisual"', ['action' => 'saveVisual']) ?>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <label for="<?= $form['channel_id']['field']; ?>"><?= $form['channel_id']['label']; ?></label>
            <?= form_dropdown($form['channel_id']['field'], $form['channel_id']['options'], $form['channel_id']['post'], 'class="custom-select" id="dopdownCampaignChannelIds" data-url="' . route_to('campaign_get_channel_type') . '" required'); ?>
            <?= $validation->showError($form['channel_id']['field']) ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <label for="<?= $form['campaign_sender']['field']; ?>"><?= $form['campaign_sender']['label']; ?></label>
            <?= form_input($form['campaign_sender']['field'], $form['campaign_sender']['post'], 'class="form-control" required'); ?>
            <?= $validation->showError($form['campaign_sender']['field']) ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <label for="<?= $form['campaign_subject']['field']; ?>"><?= $form['campaign_subject']['label']; ?></label>
            <?= form_input($form['campaign_subject']['field'], $form['campaign_subject']['post'], 'class="form-control" required'); ?>
            <?= $validation->showError($form['campaign_subject']['field']) ?>
        </div>
    </div>
</div>
<div class="row" id="codeVisualContainer">
    <div class="col-sm-12">
        <div class="form-group">
            <label for="<?= $form['campaign_visual']['field']; ?>"><?= $form['campaign_visual']['label']; ?></label>
            <?= form_textarea($form['campaign_visual']['field'], $form['campaign_visual']['post'], 'class="form-control" id="codeVisual"'); ?>
            <?= $validation->showError($form['campaign_visual']['field']) ?>
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
        <button type="submit" class="btn btn-success float-right"><?= trad('Save', 'global'); ?></button>
    </div>
</div>

<?= form_close() ?>