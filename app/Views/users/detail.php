<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title"><?= trad('User detail', 'user'); ?></h3>
    </div>
    <div class="card-body">
        <div class="row border-bottom p-2">
            <div class="col-sm-2">
                <b>ID</b>
            </div>
            <div class="col-sm-10">
                <?= $user_detail['id']; ?>
            </div>
        </div>
        <div class="row border-bottom p-2">
            <div class="col-sm-2">
                <b><?= trad('Username', 'user'); ?></b>
            </div>
            <div class="col-sm-10">
                <?= $user_detail['username']; ?>
            </div>
        </div>
        <div class="row border-bottom p-2">
            <div class="col-sm-2">
                <b><?= trad('Email', 'user'); ?></b>
            </div>
            <div class="col-sm-10">
                <?= $user_detail['email']; ?>
            </div>
        </div>
        <div class="row border-bottom p-2">
            <div class="col-sm-2">
                <b><?= trad('Firstname', 'user'); ?></b>
            </div>
            <div class="col-sm-10">
                <?= $user_detail['first_name']; ?>
            </div>
        </div>
        <div class="row border-bottom p-2">
            <div class="col-sm-2">
                <b><?= trad('Lastname', 'user'); ?></b>
            </div>
            <div class="col-sm-10">
                <?= $user_detail['last_name']; ?>
            </div>
        </div>
        <div class="row border-bottom p-2">
            <div class="col-sm-2">
                <b><?= trad('Status', 'user'); ?></b>
            </div>
            <div class="col-sm-10">
                <?= $user_detail['status_name']; ?>
            </div>
        </div>
        <div class="row border-bottom p-2">
            <div class="col-sm-2">
                <b><?= trad('Phone', 'user'); ?></b>
            </div>
            <div class="col-sm-10">
                <?= $user_detail['phone']; ?>
            </div>
        </div>
        <div class="row p-2 text-muted text-right">
            <div class="col-sm-12">
                <small><b><?= trad('Created', 'user'); ?>:</b> <?= $user_detail['created']; ?> <b><?= trad('Updated', 'user'); ?>:</b> <?= $user_detail['updated']; ?></small>
            </div>
        </div>
    </div>
</div>

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title"><?= trad('Companies', 'user'); ?></h3>
    </div>
    <div class="card-body">
        <table id="userGroupsComapnies" class="table table-hover">
            <thead>
                <tr>
                    <th style="width: 5%"></th>
                    <th style="width: 20%"><?= trad('Group', 'user'); ?></th>
                    <th style="width: 75%"><?= trad('Companies', 'user'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($user_groups_companies)):
                    foreach ($user_groups_companies as $user_group => $user_company):
                        $i = 1;
                        ?>
                        <tr>
                            <td></td>
                            <td>
                                <div class="form-group">
                                    <?php foreach ($groups as $group): ?>
                                        <?= ($user_group == $group['id']) ? trad($group['name'], 'user') : ''; ?>
                                    <?php endforeach; ?>
                                </div>
                            </td>
                            <td>
                                <?php foreach ($companies as $company): ?>
                                    <?= (in_array($company['id'], $user_company) ? $company['commercial_name'] : ''); ?>
                                <?php endforeach; ?>
                            </td>
                        </tr>
                        <?php
                        $i++;
                    endforeach;
                endif;
                ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card card-primary">
    <div class="card-footer">
        <?php if (userHasEditUserAccess($user_detail['id'])) { ?>
            <a class="btn btn-app"
               href="<?php echo route_to('user_edit', $user_detail['id']) ?>">
                <i class="fas fa-edit"></i> <?php echo trad('Edit') ?>
            </a>
        <?php } ?>
    </div>
</div>
