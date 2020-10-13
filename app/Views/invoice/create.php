<div class="invoice p-3 mb-4">
    <?php if ((int)$_SESSION['current_main_company'] === 0): ?>
        <?php echo trad(
            'Please select the company for which to display the order creation'
        ) ?>
    <?php else: ?>
        <!-- title row -->
         <form method="post" id="formInvoice" action="<?php echo base_url();?>/invoice/createManual">
        <div class="row">
            <div class="col-12">
                <h2 class="page-header d-inline-block">
                    <?= img(ASSETS . 'img/logo-cardata-black.png', false, 'alt="Cardata" class="img-fluid logo"') ?>
                </h2>
                <div class="float-right">
                <span  class="input-group">
                    <input type="date" name="date" class="form-control" id="invoiceDate" placeholder="aaaa-mm-dd"  />
                    <span class="input-group-append">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </span>
                </span>
                </div>

            </div>

        </div>

        <hr />
        <!-- /.col -->

        <!-- info row -->
        <div class="row invoice-info mb-3">
            <div class="col-sm-5 invoice-col">
               <select name="company" class="custom-select" style="width:50%">
                    <option value="1">Cardata S.A</option>
                    
                </select>
                <address>
                    <?php $currentCompany = model('CompanyModel')->selectCompany(1); ?>
                    <strong><?php echo $currentCompany['fiscal_name'] ?></strong><br>
                    <?php echo $currentCompany['address_1'] . ' ' . $currentCompany['address_2'] ?><br>
                    <?php echo $currentCompany['zip_code'] . ' ' . $currentCompany['city'] ?><br>
                    <?php echo trad('Phone') ?>: <?php echo $currentCompany['phone_number'] ?><br>
                    <?php echo trad('Email') ?>: <?php echo $currentCompany['email'] ?>
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col" id="divCompany">
                <?php echo trad('To') ?>
                <address id="companySelected">
                    <?php $currentCompany = model('CompanyModel')->selectCompany((int)$_SESSION['current_sub_company']); ?>
                    <strong><?php echo $currentCompany['fiscal_name'] ?></strong><br>
                    <?php echo $currentCompany['address_1'] . ' ' . $currentCompany['address_2'] ?><br>
                    <?php echo $currentCompany['zip_code'] . ' ' . $currentCompany['city'] ?><br>
                    <?php echo trad('Phone') ?>: <?php echo $currentCompany['phone_number'] ?><br>
                    <?php echo trad('Email') ?>: <?php echo $currentCompany['email'] ?>
                </address>
            </div>

            <!-- /.col -->
        </div>
        <!-- /.row -->
       
            <input type="hidden" name="companyTo"  id="companyTo" value="<?php echo (int)$_SESSION['current_sub_company']; ?>">
        <!-- Table row -->
        <div class="row">
            <div class="col-12 table-responsive" id="products">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th></th>
                        <th><?php echo trad('Order') ?></th>
                        <th><?php echo trad('At'); ?></th>
                        <th><?php echo trad('Product') ?></th>
                        <th><?php echo trad('Subtotal') ?></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr id="firstLine" style="display:none">
                        <input type="hidden">
                        <td width="7%" style="padding-right: 0px!important;">
                        </td>
                        <td width="15%%">

                        </td>
                        <td width="15%">
                           
                        </td>
                        <td width="15%">
                            
                        </td>
                        <td width="15%">0 €</td>
                        <td width="8%">
                            <button type="button" class="btn btn-outline-danger btn-sm btn-remove"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="row">
    
            <div style="display: flex; width: 25em">
                 <span class="input-group list">
                                <select name="add_product" id="add-order-select" class="custom-select">
                                    <option value=""><?= trad('Select'); ?></option>
                                    <?php foreach ($orders as $o) { ?>
                                        <option data-date="<?= $o['order_at']; ?>" data-products="<?= $o['products']; ?>" data-total="<?= $o['total']; ?>" value="<?= $o['id']; ?>">
                                        <?php echo "Order " . $o['id'] . " - " . $o['products'] . " products - Total " . $o['total'] . "€"; ?>
                                            </option>
                                        <?php  } ?>
                                </select>
                            </span>
                <button type="button" class="btn btn-primary add-order" ><?php echo trad('Add') ?></button>
         
        </div>
            <div class="col-6 offset-6">
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <input type="hidden" id="subtotal" name="subtotal">
                            <th style="width:50%"><?php echo trad('Subtotal') ?> <sup><?= trad('HT'); ?></sup></th>
                            <td id="totalHT">0 €</td>
                        </tr>
                        <tr>
                            <th id="tauxTva"><?php echo trad('VAT') ?> (20%)</th>
                            <td id="vat">0 €</td>
                        </tr>
                        <tr>
                            <th><?php echo trad('Total') ?> <sup><?= trad('TTC'); ?></sup></th>
                            <td id="totalTTC">0 €</td>
                        </tr>
                    </table>
                </div>
            </div>
            <!-- /.col -->
        </div>

        <div class="row">
            <div class="col-12 mb-3">
                <hr />
                <div class="btn-group  float-right" role="group">
                   
                    <button type="button" class="btn btn-success" id="createInvoice">
                        <i class="fas fa-check"></i> <?php echo trad('Create invoice') ?>
                    </button>
                </div>
            </div>
        </div>
        </form>
    <?php endif; ?>
</div>
