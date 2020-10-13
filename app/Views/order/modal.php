<div class="modal fade" id="recipientMailModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="recipientMailModalLabel"><?php echo trad('Recipients') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-hover">
                    <thead>
                        <th><input onclick="checkAll()" onchange="removeError()" type="checkbox"></th>
                        <th><?php echo trad('Email') ?></th>
                        <th><?php echo trad('Firstname') ?></th>
                        <th><?php echo trad('Lastname') ?></th>
                    </thead>
                    <tbody>
                        <?php if (!$fromList) { ?>
                            <?php $users= model('UserModel')->getAccountantCompany((int)$order["company_to"], []) ?>
                            <?php if ($users) { ?>
                                <?php foreach ($users as $user) { ?>
                                    <tr>
                                        <td><input type="checkbox" onchange="removeError();"></td>
                                        <td user-id="<?php echo $user['id'] ?>"><?php echo $user['email'] ?></td>
                                        <td><?php echo $user['first_name'] ?></td>
                                        <td><?php echo $user['last_name'] ?></td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                    <tr>
                                        <td colspan="4" class="text-center"><?php echo trad('No recipient available') ?></td>
                                    </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer" style="position: absulute; left:40%; right:40%;">
                <?php if (!$fromList) { ?>
                    <?php if ($users) { ?>
                        <a id="btn-send-confirm" href="#" class="btn btn-app"
                           onclick="ajaxOrderSend(event, <?php echo $order["id"]; ?>, '<?php echo trad(
                               "Are you sure you want to send this order ?"
                           ); ?>', '<?php echo trad(
                               "Send success"
                           ); ?>', '<?php echo trad(
                               "Send fail"
                           ); ?>', '<?php echo trad(
                               "Please select at least one recipient before continuing"
                           ); ?>');"
                        >
                            <i class="fas fa-envelope"></i> <?php echo trad('Send') ?>
                        </a>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>