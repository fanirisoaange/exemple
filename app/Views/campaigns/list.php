<?php if ($companyId && hasCampaignAccess($companyId)) { ?>
    <div class="row">
        <?php companySelector(); ?>
        <div class="col-6 text-right">
            <a href="<?= route_to('create_campaign') ?>"
               class="btn btn-success mb-3">
                <i class="nav-icon fas fa-plus"></i> <?php echo trad(
                    'Create campaign'
                ) ?>
            </a>
        </div>
    </div>
<?php } ?>

<div class="card card-primary card-outline">
    <div class="card-body">
        <?php if ( ! $companyId): ?>
            <?php echo trad(
                'Please select the company for which to display the campaign'
            ) ?>
        <?php else: ?>
            <table id="campaignList"
                   class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>#<?php echo trad('Campagn ID') ?></th>
                    <th><?php echo trad('Name') ?></th>
                    <th><?php echo trad('Type') ?></th>
                    <th><?php echo trad('Create') ?></th>
                    <th><?php echo trad('Status') ?></th>
                    <th><?php echo trad('Action') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($campaigns as $campagn) { ?>
                    <tr>
                        <td><?php echo $campagn["id"]; ?></td>
                        <td><?php echo $campagn["name"]; ?></td>
                        <td><?php echo campaign_type($campagn["type"]); ?></td>
                        <td><?php echo date_format(new DateTime($campagn["created"]),'d-m-Y H:i:s'); ?></td>
                        <td><?php echo campaign_status($campagn["status"]) ?></td>
                        <td>
                            <?php if($campagn['status'] < \App\Enum\CampaignStatus::VALIDATED): ?>
                                <a href="<?= route_to('campaign_detect', $campagn['id']); ?>"
                                   title="<?php echo trad('View campaign') ?>"
                                   class="btn btn-outline-warning"><i
                                            class="far fa-eye"></i>
                                </a>
                            <?php endif; ?>
                            <?php if (canDeleteCampagn($campagn["status"])) { ?>
                            <a href="#"
                               onclick="ajaxCampaignDelete(event, <?php echo $campagn["id"]; ?>, '<?php echo trad(
                                   "This operation is irreverssible. Are you sure you want to delete this campaign ?"
                               ); ?>', '<?php echo trad(
                                   "Deletion OK"
                               ); ?>', '<?php echo trad(
                                   "Deletion Error"
                               ); ?>');"
                               title="<?php echo trad('Delete') ?>"
                               class="btn btn-outline-danger"><i
                                        class="fas fa-trash"></i>
                            </a>
                          <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
