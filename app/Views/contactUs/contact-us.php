<div class="row">
    <div class="col-xl-12" id="tabsCampaignContainer">
        <div class="card card-primary">
            <div class="card-header p-2" style="height: 53px;">
                <h5 class="text-center text-white"><?= trad('Contact Us') ?></h5>
            </div>
            <div class="card-body">
                <div class="tab-content" id="tabsContent">
                    <div class="tab-pane fade show active" id="tabCampaign" role="tabpanel"
                         aria-labelledby="navTabCampaign">
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header"><?= trad("Please contact us for your request") ?></div>
                                    <div class="card-body">
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
<!--                                             <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="<?//= $form['type']['field']; ?>"><?//= $form['type']['label']; ?></label>
                                                    <?//= form_dropdown($form['type']['field'], $form['type']['options'], $form['type']['post'], 'class="custom-select" id="dopdownCampaignType" required'); ?>
                                                </div>
                                            </div> -->
<!--                                             <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for=""><?//= $form['model_economique']['label']; ?></label>
                                                    <?//= form_dropdown($form['model_economique']['field'], $form['model_economique']['options'], $form['model_economique']['post'], 'class="custom-select select2" required'); ?>
                                                </div>
                                            </div> -->
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <?php if ($companyId && canCreateOrder()) { ?>
                                                        <?php companySelector(true, 'col-12', false); ?>
                                                        <?= form_hidden('company', $companyId ? $companyId : '') ?>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for=""><?= trad("Your message") ?></label>
                                                    <?= form_textarea($form['message']['field'], $form['message']['post'], 'class="form-control", placeholder="Leave your message here"'); ?>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for=""><?= trad("Your email address") ?></label>
                                                    <?= form_input('email', '', 'class="form-control"'); ?>
                                                </div>
                                            </div>
                                        </div>

                                        <?php if (!empty($created_date) || !empty($updated_date)): ?>
                                            <div class="row p-2 text-muted text-right">
                                                <div class="col-sm-12">
                                                    <small><b><?= trad('Created', 'global'); ?>
                                                            :</b> <?= !empty($created_date) ? format_date($created_date, 'd/m/Y', true) : ''; ?>
                                                        <b><?= trad('Updated', 'global'); ?>
                                                            :</b> <?= !empty($updated_date) ? format_date($updated_date, 'd/m/Y', true) : ''; ?>
                                                    </small>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <div class="row">
                                            <div class="col-sm-12 pt-2">
                                                <button type="submit"
                                                        class="btn btn-success btnSubmitCampaign float-right"><?= trad('Send'); ?></button>
                                            </div>
                                        </div>

                                        <?= form_close() ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>
