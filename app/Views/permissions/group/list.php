

<div id="addPermission">
    <?php
    if (!empty($permission_msg)):
        switch ($permission_msg['status']) :
            case 'success':
                $alert_class = 'success';
                break;
            case 'error':
                $alert_class = 'danger';
                break;
            default:
                $alert_class = 'info';
                break;
        endswitch;
        ?>
        <div class="alert alert-<?= $alert_class; ?> alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <?= $permission_msg['msg']; ?>
        </div>
    <?php endif; ?>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title float-right">
                <a class="btn btn-success" data-toggle="collapse" data-parent="#addPermission" href="#collapseOne">
                    <?= trad('Add permission', 'permission'); ?>
                </a>
            </h4>
        </div>
        <div id="collapseOne" class="panel-collapse collapse in">
            <?= $form_permission; ?>
        </div>
    </div>
</div>


<div class="card">
    <div class="card-body">
        <?php if (!empty($groups_permissions)): ?>
            <table class="table" id="permissionsList">
                <thead>
                    <tr>
                        <th><?= trad('Module::Action', 'permission'); ?></th>
                        <?php foreach ($groups_permissions['groups'] as $group): ?>
                            <th class="upper-text"><?= $group['name']; ?> <i class="fas fa-info-circle text-info text-sm" data-content="<?= $group['description']; ?>" data-toggle="popover" data-trigger="hover"  data-placement="top"></i></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($groups_permissions['permissions'] as $permission): ?>
                        <tr>
                            <td>
                                <?= $permission['name']; ?> <i class="fas fa-info-circle text-info text-sm" data-content="<?= trad('Var name', 'permission') . ': ' . $permission['var'] . '<br>' . $permission['comments']; ?>" data-toggle="popover" data-trigger="hover"  data-placement="right" data-html="true"></i></td>
                                <?php foreach ($groups_permissions['groups'] as $group): ?>
                                <td>
                                    <input type="checkbox" class=" updateGroupPermission" value="1" data-url="<?= route_to('groups_permissions_update'); ?>" data-group_id="<?= $group['id']; ?>" data-permission_id="<?= $permission['id']; ?>" <?= (!empty($groups_permissions['groups_permissions'][$permission['id']][$group['id']]) ? 'checked' : ''); ?>/>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>