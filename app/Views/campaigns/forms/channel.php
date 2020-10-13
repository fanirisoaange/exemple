<?= form_open('', ['action' => 'channel']) ?>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <?= form_label(trad('Channel', 'channel'), 'channel') ?>
            <?= form_dropdown('channel[]', App\Models\CampaignModel::listChannelTypes(), $channel ? $channel : '','class="form-control select2" id="channel" multiple') ?>
            <small class="help-block"><i><span style="color:red;">*</span><?= trad("Please choose channel") ?></i></small>
            <?= form_hidden('defaultChannel', $channel) ?>
            <?= form_hidden('error', "Please choose channel") ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <button type="submit" class="btn btn-success float-right">Next</button>
        </div>
    </div>
</div>
<?= form_close() ?>