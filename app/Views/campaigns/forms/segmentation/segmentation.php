<div class="tab-pane fade show" id="tabSegmentation" role="tabpanel"
     aria-labelledby="navTabSegmentation">
    <h3 onclick="toogleCollapseSegments()" class="segmentationTitle">segmentation</h3>
    <div class="row segmentationContainer" data-segments='<?=json_encode($segments)?>'>
        <div class="col-md-6" id='row-add-segmentation'>
            <?php if(canDeleteCampagn((int)$status)){ ?>
            <div class="card">
                <div class="card-header">
                    <h3 class="box-title"> <?= trad('Create a new segment') ?></h3>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <button class="btn btn-add " id='addSegmentation' data-campagn-id='<?=$campaignId?>'>
                        <i class="fa fa-plus-circle"></i>
                         <?= trad('New segment') ?>
                    </button>
                </div>
            </div>
            <?php } ?>
        </div>
        <div class="col-12 d-flex align-items-center justify-content-center flex-column">
            <button class="btn btn-envoye  count-segmentation-step segmentation-step" id="startCountingAction" onclick="countSegmentation(<?=$campaignId?>)">
                <?= trad('Start counting') ?>
                <i class="fa fa-angle-right"></i>
            </button>
            <div class="ajax-segmentation-loader justify-content-center wait-segmentation-step segmentation-step flex-column" style="display: none;">
                <div class="loader"></div>
                <div class="wait text-center">Please wait ...</div>
            </div>
            <div class="segmentation-result segmentation-step result-segmentation-step" style=" display: none;">Total: 0</div>
            <a class="btn btn-success next-segmentation-step segmentation-step"  href="<?=route_to('submit_segmentation', $campaignId) ?>" style=" display: none;">Next</a>
        </div>
    </div>
</div>
