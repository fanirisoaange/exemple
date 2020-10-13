<script src="https://js.stripe.com/v3/"></script>
<input type="hidden" id="session_message" value="<?php echo $session_message; ?>">
<input type="hidden" id="stripe_key" value="<?= getenv('stripe.api_secret'); ?>">
<input type="hidden" id="tradPmActive" value="<?= trad('Active payment method updated');?>">
<input type="hidden" id="tradPmDelete" value="<?= trad('Payment method deleted'); ?>">
<input type="hidden" id="tradPmUnable" value="<?= trad('Unable to delete the payment method');?>">

<div class="row">
    <div class="col-12 text-right">
        <?php if($companyId) { ?>
            <a href="#" id="addPaymentMethodBtn" class="btn btn-success mb-3">
                <i class="nav-icon fas fa-plus"></i> <?= trad('Add payment method'); ?>
            </a>
        <?php } ?>
    </div>
</div>
<div class="card card-primary card-outline">
    <div class="card-body">
        <?php if ( !$companyId) { 
            echo trad(
                'Please select the company for which to display the payment methods'
            ); }
        else if(!count($data)) {  ?> <span id="noPaymentYet"> <?php 
             echo trad(
                'No payment methods yet'
            ); ?> </span> <?php } ?>

        <table <?php if(!$companyId || !count($data)) echo 'style="display: none;"'; ?> class="table dataTable" id="tablePaymentMethod">
            <thead>
                <tr>
                    <th><?= trad('Active'); ?></th>
                    <th><?= trad('Type'); ?></th>
                    <th><?= trad('Number'); ?></th>
                    <th><?= trad('Expiry'); ?></th>
                    <th><?= trad('Status'); ?></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                 <tr id="trCardSample" style="display: none">
                    <td class="tdActive">
                        <div class="icheck-success d-inline">
                            <input type="radio" id="default01" name="default">
                            <label for="default01"></label>
                        </div>
                    </td>
                    <td style="display: none">
                        <input type="checkbox" checked data-toggle="toggle" data-on="Active" data-width="100" data-off="Inactive " data-size="sm" data-onstyle="success" data-offstyle="danger">
                    </td>
                    <td><img src="/library/theme-lte/img/credit/visa.png" alt="Visa"> <?= trad('Credit Card'); ?></td>
                    <td class="tdLastFour">
                    </td>
                    <td class="tdExpiry"></td>
                    <td class="tdStatus"></td>
                    <td>
                        <button class="btn btn-outline-warning btn-sm"><i class="fa fa-edit"></i></button>
                        <button class="btn btn-outline-danger btn-sm btnShowDelete"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
                <?php $c=0; foreach($data as $d) { $c++; ?>
                <tr data-id='<?=$d['id'];?>'>
                    <td>
                        <div class="icheck-success d-inline">
                            <input type="radio" id="default<?= $c; ?>" name="default" <?php if($d['active']) echo 'checked'; ?>>
                            <label for="default<?= $c; ?>"></label>
                        </div>
                    </td>
                    <td style="display: none">
                        <input type="checkbox" checked data-toggle="toggle" data-on="Active" data-width="100" data-off="Inactive " data-size="sm" data-onstyle="success" data-offstyle="danger">
                    </td>
                    <td><img src="/library/theme-lte/img/credit/visa.png" alt="Visa"> <?= trad('Credit Card'); ?></td>
                    <td><?php if($d['type'] == 'card') { ?>
                        ****-<?= $d['last_four']; ?>
                            <?php } ?>
                    </td>
                    <td><?= $d['expiry']; ?></td>
                    <td><?= payment_method_status($d['status']); ?></td>
                    <td>
                        <button class="btn btn-outline-warning btn-sm"><i class="fa fa-edit"></i></button>
                        <button class="btn btn-outline-danger btn-sm btnShowDelete"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
                 <?php } ?>  
            </tbody>
        </table>
    </div>
</div>

<div class="modal" id="modalAddPaymentMethod" aria-modal="true">
        <div class="modal-dialog">
          <div class="modal-content">

            <div class="modal-header">
              <h4 class="modal-title"><?= trad('Add Payment Method'); ?></h4>
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

<div class="modal" id="modalDeletePaymentMethod" aria-modal="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title"><?= trad('Delete Payment Method'); ?></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">
                <span><?= trad("Are you sure ? This can't be undone.");?></span>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal"><?= trad('Close'); ?></button>
              <button type="button" class="btn btn-danger btnDelete"><?= trad('Delete'); ?></button>
            </div>
          </div>        
        </div>
 </div>