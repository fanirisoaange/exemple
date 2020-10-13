<?php if (isset($_SESSION['current_main_company']) && $_SESSION['current_main_company']): ?>
<div class="row">
    <div class="col-xl-9" id="tabsCampaignContainer">
        <div class="card card-primary card-tabs">
            <div class="card-header p-2">
                <ul class="nav nav-tabs" id="navTabsCampaign" role="tablist">
                    <?php $segment = getSegment(2); ?>
                    <?php $steps = campaignWorkflow($status, $campaignId, $segment); ?>
                    <?php if ($steps): ?>
                        <?php foreach ($steps as $step): ?>
                            <li class="nav-item">
                                <a class="nav-link <?= $step['status']; ?> <?= $step['activeLink']; ?>" href="<?= $step['href']; ?>">
                                    <i class="<?= $step['icon']; ?>"></i><br>
                                    <?= $step['label']; ?>
                                    <?= $step['status'] == "enabled" ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>' ?>
                                </a>
                            </li>
                        <?php endforeach ?>
                    <?php endif ?>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="tabsContent">
                    <div class="tab-pane fade show active" id="tabCampaign" role="tabpanel" aria-labelledby="navTabCampaign">
                        <?= $template; ?>
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
    <div class="col-xl-3">
        <div class="card not-valide card-validation">
            <div class="card-header">
                <span class="nav-link" href="<?= route_to('create_validation') ?>">
                    <i class="fas fa-paper-plane"></i><br>
                    <?= trad('Validation', 'campaign'); ?>
                </span>
            </div>
            <div class="card-body" style="position:relative;">
                <div class="loader-validation-campaign">
                    <div class="loader" style="display:none;border-top: 2px solid #000 !important;border:3px solid #ccc;"></div>
                </div>
                <div id="card-validation-ajax">
                    <div class="ajax-loader d-flex justify-content-center" style="flex-direction: column;align-items: center;">
                        <div class="loader"></div>
                        <div class="wait text-center">Please wait ...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
    <div class="card card-primary card-outline">
        <div class="card-body">
            <?= trad("Please select the company for which to display the campaign workflow") ?>
        </div>
    </div>
<?php endif; ?>