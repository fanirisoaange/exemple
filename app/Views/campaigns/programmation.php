<div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <span style="color:#000;"><?= trad("Create programmation") ?></span>
            </div>
            <div class="card-body">
                <form action="<?= route_to('create_programmation', $campaignId) ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for=""><?= trad("Name") ?></label>
                        <input type="text" name="name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for=""><?= trad("Type programmation") ?></label>
                        <input type="text" name="type" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for=""><?= trad("Volume") ?></label>
                        <input type="number" name="volume" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for=""><?= trad("Repoussoir") ?></label>
                        <select name="repoussoir[]" class="form-control select2" multiple>
                            <option value="<?= \App\Enum\Repoussoir::LOCAL ?>"><?= trad("Local") ?></option>
                            <option value="<?= \App\Enum\Repoussoir::NATIONAL ?>"><?= trad("National") ?></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for=""><?= trad("File crypt") ?></label>
                        <input type="file" name="file">
                    </div>
                    <div class="form-group">
                        <label for=""><?= trad("Canal") ?></label>
                        <div>
                            <input type="checkbox" name="canalEmail" id="canalEmail" value="<?= \App\Enum\CampaignCanalType::EMAIL ?>"> Email
                            <input type="checkbox" name="canalMobile" id="canalMobile" value="<?= \App\Enum\CampaignCanalType::MOBILE ?>"> Mobile
                        </div>
                    </div>
                    <!-- for email -->
                    <div class="canal canalEmail">
                        <div class="form-group">
                            <label for=""><?= trad("Custom field") ?></label>
                            <input type="text" name="customField" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for=""><?= trad("HTML") ?></label>
                            <textarea name="html" class="form-control summernote" id="" cols="30" rows="10"></textarea>
                        </div>
                        <div class="form-group">
                            <label for=""><?= trad("Object") ?></label>
                            <input type="text" name="objet" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for=""><?= trad("Sender") ?></label>
                            <input type="text" name="sender" class="form-control">
                        </div>
                    </div>

                    <!-- for mobile -->
                    <div class="canal canalMobile">
                        <div class="form-group">
                            <label for=""><?= trad("SMS oneclick") ?></label>
                            <div>
                                <input type="checkbox" name="sms" value="1"> <?= trad("Yes") ?>
                                <input type="checkbox" name="sms" value="0"> <?= trad("No") ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for=""><?= trad("Mobile sender") ?></label>
                            <input type="number" name="mobileSender" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for=""><?= trad("Mobile message") ?></label>
                            <input type="text" name="mobileMessage" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for=""><?= trad("Mobile url redirection") ?></label>
                            <input type="url" name="mobileUrlRedirection" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"><?= trad("Create") ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-3"></div>
</div>