<div id="wrapper-login">
    <div class="text-center">
        <img src="/assets/img/logo-cardata-black.png" alt="Cardata" class="mb-4 img-fluid" />
    </div>

    <?php echo $message; ?>
    <h1><?php echo lang('Auth.forgot_password_heading'); ?></h1>
    <p><?php echo sprintf(lang('Auth.forgot_password_subheading'), $identity_label); ?></p>

    <?php echo form_open(route_to('forgot_password')); ?>

    <div class="input-group mb-3">
        <?php echo form_input($identity + ['class' => 'form-control', 'placeholder' => lang('Auth.login_identity_label')]); ?>
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-envelope"></span>
            </div>
        </div>
    </div>

    <p><?php echo form_submit('submit', lang('Auth.forgot_password_submit_btn'), 'class="btn btn-primary btn-block mt-3"'); ?></p>

    <?php echo form_close(); ?>
</div>
