<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title"><?= trad('Company detail', 'company'); ?></h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-4">
                <div class="mb-2 p-1 border-bottom">
                    <span class="text-muted"><?= trad('Main company', 'company'); ?></span><br/>
                    <?= !empty($main_company['fiscal_name']) ? $main_company['fiscal_name'] : ''; ?>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="mb-2 p-1 border-bottom">
                    <span class="text-muted"><?= trad('Parent company', 'company'); ?></span><br/>
                    <?= !empty($parent_company['fiscal_name']) ? $parent_company['fiscal_name'] : trad('No parent', 'company'); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-1">
                <div class="mb-2 p-1 border-bottom">
                    <span class="text-muted"><?= trad('ID', 'company'); ?></span><br/>
                    <?= $company['id']; ?>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="mb-2 p-1 border-bottom">
                    <span class="text-muted"><?= trad('Fiscal name', 'company'); ?></span><br/>
                    <?= $company['fiscal_name']; ?>
                </div>
            </div>
            <div class="col-sm-5">
                <div class="mb-2 p-1 border-bottom">
                    <span class="text-muted"><?= trad('Commercial name', 'company'); ?></span><br/>
                    <?= $company['commercial_name']; ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="mb-2 p-1 border-bottom">
                    <span class="text-muted"><?= trad('Address', 'company'); ?></span><br/>
                    <?= $company['address_1']; ?>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="mb-2 p-1 border-bottom">
                    <span class="text-muted"><?= trad('Additional address', 'company'); ?></span><br/>
                    <?= $company['address_2']; ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-1">
                <div class="mb-2 p-1 border-bottom">
                    <span class="text-muted"><?= trad('ZIP', 'company'); ?></span><br/>
                    <?= $company['zip_code']; ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="mb-2 p-1 border-bottom">
                    <span class="text-muted"><?= trad('City', 'company'); ?></span><br/>
                    <?= $company['city']; ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="mb-2 p-1 border-bottom">
                    <span class="text-muted"><?= trad('City alias', 'company'); ?></span><br/>
                    <?= $company['city_display']; ?>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="mb-2 p-1 border-bottom">
                    <span class="text-muted"><?= trad('Country', 'company'); ?></span><br/>
                    <?= $company['country_name']; ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-9">
                <div class="mb-2 p-1 border-bottom">
                    <span class="text-muted"><?= trad('VAT number', 'company'); ?></span><br/>
                    <?= $company['vat_number']; ?>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="mb-2 p-1 border-bottom">
                    <span class="text-muted"><?= trad('VAT', 'company'); ?></span><br/>
                    <?= $company['vat']; ?>%
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="mb-2 p-1 border-bottom">
                    <span class="text-muted"><?= trad('Phone', 'company'); ?></span><br/>
                    <?= $company['phone_number']; ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="mb-2 p-1 border-bottom">
                    <span class="text-muted"><?= trad('E-mail', 'company'); ?></span><br/>
                    <?= $company['email']; ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="mb-2 p-1 border-bottom">
                    <span class="text-muted"><?= trad('Website', 'company'); ?></span><br/>
                    <?= $company['website']; ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="mb-2 p-1 border-bottom">
                    <span class="text-muted"><?= trad('Dealer ship ID', 'company'); ?></span><br/>
                    <?= $company['dealer_ship_id']; ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="mb-2 p-1 border-bottom">
                    <span class="text-muted"><?= trad('Site number', 'company'); ?></span><br/>
                    <?= $company['site_number']; ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="mb-2 p-1 border-bottom">
                    <span class="text-muted"><?= trad('Orias', 'company'); ?></span><br/>
                    <?= $company['phone_number']; ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="mb-2 p-1 border-bottom">
                    <span class="text-muted"><?= trad('Comments', 'company'); ?></span><br/>
                    <?= $company['comments']; ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <div class="mb-2 p-1 border-bottom">
                    <span class="text-muted"><?= trad('Status', 'company'); ?></span><br/>
                    <?= $company['status_name']; ?>
                </div>
            </div>
        </div>
        <div class="row p-2 text-muted text-right">
            <div class="col-sm-12">
                <small><b><?= trad('Created', 'global'); ?>:</b> <?= isset($company['created']) ? format_date($company['created'], 'd/m/Y', true) : ''; ?> <b><?= trad('Updated', 'global'); ?>:</b> <?= isset($company['updated']) ? format_date($company['updated'], 'd/m/Y', true) : ''; ?></small>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($company_billing)): ?>
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title"><?= trad('Billing address', 'company'); ?></h3>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-sm-1">
                    <div class="mb-2 p-1 border-bottom">
                        <span class="text-muted"><?= trad('ID', 'company'); ?></span><br/>
                        <?= $company_billing['id']; ?>
                    </div>
                </div>
                <div class="col-sm-11">
                    <div class="mb-2 p-1 border-bottom">
                        <span class="text-muted"><?= trad('Fiscal name', 'company'); ?></span><br/>
                        <?= $company_billing['fiscal_name']; ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="mb-2 p-1 border-bottom">
                        <span class="text-muted"><?= trad('Address', 'company'); ?></span><br/>
                        <?= $company_billing['address_1']; ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="mb-2 p-1 border-bottom">
                        <span class="text-muted"><?= trad('Additional address', 'company'); ?></span><br/>
                        <?= $company_billing['address_2']; ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-1">
                    <div class="mb-2 p-1 border-bottom">
                        <span class="text-muted"><?= trad('ZIP', 'company'); ?></span><br/>
                        <?= $company_billing['zip_code']; ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="mb-2 p-1 border-bottom">
                        <span class="text-muted"><?= trad('City', 'company'); ?></span><br/>
                        <?= $company_billing['city']; ?>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="mb-2 p-1 border-bottom">
                        <span class="text-muted"><?= trad('Country', 'company'); ?></span><br/>
                        <?= $company_billing['country_name']; ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-9">
                    <div class="mb-2 p-1 border-bottom">
                        <span class="text-muted"><?= trad('VAT number', 'company'); ?></span><br/>
                        <?= $company_billing['vat_number']; ?>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="mb-2 p-1 border-bottom">
                        <span class="text-muted"><?= trad('VAT', 'company'); ?></span><br/>
                        <?= $company_billing['vat']; ?>%
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="mb-2 p-1 border-bottom">
                        <span class="text-muted"><?= trad('Phone', 'company'); ?></span><br/>
                        <?= $company_billing['phone_number']; ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="mb-2 p-1 border-bottom">
                        <span class="text-muted"><?= trad('E-mail', 'company'); ?></span><br/>
                        <?= $company_billing['email']; ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="mb-2 p-1 border-bottom">
                        <span class="text-muted"><?= trad('Comments', 'company'); ?></span><br/>
                        <?= $company_billing['comments']; ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <div class="mb-2 p-1 border-bottom">
                        <span class="text-muted"><?= trad('Status', 'company'); ?></span><br/>
                        <?= $company_billing['status_name']; ?>
                    </div>
                </div>
            </div>
            <div class="row p-2 text-muted text-right">
                <div class="col-sm-12">
                    <small><b><?= trad('Created', 'global'); ?>:</b> <?= isset($company_billing['created']) ? format_date($company_billing['created'], 'd/m/Y', true) : ''; ?> <b><?= trad('Updated', 'global'); ?>:</b> <?= isset($company_billing['updated']) ? format_date($company_billing['updated'], 'd/m/Y', true) : ''; ?></small>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title"><?= trad('Geolocalisation', 'company'); ?></h3>
        </div>
        <div class="card-body">
            <div class="row">
                <table id="geolocalisationList"
                   class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th><?php echo trad('Name') ?></th>
                        <th><?php echo trad('Postal code') ?></th>
                        <th><?php echo trad('Insee') ?></th>
                        <th><?php echo trad('Iris') ?></th>
                        <th><?php echo trad('Hexacle') ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                foreach ($company_localizations as $localisation) { ?>
                    <tr>
                        <td><?php echo $localisation["name"]; ?></td>
                        <td><?php echo $localisation["postal_code"]; ?></td>
                        <td><?php echo $localisation["insee"]; ?></td>
                        <td><?php echo $localisation["iris"]; ?></td>
                        <td><?php echo $localisation["hexacle"]; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>