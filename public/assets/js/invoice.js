$(document).ready(function() {
    if($("#session_message").val())
        toastr.success($("#session_message").val());

	$("#btnPrint").click(function() {
		var printContents = document.getElementById("cardInvoice").innerHTML;
     	var originalContents = document.body.innerHTML;
     	document.body.innerHTML = printContents;
        var p = document.body.style.padding;
        document.body.style.padding = "3em";
     	window.print();
        document.body.style.padding = p;
     	document.body.innerHTML = originalContents;
	})

	$("#cronbtn").click(function() {
		window.location.href = "/invoice/createOne?date=" + $("#cronjob").val();
	})

    $('.select2').select2({
      theme: 'bootstrap4',
    })

    $("#invoicePay").click(function() {
        var stripe = Stripe($("#stripe_key").val());  
        stripe.confirmCardPayment($(this).data("client"), {
          payment_method: $(this).data("pm")
        }).then(function(result) {
          if (result.error) {
            toastr.error(result.error.message);
            console.log(result.error.message);
          } else {
            if (result.paymentIntent.status === 'succeeded') {
              $.ajax({
                type: "POST",
                url: '/invoice/authenticationSuccess',
                data: { paymentIntent: result.paymentIntent.id },
                success: function(res) {
                    window.location.reload();
                }
              })
            }
          }
        });
    })
  
    $('#sub_company').on('select2:select', function (e) {
            var data = e.params.data;
            $.ajax({
                type: "POST",
                url: "/invoice/setSubCompany",
                data: { id: data.id },
                success: function(res) {
                    window.location.reload();
                }
            })
        });

    $("#createInvoice").click(function() { 
        var b = 1;
        $("#formInvoice #subtotal").val(parseFloat($("#totalHT").html().replace(" €", "")).toFixed(2));
        if(!$("#products table tbody tr:not(#firstLine)").length) {
            b = 0;
            toastr.error('Please add at least one order.');
        }
        if(!$("#invoiceDate").val()) {
            b = 0;
            toastr.error("Please select the invoice date.");
        }
        if(b)
            $("#formInvoice")[0].submit();

    });

	  if (jQuery().dataTable && $('.dataTableInvoice').length > 0) {
        $('.dataTableInvoice').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "info": true,
            "ordering": false,
            "autoWidth": false,
            "responsive": true,

        });
    }

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

    $('#divCompany').find('select').click(function (e) {
        var companyId = $('#divCompany').find('select').val();
        if (companyId == '')
            return;
        $.ajax({
            type : 'GET',
            url : '/invoice/getCompanyByAjax/' + companyId,
            success : function(res, statut) {
                 var html = `<strong>` + res['fiscal_name'] + `</strong><br>`
                    + res['address_1'] + (res['address_2'] ? ' ' + res['address_2'] : '') + `<br>`
                    + res['zip_code'] + ' ' + res['city'] + `<br>`
                    + `Phone:` + ' ' + res['phone_number'] + `<br>`
                    + `Email:` + ' ' + (res['email'] ? res['email'] : '');
                $('#companySelected').html(html);
            },
            error : function (request, status, error) {
                toastr.error(error.message, 'Error');
            },
            dataType : 'json'
        });
    });

    var firstLine = $('#firstLine');
    var eq0 = firstLine.find('td:eq(0)');
    var listProducts = [];
    var k = 0;
    firstLine.find('td:eq(1)').find('span.list').find('select').find('option').each(function (e) {
        listProducts[k] = {value: $(this).attr('value'), text: $(this).text()};
        k++;
    });
    firstLine.find('td:eq(1)').find('span.form-input').hide();
    firstLine.find('td:eq(1)').find('span.list').addClass('show');
    eq0.find('button.btn-list').prop('disabled', true);
    eq0.find('button.btn-form').click(function (e) {
        $(this).removeClass('btn-success');
        $(this).addClass('btn-outline-secondary');
        $(this).prop('disabled', true);
        eq0.find('button.btn-list').prop('disabled', false);
        eq0.find('button.btn-list').removeClass('btn-outline-secondary');
        eq0.find('button.btn-list').addClass('btn-success');
        firstLine.find('td:eq(1)').find('span.list').hide();
        firstLine.find('td:eq(1)').find('span.list').removeClass('show');
        firstLine.find('td:eq(1)').find('span.form-input').show();
        firstLine.find('td:eq(1)').find('span.form-input').addClass('show');
    });
    eq0.find('button.btn-list').click(function (e) {
        $(this).removeClass('btn-success');
        $(this).addClass('btn-outline-secondary');
        $(this).prop('disabled', true);
        eq0.find('button.btn-form').prop('disabled', false);
        eq0.find('button.btn-form').removeClass('btn-outline-secondary');
        eq0.find('button.btn-form').addClass('btn-success');
        firstLine.find('td:eq(1)').find('span.form-input').hide();
        firstLine.find('td:eq(1)').find('span.form-input').removeClass('show');
        firstLine.find('td:eq(1)').find('span.list').show();
        firstLine.find('td:eq(1)').find('span.list').addClass('show');
    });

    $(".add-order").click(function() {
        if($("#add-order-select").val()) {
            var opt = $("#add-order-select option:selected");
            var tr = $("#firstLine").clone(true).attr("id", "").show();
            tr.find("td").eq(1).html("#" + opt.val());
            tr.find("td").eq(2).html(opt.data("date"));
            tr.find("td").eq(3).html(opt.data("products"));
            tr.find("td").eq(4).html(opt.data("total") + " €");
            tr.data("id", opt.val());
            tr.find("input").attr('name', 'orders[]').val(opt.val());

            $("#products table").append(tr);
            opt.prop("selected", false).hide();

            calculTotal();
        }
        else
            toastr.error("Please select an order to add.");
    })

    $('.add-product').click(function (e) {
        var eq1 = firstLine.find('td:eq(1)');
        var spanList = firstLine.find('td:eq(1)').find('span.list');
        var spanForm = firstLine.find('td:eq(1)').find('span.form-input');
        var nameProduct = spanList.find('select option:selected').text();
        nameProduct = spanForm.hasClass('show') ? spanForm.find('input').val() : nameProduct;
        var quantity = firstLine.find('td:eq(2)').find('input[type="text"]').val();
        var cpm = firstLine.find('td:eq(3)').find('input').val();
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
                                <select name="add_product" class="custom-select">` +
                                    htmlOption +
                                `</select>
                            </span>
                            <span class="input-group form-input ` + subclassForm + `" ` + hideForm +  `>
                                <input type="text" name="nameProduct" class="form-control nameProduct" placeholder="Product name" value="` + nameProduct + `">
                            </span>`;
        var html = `<tr>
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
                    <td width="15%">
                        <div class="form-group"><input type="text" name="qty" value="` + quantity + `" class="form-control qty" onclick="changeQty(this)" onkeyup="changeQty(this)" onchange="changeQty(this)">
                        </div>
                    </td>
                    <td width="15%">
                        <span class="input-group">
                            <input type="text" name="cpm" onclick="changeCpm(this)" onkeyup="changeCpm(this)" onchange="changeCpm(this)" class="form-control cpm" placeholder="Price CPM" value="` + cpm + `">
                            <span class="input-group-append">
                                <div class="input-group-text">€</div>
                            </span>
                        </span>
                    </td>
                    <td width="15%">` + (quantity * cpm.replace(' €', '')).toFixed(2) + ` €</td>
                    <td width="8%">
                        <button type="button" class="btn btn-outline-danger btn-remove btn-sm"><i class="fa fa-trash"></i></button>
                    </td>
                    </tr>
        `;
        $(html).insertBefore(firstLine);
        calculTotal();
    });
});

function calculTotal() {
    var subTotal = 0.00;
    $('#products').find('tbody').find('tr').each(function (e) {
        subTotal += parseFloat($(this).find('td:eq(4)').text().trim().replace(' €', ''));
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
    $("#add-order-select option[value='" + $(e.target).parents("tr").data("id") + "']").show();
    $(e.target).parents('tr').remove();

    calculTotal();
});

$(document).on('click', '.btn-list', function(e) {
    var parent = $(e.target).parents('td');
    parent.find('.btn-list').prop('disabled', true);
    parent.find('.btn-form').prop('disabled', false);

    parent.find('.btn-list').removeClass('btn-success');
    parent.find('.btn-list').addClass('btn-outline-secondary');

    parent.find('button.btn-form').removeClass('btn-outline-secondary');
    parent.find('button.btn-form').addClass('btn-success');

    var parentTr = parent.parents('tr');
    parentTr.find('td:eq(1)').find('span.form-input').hide();
    parentTr.find('td:eq(1)').find('span.form-input').removeClass('show');
    parentTr.find('td:eq(1)').find('span.list').show();
    parentTr.find('td:eq(1)').find('span.list').addClass('show');
});

$(document).on('click', '.btn-form', function(e) {
    var parent = $(e.target).parents('td');
    parent.find('.btn-form').prop('disabled', true);
    parent.find('.btn-list').prop('disabled', false);

    parent.find('.btn-form').removeClass('btn-success');
    parent.find('.btn-form').addClass('btn-outline-secondary');

    parent.find('.btn-list').removeClass('btn-outline-secondary');
    parent.find('.btn-list').addClass('btn-success');

    var parentTr = parent.parents('tr');
    parentTr.find('td:eq(1)').find('span.list').hide();
    parentTr.find('td:eq(1)').find('span.list').removeClass('show');
    parentTr.find('td:eq(1)').find('span.form-input').show();
    parentTr.find('td:eq(1)').find('span.form-input').addClass('show');
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
        parentTr.find('td:eq(3)').find('span.form-error').each(function (e) {
            nbErrors++;
        });
        if (nbErrors == 0) {
            var subTotal = inputColumn2.val() * parentTr.find('td:eq(3)').find('input[type="text"]').val();
            parentTr.find('td:eq(4)').text(subTotal.toFixed(2) + ' €');
            calculTotal();
        }
    }
}

function changeCpm(object)
{
    var parentTr = $(object).parents('tr');
    var inputColumn3 = parentTr.find('td:eq(3)').find('input[type="text"]');
    if (true == controlField(parentTr.find('td:eq(3)'))) {
        var nbErrors = 0;
        parentTr.find('td:eq(2)').find('span.form-error').each(function (e) {
            nbErrors++;
        });
        parentTr.find('td:eq(3)').find('span.form-error').each(function (e) {
            nbErrors++;
        });
        if (nbErrors == 0) {
            var subTotal = inputColumn3.val() * parentTr.find('td:eq(2)').find('input[type="text"]').val();
            parentTr.find('td:eq(4)').text(subTotal.toFixed(2) + ' €');
            calculTotal();
        }
    }
}


function createOrder()
{
    var products = [];
    var nbErrors = 0;
    var companyId = $('#divCompany').find('select').val();
    if (companyId == '')
        nbErrors++;
    $('#products').find('tbody').find('tr').each(function (e) {
        var form = $(this).find('td:eq(2)').find('div.form-group');
        var fieldQty = form.find('input');
        var htmlError = "<span class=\"form-error\">Invalid field.</span>";
        if (fieldQty.val() == '' || fieldQty.val() == 0) {
            $(this).find('td:eq(2)').append(htmlError);
            nbErrors++;
        }
        var span = $(this).find('td:eq(3)').find('span.input-group');
        var fieldCpm = span.find('input');
        if (fieldCpm.val() == '' || fieldCpm.val() == 0) {
            $(this).find('td:eq(3)').append(htmlError);
            nbErrors++;
        }
    });
    $('#products').find('tbody').find('tr').each(function (e) {
        $(this).find('span.form-error').each(function (e) {
            nbErrors++;
        });
    });
    if (nbErrors == 0) {
        var k = 0;
        $('#products').find('tbody').find('tr').each(function (e) {
            var column1 = $(this).find('td:eq(1)');
            var column2 = $(this).find('td:eq(2)');
            var column3 = $(this).find('td:eq(3)');
            var inputProductName = column1.find('span.show').find('input');
            var selectProductName = column1.find('span.show').find('select');
            if (inputProductName.length == 1)
                var productName = inputProductName.val().trim();
            if (selectProductName.length == 1)
                var productName = selectProductName.find('option:selected').text().trim();
            var productQuantity = column2.find('input').val();
            var cpmProduct = column3.find('input').val();
            products[k] = {
                name: productName,
                quantity: productQuantity,
                cpm: cpmProduct
            };
            k++;
        });
        var data = {'companyId': companyId,'products': products};
        $.ajax({
            type : 'POST',
            data : 'data=' + JSON.stringify(data),
            url : '/order/create',
            success : function(res, statut) {
                toastr.success(res.message, 'Order created successfully', toastr_options);
                window.location.href = '/order/detail/' + res.orderId;
            },
            error : function (request, status, error) {
                toastr.error(error.message, 'Order creation error');
            },
            dataType : 'json'
        });
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

function ajaxOrderSend(event, id, msgConfirm, msgSuccess, msgError) {
    event.preventDefault();
    if (confirm(msgConfirm)) {
        sendOrderAjax(id,'send', msgSuccess, msgError)
    } else {
        return;
    }
}

function sendOrderAjax(id, type, msgSuccess, msgError) {
    $.ajax({
        type: 'GET',
        url: '/order/' + type + '/' + id,
        success: function (res, statut) {
            toastr.success(res.message, msgSuccess, toastr_options);
            window.location.href = '/order/list';
        },
        error: function (request, status, error) {
            toastr.error(error.message, msgError);
        },
        dataType: 'json'
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
