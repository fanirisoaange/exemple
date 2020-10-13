<input type="hidden" id="session_message" value="<?php echo $message; ?>">
<input type="hidden" id="ajaxUrl" value="<?= route_to('invoice_pagelength'); ?>">
<?php if($companyId) { ?> 
<div class="row" style="align-items: center; margin-bottom: 0.9em">
    <div class="col-6" style="align-items: center; display: flex"><span style="font-weight: bold; font-size: 95%; margin-right: 0.7em">Company</span>
    <select id="sub_company" class="form-control select2"> <?php foreach($user_sub_companies as $k => $v) { ?>
        <optgroup label="<?= $k; ?>">
        <?php foreach($v as $sc) { ?> 
            <option <?php if($sc['id'] == $user_sub_company) echo 'selected'; ?> value="<?= $sc['id']; ?>"><?= $sc['fiscal_name']; ?></option>
        <?php }?>
        </optgroup>
    <?php } ?> 
    </select>
    </div>
    <div class="col-6 text-right" style="align-items: center">
        <a href="<?= route_to('invoice_create'); ?>" class="btn btn-success mb-3">
            <i class="nav-icon fas fa-plus"></i> <?= trad('Create invoice'); ?>
        </a>
    </div>
</div>
<?php } ?>

<div class="card card-primary card-outline">
    <div class="card-body">
        <?php if ( ! $companyId): ?>
                    <?php echo trad(
                        'Please select the company for which to display the invoices'
                    ) ?>
                <?php else: ?>

        <table class="table dataTableInvoice">
            <thead>
                <tr>
                    <th><?= trad('Date'); ?></th>
                    <th><?= trad('Bill'); ?></th>
                    <th><?= trad('Status'); ?></th>
                    <th><?= trad('Total'); ?></th>
                    <th><?= trad('Payment method'); ?></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($invoices as $i) { ?>

                <tr>
                    <td><?= date_format(date_create($i['invoice_date']), "d M Y"); ?></td>
                    <td><?= $i['id']; ?></td>
                    <td><?php echo invoice_status($i['status']); ?></td>
                    <td><?= $i['total']; ?>€</td>
                    <td> <?php if($i['status'] == 1) { ?> <img style="height: 2.5em" src="<?= base_url(); ?>/assets/img/<?php if($i['payment_method'] == 'SEPA') echo 'sepa.png'; else echo 'cb.jpg'; ?>" alt="Visa"> <?php } ?></td>
                    <td>
                        <a href="<?= route_to('invoice_show', $i['id']); ?>" class="btn btn-outline-warning"><i class="far fa-eye"></i></a> 
                        <?php if($i['status'] == 1) { ?> <a href="<?= route_to('invoice_pdf', $i['id']); ?>" class="btn btn-outline-danger" disabled><i class="far fa-file-pdf"></i></a>
                        <?php } ?> 
                    </td>
                </tr>
                <?php }?>

            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>