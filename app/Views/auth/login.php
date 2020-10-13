<div id="wrapper-login">
    <div class="text-center">
        <img src="/assets/img/logo-cardata-black.png" alt="Cardata" class="mb-4 img-fluid" />
    </div>

    <?php echo $message; ?>

    <?php echo form_open('login'); ?>
    <div class="input-group mb-3">
        <?php echo form_input($identity + ['class' => 'form-control', 'placeholder' => lang('Auth.login_identity_label')]); ?>
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-envelope"></span>
            </div>
        </div>
    </div>
    <div class="input-group mb-3">
        <?php echo form_input($password + ['class' => 'form-control', 'placeholder' => lang('Auth.login_password_label')]); ?>
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-lock"></span>
            </div>
        </div>
    </div>

    <div class="custom-control custom-checkbox">
        <?php echo form_checkbox('remember', '1', false, 'id="remember" class="custom-control-input"'); ?>                        
        <?php echo form_label(lang('Auth.login_remember_label'), 'remember', ['class' => 'custom-control-label']); ?>
    </div>

    <?php echo form_submit('submit', lang('Auth.login_submit_btn'), 'class="btn btn-primary btn-block mt-3"'); ?>

    <!-- /.col -->

    <?php echo form_close(); ?>

    <p class="mb-1">
        <a href="forgot_password"><?php echo lang('Auth.login_forgot_password'); ?></a>
    </p>
<!--    <p class="mb-0">
        <a href="register.html" class="text-center">Register a new membership</a>
    </p>-->

</div>
