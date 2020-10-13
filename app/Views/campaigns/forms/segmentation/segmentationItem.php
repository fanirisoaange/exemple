<div class="col-md-6 segmentationRows" id='row-segment-<?=$id?>'>
    <div class="card">
        <div class="card-header tooglable" data-toogle-target='body-segment-<?=$id?>' onClick="$('#body-segment-<?=$id?>').slideToggle();">
            <h3 class="box-title"><?=trad('segmentation'); ?> <?=$id?></h3>
        </div>
        <div class="card-body tooglable-item" id='body-segment-<?=$id?>'>
            <?= form_open($form['action']['field'],$form['action']['attributes'], $form['action']['hidden']) ?>
                <label><?=trad('Socio criteria'); ?></label>
                <div class="form-group">
                    <label class="d-flex  justify-content-end">Age&nbsp;&nbsp;<span id="segmentationValue-<?=$id?>"><?=$age_min.'- '.$age_max?></span>&nbsp;<?=trad('years old'); ?> </label>
                    <div class="slider-blue">
                        <input type="text" value="" class="slider form-control slider-<?=$id?>" data-slider-min="0" data-slider-max="100"
                               data-slider-step="5" data-slider-value="[<?=$age_min.','.$age_max?>]" data-slider-orientation="horizontal"
                               data-slider-selection="before" data-slider-tooltip="show" id="<?=$id?>" style='display:none;'>
                        <?= form_input($form['age_min']) ?>
                        <?= form_input($form['age_max']) ?>
                    </div>
                </div>
                <div class="form-group">
                    <label><?=trad('Nature'); ?></label>
                    <div class="check-genre d-flex align-items-center justify-content-start">
                        <div class="checkbox">
                            <?= form_checkbox($form['nature_individual'],'',$form['nature_individual']['checked']); ?>
                            <label for="individual-<?=$id?>">
                                <i class="fa fa-checkable"></i>
                                <?= trad('individual') ?>
                            </label>
                        </div>
                        <div class="checkbox">
                            <?= form_checkbox($form['nature_company'],'',$form['nature_company']['checked']); ?>
                            <label for="company-<?=$id?>">
                                <i class="fa fa-checkable"></i>
                                <?= trad('company') ?>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label><?=trad('civility'); ?></label>
                    <div class="check-genre d-flex align-items-center justify-content-start">
                        <div class="checkbox">
                            <?= form_checkbox($form['civility_male'],'',$form['civility_male']['checked']); ?>
                            <label for="male-<?=$id?>">
                                <i class="fa fa-checkable"></i>
                                <?= trad('Man') ?>
                            </label>
                        </div>
                        <div class="checkbox">
                            <?= form_checkbox($form['civility_female'],'',$form['civility_female']['checked']); ?>
                            <label for="female-<?=$id?>">
                                <i class="fa fa-checkable"></i>
                                <?= trad('Woman') ?>
                            </label>
                        </div>
                    </div>
                </div>
                <label><?=trad('Affinity criteria'); ?></label>
                <div class="form-group">
                    <label><?=trad('Vehicle owner'); ?></label>
                    <div class="check-genre d-flex align-items-center justify-content-start">
                        <div class="checkbox">
                            <?= form_checkbox($form['car_owner_yes'],'',$form['car_owner_yes']['checked']); ?>
                            <label for="car_owner_yes-<?=$id?>">
                                <i class="fa fa-checkable"></i>
                                <?=trad('YES'); ?>
                            </label>
                        </div>
                        <div class="checkbox">
                            <?= form_checkbox($form['car_owner_no'],'',$form['car_owner_no']['checked']); ?>
                            <label for="car_owner_no-<?=$id?>">
                                <i class="fa fa-checkable"></i>
                                <?=trad('NO'); ?>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-12 auto_owned_container">
                    <label><?= trad('owned car') ?></label>
                    <?= form_dropdown($form['auto_owned']) ?>
                </div>
                <div class="form-group">
                    <label><?=trad('Intentionniste auto'); ?></label>
                    <div class="check-genre d-flex align-items-center justify-content-start">
                        <div class="checkbox">
                            <?= form_checkbox($form['is_auto_intention_yes'],'',$form['is_auto_intention_yes']['checked']); ?>
                            <label for="is_auto_intention_yes-<?=$id?>">
                                <i class="fa fa-checkable"></i>
                                <?=trad('YES'); ?>
                            </label>
                        </div>
                        <div class="checkbox">
                            <?= form_checkbox($form['is_auto_intention_no'],'',$form['is_auto_intention_no']['checked']); ?>
                            <label for="is_auto_intention_no-<?=$id?>">
                                <i class="fa fa-checkable"></i>
                                <?=trad('NO'); ?>
                            </label>
                        </div>
                    </div>
                </div>
                <?php if(canDeleteCampagn((int)$campaign['status'])){ ?>
                <div class="justify-content-end d-flex align-items-center">
                    <button class="btn btn-delete" id='row-delete-<?=$id?>' >
                        <i class="fa fa-times"></i>
                        <?= trad('Delete segment'); ?>
                    </button>
                    <button class="btn btn-modif btn-save-segmentation">
                        <i class="fa fa-edit"></i>
                        <?= trad('Save segment'); ?>
                    </button>
                </div>
                <?php } ?>  
            <?= form_close() ?>
        </div>
    </div>
</div>