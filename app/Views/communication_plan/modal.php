<div class="modal fade" id="externeCampaignModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="externeCampaignModalLabel"><?php echo trad('Externe campaign') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="nameCampaign"><?= trad('Name'); ?></label>
                            <input type="text" class="form-control" name="nameCampaign" id="nameCampaign">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <span class="input-group">
                                <label for="nameCampaign"><?= trad('Periode Date'); ?></label>
                            </span>
                            <span class="input-group"> 
                                <input type="date" name="startDate" id="startDateCampaign" class="form-control" placeholder="dd/mm/aaaa">
                                <input type="date" name="endDate" id="endDateCampaign" class="form-control" placeholder="dd/mm/aaaa">
                                <span class="input-group-append">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="position: absulute; left:40%; right:40%;">
                <?php if (isMemberAdmin()) { ?>
                    <a id="btn-save" href="#" class="btn btn-success"
                        onclick="ajaxExterneCampaignSave(event, <?php echo $_SESSION['current_sub_company']; ?>, '<?php echo trad(
                            "Are you sure you want to save this extern campaign ?"
                        ); ?>', '<?php echo trad(
                            "Send success"
                        ); ?>', '<?php echo trad(
                            "Send fail"
                        ); ?>');"
                    >
                        <i class="fas fa-check"></i> <?php echo trad('Save') ?>
                    </a>
                <?php } ?>
            </div>
        </div>
    </div>
</div>