<?php
if (!empty($company_msg)):
    switch ($company_msg['status']) :
        case 'success':
            $alert_class = 'success';
            break;
        case 'error':
            $alert_class = 'danger';
            break;
        default:
            $alert_class = 'info';
            break;
    endswitch;
    ?>
    <div class="alert alert-<?= $alert_class; ?> alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?= $company_msg['msg']; ?>
    </div>
<?php endif; ?>
<?= form_open_multipart() ?>
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title"><?= $form_title; ?></h3>
    </div>
    <div class="card-body">
        <div id="formCompany">
            <?= $form_company; ?>
        </div>
    </div>
</div>
<div class="card card-primary" id="formCompanyBilling" style="display:none;">
    <div class="card-header">
        <h3 class="card-title"><?= trad('Billing address', 'company'); ?></h3>
    </div>
    <div class="card-body">
        <?= $form_compay_billing; ?>
    </div>
</div>
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title"><?= trad('Import IRIS file'); ?></h3>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label for="">Upload CSV file</label>  <a href="<?= base_url('assets/uploads/example.csv') ?>">example</a> <br>
            <input type="file" name="file" required>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-footer">
        <button type="submit" class="btn btn-success float-right"><?= trad('Save', 'global'); ?></button>
    </div>
</div>
<?= form_close() ?>