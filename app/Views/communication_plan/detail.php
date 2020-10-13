<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title"><?= trad('External campaign'); ?></h3>
    </div>
    <div class="card-body">
        <div class="row border-bottom p-2">
            <div class="col-sm-2">
                <b>ID</b>
            </div>
            <div class="col-sm-10">
                <?= $externalCampaign["id"]; ?>
            </div>
        </div>
        <div class="row border-bottom p-2">
            <div class="col-sm-2">
                <b><?= trad('Name'); ?></b>
            </div>
            <div class="col-sm-10">
                <?= $externalCampaign["name"]; ?>
            </div>
        </div>
        <div class="row border-bottom p-2">
            <div class="col-sm-2">
                <b><?= trad('Start Date'); ?></b>
            </div>
            <div class="col-sm-10">
                <?= date_format(date_create($externalCampaign['startDate']), "d/m/Y h:i"); ?>
            </div>
        </div>
        <div class="row border-bottom p-2">
            <div class="col-sm-2">
                <b><?= trad('End Date'); ?></b>
            </div>
            <div class="col-sm-10">
                <?= date_format(date_create($externalCampaign['endDate']), "d/m/Y h:i"); ?>
            </div>
        </div>
        <div class="row p-2 text-muted text-right">
            <div class="col-sm-12">
                <small><b><?= trad('Created'); ?>
                        :</b> <?= $externalCampaign['createdAt']; ?></small>
            </div>
        </div>
    </div>
</div>