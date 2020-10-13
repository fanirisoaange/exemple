<div class="invoice p-3 mb-4">
    <?php if ((int)$_SESSION['current_main_company'] === 0): ?>
        <?php echo trad(
            'Please select the company for which to display the blacklist File upload'
        ) ?>
    <?php else: ?>
        <div class="row">
            <div class="col-12 invoice-col" id="divCompany">
                <?php echo trad('Company') ?>
                <?php companySelector(false); ?>
                <?php $currentCompany = model('CompanyModel')->selectCompany((int)$_SESSION['current_sub_company']); ?>
                <address id="companySelected" data-id="<?php echo $currentCompany['id'] ?>">
                    <strong><?php echo $currentCompany['fiscal_name'] ?></strong><br>
                    <?php echo $currentCompany['address_1'] . ' ' . $currentCompany['address_2'] ?><br>
                    <?php echo $currentCompany['zip_code'] . ' ' . $currentCompany['city'] ?><br>
                    <?php echo trad('Phone') ?>: <?php echo $currentCompany['phone_number'] ?><br>
                    <?php if ($currentCompany['email'] !='') echo trad('Email : ') . $currentCompany['email'] ?>
                </address>
            </div>
        </div>

        <hr />

        <form method="post" enctype="multipart/form-data" action="<?= route_to('blacklistfile_upload') ?>" 
            idFieldsFiles="files"
            upload_max_filesize="<?php echo ini_get('upload_max_filesize'); ?>"
            msgFilesEmpty="<?php echo trad('The upload field is required'); ?>"
            msgInvalidExtension="<?php echo trad('Please upload CSV files only'); ?>"
            msgInvalidSize="<?php echo trad('The maximum file size to upload is '.ini_get('upload_max_filesize').'o'); ?>"
            msgEmptyName="<?php echo trad('Name field required'); ?>"
        >
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for=""><?= trad('Name'); ?></label>
                        <input type="text" class="form-control" name="name">
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                      <div class="btn btn-default btn-file">
                        <i class="fas fa-paperclip"></i> <?php echo trad('Upload'); ?>
                       <input type="file" id="files" name="files[]" multiple>
                      </div>

                      </div>
                      <p class="help-block">Max. <?php echo ini_get('upload_max_filesize'); ?>B</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 mb-3">
                    <hr />
                    <div class="btn-group  float-right" role="group">
                        <button type="submit" class="btn btn-success" >
                            <i class="fas fa-check"></i> <?php echo trad('Upload') ?>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>
