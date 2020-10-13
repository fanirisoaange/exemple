
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-10">
                <h3><?= isset($main_company['fiscal_name']) ? $main_company['fiscal_name'] : ''; ?></h3>
            </div>
            <div class="col-sm-2">
                <a href="<?= route_to('company_create'); ?>" class="btn btn-outline-success"><?= trad('Create a new company', 'company'); ?></a>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-12">
                <table class="table">
                    <thead>
                        <tr>
                            <td></td>
                            <td><?= trad('Fiscal name'); ?></td>
                            <td><?= trad('City display'); ?></td>
                            <td><?= trad('Status'); ?></td>
                        </tr>
                    </thead>
                    <?php if (!empty($companies)): ?>
                        <tbody>
                            <?php foreach ($companies as $company): ?>
                                <tr>
                                    <td>
                                        <?php if ($company['parent_id'] == 0): ?>
                                            <form method="post" class="float-left">
                                                <input type="hidden" name="company_main_id" value="<?= $company['main_id']; ?>"/>
                                                <input type="hidden" name="company_parent_id" value="<?= $company['id']; ?>"/>
                                                <button type="submit" class="btn btn-outline-primary btn-xs"><i class="fas fa-project-diagram"></i></button>
                                            </form>
                                        <?php endif; ?>
                                        <?php if (!empty($company['children'])): ?>
                                            <button class="btn btn-outline-primary btn-xs ml-1 display_subcompany"> <i class="fas fa-th-list"></i> </button>
                                        <?php endif; ?>
                                        <a href="<?= route_to('company_detail', $company['id']); ?>" class="btn btn-outline-primary ml-1 btn-xs"><i class="fas fa-eye"></i></a>
                                        <?php if (hasGroupPermission('company_edit')): ?>
                                            <a href="<?= route_to('company_edit', $company['id']); ?>" class="btn btn-outline-warning btn-xs"><i class="fas fa-edit"></i></a>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $company['fiscal_name']; ?></td>
                                    <td><?= $company['city_display']; ?></td>
                                    <td><?= $company['status_name']; ?></td>
                                </tr>
                                <?php if (!empty($company['children'])) { ?>
                                    <tr style="display:none;">
                                        <td colspan="4" class="bg-light">
                                            <table class="table">
                                                <?php
                                                foreach ($company['children'] as $company1) {
                                                    echo view('companies/subcompanies_table', ['company' => $company1]);
                                                    if (!empty($company1['children'])) {
                                                        ?>
                                                        <tr style="display:none;">
                                                            <td colspan="4">
                                                                <table class="table">
                                                                    <?php
                                                                    foreach ($company1['children'] as $company2) {
                                                                        echo view('companies/subcompanies_table', ['company' => $company2]);
                                                                        if (!empty($company2['children'])) {
                                                                            ?>
                                                                            <tr style="display:none;">
                                                                                <td colspan="4">
                                                                                    <table class="table">
                                                                                        <?php
                                                                                        foreach ($company2['children'] as $company3) {
                                                                                            echo view('companies/subcompanies_table', ['company' => $company3]); ?>
                                                                                        <?php
                                                                                        } ?>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                            <?php
                                                                        }
                                                                    } ?>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </table>
                                        </td>
                                    </tr>
                                <?php }
                                ?>
                            <?php endforeach; ?>
                        </tbody>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
</div>