<?= form_open('', ['action' => 'content', 'id' => 'campaignForm']) ?>
<div id="ajax-content">
    <?php foreach(explode(',', $channels) as $channel): ?>
        <?= view("campaigns/partials/content.php", [
            'content' => $content,
            'channel' => $channel
        ]); ?>
    <?php endforeach; ?>
    <?= form_hidden("channels", $channels) ?>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <button type="submit" class="btn btn-success float-right">Next</button>
        </div>
    </div>
</div>
<?= form_close() ?>

