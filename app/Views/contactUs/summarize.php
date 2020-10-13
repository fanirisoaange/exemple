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
                                <?php if (isset($_SESSION['success'])): ?>
                                    <div class="alert alert-success text-center">
                                        <?= $_SESSION['success']; ?>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <?= trad("Summarize of your request") ?>
                                        </div>
                                        <div class="card-body">
                                            <strong><?= trad("Name") ?></strong> : <?= $campaign['name'] ?> <br>
                                            <strong><?= trad("Type") ?></strong>
                                            : <?= ucfirst(\App\Enum\CampaignType::getDescriptionById($campaign['type'])) ?>
                                            <br>
                                            <strong><?= trad("Model economic") ?></strong>
                                            : <?= $campaign['model_economique'] ?>
                                            <br>
                                            <strong><?= trad("Company") ?></strong> :
                                            <?php foreach ($companies as $company): ?>
                                                <span class="badge badge-primary"><?= $company ?></span>
                                            <?php endforeach; ?> <br>
                                            <strong><?= trad("Channel") ?> </strong> : <span class="badge badge-purple"><?= ucfirst(\App\Enum\campaignChannelType::getDescriptionById(3)); ?></span>
                                            <br>
                                            <strong><?= trad("Message") ?></strong> :
                                            <p><?= $campaign['message']; ?></p>
                                        </div>
                                    </div>
                                <?php endif; ?>
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
