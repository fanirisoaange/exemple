/* 
 * Users
 */
var firstLine = null;
var htmlFirstLine = '';
var listProducts = [];
var listUnity = [];

$(document).ready(function () {
    calculTotalOnLoad();
    if (jQuery().dataTable) {
        var arrayColumnDefs = [];
        arrayColumnDefs[0] = { type: 'date-eu', targets: 1 }
        $('#userList').DataTable({
            'paging': true,
            'lengthChange': true,
            'searching': true,
            'ordering': true,
            'info': true,
            'autoWidth': false,
            'responsive': true,
            'aaSorting': [],
            'columnDefs': arrayColumnDefs
        });
    }

    $('#products').find('tbody').find('tr').each(function (e) {
        var curentTr = $(this);
        var eq0 = curentTr.find('td:eq(0)');
        var btnForm = eq0.find('.btn-form');
        var btnList = eq0.find('.btn-list');
        var eq1 = curentTr.find('td:eq(1)');
        if (btnForm.hasClass('btn-success')) {
            btnForm.prop('disabled', false);
            btnList.prop('disabled', true);
            eq1.find('span.list').addClass('show');
            eq1.find('span.form-input').addClass('hide');
        }
        if (btnForm.hasClass('btn-outline-secondary')) {
            btnList.prop('disabled', false);
            btnForm.prop('disabled', true);
            eq1.find('span.form-input').addClass('show');
            eq1.find('span.list').addClass('hide');
        }
        eq0.find('button.btn-form').click(function (e) {
            $(this).removeClass('btn-success');
            $(this).addClass('btn-outline-secondary');
            $(this).prop('disabled', true);
            var btnListEq0 = eq0.find('button.btn-list');
            btnListEq0.prop('disabled', false);
            btnListEq0.removeClass('btn-outline-secondary');
            btnListEq0.addClass('btn-success');
            var spanListEq1 = curentTr.find('td:eq(1)').find('span.list');
            spanListEq1.addClass('hide');
            spanListEq1.removeClass('show');
            var spanFormEq1 = curentTr.find('td:eq(1)').find('span.form-input');
            spanFormEq1.addClass('show');
            spanFormEq1.removeClass('hide');
        });
        eq0.find('button.btn-list').click(function (e) {
            $(this).removeClass('btn-success');
            $(this).addClass('btn-outline-secondary');
            $(this).prop('disabled', true);
            var btnFormEq0 = eq0.find('button.btn-form');
            btnFormEq0.prop('disabled', false);
            btnFormEq0.removeClass('btn-outline-secondary');
            btnFormEq0.addClass('btn-success');
            var spanFormEq1 = curentTr.find('td:eq(1)').find('span.form-input');
            spanFormEq1.addClass('hide');
            spanFormEq1.removeClass('show');
            var spanListEq1 = curentTr.find('td:eq(1)').find('span.list');
            spanListEq1.addClass('show');
            spanListEq1.removeClass('hide');
        });
    });

    firstLine = $('#firstLine');
    var companyid = firstLine.attr('company-id');
    htmlFirstLine = firstLine.html();
    var eq0 = firstLine.find('td:eq(0)');
    var k = 0;
    firstLine.find('td:eq(1)').find('span.list').find('select').find('option').each(function (e) {
        listProducts[k] = {value: $(this).attr('value'), text: $(this).text()};
        k++;
    });
    var j = 0;
    firstLine.find('td:eq(3)').find('select').find('option').each(function (e) {
        listUnity[j] = {value: $(this).attr('value'), text: $(this).text()};
        j++;
    });

    eq0.find('button.btn-form').click(function (e) {
        $(this).removeClass('btn-success');
        $(this).addClass('btn-outline-secondary');
        $(this).prop('disabled', true);
        var btnListEq0 = eq0.find('button.btn-list');
        btnListEq0.prop('disabled', false);
        btnListEq0.removeClass('btn-outline-secondary');
        btnListEq0.addClass('btn-success');
        var spanListEq1 = firstLine.find('td:eq(1)').find('span.list');
        spanListEq1.addClass('hide');
        spanListEq1.removeClass('show');
        var spanFormEq1 = firstLine.find('td:eq(1)').find('span.form-input');
        spanFormEq1.addClass('show');
        spanFormEq1.removeClass('hide');
    });
    eq0.find('button.btn-list').click(function (e) {
        $(this).removeClass('btn-success');
        $(this).addClass('btn-outline-secondary');
        $(this).prop('disabled', true);
        var btnFormEq0 = eq0.find('button.btn-form');
        btnFormEq0.prop('disabled', false);
        btnFormEq0.removeClass('btn-outline-secondary');
        btnFormEq0.addClass('btn-success');
        var spanFormEq1 = firstLine.find('td:eq(1)').find('span.form-input');
        spanFormEq1.addClass('hide');
        spanFormEq1.removeClass('show');
        var spanListEq1 = firstLine.find('td:eq(1)').find('span.list');
        spanListEq1.addClass('show');
        spanListEq1.removeClass('hide');
    });

    $('.add-product').click(function (e) {
        var eq1 = firstLine.find('td:eq(1)');
        var spanList = eq1.find('span.list');
        var spanForm = eq1.find('span.form-input');
        var nameProduct = spanList.find('select option:selected').text().trim();
        nameProduct = spanForm.hasClass('show') ? spanForm.find('input').val() : nameProduct;
        var quantity = firstLine.find('td:eq(2)').find('input[type="text"]').val();
        var cpm = firstLine.find('td:eq(4)').find('input').val();
        var htmlOption = '';
        $.each(listProducts, function() {
            if ((this).value == spanList.find('select').val()) {
                htmlOption += '<option value="' + (this).value + '" selected>' + (this).text + '</option>';
            } else
                htmlOption += '<option value="' + (this).value + '">' + (this).text + '</option>';
        });
        var hideList = spanForm.hasClass('show') ? 'style="display: none;"' : '';
        var hideForm = spanList.hasClass('show') ? 'style="display: none;"' : '';
        var disableBtnForm = spanForm.hasClass('show') ? 'disabled' : '';
        var disableBtnList = spanList.hasClass('show') ? 'disabled' : '';
        if (spanForm.hasClass('show')) {
            var subclassBtnForm = 'btn-success';
            var subclassBtnList = 'btn-outline-secondary';
        } else {
            var subclassBtnForm = 'btn-outline-secondary';
            var subclassBtnList = 'btn-success';
        }
        var subclassForm = spanForm.hasClass('show') ? 'show' : '';
        var subclassList = spanForm.hasClass('show') ? '' : 'show';

        var htmlProduct = `<span class="input-group list ` + subclassList + `" ` + hideList +  `>
                                <select name="add_product" class="custom-select" onchange="getDefaultPrice(this,` + companyid + `)" >` +
                                    htmlOption +
                                `</select>
                            </span>
                            <span class="input-group form-input ` + subclassForm + `" ` + hideForm +  `>
                                <input type="text" name="nameProduct" class="form-control nameProduct" placeholder="Product name" value="` + nameProduct + `">
                            </span>`;

        var htmlUnityOption = '';
        $.each(listUnity, function() {
            if ((this).value == firstLine.find('td:eq(3)').find('select').val()) {
                htmlUnityOption += '<option value="' + (this).value + '" selected>' + (this).text + '</option>';
            } else
                htmlUnityOption += '<option value="' + (this).value + '">' + (this).text + '</option>';
        });                    
        var htmlUnity = `<select name="unity" class="custom-select">` + htmlUnityOption + `</select>`;                  
        var html = `<tr class="show">
                    <td width="7%" style="padding-right: 0px!important;">
                        <div class="btn-group float-right" role="group">
                            <button type="button" class="btn ` + subclassBtnForm + ` btn-list" title="list" ` + disableBtnList + `>
                                <i class="fa fa-list"></i>
                            </button>
                            <button type="button" class="btn ` + subclassBtnList + ` btn-form" title="form" ` + disableBtnForm + `>
                                <i class="fab fa-wpforms"></i>
                            </button>
                        </div>
                    </td>
                    <td width="40%">` +
                        htmlProduct +
                    `</td>
                    <td width="8%">
                        <div class="form-group"><input type="text" name="qty" value="` + quantity + `" class="form-control qty" onclick="changeQty(this)" onkeyup="changeQty(this)" onchange="changeQty(this)">
                        </div>
                    </td>
                    <td width="12%">
                        <div class="form-group">`+ htmlUnity + `</div>
                    </td>
                    <td width="13%">
                        <span class="input-group">
                            <input type="text" name="cpm" onclick="changeCpm(this)" onkeyup="changeCpm(this)" onchange="changeCpm(this)" class="form-control cpm" placeholder="Price CPM" value="` + cpm + `">
                            <span class="input-group-append">
                                <div class="input-group-text">€</div>
                            </span>
                        </span>
                    </td>
                    <td width="12%">` + (quantity * cpm.replace(' €', '')).toFixed(2) + ` €</td>
                    <td width="8%">
                        <button type="button" class="btn btn-outline-danger btn-remove btn-sm"><i class="fa fa-trash"></i></button>
                    </td>
                    </tr>
        `;
        $(html).insertBefore(firstLine);
        var nbLine = 0;
        $('#products').find('tbody').find('tr.show').each(function (e) {
            nbLine++;
        });
        if (nbLine > 0) {
            $('#createOrder').prop('disabled', false);
        }
        calculTotal();
    });
});

function calculTotal() {
    var subTotal = 0.00;
    $('#products').find('tbody').find('tr.show').each(function (e) {
        subTotal += parseFloat($(this).find('td:eq(5)').text().trim().replace(' €', ''));
    });
    $('#totalHT').text(subTotal.toFixed(2) + ' €');
    var tauxTva= $('#tauxTva').text().trim();
    tauxTva = tauxTva.substring(tauxTva.indexOf('(') + 1, tauxTva.indexOf('%)'));
    var vat = (parseFloat(tauxTva/100) * subTotal).toFixed(2);
    $('#vat').text(vat + ' €');
    var totalTTC = parseFloat(subTotal) + parseFloat(vat);
    $('#totalTTC').text(totalTTC.toFixed(2) + ' €');
}


$(document).on('click', '.btn-remove', function(e) {
    var parentTr = $(e.target).parents('tr');
    if (parentTr.attr('id') == 'firstLine') {
        parentTr.removeClass('show');
        parentTr.addClass('hide');
    } else {
        parentTr.remove();
    }
    
    var nbLine = 0;
    $('#products').find('tbody').find('tr.show').each(function (e) {
        nbLine++;
    });
    if (nbLine == 0) {
        $('#createOrder').prop('disabled', true);
    }
    calculTotal();
});

$(document).on('click', '.btn-list', function(e) {
    var parent = $(e.target).parents('td');
    var btnList = parent.find('.btn-list');
    var btnForm = parent.find('.btn-form');
    btnList.prop('disabled', true);
    btnForm.prop('disabled', false);

    btnList.removeClass('btn-success');
    btnList.addClass('btn-outline-secondary');

    btnForm.removeClass('btn-outline-secondary');
    btnForm.addClass('btn-success');

    var parentTr = parent.parents('tr');
    var spanFormInput = parentTr.find('td:eq(1)').find('span.form-input');
    spanFormInput.hide();
    spanFormInput.removeClass('show');
    var spanList = parentTr.find('td:eq(1)').find('span.list');
    spanList.show();
    spanList.addClass('show');
});

$(document).on('click', '.btn-form', function(e) {
    var parent = $(e.target).parents('td');
    var btnForm = parent.find('.btn-form');
    var btnList = parent.find('.btn-list');
    btnForm.prop('disabled', true);
    btnList.prop('disabled', false);

    btnForm.removeClass('btn-success');
    btnForm.addClass('btn-outline-secondary');

    btnList.removeClass('btn-outline-secondary');
    btnList.addClass('btn-success');

    var parentTr = parent.parents('tr');
    var spanList = parentTr.find('td:eq(1)').find('span.list');
    spanList.hide();
    spanList.removeClass('show');
    var spanForm = parentTr.find('td:eq(1)').find('span.form-input');
    spanForm.show();
    spanForm.addClass('show');
});

function changeQty(object)
{
    var parentTr = $(object).parents('tr');
    var inputColumn2 = parentTr.find('td:eq(2)').find('input[type="text"]');
    if (true == controlField(parentTr.find('td:eq(2)'))) {
        var nbErrors = 0;
        parentTr.find('td:eq(2)').find('span.form-error').each(function (e) {
            nbErrors++;
        });
        parentTr.find('td:eq(4)').find('span.form-error').each(function (e) {
            nbErrors++;
        });
        if (nbErrors == 0) {
            var subTotal = inputColumn2.val() * parentTr.find('td:eq(4)').find('input[type="text"]').val();
            parentTr.find('td:eq(5)').text(subTotal.toFixed(2) + ' €');
            calculTotal();
        }
    }
}

function calculTotalOnLoad() {
    var subTotal = 0.00;
    $('#products').find('tbody').find('tr.show').each(function (e) {
        var cpm = parseInt($(this).find('td:eq(4)').find('input[type="text"]').val());
        var quantity = parseInt($(this).find('td:eq(2)').find('input[type="text"]').val());
        subTotalValue = cpm * quantity;
        $(this).find('td:eq(5)').text(subTotalValue.toFixed(2) + ' €');
        subTotal += subTotalValue;
    });
    calculTotal();
}

function changeCpm(object)
{
    var parentTr = $(object).parents('tr.show');
    var inputColumn3 = parentTr.find('td:eq(4)').find('input[type="text"]');
    if (true == controlField(parentTr.find('td:eq(4)'))) {
        var nbErrors = 0;
        parentTr.find('td:eq(2)').find('span.form-error').each(function (e) {
            nbErrors++;
        });
        parentTr.find('td:eq(4)').find('span.form-error').each(function (e) {
            nbErrors++;
        });
        if (nbErrors == 0) {
            var subTotal = inputColumn3.val() * parentTr.find('td:eq(2)').find('input[type="text"]').val();
            parentTr.find('td:eq(5)').text(subTotal.toFixed(2) + ' €');
            calculTotal();
        }
    }
}

function getDefaultPrice(objectSelectProduct, companyTo) {
    var objectSelectProduct = $(objectSelectProduct);
    var product = objectSelectProduct.val().split('-');
    var type = product[0];
    var service = product[1];
    var data = {'companyTo': companyTo, 'type': type, 'service': service};
    $.ajax({
        type : 'POST',
        data : 'data=' + JSON.stringify(data),
        url : '/price/getDefaultPrice',
        success : function(res, statut) {
            var parentTr = objectSelectProduct.parents('tr');
            parentTr.find('td:eq(4)').find('.cpm').val(res.price);
            if (true == controlField(parentTr.find('td:eq(4)'))) {
                var inputColumn2 = parentTr.find('td:eq(2)').find('input[type="text"]');
                var subTotal = inputColumn2.val() * parentTr.find('td:eq(4)').find('input[type="text"]').val();
                parentTr.find('td:eq(5)').text(subTotal.toFixed(2) + ' €');
                calculTotal();
            }
        },
        error : function (request, status, error) {
            toastr.error(error.message, failMessage);
        },
        dataType : 'json'
    });
}

function handleOrder(orderId, successMessage, failMessage)
{
    var products = [];
    var nbErrors = 0;
    var companyTo = $('#companySelected').attr('data-id');
    if (companyTo == '')
        nbErrors++;
    $('#products').find('tbody').find('tr.show').each(function (e) {
        var form = $(this).find('td:eq(2)').find('div.form-group');
        var fieldQty = form.find('input');
        var htmlError = "<span class=\"form-error\">Invalid field.</span>";
        if (fieldQty.val() == '') {
            $(this).find('td:eq(2)').find('span.form-error').remove();
            $(this).find('td:eq(2)').append(htmlError);
            nbErrors++;
        }
        var span = $(this).find('td:eq(4)').find('span.input-group');
        var fieldCpm = span.find('input');
        if (fieldCpm.val() == '') {
            $(this).find('td:eq(4)').find('span.form-error').remove();
            $(this).find('td:eq(4)').append(htmlError);
            nbErrors++;
        }
    });
    $('#products').find('tbody').find('tr.show').each(function (e) {
        $(this).find('span.form-error').each(function (e) {
            nbErrors++;
        });
    });
    if (nbErrors == 0) {
        var k = 0;
        $('#products').find('tbody').find('tr.show').each(function (e) {
            var column1 = $(this).find('td:eq(1)');
            var column2 = $(this).find('td:eq(2)');
            var column3 = $(this).find('td:eq(3)');
            var column4 = $(this).find('td:eq(4)');
            var inputProductName = column1.find('span.show').find('input');
            var selectProductName = column1.find('select');
            if (inputProductName.length == 1) {
                var productName = inputProductName.val().trim();
            } else {
                var productName = selectProductName.find('option:selected').text().trim();
            }
            var productQuantity = column2.find('input').val();
            var unity = column3.find('select').val();
            var cpmProduct = column4.find('input').val();
            products[k] = {
                name: productName,
                quantity: productQuantity,
                unity: unity,
                cpm: cpmProduct
            };
            k++;
        });
        if (k > 0) {
            var companyFrom = $('#companyFrom').val();
            var cmdDate = $('#created_at').val();
            var data = {'companyFrom': companyFrom, 'companyTo': companyTo, 'cmdDate': cmdDate, 'products': products};
            var url = orderId ? '/order/update/' + orderId : '/order/create';
            $.ajax({
                type : 'POST',
                data : 'data=' + JSON.stringify(data),
                url : url,
                success : function(res, statut) {
                    toastr.success(res.message, successMessage, toastr_options);
                    var redirectOrderId = orderId ? orderId : res.orderId;
                    var redirectUrl = '/order/detail/' + redirectOrderId;
                    window.location.href = redirectUrl;
                },
                error : function (request, status, error) {
                    toastr.error(error.message, failMessage);
                },
                dataType : 'json'
            });
        }
    }
}

function ajaxOrderValidate(e, id, msgSuccess, msgError, action) {
    e.preventDefault();
    $.ajax({
        type: 'GET',
        url: '/order/validate/' + id,
        success: function (res, statut) {
            toastr.success(res.message, msgSuccess, toastr_options);
            window.location.reload();
        },
        error: function (request, status, error) {
            toastr.error(error.message, msgError);
        },
        dataType: 'json'
    });
}

function ajaxOrderCancel(event, id, msgSuccess, msgError) {
    event.preventDefault();
    $.ajax({
        type : 'GET',
        url : '/order/cancel/' + id,
        success : function(res, statut) {
            toastr.success(res.message, msgSuccess, toastr_options);
            window.location.reload();
        },
        error : function (request, status, error) {
            toastr.error(error.message, msgError);
        },
        dataType : 'json'
    });
}

function ajaxOrderDelete(event, id, msgConfirm, msgSuccess, msgError) {
    event.preventDefault();
    if (confirm(msgConfirm)) {
        $.ajax({
            type : 'GET',
            url : '/order/delete/' + id,
            success : function(res, statut) {
                toastr.success(res.message, msgSuccess, toastr_options);
                window.location.href = '/order/list';
            },
            error : function (request, status, error) {
                toastr.error(error.message, msgError);
            },
            dataType : 'json'
        });
    } else
        return;
}

function ajaxOrderDraft(event, id, msgConfirm, msgSuccess, msgError) {
    event.preventDefault();
    if (confirm(msgConfirm)) {
        sendOrderAjax(id,'draft', msgSuccess, msgError)
    } else {
        return;
    }
}

function ajaxOrderSend(event, id, msgConfirm, msgSuccess, msgError, msgAlertWhenNoSelect) {
    event.preventDefault();
    if (confirm(msgConfirm)) {
        sendOrderAjax(id,'send', msgSuccess, msgError)
    } else {
        return;
    }
}

function openModal(
    idModal,
    noRecipients,
    msgConfirmSend,
    msgSuccess,
    msgFailure,
    msgAlertWhenNoSelect,
    fromList=false,
    companyTo=0,
    orderId=0
) {
    $(idModal).css({
        'width': '60%',
        'left': '20%',
        'right': '20%'
    });
    
    if (!fromList) {
        $(idModal).modal('show');
    } else {
        $.ajax({
            type: 'POST',
            url: '/user/getAccountantCompanyByAjax/' + companyTo,
            success: function (res, statut) {
                var html = ``;
                if (res.length == 0) {
                    html = `<tr><td colspan="4" class="text-center">` + noRecipients + `</td></tr>`;
                } else {
                    var k = 0;
                    while(k < res.length) {
                        html += `<tr>
                                    <td><input type="checkbox" onchange="removeError();"></td>
                                    <td user-id="` + res[k]['id'] + `">` + res[k]['email'] + `</td>
                                    <td>` + res[k]['first_name'] + `</td>
                                    <td>` + res[k]['last_name'] + `</td>
                                </tr>
                        `;
                        k++;
                    }
                    var htmlBtnSend = `<a id="btn-send-confirm" href="#" class="btn btn-app"
                           onclick="ajaxOrderSend(event, `
                            + orderId + `, '${msgConfirmSend}', '${msgSuccess}', '${msgFailure}', '${msgAlertWhenNoSelect}');"
                        >
                            <i class="fas fa-envelope"></i> Send
                        </a>
                    `;
                    $('#recipientMailModal').find('.modal-footer').html(htmlBtnSend);
                }
                $('#recipientMailModal').find('tbody').html(html);
                $(idModal).modal('show');
            },
            error: function (request, status, error) {
                toastr.error(error.message, msgFailure);
            },
            dataType: 'json'
        });
    }
}

function removeError() {
    $('#recipientMailModal').find('.form-error').each(function(e) {
        $(this).remove();
    });
}

function sendOrderAjax(id, type, msgSuccess, msgError, recipients=[]) {
    $.ajax({
        type: 'POST',
        url: '/order/' + type + '/' + id,
        data: 'data=' + JSON.stringify(recipients),
        success: function (res, statut) {
            $('#recipientMailModal').hide();
            toastr.success(res.message, msgSuccess, toastr_options);
            window.location.reload();
        },
        error: function (request, status, error) {
            toastr.error(error.message, msgError);
        },
        dataType: 'json'
    });
}

function checkAll() {
    $('#recipientMailModal').find('tbody').find('tr').each(function(e) {
        $(this).find('td:eq(0)').find('input:checkbox').prop("checked", true);
    });
}

/**
 * @param object
 * @returns {boolean}
 */
function controlField(object)
{
    var next_step = true;

    $(object).find('.form-error').each(function() {
        $(this).remove();
    });

    $(object).find('span.input-group, div.form-group').each(function() {
        if (checkFormatField($(this).find('input')) == false) {
            var html = "<span class=\"form-error\">Invalid field.</span>";
            $(html).insertAfter(this);
            next_step = false;
        }
    });

    return next_step;
}
