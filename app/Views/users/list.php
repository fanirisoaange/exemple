<?php if ($companyId) { ?>
    <div class="row">
        <?php companySelector(); ?>
        <div class="col-6 text-right">
            <?php if (isHeadAdmin()) { ?>
                <a href="<?= route_to('user_create') ?>"
                    class="btn btn-success mb-3">
                    <i class="nav-icon fas fa-plus"></i> <?php echo trad(
                        'Create'
                    ) ?>
                </a>
            <?php } ?>
        </div>
    </div>
<?php } ?>

<div class="row">
    <div class="col-12">
        <?php if ( ! $companyId): ?>
            <?php echo trad(
                'Please select the company for which to display the users list'
            ) ?>
        <?php else: ?>
            <div class="card">
                <div class="card-body">
                    <table id="userList" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <?php foreach ($users['columns'] as $col): ?>
                                    <th><?= $col; ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($users['users'])):
                                foreach ($users['users'] as $user):
                                    ?>
                                    <tr>
                                        <td>
                                            <?php if (isMember() || isMemberAccounting()) { ?>
                                                <a href="<?= route_to('user_detail', $user['id']); ?>" class="btn btn-outline-primary btn-xs"><i class="fas fa-eye"></i></a>
                                            <?php } ?>
                                            <?php if (userHasEditUserAccess($user['id'])) { ?>
                                                <a href="<?= route_to('user_edit', $user['id']); ?>" class="btn btn-outline-warning btn-xs"><i class="fas fa-edit"></i></a>
                                            <?php } ?>
                                            <?php if (isHeadAdmin()) { ?>
                                                <a href="#" 
                                                    onclick="ajaxUserDelete(event, <?php echo $user['id']; ?>, '<?php echo trad(
                                                        "This operation is irreverssible. Are you sure you want to delete this order ?"
                                                    ); ?>', '<?php echo trad(
                                                        "Deletion OK"
                                                    ); ?>', '<?php echo trad(
                                                        "Deletion Error"
                                                    ); ?>');"
                                                    title="<?php echo trad('Delete') ?>"
                                                    class="btn btn-outline-danger btn-xs"
                                                    >
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            <?php } ?>
                                        </td>
                                        <td><?= $user['id']; ?></td>
                                        <td><?= $user['firstname']; ?></td>
                                        <td><?= $user['lastname']; ?></td>
                                        <td><?= $user['email']; ?></td>
                                        <td><?= $user['status_name']; ?></td>
                                    </tr>
                                    <?php
                                endforeach;
                            endif;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>