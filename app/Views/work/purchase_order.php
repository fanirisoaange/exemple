<div class="row no-print">
    <div class="col-12 mb-3">
        <div class="btn-group  float-right" role="group">
            <a href="#" target="_blank" class="btn btn-secondary"><i class="fas fa-print"></i> Print</a>
            <button type="button" class="btn btn-danger">
                <i class="fa fa-file-pdf"></i> Download PDF
            </button>
        </div>
    </div>
</div>
<div class="invoice p-3 mb-4">
    <!-- title row -->
    <div class="row">
        <div class="col-12">
            <h2 class="page-header">
                <?= img(ASSETS . 'img/logo-cardata-black.png', false, 'alt="Cardata" class="img-fluid" style="max-width:180px"') ?>
                <small class="float-right">
                    <b>Order #123456</b><br>
                    <small>Date: 20/10/2020</small>
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
        <div class="col-6 invoice-col">
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
        <div class="col-12 table-responsive mb-4">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>CPM</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Emailing + geographic targeting</td>
                        <td>7 890</td>
                        <td>60 €</td>
                        <td>473.40 €</td>
                    </tr>
                    <tr>
                        <td>Emailing + geographic targeting</td>
                        <td>7 890</td>
                        <td>60 €</td>
                        <td>473.40 €</td>
                    </tr>
                    <tr>
                        <td>Emailing + geographic targeting</td>
                        <td>7 890</td>
                        <td>60 €</td>
                        <td>473.40 €</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
        <!-- accepted payments column -->
        
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

</div>
