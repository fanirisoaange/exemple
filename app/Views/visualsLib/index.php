<nav class="nav-tabs-custom">
    <div class="nav nav-tabs nav-tabs-custom" id="nav-tab" role="tablist">
        <a class="nav-item nav-link active" onclick="getVisualByAjax(3);" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Email</a>
        <a class="nav-item nav-link" onclick="getVisualByAjax(2);" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">SMS</a>
    </div>
</nav>
<div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
        <div class="row" style="margin-top:20px;">
            <?php echo trad('Filters') ?>
            <div class="col-md-3">
                <div class="form-group">
                    <select name="features_email" id="features_email" data="" class="form-control select2" multiple onchange="getVisualByAjax(3)">
                        <?php foreach ($company_features as $company_feature) { ?>
                            <option <?php if ($company_feature['id_client_feature'] == $companyFeatureSeletected) { ?>selected<?php } ?> value="<?php echo $company_feature['id_client_feature']; ?>">
                                <?php echo $company_feature['name']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="row" id="listVisuals_email" style="margin-top:20px;">
            <!-- Here calling getVisualByAjax(3); in visualsLib.js -->
        </div>
    </div>
    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
        <div class="row" style="margin-top:20px;">
            <?php echo trad('Filters') ?>
            <div class="col-md-3">
                <div class="form-group">
                    <select name="features_sms" id="features_sms" data="" class="form-control select2" multiple onchange="getVisualByAjax(2)">
                        <?php foreach ($company_features as $company_feature) { ?>
                            <option <?php if ($company_feature['id_client_feature'] == $companyFeatureSeletected) { ?>selected<?php } ?> value="<?php echo $company_feature['id_client_feature']; ?>">
                                <?php echo $company_feature['name']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="row" id="listVisuals_sms"  style="margin-top:20px;">
            <!-- Here calling getVisualByAjax(2); in visualsLib.js -->
        </div>
    </div>
</div>