<nav class="nav-tabs-custom">
    <div class="nav nav-tabs nav-tabs-custom" id="nav-tab" role="tablist">
        <?php foreach ($visual_categories as $key => $visual_category) { ?>
            <a class="nav-item nav-link <?php echo ($key == 1) ? "active" : ""; ?>" onclick="getVisualByAjax(<?php echo $key ?>);" id="nav-home-tab-<?php echo $key ?>" data-toggle="tab" href="#nav-home-<?php echo $key ?>" role="tab" aria-controls="nav-home-<?php echo $key ?>" aria-selected="true">
                <?php echo $visual_category ?>
            </a>
        <?php } ?>
        <!--        <a class="nav-item nav-link active" onclick="getVisualByAjax(3);" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Email</a>-->
        <!--        <a class="nav-item nav-link" onclick="getVisualByAjax(2);" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">SMS</a>-->
    </div>
</nav>
<div class="tab-content" id="nav-tabContent">
    <?php foreach ($visual_categories as $key => $visual_category) { ?>
        <div class="tab-pane fade <?php echo ($key == 1) ? "show active" : ""; ?>" id="nav-home-<?php echo $key ?>" role="tabpanel" aria-labelledby="nav-home-tab-<?php echo $key ?>">
            <div class="row" style="margin-top:20px;">
                <?php echo trad('Filters') ?>
                <div class="col-md-3">
                    <div class="form-group">
                        <select name="features_<?php echo $key ?>" id="features_<?php echo $key ?>" data="" class="form-control select2" multiple onchange="getVisualByAjax(<?php echo $key ?>)">
                            <?php foreach ($company_features as $company_feature) { ?>
                                <option <?php if ($company_feature['id_client_feature'] == $companyFeatureSeletected) { ?>selected<?php } ?> value="<?php echo $company_feature['id_client_feature']; ?>">
                                    <?php echo $company_feature['name']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row" id="listVisuals_<?php echo $key ?>" style="margin-top:20px;">
            </div>
        </div>
    <?php } ?>
    <!--    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">-->
    <!--        <div class="row" style="margin-top:20px;">-->
    <!--            --><?php //echo trad('Filters') ?>
    <!--            <div class="col-md-3">-->
    <!--                <div class="form-group">-->
    <!--                    <select name="features_email" id="features_email" data="" class="form-control select2" multiple onchange="getVisualByAjax(3)">-->
    <!--                        --><?php //foreach ($company_features as $company_feature) { ?>
    <!--                            <option --><?php //if ($company_feature['id_client_feature'] == $companyFeatureSeletected) { ?><!--selected--><?php //} ?><!-- value="--><?php //echo $company_feature['id_client_feature']; ?><!--">-->
    <!--                                --><?php //echo $company_feature['name']; ?>
    <!--                            </option>-->
    <!--                        --><?php //} ?>
    <!--                    </select>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--        <div class="row" id="listVisuals_email" style="margin-top:20px;">-->
    <!--        </div>-->
    <!--    </div>-->
    <!--    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">-->
    <!--        <div class="row" style="margin-top:20px;">-->
    <!--            --><?php //echo trad('Filters') ?>
    <!--            <div class="col-md-3">-->
    <!--                <div class="form-group">-->
    <!--                    <select name="features_sms" id="features_sms" data="" class="form-control select2" multiple onchange="getVisualByAjax(2)">-->
    <!--                        --><?php //foreach ($company_features as $company_feature) { ?>
    <!--                            <option --><?php //if ($company_feature['id_client_feature'] == $companyFeatureSeletected) { ?><!--selected--><?php //} ?><!-- value="--><?php //echo $company_feature['id_client_feature']; ?><!--">-->
    <!--                                --><?php //echo $company_feature['name']; ?>
    <!--                            </option>-->
    <!--                        --><?php //} ?>
    <!--                    </select>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--        <div class="row" id="listVisuals_sms"  style="margin-top:20px;">-->
    <!--        </div>-->
    <!--    </div>-->
</div>