<form class="form-horizontal" method="post" action="">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title"><?= trad('User', 'user'); ?></h3>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <label for="username" class="col-sm-2 col-form-label"><?= trad('Username', 'user'); ?></label>
                <div class="col-sm-10">
                    <input value="<?= $user_detail['username']; ?>" type="text" class="form-control" name="username" id="username" placeholder="<?= trad('Username', 'user'); ?>" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="email" class="col-sm-2 col-form-label"><?= trad('Email', 'user'); ?></label>
                <div class="col-sm-10">
                    <input value="<?= $user_detail['email']; ?>" type="text" class="form-control" name="email" id="email" placeholder="<?= trad('Email', 'user'); ?>" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="password" class="col-sm-2 col-form-label"><?= trad('Password', 'user'); ?></label>
                <div class="col-sm-10">
                    <input value="" type="password" class="form-control" name="password" id="password" placeholder="<?= trad('Password', 'user'); ?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="firstName" class="col-sm-2 col-form-label"><?= trad('firstname', 'user'); ?></label>
                <div class="col-sm-10">
                    <input value="<?= $user_detail['first_name']; ?>" type="text" class="form-control" name="first_name" id="firstName" placeholder="<?= trad('Firstname', 'user'); ?>" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="lastName" class="col-sm-2 col-form-label"><?= trad('Lastname', 'user'); ?></label>
                <div class="col-sm-10">
                    <input value="<?= $user_detail['last_name']; ?>" type="text" class="form-control" name="last_name" id="lastName" placeholder="<?= trad('Lastname', 'user'); ?>" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="phone" class="col-sm-2 col-form-label"><?= trad('Phone', 'user'); ?></label>
                <div class="col-sm-10">
                    <input value="<?= $user_detail['phone']; ?>" type="text" class="form-control" name="phone" id="phone" placeholder="<?= trad('Phone', 'user'); ?>" >
                </div>
            </div>
            <?php if (isMemberAdmin()) { ?>
                <div class="form-group row">
                    <label for="status" class="col-sm-2 col-form-label"><?= trad('Status', 'user'); ?></label>
                    <div class="col-sm-10">
                        <select name="status" class="form-control" id="status" required>
                            <?php foreach ($user_status as $kstatus => $status): ?>
                                <option value="<?= $kstatus; ?>" <?= ($kstatus == $user_detail['status'] ? 'selected' : ''); ?>><?= $status; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            <?php } ?>
            <div class="row p-2 text-muted text-right">
                <div class="col-sm-12">
                    <small><b><?= trad('Created', 'user'); ?>:</b> <?= $user_detail['created']; ?> <b><?= trad('Updated', 'user'); ?>:</b> <?= $user_detail['updated']; ?></small>
                </div>
            </div>
        </div>
    </div>

    <?php if (isMemberAdmin()) { ?>
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"><?= trad('Companies', 'user'); ?></h3>
            </div>
            <div class="card-body">
                <div class="callout">
                    <div class="form-group row">
                        <label for="newGroup" class="col-sm-1 col-form-label"><?= trad('Group', 'user'); ?></label>
                        <div class="col-sm-2">
                            <select id="newGroup" class="form-control select2bs4" data-placeholder="<?= trad('Select a group', 'global'); ?>"  style="width: 100%;">
                                <option value=""><?= trad('Select a group', 'global'); ?></option>
                                <?php foreach ($groups as $group): ?>
                                    <option value="<?= $group['id']; ?>" ><?= trad($group['name'], 'user'); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <label for="newCompanies" class="col-sm-1 col-form-label"><?= trad('Companies', 'user'); ?></label>
                        <div class="col-sm-7">
                            <select id="newCompanies" class="form-control select2bs4" multiple="multiple" data-placeholder="<?= trad('Select a company', 'global'); ?>" style="width:100%" >
                                <?php foreach ($companies as $company): ?>
                                    <option value="<?= $company['id']; ?>"><?= $company['commercial_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-1">
                            <button type="button" class="btn btn-warning" id="addUserGroupCompanies"><?= trad('Add', 'global'); ?></button>
                        </div>
                    </div>
                </div>
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
                                            <select name="group_companies[<?= $i; ?>][group]" class="form-control select2bs4" required>
                                                <?php foreach ($groups as $group): ?>
                                                    <option value="<?= $group['id']; ?>" <?= ($user_group == $group['id']) ? 'selected' : ''; ?>><?= trad($group['name'], 'user'); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select name="group_companies[<?= $i; ?>][companies][]" class="form-control select2bs4" multiple="multiple" data-placeholder="<?= trad('Select a company', 'global'); ?>" required>
                                                <?php foreach ($companies as $company): ?>
                                                    <option value="<?= $company['id']; ?>" <?= (in_array($company['id'], $user_company) ? 'selected' : ''); ?>><?= $company['commercial_name']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
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
    <?php } ?>

    <div class="card card-primary">
        <div class="card-footer">
            <button type="submit" class="btn btn-warning float-right"><?= trad('Save', 'global'); ?></button>
        </div>
    </div>
</form>
<div class="clearfix"></div>
<br>
<script>
    var userGroups = <?= json_encode($groups, JSON_HEX_QUOT); ?>;
    var userCompanies = <?= json_encode($companies, JSON_HEX_QUOT); ?>
</script>