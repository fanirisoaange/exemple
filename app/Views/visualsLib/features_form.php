<?= form_open('/visualslib/features', ['class' => 'ajax-form-features form-row align-items-center'], ['id_client_feature' => $id_client_feature, 'main_company_id' => $main_company_id]); ?>
<div class="col-md-4 col-6">
    <div class="form-group mb-0">
        <label class="col-form-label-sm mb-0 p-0"><?= trad('Name') ?></label>
        <?= form_input('name', $name, 'class="form-control" data-rules="required"') ?>
    </div>
</div>
<div class="col-6 col-md-5">
    <div class="form-group mb-0">
        <label class="col-form-label-sm mb-0 p-0"><?= trad('Comment') ?></label>
        <?= form_input('comment', $comment, 'class="form-control"') ?>
    </div>
</div>
<div class="col-md-3 col-12">
    <?=
    form_button([
        'name' => 'edit_feature',
        'value' => 1,
        'type' => 'submit',
        'content' => '<i class="far fa-edit"></i> ' . trad('Edit'),
        'class' => 'btn btn-warning btn-block mt-2 mt-md-4'
    ])
    ?>
</div>
<?= form_close(); ?>