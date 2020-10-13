<ul class="list-validation">
    <!-- section campaign -->
    <?php if ($getCampaign['campaign']): ?>
        <li class="active">
            <div class="title-champ d-flex align-items-center">
                <span><i class="fas fa-bullhorn"></i> <?= trad('Campaign', 'campaign'); ?></span>
                <?= campaignIconStatus($getCampaign['campaign']['type']) ?>
            </div>
            <div class="list-value">
                <span>Name:</span> <?= $getCampaign['campaign']['name'] ?><br>
                <span>Type:</span>
                <?= choiceCampaignType($getCampaign['campaign']['type'], 'campaign') ?>
                <br>
                <span>Modèle économique:</span> <?= $getCampaign['campaign']['model_economique'] ?> <br>
                <span>Company:</span>
                <?php foreach ($getCampaign['companies'] as $company): ?>
                    <?= $company; ?>,
                <?php endforeach; ?>
            </div>
        </li>
    <?php endif ?>

    <!-- section channel -->
    <?php if ($getCampaign['channels']): ?>
        <li class="active">
            <div class="title-champ d-flex align-items-center">
                <span><i class="fas fa-envelope"></i> <?= trad('Channel', 'campaign'); ?></span>
                <?= campaignIconStatus(explode(',', $getCampaign['channels']['channel_id'])[0]) ?>
            </div>
            <div class="list-value">
                <span>Channel:</span>
                <?php foreach (explode(',', $getCampaign['channels']['channel_id']) as $channel): ?>
                    <?= choiceCampaignType(intval($channel), 'channel') ?>
                <?php endforeach; ?>
            </div>
        </li>
    <?php endif ?>

    <!-- section segmentation -->
    <?php if ($getCampaign['segmentation']): ?>
        <li class="active">
            <div class="title-champ d-flex align-items-center">
                <span><i class="fas fa-user-cog"></i> <?= trad('Segmentation', 'campaign'); ?></span>
                <?= campaignIconStatus($getCampaign['segmentation'][0]['campaign_id']) ?>
            </div>
        </li>
        <div id="accordion">
            <?php foreach ($getCampaign['segmentation'] as $key => $segmentationItem): ?>
                <div class="card-accordion">
                    <div class="card-accordion-header" id="headingOne">
                        <h5 class="mb-0">
                            <button class="btn btn-link" data-toggle="collapse"
                                    data-target="#collapseSegmentation-<?= $key; ?>" aria-expanded="true"
                                    aria-controls="collapseOne">
                                <?= trad("Segmentation n°") . ($key + 1) ?>
                            </button>
                        </h5>
                    </div>
                    <div id="collapseSegmentation-<?= $key; ?>" class="collapse show" aria-labelledby="headingOne"
                         data-parent="#accordion">
                        <div class="card-body">
                            <span><strong><?= trad("Socio criteria:") ?></strong></span> <?= $segmentationItem['age_min']; ?>
                            - <?= $segmentationItem['age_max']; ?>
                            <br>
                            <?php if ($segmentationItem['nature']): ?>
                                <span><strong><?= trad("Nature:") ?></strong></span>
                                <?php foreach (explode(',', $segmentationItem['nature']) as $nature): ?>
                                    <?= ucfirst(\App\Enum\campaignNatureType::getDescriptionByKey($nature)) ?>,
                                <?php endforeach; ?>
                                <br>
                            <?php endif; ?>
                            <?php if ($segmentationItem['civility']): ?>
                                <span><strong><?= trad("Civility:") ?></strong></span>
                                <?php foreach (explode(',', $segmentationItem['civility']) as $civility): ?>
                                    <?= ucfirst(\App\Enum\civilityType::getDescriptionByKey($civility)) ?>,
                                <?php endforeach; ?>
                                <br>
                            <?php endif; ?>
                            <?php if ($segmentationItem['car_owner']): ?>
                                <span><strong><?= trad("Vehicle owner:") ?></strong></span> <?= $segmentationItem['car_owner']; ?>
                                <br>
                            <?php endif; ?>
                            <?php if ($segmentationItem['auto_owned']): ?>
                                <span><strong><?= trad("Auto owned:") ?></strong></span> <?= $segmentationItem['auto_owned']; ?>
                                <br>
                            <?php endif; ?>
                            <?php if ($segmentationItem['is_auto_intention']): ?>
                                <span><strong><?= trad("auto intention:") ?></strong></span>
                                <?php foreach (explode(',', $segmentationItem['is_auto_intention']) as $autoIntention): ?>
                                    <?= ucfirst(\App\Enum\AutoIntentionnistType::getDescriptionByKey($autoIntention)) ?>,
                                <?php endforeach; ?>
                                <br>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    <?php endif ?>

    <!-- section content -->
    <?php if ($getCampaign['content']): ?>
        <li class="active">
            <div class="title-champ d-flex align-items-center">
                <span><i class="fas fa-images"></i> <?= trad('Content', 'campaign'); ?></span>
                <?= campaignIconStatus($getCampaign['content'][0]['campaign_id']) ?>
            </div>
        </li>
        <div id="accordion">
            <?php if ($getCampaign['content']): ?>
                <?php $channels = explode(',', $getCampaign['channels']['channel_id']); ?>
                <?php foreach ($channels as $key => $channel): ?>
                    <?php $content = $getCampaign['content'][$key]; ?>
                    <?php if (!is_null($content) && $channel == \App\Enum\campaignChannelType::EMAIL): ?>
                        <div class="card-accordion">
                            <div class="card-accordion-header" id="headingOne">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" data-toggle="collapse"
                                            data-target="#collapseOne-<?= $key; ?>" aria-expanded="true"
                                            aria-controls="collapseOne">
                                        Content <?= \App\Enum\campaignChannelType::getDescriptionById($channel); ?>
                                    </button>
                                </h5>
                            </div>
                            <div id="collapseOne-<?= $key; ?>" class="collapse show" aria-labelledby="headingOne"
                                 data-parent="#accordion">
                                <div class="card-body">
                                    <span><strong>Sender:</strong></span> <?= $content['sender']; ?><br>
                                    <span><strong>Objet:</strong></span> <?= $content['object'] ?><br>
                                    <span><strong>Html text:</strong></span> <?= $content['html_text'] ?><br>
                                    <a href="javascript::void(0)" style="text-decoration: underline;"
                                       onclick="window.open('/campaign/content/preview/<?= $content['id']; ?>', '_blank')">Preview
                                        HTML</a>
                                </div>
                            </div>
                        </div>
                    <?php elseif (!is_null($content) && $channel == \App\Enum\campaignChannelType::SMS): ?>
                        <div class="card-accordion">
                            <div class="card-accordion-header" id="headingOne">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" data-toggle="collapse"
                                            data-target="#collapseOne-<?= $key; ?>" aria-expanded="true"
                                            aria-controls="collapseOne">
                                        Content <?= \App\Enum\campaignChannelType::getDescriptionById($channel); ?>
                                    </button>
                                </h5>
                            </div>
                            <div id="collapseOne-<?= $key; ?>" class="collapse show" aria-labelledby="headingOne"
                                 data-parent="#accordion">
                                <div class="card-body">
                                    <span><strong>SMS oneclick:</strong></span> <?= $content['sms_oneclick'] == 0 ? "Yes" : "No"; ?>
                                    <br>
                                    <span><strong>Mobile expediteur:</strong></span> <?= $content['mobile_expediteur'] ?>
                                    <br>
                                    <span><strong>Mobile message:</strong></span> <?= $content['mobile_message'] ?>
                                    <br>
                                    <span><strong>Mobile url redirection:</strong></span> <?= $content['mobile_url_redirection'] ?>
                                    <br>
                                    <span><strong>Text:</strong></span> <?= $content['text'] ?><br>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif ?>
        </div>
    <?php endif ?>

    <!-- section planning -->
    <?php if ($getCampaign['planning']): ?>
        <li class="active">
            <div class="title-champ d-flex align-items-center">
                <span><i class="fas fa-calendar-alt"></i> <?= trad('Planning', 'campaign'); ?></span>
                <?= campaignIconStatus($getCampaign['planning'][0]['campaign_id']) ?>
            </div>
            <div class="list-value">
                <?php foreach ($getCampaign['planning'] as $planning): ?>
                    <div class="row container">
                        <div class="panel-planning">
                            <div class="panel-planning-header">
                                Channel <?= \App\Enum\campaignChannelType::getDescriptionById($planning['channel_id']); ?>
                            </div>
                            <div class="panel-planning-body">
                                <span><?= trad("Date send") ?>
                                    : <?= date('d-m-Y H:i:s', $planning['date_send']); ?></span>
                                <br>
                                <span><?= trad("Volume") ?>: <?= $planning['volume']; ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </li>
    <?php endif ?>
</ul>
<?php if (disableButtonCampaign($getCampaign['campaign']['status'])): ?>
    <a href="javascript:void(0)" data-campaign-id="<?= $getCampaign['campaign']['id']; ?>" type="button"
       class="btn btn-envoye validateCampaign">Valider ma campaigne</a>
<?php endif; ?>
