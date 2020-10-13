<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title"><?= trad('Blacklist File'); ?></h3>
    </div>
    <div class="card-body">
        <div class="row border-bottom p-2">
            <div class="col-sm-2">
                <b>ID</b>
            </div>
            <div class="col-sm-10">
                <?= $blacklistFile["id"]; ?>
            </div>
        </div>
        <div class="row border-bottom p-2">
            <div class="col-sm-2">
                <b><?= trad('Name'); ?></b>
            </div>
            <div class="col-sm-10">
                <?= $blacklistFile["name"]; ?>
            </div>
        </div>
        <div class="row border-bottom p-2">
            <div class="col-sm-2">
                <b><?= trad('Number of lines'); ?></b>
            </div>
            <div class="col-sm-10">
                <?= blacklistFileNbline($blacklistFile); ?>
            </div>
        </div>
        <div class="row border-bottom p-2">
            <div class="col-sm-2">
                <b><?= trad('Status'); ?></b>
            </div>
            <div class="col-sm-10">
                <?php
                $blacklistFileStatus = (int)$blacklistFile["status"];
                $status = trad(
                    \App\Enum\BlacklistFileStatus::getDescriptionById(
                        $blacklistFileStatus
                    )
                );
                ?>
                <?php blacklistFileStatus($blacklistFileStatus); ?>
            </div>
        </div>
        <div class="row p-2 text-muted text-right">
            <div class="col-sm-12">
                <small><b><?= trad('Uploaded'); ?>
                        :</b> <?= $blacklistFile['upload_date']; ?></small>
                <?php if($blacklistFile['send_date']) { ?> <br><small><b><?= trad('Sent'); ?>
                        : </b><?= $blacklistFile['send_date']; ?></small> <?php } ?>
            </div>
        </div>
    </div>
</div>

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title"><?= trad('Company'); ?></h3>
    </div>
    <div class="card-body">
        <div class="row border-bottom p-2">
            <div class="col-sm-2">
                <b><?= trad('Fiscal name'); ?></b>
            </div>
            <div class="col-sm-10">
                <?= $blacklistFile["fiscal_name"]; ?>
            </div>
        </div>
        <div class="row border-bottom p-2">
            <div class="col-sm-2">
                <b><?= trad('Commercial name'); ?></b>
            </div>
            <div class="col-sm-10">
                <?= $blacklistFile["commercial_name"]; ?>
            </div>
        </div>
        <div class="row border-bottom p-2">
            <div class="col-sm-2">
                <b><?= trad('Address'); ?></b>
            </div>
            <div class="col-sm-10">
                <?= $blacklistFile["address_1"].' '.$blacklistFile["address_2"]; ?>
                <?= ', '.$blacklistFile["city"]; ?>
                <?= ', '.$blacklistFile["zip_code"]; ?>
                <?= $blacklistFile["city_display"]; ?>
            </div>
        </div>
    </div>
</div>

<div class="card card-primary">
    <div class="card-footer">
        <?php if ($blacklistFileStatus == \App\Enum\BlacklistFileStatus::ACTIF) { ?>
            <a href="#" class="btn btn-app"
               onclick="ajaxBlacklistFileSend(event, <?php echo $blacklistFile["id"]; ?>, '<?php echo trad(
               "Are you sure you want to send this blacklistFile ?"
               ); ?>', '<?php echo trad(
                   "Send success"
               ); ?>', '<?php echo trad(
                   " imported lines"
               ); ?>', '<?php echo trad(
                   "Send fail"
               ); ?>');"
            >
                <i class="fas fa-envelope"></i> <?php echo trad('Send') ?>
            </a>
        <?php } ?>
    </div>
</div>