<div class=" col-sm-4 ml-2" id="userSubCompanies" data-url="<?= route_to('user_sub_companies'); ?>"></div>
<input type="hidden" id="session_message" value="<?php echo $session_message; ?>">
<input type="hidden" id="stripe_key" value="<?= getenv('stripe.api_secret'); ?>">

<div class="row no-print">
    <div class="col-12 mb-3">
        <div class="btn-group  float-right" role="group">
            <button id="btnPrint" target="_blank" class="btn btn-secondary"><i class="fas fa-print"></i> <?= trad('Print'); ?></button>
            <a href="<?= route_to('invoice_pdf', $invoice['id']); ?>"><button type="button" class="btn btn-danger">
                <i class="fa fa-file-pdf"></i> <?= trad('Download PDF'); ?>
            </button></a>
        </div>
    </div>
</div>
<div id="cardInvoice" class="invoice p-3 mb-4">
    <!-- title row -->
    <div class="row">
        <div class="col-12">
            <h2 class="page-header">
                <?= img(ASSETS . 'img/logo-cardata-black.png', false, 'alt="Cardata" class="img-fluid logo"') ?>
                <small class="float-right" style="text-align: right">
                    <b><?= trad('Invoice'); ?>: <?= $invoice['id']; ?></b><br>
                    <small><?= trad('Created on'); ?>: <?= date_format(date_create($invoice['invoice_date']), "d/m/Y"); ?></small><br>
                </small>
            </h2>
            <div class="clearfix"></div>
            <hr  />
        </div>
        <!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info mb-3">
        <div class="col-6 invoice-col">
            <?= trad('From'); ?>
            <address>
                <strong><?= $invoice['from']['fiscal_name']; ?></strong><br>
                <?= $invoice['from']['address_1']; ?><br>
                <?= $invoice['from']['zip_code'] . " " . $invoice['from']['city_display']; ?><br>
                <?= trad('Phone'); ?>: <?= $invoice['from']['phone_number']; ?><br>
                <?= trad('Email'); ?>: <?= $invoice['from']['email']; ?>
            </address>
        </div>
        <!-- /.col -->
        <div class="col-6 invoice-col">
            <?= trad('To'); ?>
            <address>
                <strong><?= $invoice['to']['fiscal_name']; ?></strong><br>
                <?= $invoice['to']['address_1']; ?><br>
                <?= $invoice['to']['zip_code'] . " " . $invoice['to']['city_display']; ?><br>
                <?= trad('Phone'); ?>: <?= $invoice['to']['phone_number']; ?><br>
                <?= trad('Email'); ?>: <?= $invoice['to']['email']; ?>
            </address>
        </div>
            <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- Table row -->
    <div class="row">
        <div class="col-12 table-responsive mb-4">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><?= trad('Order'); ?></th>
                        <th><?= trad('Product'); ?></th>
                        <th><?= trad('Quantity'); ?></th>
                        <th><?= trad('CPM'); ?></th>
                        <th><?= trad('Subtotal'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($invoice['orders'] as $o1) { foreach($o1 as $o) { ?>
                    <tr>
                        <td><a style="text-decoration: none" href="<?= route_to('order_detail', $o['order_id']); ?>">#<?= $o['order_id']; ?></a></td>
                        <td><?= $o['name']; ?></td>
                        <td><?= $o['quantity']; ?></td>
                        <td><?= $o['price']; ?>€</td>
                        <td><?= $o['quantity'] * $o['price']; ?>€</td>
                    </tr>
                    <?php } }?>
                </tbody>
            </table>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
        <!-- accepted payments column -->
            <div class="col-6">
            <?php if($invoice['status'] == 1) { ?> <p class="lead"><?= trad('Payment Methods'); ?>:</p>
                <img src="<?= base_url(); ?>/assets/img/<?php if($invoice['payment_method'] == 'SEPA') echo 'sepa.png'; else echo 'cb.jpg'; ?>" alt="Stripe" style="height: 4em">
            <?php }

            else if($invoice['status'] == App\Enum\InvoiceStatus::CANCELLED) { 
                if($invoice['error_code'] == 'authentication_required') { ?>
                     <script src="https://js.stripe.com/v3/"></script>
                     <p class="lead"><?= trad('Payment Status'); ?>: <span style="font-size: 90%"> Authentication required </span> </p>
                     <button id="invoicePay" data-client="<?= $client_secret; ?>" data-pm="<?= $invoice['stripe_payment_method']; ?>" class="btn btn-secondary"><?= trad('Pay now'); ?></button>
                <?php }
             } ?>
        </div>
        <!-- /.col -->
        <div class="col-6">
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th style="width:50%"><?= trad('Subtotal'); ?> <sup><?= trad('HT'); ?></sup></th>
                        <td><?= $invoice['subtotal'];?>€</td>
                    </tr>
                    <tr>
                        <th><?= trad('VAT'); ?> (20%)</th>
                        <td><?= $invoice['vat']; ?>€</td>
                    </tr>
                    <tr>
                        <th><?= trad('Total'); ?> <sup><?= trad('TTC'); ?></sup></th>
                        <td><?= $invoice['total']; ?>€</td>
                    </tr>
                </table>
            </div>
        </div>
        <!-- /.col -->
    </div>

</div>

<?php if($invoice['status'] == App\Enum\InvoiceStatus::CANCELLED) { ?>

<div class="modal" id="modalPayInvoice" aria-modal="true">
        <div class="modal-dialog">
          <div class="modal-content">

            <div class="modal-header">
              <h4 class="modal-title"><?= trad('Pay invoice') . $invoice['id']; ?> </h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">
          
                    <form action="#" method="post" id="payment-form">               
                    <span class="cardLabel1"><?= trad('Card information'); ?></span>
                    <div class="fieldset">
                          <div id="card-card-number" class="field empty StripeElement"></div>
                          <span style="color: #fa755a; font-size: 90%" id="cardError"></span>
                          <div style="display: flex; flex-direction: column; width: 70%">
                            <div id="card-card-expiry" class="field empty StripeElement"></div>
                            <span style="color: #fa755a; font-size: 90%" id="cardExpiryError"></span>
                            </div>
                            <div style="display: flex; flex-direction: column; width: 28%">
                          <div id="card-card-cvc" class="field empty half-width StripeElement"></div>
                          <span style="color: #fa755a; font-size: 90%" id="cardCVCError"></span>
                        </div>
                    </div>               
                  <span  style="color: #fa755a; font-size: 90%" class="error"></span>
                  </form>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal"><?= trad('Close'); ?></button>
              <button type="button" id="card-button" data-tid="elements_examples.form.pay_button" class="btn btn-primary"><?= trad('Confirm'); ?></button>
            </div>
          </div>
        </div>
      </div>

<?php } ?>