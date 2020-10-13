<?php if ($companyId && canCreateOrder()) { ?>
    <div class="row">
        <?php companySelector(); ?>
        <div class="col-6 text-right">
            <a href="<?= route_to('order_create') ?>"
               class="btn btn-success mb-3">
                <i class="nav-icon fas fa-plus"></i> <?php echo trad(
                    'Create order'
                ) ?>
            </a>
        </div>
    </div>
<?php } ?>

<div class="card card-primary card-outline">
    <div class="card-body">
        <?php if ( ! $companyId): ?>
            <?php echo trad(
                'Please select the company for which to display the order'
            ) ?>
        <?php else: ?>
            <table id="userList"
                   class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>#<?php echo trad('Order ID') ?></th>
                    <th><?php echo trad('Date') ?></th>
                    <th><?php echo trad('Status') ?></th>
                    <th><?php echo trad('Action') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($orders as $order) { ?>
                  <?php $orderStatus = (int)$order["progress_status"]; ?>
                  <?php if (canViewOrder((int)$order['progress_status'])) { ?>
                    <tr>
                        <td><?php echo $order["id"]; ?></td>
                        <td><?php echo $order["order_at"]; ?></td>
                        <td>
                            <?php order_status($orderStatus); ?>
                        </td>
                        <td>

                            <a href="<?= route_to(
                                'order_detail',
                                $order["id"]
                            ); ?>"
                               title="<?php echo trad('View order') ?>"
                               class="btn btn-outline-warning"><i
                                        class="far fa-eye"></i>
                            </a>

                            <?php if (canEditOrder($orderStatus)) { ?>
                              <a href="<?= route_to(
                                  'order_edit',
                                  $order["id"]
                              ); ?>"
                                 title="<?php echo trad('Edit order') ?>"
                                 class="btn btn-outline-info"><i
                                          class="far fa-edit"></i>
                              </a>
                            <?php } ?>

                            <a href="<?= route_to('order_pdf', $order['id']); ?>"
                               title="<?php echo trad('View PDF') ?>"
                               class="btn btn-outline-danger"><i
                                        class="far fa-file-pdf"></i>
                            </a>

                            <?php if (canValidateOrder($orderStatus)) { ?>
                                <a href="#"
                                   onclick="ajaxOrderValidate(event, <?php echo $order["id"]; ?>, '<?php echo trad(
                                       "Validation OK"
                                   ); ?>', '<?php echo trad(
                                       "Validation Error"
                                   ); ?>');"
                                   title="Validate"
                                   class="btn btn-outline-success"><i
                                            class="fa fa-check"></i>
                                </a>
                            <?php } ?>

                            <?php if (canDraftOrder($orderStatus)) { ?>

                                <a href="#"
                                   onclick="ajaxOrderDraft(event, <?php echo $order["id"]; ?>, '<?php echo trad(
                                       "Are you sure you want to draft this order ?"
                                   ); ?>', '<?php echo trad(
                                       "Draft success"
                                   ); ?>', '<?php echo trad(
                                       "Draft fail"
                                   ); ?>', 'draft');"
                                   title="<?php echo trad('Draft') ?>"
                                   class="btn btn-outline-danger"><i
                                            class="fas fa-arrow-left"></i>
                                </a>

                            <?php } ?>

                            <?php if (canSendOrder($orderStatus)) { ?>
                                <a href="#"
                                   onclick="ajaxOrderSend(event, <?php echo $order["id"]; ?>, '<?php echo trad("Are you sure you want to send this order ?"); ?>', '<?php echo trad("Send success"); ?>', '<?php echo trad("Send fail"); ?>', '<?php echo trad("Please select at least one recipient before continuing"); ?>');"
                                   
                                   title="<?php echo trad('Send') ?>"
                                   class="btn btn-outline-success"><i
                                            class="fas fa-envelope"></i>
                                </a>
                            <?php } ?>

                            <?php if (canCancelOrder($orderStatus)) { ?>
                                <a href="#"
                                   onclick="ajaxOrderCancel(event, <?php echo $order["id"]; ?>, '<?php echo trad(
                                       "Are you sure you want to cancel this order ?"
                                   ); ?>', '<?php echo trad(
                                       "Cancel success"
                                   ); ?>', '<?php echo trad(
                                       "Cancel fail"
                                   ); ?>');"
                                   title="<?php echo trad('Cancel') ?>"
                                   class="btn btn-outline-danger"><i
                                            class="fa fa-window-close"></i>
                                </a>
                            <?php } ?>

                            <?php if (canDeleteOrder($orderStatus)) { ?>
                            <a href="#"
                               onclick="ajaxOrderDelete(event, <?php echo $order["id"]; ?>, '<?php echo trad(
                                   "This operation is irreverssible. Are you sure you want to delete this order ?"
                               ); ?>', '<?php echo trad(
                                   "Deletion OK"
                               ); ?>', '<?php echo trad(
                                   "Deletion Error"
                               ); ?>');"
                               title="<?php echo trad('Delete') ?>"
                               class="btn btn-outline-danger"><i
                                        class="fas fa-trash"></i>
                            </a>
                            <?php } ?>

                        </td>
                    </tr>
                    <?php } ?>
                <?php } ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php echo view('order/modal.php', ['fromList'=> true, 'order'=> null]); ?>
