<div class="invoice p-3 mb-4">
    <!-- title row -->
    <div class="row">
        <div class="col-12">
            <h2 class="page-header d-inline-block">
                <?= img(ASSETS . 'img/logo-cardata-black.png', false, 'alt="Cardata" class="img-fluid" style="max-width:180px"') ?>
            </h2>
            <div class="float-right">
                <span  class="input-group">
                    <input type="text" name="date" class="form-control" placeholder="dd/mm/aaaa"  />
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
            From
            <address>
                <strong>Cardata SA</strong><br>
                2 rue de marly le roi<br>
                78330 Le Chesnay<br>
                Phone: +331 22 33 44 55<br>
                Email: contact@cardata.fr
            </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
            <?= custom_dropdown(
                    [
                        'field' => 'company',
                        'options' => ['' => 'Select', 'company1' => 'Company 1'], 'post' => 'company1']
                    ,
                    ['class' => 'custom-select']) ?>
            To
            <address>
                <strong>Company 1</strong><br>
                795 Folsom Ave, Suite 600<br>
                San Francisco, CA 94107<br>
                Phone: (555) 539-1037<br>
                Email: john.doe@example.com
            </address>
        </div>

        <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- Table row -->
    <div class="row">
        <div class="col-12 table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>CPM</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <span  class="input-group">
                                <input type="text" name="date" class="form-control" placeholder="Product name"  />
                                <button type="button" class="btn btn-primary">list</button>
                            </span>
                        </td>
                        <td><?= custom_input(['field' => 'qty', 'post' => '7890'], ['class' => 'form-control']) ?></td>
                        <td>
                            <span  class="input-group">
                                <input type="text" name="cpm" class="form-control" placeholder="Price CPM"  />
                                <span class="input-group-append">
                                    <div class="input-group-text">€</div>
                                </span>
                            </span>
                        </td>
                        <td>473.40 €</td>
                        <td>
                            <button class="btn btn-outline-danger btn-sm"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>

                            <span  class="input-group">
                                <?= form_dropdown('add_product', ['' => 'Select', '1' => 'Emailing', '2' => 'Emailing + geographic targeting', '3' => '...'], '2', ['class' => 'custom-select']) ?>
                                <button type="button" class="btn btn-success">add</button>
                            </span>
                        </td>
                        <td><?= custom_input(['field' => 'qty', 'post' => '7890'], ['class' => 'form-control']) ?></td>
                        <td>60 €</td>
                        <td>473.40 €</td>
                        <td>
                            <button class="btn btn-outline-danger btn-sm"><i class="fa fa-trash"></i></button>
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
        <div class="col-6 offset-6">
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th style="width:50%">Subtotal <sup>HT</sup></th>
                        <td>1 420€</td>
                    </tr>
                    <tr>
                        <th>VAT (20%)</th>
                        <td>10.34€</td>
                    </tr>
                    <tr>
                        <th>Total <sup>TTC</sup></th>
                        <td>1 704 €</td>
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
                <button type="button" class="btn btn-outline-secondary">
                    <i class="fa fa-eye"></i> Preview order
                </button>
                <button type="button" class="btn btn-success">
                    <i class="fas fa-check"></i> Create order
                </button>
            </div>
        </div>
    </div>

</div>
