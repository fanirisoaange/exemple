<div class="invoice p-3 mb-4">
    <?php if ((int)$_SESSION['current_main_company'] === 0): ?>
        <?php echo trad(
            'Please select the company for which to display the order creation'
        ) ?>
    <?php else: ?>
        <!-- title row -->
        <div class="row">
            <div class="col-12">
                <h2 class="page-header d-inline-block">
                    <?= img(ASSETS . 'img/logo-cardata-black.png', false, 'alt="Cardata" class="img-fluid" style="max-width:180px"') ?>
                </h2>
                <div class="float-right">
                <span  class="input-group">
                    <input type="date" name="date" id="created_at" class="form-control" placeholder="dd/mm/aaaa"  />
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
            <div class="col-sm-4 invoice-col">
                <?php echo trad('From') ?>
                <select name="company" id="companyFrom" class="custom-select">
                    <?php $companyCardata = model('CompanyModel')->selectCompany(1); ?>
                    <option value="<?php echo $companyCardata['id'] ?>">
                        <?php echo $companyCardata['fiscal_name'] ?>
                     </option>
                </select>
                <address id="companyFromSelected" data-id="<?php echo $companyCardata['id'] ?>">
                    <strong><?php echo $companyCardata['fiscal_name'] ?></strong><br>
                    <?php echo $companyCardata['address_1'] . ' ' . $companyCardata['address_2'] ?><br>
                    <?php echo $companyCardata['zip_code'] . ' ' . $companyCardata['city'] ?><br>
                    <?php echo trad('Phone') ?>: <?php echo $companyCardata['phone_number'] ?><br>
                    <?php if ($companyCardata['email'] !='') echo trad('Email : ') . $companyCardata['email'] ?>
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-8 invoice-col" id="divCompany">
                <?php echo trad('To') ?>
                <?php companySelector(false); ?>
                <?php $currentCompany = model('CompanyModel')->selectCompany((int)$_SESSION['current_sub_company']); ?>
                <address id="companySelected" data-id="<?php echo $currentCompany['id'] ?>">
                    <strong><?php echo $currentCompany['fiscal_name'] ?></strong><br>
                    <?php echo $currentCompany['address_1'] . ' ' . $currentCompany['address_2'] ?><br>
                    <?php echo $currentCompany['zip_code'] . ' ' . $currentCompany['city'] ?><br>
                    <?php echo trad('Phone') ?>: <?php echo $currentCompany['phone_number'] ?><br>
                    <?php if ($currentCompany['email'] !='') echo trad('Email : ') . $currentCompany['email'] ?>
                </address>
            </div>

            <!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- Table row -->
        <div class="row">
            <div class="col-12 table-responsive" id="products">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th></th>
                        <th><?php echo trad('Product') ?></th>
                        <th><?php echo trad('Quantity') ?></th>
                        <th><?php echo trad('Unity') ?></th>
                        <th><?php echo trad('Price') ?></th>
                        <th><?php echo trad('Subtotal') ?></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr id="firstLine" class="show" company-id="<?php echo $currentCompany['id'] ?>">
                        <td width="7%" style="padding-right: 0px!important;">
                            <div class="btn-group float-right" role="group">
                                <button type="button" class="btn btn-outline-secondary btn-list" title="<?php echo trad('list') ?>">
                                    <i class="fa fa-list"></i>
                                </button>
                                <button type="button" class="btn btn-success btn-form" title="<?php echo trad('form') ?>">
                                    <i class="fab fa-wpforms"></i>
                                </button>
                            </div>
                        </td>
                        <td width="40%">
                            <span class="input-group list">
                                <select name="add_product" class="custom-select"
                                onchange="getDefaultPrice(this, <?php echo $currentCompany['id'] ?>)"
                                >
                                    <option value="">Select</option>
                                    <?php foreach (\App\Enum\ProductTypes::getDescriptions() as $keyType=>$valueType) { ?>

                                        <?php foreach (\App\Enum\ProductServices::getDescriptions() as $keyService=>$valueService) { ?>
                                            <option value="<?php echo $keyType.'-'.$keyService; ?>"
                                                    <?php if ($keyType.'-'.$keyService == \App\Enum\ProductTypes::TELEMARKETING
                                                    .'-'.\App\Enum\ProductServices::WITH_GEOGRAPHIC_TARGETING) { ?>selected<?php } ?>
                                            >
                                        <?php echo $valueType.' '.$valueService; ?>
                                            </option>
                                        <?php  } ?>
                                    <?php  } ?>
                                </select>
                            </span>
                            <span class="input-group form-input hide">
                                <input type="text" name="nameProduct" class="form-control nameProduct" placeholder="Product name">
                            </span>
                        </td>
                        <td width="8%">
                            <div class="form-group">
                                <input type="text" name="qty" onclick="changeQty(this)" onkeyup="changeQty(this)" onchange="changeQty(this)" value="1" class="form-control qty">
                            </div>
                        </td>
                        <td width="12%">
                            <div class="form-group">
                                <select name="productPriceType" class="custom-select">
                                    <?php foreach (\App\Enum\ProductPriceType::getAll() as $i=>$value) { ?>
                                        <option value="<?php echo $i ?>">
                                            <?php echo trad($value) ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </td>
                        <td width="13%">
                            <span class="input-group">
                                <input type="text" name="cpm" class="form-control cpm" onclick="changeCpm(this)" onkeyup="changeCpm(this)" onchange="changeCpm(this)"
                                       placeholder="Price CPM" value="0" />
                                <span class="input-group-append">
                                    <div class="input-group-text">€</div>
                                </span>
                            </span>
                        </td>
                        <td width="12%">0 €</td>
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
            <!-- /.col -->
            <div class="col-4 text-center" style="">
                <button type="button" class="btn btn-primary add-product"><?php echo trad('Add Product') ?></button>
            </div>
            <div class="col-6 offset-6">
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th style="width:50%"><?php echo trad('Subtotal') ?> <sup>HT</sup></th>
                            <td id="totalHT">0 €</td>
                        </tr>
                        <tr>
                            <th id="tauxTva"><?php echo trad('VAT') ?> (20%)</th>
                            <td id="vat">0 €</td>
                        </tr>
                        <tr>
                            <th><?php echo trad('Total') ?> <sup>TTC</sup></th>
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
                    <button type="button" class="btn btn-success" id="createOrder"
                            onclick="handleOrder(null, '<?php echo trad(
                                'Order created successfully'
                            ) ?>', '<?php echo trad(
                                'Order create error'
                            ) ?>')">
                        <i class="fas fa-check"></i> <?php echo trad('Create order') ?>
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
