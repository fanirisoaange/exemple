<div class="card">
    <div class="card-header bg-primary"><?php echo lang('Auth.create_user_heading'); ?></div>
    <div class="card-body">
        <p><?php echo lang('Auth.create_user_subheading'); ?></p>

        <?php echo $message; ?>

        <?php echo form_open('auth/create_user'); ?>
        <div class="row">
            <div class="col-md-6">


                <div class="form-group">
                    <?php echo form_label(lang('Auth.create_user_fname_label'), 'first_name'); ?> <br />
                    <?php echo form_input($first_name + ['class' => 'form-control']); ?>
                </div>

                <div class="form-group">
                    <?php echo form_label(lang('Auth.create_user_lname_label'), 'last_name'); ?> <br />
                    <?php echo form_input($last_name + ['class' => 'form-control']); ?>
                </div>

                <?php
                if ($identity_column !== 'email') {
                    echo '<p>';
                    echo form_label(lang('Auth.create_user_identity_label'), 'identity');
                    echo '<br />';
                    echo form_error('identity');
                    echo form_input($identity);
                    echo '</p>';
                }
                ?>

                <div class="form-group">
                    <?php echo form_label(lang('Auth.create_user_company_label'), 'company'); ?> <br />
                    <?php echo form_input($company + ['class' => 'form-control']); ?>
                </div>

                <div class="form-group">
                    <?php echo form_label(lang('Auth.create_user_email_label'), 'email'); ?> <br />
                    <?php echo form_input($email + ['class' => 'form-control']); ?>
                </div>

                <div class="form-group">
                    <?php echo form_label(lang('Auth.create_user_phone_label'), 'phone'); ?> <br />
                    <?php echo form_input($phone + ['class' => 'form-control']); ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?php echo form_label(lang('Auth.create_user_password_label'), 'password'); ?> <br />
                    <?php echo form_input($password + ['class' => 'form-control']); ?>
                </div>

                <div class="form-group">
                    <?php echo form_label(lang('Auth.create_user_password_confirm_label'), 'password_confirm'); ?> <br />
                    <?php echo form_input($password_confirm + ['class' => 'form-control']); ?>
                </div>
            </div>
        </div>
        <div class="text-right">
            <a href="/auth" class="btn btn-secondary float-left"><?= trad('Back', 'global') ?></a>
            <?php echo form_submit('submit', lang('Auth.create_user_submit_btn'), 'class="btn btn-primary"'); ?>
        </div>

        <?php echo form_close(); ?>
    </div>
</div>
