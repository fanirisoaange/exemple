<div class="card">
    <div class="card-header bg-primary"><?php echo lang('Auth.create_group_heading'); ?></div>
    <div class="card-body">
        <p><?php echo lang('Auth.create_group_subheading'); ?></p>

        <div id="infoMessage"><?php echo $message; ?></div>

        <?php echo form_open("auth/create_group"); ?>

        <div class="form-group">
            <?php echo form_label(lang('Auth.create_group_name_label'), 'group_name'); ?> <br />
            <?php echo form_input($group_name + ['class' => 'form-control']); ?>
        </div>

        <div class="form-group">
            <?php echo form_label(lang('Auth.create_group_desc_label'), 'description'); ?> <br />
            <?php echo form_input($description + ['class' => 'form-control']); ?>
        </div>
        <hr />
        <div class="text-right">
            <a href="/auth" class="btn btn-secondary float-left"><?= trad('Back', 'global') ?></a>
            <?php echo form_submit('submit', lang('Auth.create_group_submit_btn'), 'class="btn btn-primary"'); ?>
        </div>

        <?php echo form_close(); ?>
    </div>
</div>