<input type="hidden" id="session_message" value="<?php echo $message; ?>">
<div class="card card-primary card-outline">
    <h4 class="card-header text-center">
        <?php echo trad('Prices in Euros CPM (cost per thousand contacts)') . ' - ' . ($company_name != '' ?  $company_name : 'All companies'); ?>
    </h4>

<div class="card-body">
                     <table class="table text-center">
                            <thead>
                                <tr>
                                    <th></th>
                                    <?php foreach($services as $sk => $sv) { ?> 
                                    <th><?= trad($sv) ?></th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($types as $tk => $tv) { ?>
                                <tr>
                                    <th><?= trad($tv) ?></th>
                                    <?php foreach($services as $sk => $sv) { ?> 
                                    <td><?= isset($prices[$sk][$tk]) ? $prices[$sk][$tk] : (isset($prices_def[$sk][$tk]) ? $prices_def[$sk][$tk] : '-'); ?>&#128; <sup>HT</sup></td>
                                     <?php } ?>
                                  
                                </tr>
                                 <?php } ?>          
                            </tbody>
                    </table>
             <?php if($canEdit) { ?>
                    <div class="row">
                            <div class="col-12">
                                <hr />
                                <div class="btn-group  float-right" role="group">
                                    <a href="<?= base_url();?>/price/edit">
                                    <button type="button" class="btn btn-warning">
                                        <i class="fa fa-edit"></i> <?= trad('Edit prices');?>
                                    </button></a>

                                </div>
                            </div>
                    </div>   
            <?php } ?>
</div>
</div>