<div class="row">
    <div class="col-12 text-right">
        <a href="/work/add_paiment_method" class="btn btn-success mb-3">
            <i class="nav-icon fas fa-plus"></i> Add payment method
        </a>
    </div>
</div>

<div class="card card-primary card-outline">
    <div class="card-body">
        <table class="table dataTable">
            <thead>
                <tr>
                    <th>Default</th>
                    <th>Active</th>
                    <th>Type</th>
                    <th>Number</th>
                    <th>Expire</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="icheck-success d-inline">
                            <input type="radio" id="default01" name="default" checked>
                            <label for="default01"></label>
                        </div>
                    </td>
                    <td>
                        <input type="checkbox" checked data-toggle="toggle" data-on="Active" data-width="100" data-off="Inactive " data-size="sm" data-onstyle="success" data-offstyle="danger">
                    </td>
                    <td><img src="/library/theme-lte/img/credit/visa.png" alt="Visa"> Visa / Electron</td>
                    <td>****-1234</td>
                    <td>10/22</td>
                    <td><span class="badge badge-success">Verified</span></td>
                    <td>
                        <button class="btn btn-outline-warning btn-sm"><i class="fa fa-edit"></i></button>
                        <button class="btn btn-outline-danger btn-sm"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="icheck-success d-inline">
                            <input disabled type="radio" name="default" id="default02">
                            <label for="default02"></label>
                        </div>
                    </td>
                    <td>
                        <input type="checkbox" disabled data-toggle="toggle" data-on="Active" data-width="100" data-off="Inactive" data-size="sm" data-onstyle="success" data-offstyle="danger">
                    </td>
                    <td><img src="/library/theme-lte/img/credit/sepa.png" alt="Sepa"> SEPA direct debit</td>
                    <td>****-1234</td>
                    <td>10/22</td>
                    <td><span class="badge badge-warning">Pending</span></td>
                    <td>
                        <button class="btn btn-outline-warning btn-sm"><i class="fa fa-edit"></i></button>
                        <button class="btn btn-outline-danger btn-sm"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="icheck-success d-inline">
                            <input type="radio" id="default01" name="default" disabled>
                            <label for="default01"></label>
                        </div>
                    </td>
                    <td>
                        <input type="checkbox" disabled data-toggle="toggle" data-on="Active" data-width="100" data-off="Inactive " data-size="sm" data-onstyle="success" data-offstyle="danger">
                    </td>
                    <td><img src="/library/theme-lte/img/credit/visa.png" alt="Visa"> Visa / Electron</td>
                    <td>****-1234</td>
                    <td>10/22</td>
                    <td><span class="badge badge-danger">refused</span></td>
                    <td>
                        <button class="btn btn-outline-warning btn-sm"><i class="fa fa-edit"></i></button>
                        <button class="btn btn-outline-danger btn-sm"><i class="fa fa-trash"></i></button>
                    </td
            </tbody>
        </table>
    </div>
</div>