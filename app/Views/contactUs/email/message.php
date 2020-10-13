<div style="width:100%;height: 350px;position:relative;">
    <div style="max-width:500px;margin:0 auto;margin-top: 50px;background:#FFF;border:1px solid #ddd;padding: 20px;position: absolute;top:25px;">
        <div>
            <h2><strong>Request for channel Telemarketing</strong></h2>
            <strong><?= trad("Name") ?></strong> : <?= $campaign['name'] ?> <br>
            <strong><?= trad("Type") ?></strong>
            : <?= ucfirst(\App\Enum\CampaignType::getDescriptionById($campaign['type'])) ?>
            <br>
            <strong><?= trad("Model economic") ?></strong>
            : <?= $campaign['model_economique'] ?>
            <br>
            <br>
            <strong><?= trad("Company") ?></strong> :
            <span class="badge badge-primary"><?= $companies[0] ?></span><br>
            <strong><?= trad("Channel") ?> </strong> : <span
                class="badge badge-purple"><?= ucfirst(\App\Enum\campaignChannelType::getDescriptionById(3)); ?></span>
            <br>
            <strong><?= trad("Email address") ?></strong>: <span><?= $_POST['email']; ?></span> <br>
            <strong><?= trad("Message") ?></strong> :
            <p><?= $_POST['message']; ?></p>
            <h3><strong><?= trad("Segmentation criteria") ?></strong></h2>
            <?php foreach ($segmentation as $key => $segmentationItem): ?>
                <strong><?= trad("Segmentation n°"). ($key + 1) ?></strong><br>
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
                    <?php endif; ?><br>
            <?php endforeach ?>
            <!-- <strong><?//= trad("Segmentation volume :").'0' ?></strong> -->
        </div>
    </div>
</div>