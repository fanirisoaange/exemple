<div class="card">
    <div class="card-header"><?php echo lang('Auth.edit_user_heading'); ?></div>
    <div class="card-body">

        <p><?php echo lang('Auth.edit_user_subheading'); ?></p>

        <div id="infoMessage"><?php echo $message; ?></div>

        <?php echo form_open(uri_string()); ?>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <?php echo form_label(lang('Auth.edit_user_fname_label'), 'first_name'); ?> <br />
                    <?php echo form_input($first_name + ['class' => 'form-control']); ?>
                </div>

                <div class="form-group">
                    <?php echo form_label(lang('Auth.edit_user_lname_label'), 'last_name'); ?> <br />
                    <?php echo form_input($last_name + ['class' => 'form-control']); ?>
                </div>

                <div class="form-group">
                    <?php echo form_label(lang('Auth.edit_user_company_label'), 'company'); ?> <br />
                    <?php echo form_input($company + ['class' => 'form-control']); ?>
                </div>

                <div class="form-group">
                    <?php echo form_label(lang('Auth.edit_user_phone_label'), 'phone'); ?> <br />
                    <?php echo form_input($phone + ['class' => 'form-control']); ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?php echo form_label(lang('Auth.edit_user_password_label'), 'password'); ?> <br />
                    <?php echo form_input($password + ['class' => 'form-control']); ?>
                </div>

                <div class="form-group">
                    <?php echo form_label(lang('Auth.edit_user_password_confirm_label'), 'password_confirm'); ?><br />
                    <?php echo form_input($password_confirm + ['class' => 'form-control']); ?>
                </div>

                <?php if ($ionAuth->isAdmin()): ?>
                    <hr />
                    <h5><?php echo lang('Auth.edit_user_groups_heading'); ?></h5>
                    <?php foreach ($groups as $group): ?>

                        <div class="custom-control custom-checkbox custom-control-inline">
                            <?php
                            $gID = $group['id'];
                            $checked = null;
                            $item = null;
                            foreach ($currentGroups as $grp) {
                                if ($gID == $grp->id) {
                                    $checked = ' checked';
                                    break;
                                }
                            }
                            ?>
                            <input id="group_<?= $group['id'] ?>" class="custom-control-input" type="checkbox" name="groups[]" value="<?php echo $group['id']; ?>"<?php echo $checked; ?>>
                            <label class="custom-control-label" for="group_<?= $group['id'] ?>">
                                <?php echo htmlspecialchars($group['name'], ENT_QUOTES, 'UTF-8'); ?>
                            </label>
                        </div>
                    <?php endforeach ?>

                <?php endif ?>
            </div>
        </div>
        <?php echo form_hidden('id', $user->id); ?>
        <div class="text-right">
            <a href="/auth" class="btn btn-secondary float-left"><?= trad('Back', 'global') ?></a>
            <?php echo form_submit('submit', lang('Auth.edit_user_submit_btn'), 'class="btn btn-primary float-right"'); ?>
        </div>

        <?php echo form_close(); ?>
    </div>
</div>