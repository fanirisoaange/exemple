<?php if (\App\Enum\campaignChannelType::EMAIL == $channel): ?>
    <div style="border-top: 3px solid #007bff;"
         class="card card-prirary cardutline direct-chat direct-chat-primary direct-chat-contacts-open">
        <div class="card-header" style="background: #FFF;height:40px;">
            <h3 class="card-title"><strong>
                    <?= form_label(trad('Channel', 'channel'), 'channel') ?>
                    <?= form_hidden("channel", $channel); ?>
                    <span class="badge badge-primary"><?= strtoupper(\App\Enum\campaignChannelType::getDescriptionById($channel)) ?></span>
                </strong></h3>
            <div class="card-tools" style="line-height:0px;margin:.3rem -11px;">
                <button type="button" style="color:#adb5bd;" class="btn btn-tool expand" data-card-widget="collapse"><i
                            class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body card-body-content" style="display: block;padding: 15px;">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?= form_label(trad('Sender')) ?><span class="text-red">*</span>
                        <?= form_input('sender', $content ? $content['sender'] : '', 'class="form-control"') ?>
                        <?= form_hidden('error', trad('Please fill ')) ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <?= form_label(trad('Object')) ?><span class="text-red">*</span>
                        <?= form_input('object', $content ? $content['object'] : '', 'class="form-control"') ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <?= form_label(trad('Html')) ?><span class="text-red">*</span>
                        <textarea name="html" id=""
                                  class="form-control <?= \App\Enum\campaignChannelType::getDescriptionById($channel) == "email" ? "summernote" : 'test' ?>"
                                  cols="30" rows="10">
                        <?= $content ? $content['html'] : ""; ?>
                    </textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <?= form_label(trad('Html text')) ?>
                        <textarea name="htmlText" id="" class="form-control" cols="30" rows="10">
                            <?= $content ? $content['html_text'] : ''; ?>
                        </textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div style="border-top: 3px solid #007bff;"
         class="card card-prirary cardutline direct-chat direct-chat-primary direct-chat-contacts-open">
        <div class="card-header" style="background: #FFF;height:40px;">
            <h3 class="card-title"><strong>
                    <?= form_label(trad('Channel', 'channel'), 'channel') ?>
                    <span class="badge badge-primary"><?= strtoupper(\App\Enum\campaignChannelType::getDescriptionById($channel)) ?></span></strong>
            </h3>
            <div class="card-tools" style="line-height:0px;margin:.3rem -11px;">
                <button type="button" style="color:#adb5bd;" class="btn btn-tool expand" data-card-widget="collapse"><i
                            class="fas fa-minus"></i></button>
            </div>
        </div>
        <div class="card-body card-body-content" style="padding: 15px;">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?= form_label(trad('SMS oneclick')) ?>
                        <select name="sms_oneclick" class="form-control select2 smsoneclick" id="">
                            <option value="1" <?php if ( $content && $content['sms_oneclick'] == 1): ?>selected
                                <?php endif; ?>>Oui
                            </option>
                            <option value="0" <?php if ( $content && $content['sms_oneclick'] == 0): ?>selected
                                <?php endif; ?>>Non
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <?= form_label(trad('Mobile expediteur')) ?>
                        <input type="text" name="mobile_expediteur"
                               value="<?= $content ? $content['mobile_expediteur'] : '' ?>" class="form-control">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?= form_label(trad('Mobile message')) ?>
                        <input type="text" name="mobile_message"
                               value="<?= $content ? $content['mobile_message'] : '' ?>" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <?= form_label(trad('Mobile url redirect')) ?><span class="text-red">*</span>
                        <input type="url" name="mobile_url_redirect"
                               value="<?= $content ? $content['mobile_url_redirection'] : '' ?>" class="form-control">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for=""><?= trad("Text") ?></label>
                        <textarea name="text" class="form-control" id="" cols="10" rows="5">
                            <?= $content ? $content['text'] : ''; ?>
                        </textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <span class="text-red">*</span>: <i>
                        <small><?= trad("Required fields"); ?></small>
                    </i>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>