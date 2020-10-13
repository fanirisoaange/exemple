<div class="invoice p-3 mb-4">
    <!-- title row -->
    <div class="row">
        <div class="col-12">
            <h2 class="page-header d-inline-block">
                <?= img(
                    ASSETS.'img/logo-cardata-black.png',
                    false,
                    'alt="Cardata" class="img-fluid" style="max-width:180px"'
                ) ?>
            </h2>
        </div>
    </div>

    <hr/>
    <!-- /.col -->

    <!-- info row -->
    <div class="row invoice-info mb-3">
        <div class="col-sm-4 invoice-col">
            <?php echo trad('From') ?>
            <address>
                <?php
                $fromCompany = model('CompanyModel')->selectCompany(1);
                ?>
                <strong><?php echo $fromCompany['fiscal_name'] ?></strong><br>
                <?php echo $fromCompany['address_1'].' '
                    .$fromCompany['address_2'] ?><br>
                <?php echo $fromCompany['zip_code'].' '
                    .$fromCompany['city'] ?><br>
                <?php echo trad('Phone') ?>
                : <?php echo $fromCompany['phone_number'] ?><br>
                <?php echo trad('Email') ?>
                : <?php echo $fromCompany['email'] ?>
            </address>
        </div>

        <!-- /.col -->
        <div class="col-sm-4 invoice-col" id="divCompany">
            <?php echo trad('To') ?>
            <address id="companySelected">
                <strong><?php echo $order['fiscal_name'] ?></strong><br>
                <?php echo $order['address_1'].' '
                    .$order['address_2'] ?><br>
                <?php echo $order['zip_code'].' '
                    .$order['city'] ?><br>
                <?php echo trad('Phone') ?>
                : <?php echo $order['phone_number'] ?><br>
                <?php echo trad('Email') ?>
                : <?php echo $order['email']; ?>
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
                <?php $k=1; ?>
                <?php foreach ($order['products'] as $product) { ?>
                    <?php
                    $productList = get_product_list();
                    $printData = get_product_print_data(
                        $productList,
                        $product['name']
                    );
                    ?>
                    <tr class="show" <?php if ($k==count($order['products'])) { ?>id="firstLine" company-id="<?php echo $order['companyTo']; ?>"<?php } ?>>
                        <td width="7%" style="padding-right: 0px!important;">
                            <div class="btn-group float-right" role="group">
                                <button type="button"
                                        class="btn btn-list <?php echo $printData['is_custom']
                                            ? 'btn-success'
                                            : 'btn-outline-secondary' ?>"
                                            <?php if (!$printData['is_custom']) { ?>disabled<?php } ?>
                                        title="<?php echo trad('list') ?>">
                                    <i class="fa fa-list"></i>
                                </button>
                                <button type="button"
                                        class="btn btn-form <?php echo $printData['is_custom']
                                            ? 'btn-outline-secondary'
                                            : 'btn-success' ?>"
                                        title="<?php echo trad('form') ?>">
                                    <i class="fab fa-wpforms"></i>
                                </button>
                                <input type="hidden" value="<?php echo $printData['is_custom'] ? 'custom' : 'form' ?>">
                            </div>
                        </td>
                        <td width="40%">
                            <span class="input-group list <?php echo $printData['is_custom'] ? 'hide' : '' ?>">
                                <select name="add_product"
                                        class="custom-select"
                                        onchange="getDefaultPrice(this, <?php echo $order['companyTo']; ?>)"
                                        >
                                    <option value="">Select</option>
                                    <?php foreach ($productList as $key=>$value) { ?>
                                        <?php
                                        $selected = '';
                                        if (trim(strtolower($value)) == trim(
                                                strtolower($printData['name'])
                                            )
                                        ) {
                                            $selected = 'selected';
                                        }
                                        ?>
                                        <option value="<?php echo $key ?>" <?php echo $selected ?> ><?php echo $value ?></option>
                                    <?php } ?>
                                </select>
                            </span>
                            <span class="input-group form-input <?php echo $printData['is_custom']
                                ? '' : 'hide' ?>">
                                <input type="text" name="nameProduct"
                                       class="form-control nameProduct"
                                       placeholder="Product name" value="<?php echo $printData['name']  ?>">
                            </span>
                        </td>
                        <td width="8%">
                            <div class="form-group">
                                <input type="text" name="qty"
                                       onclick="changeQty(this)"
                                       onkeyup="changeQty(this)"
                                       onchange="changeQty(this)"
                                       value="<?php echo $product['quantity'] ?>"
                                       class="form-control qty">
                            </div>
                        </td>
                        <td width="12%">
                            <div class="form-group">
                                <select name="productPriceType" class="custom-select">
                                    <?php foreach (\App\Enum\ProductPriceType::getAll() as $i=>$value) { ?>
                                        <?php
                                        $selected = '';
                                        if ($i == (int)$product['product_price_type']) {
                                            $selected = 'selected';
                                        }
                                        ?>
                                        <option value="<?php echo $i ?>" <?php echo $selected ?> >
                                            <?php echo trad($value) ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </td>
                        <td width="13%">
                            <span class="input-group">
                                <input type="text" name="cpm"
                                       class="form-control cpm"
                                       onclick="changeCpm(this)"
                                       onkeyup="changeCpm(this)"
                                       onchange="changeCpm(this)"
                                       placeholder="Price CPM"
                                       value="<?php echo $product['price'] ?>"/>
                                <span class="input-group-append">
                                    <div class="input-group-text">€</div>
                                </span>
                            </span>
                        </td>
                        <td width="12%">0 €</td>
                        <td width="8%">
                            <button type="button"
                                    class="btn btn-outline-danger btn-sm btn-remove">
                                <i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                    <?php $k++; ?>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
        <!-- /.col -->
        <div class="col-4 text-center" style="">
            <button type="button"
                    class="btn btn-primary add-product"><?php echo trad(
                    'Add Product'
                ) ?></button>
        </div>
        <div class="col-6 offset-6">
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th style="width:50%"><?php echo trad('Subtotal') ?>
                            <sup>HT</sup></th>
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
            <hr/>
            <div class="btn-group  float-right" role="group">
                <button type="button" class="btn btn-success" id="createOrder"
                        onclick="handleOrder(<?php echo $order['id'] ?>, '<?php echo trad(
                            'Order updated successfully'
                        ) ?>', '<?php echo trad(
                            'Order update error'
                        ) ?>')">
                    <i class="fas fa-check"></i> <?php echo trad(
                        'Update order'
                    ) ?>
                </button>
            </div>
        </div>
    </div>
</div>
