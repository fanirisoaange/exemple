<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title"><?= trad('Order'); ?></h3>
    </div>
    <div class="card-body">
        <div class="row border-bottom p-2">
            <div class="col-sm-2">
                <b>ID</b>
            </div>
            <div class="col-sm-10">
                <?= $order["id"]; ?>
            </div>
        </div>
        <div class="row border-bottom p-2">
            <div class="col-sm-2">
                <b><?= trad('Status'); ?></b>
            </div>
            <div class="col-sm-10">
                <?php
                $orderStatus = (int)$order["progress_status"];
                $status = trad(
                    \App\Enum\OrderStatus::getDescriptionById(
                        $orderStatus
                    )
                );
                ?>
                <?php order_status($orderStatus); ?>
            </div>
        </div>
        <div class="row p-2 text-muted text-right">
            <div class="col-sm-12">
                <small><b><?= trad('Created'); ?>
                        :</b> <?= $order['order_at']; ?></small>
                <?php if($order['accepted_at']) { ?> <br><small><b><?= trad('Accepted'); ?>
                        : </b><?= date_format(date_create($order['accepted_at']), "d/m/Y h:i"); ?></small> <?php } ?>
            </div>
        </div>
    </div>
</div>

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title"><?= trad('Products'); ?></h3>
    </div>
    <div class="card-body">
        <table id="orderProducts" class="table table-hover">
            <thead>
            <tr>
                <th style="width: 5%"></th>
                <th style="width: 50%"><?= trad('Name', 'global'); ?></th>
                <th style="width: 10%"><?= trad('Quantity', 'global'); ?></th>
                <th style="width: 10%"><?= trad('Unity', 'global'); ?></th>
                <th style="width: 15%"><?= trad('Price', 'global'); ?></th>
                <th style="width: 20%"><?= trad('Subtotal', 'global'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            if ( ! empty($order)):
                foreach ($order['products'] as $product):
                    ?>
                    <tr>
                        <td></td>
                        <td>
                            <div class="form-group">
                                <?= $product['name']; ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <?= $product['quantity']; ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <?= product_price_type($product['product_price_type']); ?>
                            </div>
                        </td>
                        <td>
                            <?= $product['price'] ?>&#128; <sup>HT</sup>
                        </td>
                        <td>
                            <?= $product['quantity'] * $product['price'] ?>
                            &#128; <sup>HT</sup>
                        </td>
                    </tr>
                <?php
                endforeach;
            endif;
            ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title"><?= trad('Company'); ?></h3>
    </div>
    <div class="card-body">
        <div class="row border-bottom p-2">
            <div class="col-sm-2">
                <b><?= trad('Fiscal name'); ?></b>
            </div>
            <div class="col-sm-10">
                <?= $order["fiscal_name"]; ?>
            </div>
        </div>
        <div class="row border-bottom p-2">
            <div class="col-sm-2">
                <b><?= trad('Commercial name'); ?></b>
            </div>
            <div class="col-sm-10">
                <?= $order["commercial_name"]; ?>
            </div>
        </div>
        <div class="row border-bottom p-2">
            <div class="col-sm-2">
                <b><?= trad('Address'); ?></b>
            </div>
            <div class="col-sm-10">
                <?= $order["address_1"].' '.$order["address_2"]; ?>
                <?= ', '.$order["city"]; ?>
                <?= ', '.$order["zip_code"]; ?>
                <?= $order["city_display"]; ?>
            </div>
        </div>
    </div>
</div>

<div class="card card-primary">
    <div class="card-footer">
        <?php if (canEditOrder($orderStatus)) { ?>
            <a class="btn btn-app"
               href="<?php echo route_to('order_edit', $order['id']) ?>">
                <i class="fas fa-edit"></i> <?php echo trad('Edit') ?>
            </a>
        <?php } ?>

        <a class="btn btn-app" href="<?= route_to('order_pdf', $order['id']); ?>">
            <i class="fas fa-file-pdf"></i> <?php echo trad('View PDF') ?>
        </a>

        <?php if (canSendOrder($orderStatus)) { ?>
            <a href="#" class="btn btn-app"
               onclick="ajaxOrderSend(event, <?php echo $order["id"]; ?>, '<?php echo trad("Are you sure you want to send this order ?"); ?>', '<?php echo trad("Send success"); ?>', '<?php echo trad("Send fail"); ?>', '<?php echo trad("Please select at least one recipient before continuing"); ?>');"
            >
                <i class="fas fa-envelope"></i> <?php echo trad('Send') ?>
            </a>
        <?php } ?>

        <?php if (canValidateOrder($orderStatus)) { ?>
            <button class="btn btn-app" type="submit"
                    onclick="ajaxOrderValidate(event, <?php echo $order["id"]; ?>, '<?php echo trad(
                        "Validation OK"
                    ); ?>', '<?php echo trad("Validation Error"); ?>');"
            >
                <i class="fas fa-check"></i> <?php echo trad('Validate order') ?>
            </button>
        <?php } ?>

        <?php if (canDraftOrder($orderStatus)) { ?>
            <a href="#" class="btn btn-app"
               onclick="ajaxOrderDraft(event, <?php echo $order["id"]; ?>, '<?php
                echo trad(
                "Are you sure you want to draft this order ?"
               ); ?>', '<?php echo trad(
                   "Draft success"
               ); ?>', '<?php echo trad(
                   "Draft fail"
               ); ?>', 'draft');"
               title="<?php echo trad('Draft') ?>"
               class="btn btn-outline-danger">
               <i class="fas fa-arrow-left"></i> <?php echo trad('Draft') ?>
            </a>
        <?php } ?>

        <?php if (canCancelOrder($orderStatus)) { ?>
            <a href="#" class="btn btn-app"
               onclick="ajaxOrderCancel(event, <?php echo $order["id"]; ?>, '<?php echo trad(
                   "Are you sure you want to cancel this order ?"
               ); ?>', '<?php echo trad(
                   "Cancel success"
               ); ?>', '<?php echo trad(
                   "Cancel fail"
               ); ?>');"
            >
                <i class="fa fa-window-close"></i> <?php echo trad('Cancel') ?>
            </a>
        <?php } ?>

        <?php if (canDeleteOrder($orderStatus)) { ?>
            <a href="#" class="btn btn-app"
               onclick="ajaxOrderDelete(event, <?php echo $order["id"]; ?>, '<?php echo trad(
                   "This operation is irreverssible. Are you sure you want to delete this order ?"
               ); ?>', '<?php echo trad(
                   "Deletion OK"
               ); ?>', '<?php echo trad(
                   "Deletion Error"
               ); ?>');"
               title="<?php echo trad('Delete') ?>"
               class="btn btn-outline-danger">
                <i class="fas fa-trash"></i> <?php echo trad('Trash') ?>
            </a>
        <?php } ?>

    </div>
</div>