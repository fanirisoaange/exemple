<div class="card card-primary card-outline">
    <h4 class="card-header text-center">
         <?php echo trad('Prices in Euros CPM (cost per thousand contacts)') . ' - ' . ($company_name != '' ?  $company_name : 'All companies'); ?>
    </h4>
    <div class="card-body">
        <form action="<?= base_url(); ?>/price/edit" method="POST">
            <input type="hidden" name="company_id" value="<?= $company_id; ?>">
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
                        <td style="width: 20%"><div class="input-group mb-3">
                          <input type="text" name="price[<?=$sk?>][<?=$tk?>]" class="form-control" placeholder='-' value="<?= isset($prices[$sk][$tk]) ? $prices[$sk][$tk] : (isset($prices_def[$sk][$tk]) ? $prices_def[$sk][$tk] : ''); ?>">
                          <div class="input-group-append">
                            <span class="input-group-text">&#128; <sup>HT</sup></span>
                          </div>
                        </div></td>
                         <?php } ?>
                      
                    </tr>
                     <?php } ?>
                   
                </tbody>
            </table>
                     <div class="row">
                        <div class="col-12">
                            <hr />
                            <div class="btn-group  float-right" role="group">
                                <a href="<?= base_url(); ?>/price/list" class="btn btn-danger" style="margin-right: 1em">
                                    <i class="fa fa-window-close"></i> <?= trad('Cancel');?>
                                </a>
                                 <button id="btnSubmit" class="btn btn-success">
                                    <i class="fa fa-edit"></i> <?= trad('Save prices');?>
                                </button>

                            </div>
                        </div>
                    </div>
        </form>
    </div>
</div>