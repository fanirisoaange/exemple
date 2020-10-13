$(document).ready(function () {
    //check for message & order notification every 30 sec
    countMessage();
    setInterval( function(){countMessage()}, 30000)

    //Apparition du modal d'information
    if ($('#msg-modal').length > 0) {
        $('#msg-modal').modal('show');
        $('#msg-modal').on('hidden.bs.modal', function (e) {
            $('#msg-modal').remove();
        });
    }

    showUserMainCompaniesHeader();
    changeUserMainCompany();

    //Select 2
    $('.select2').select2({
        theme: 'bootstrap4'
    });

    $('[data-toggle="popover"]').popover();

    if (jQuery().dataTable && $('.dataTable').length > 0) {
        $('.dataTable').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true
        });
    }

    //Clear errors
    clearError();
});

    
/**********************************************************************
 * Form validation
 */
var card_overlay = '<div class="overlay"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>';

var ajax_form_elem = '';
function ajax_form(form, ajax_action) {

    form.submit(function (event) {
        if ($(this).parents('.card').find('.overlay').length == 0) {
            $(this).parents('.card').append(card_overlay);
        }

        event.preventDefault();

        var data_ajax = $(this).serializeObject();
        data_ajax.action = ajax_action;
        ajax_function(data_ajax, ajax_form_validation, $(this));
        return false;
    });
}

var toastr_options = {
    closeButton: true,
    debug: false,
    newestOnTop: true,
    progressBar: true,
    positionClass: "toast-top-right",
    preventDuplicates: false,
    onclick: null,
    showDuration: "300",
    hideDuration: "1000",
    timeOut: "3000",
    extendedTimeOut: "1000",
    showEasing: "swing",
    hideEasing: "linear",
    showMethod: "fadeIn",
    hideMethod: "fadeOut"
};

function ajax_form_validation(elem, data) {

    if (data.return == 'ok') {
        toastr.success(data.message, 'Validation OK', toastr_options);
    } else {
        var err_msg = '<p>' + data.message + '</p>';
        if (data.errors != undefined) {
            elem.find(':input').addClass('is-valid');
            var errors = data.errors;
            for (err in errors) {
                err_msg += '<p>' + errors[err] + '</p>';
                elem.find(':input[name=' + err + ']').removeClass('is-valid').addClass('is-invalid');
            }
        }
        toastr.error(err_msg, 'Validation error', toastr_options);
    }

    if (data.addElem != undefined) {
        var add = data.addElem;
        $(add.blocId).append(add.elem);
        ajax_form($(add.blocId).find('form').last(), data.action);
    }
    //On retire l'overlay
    elem.parents('.card').find('.overlay').remove();
}

/**
 * End form validation
 *********************************************************************/

/*
 * Fonction animation du scroll sur une ancre
 */
function animateScroll(_scroll, decal) {
    var positionE = _scroll.offset();
    if (decal != undefined) {
        _decal = decal;
    } else {
        _decal = 0;
    }
    if (positionE != undefined)
        $('html,body').animate({scrollTop: positionE.top + _decal}, 400);
}


 /***
 *
 * Fonction générique d'appel ajax
 * data_ajax : Tableau json envoi des datas
 * on_complete : fonction à appeler au succes
 * elem : Element à envoyer à la fonction de retour
 */
function ajax_function(data_ajax, on_complete, elem) {
    $.ajax({
        type: "POST",
        url: '/ajax',
        data: data_ajax,
        dataType: 'json',
        success: function (data) {
            if (typeof (on_complete) == 'function') {
                on_complete(elem, data);
            }
        }
    });
}



function show_modal(data, action, param_action) {

    var content = data.content != undefined ? data.content : '';
    var titre = data.titre != undefined ? data.titre : '';
    var btn_action = data.btn_action != undefined ? '<button id="valid-action" type="button" class="btn btn-primary">' + data.btn_action + '</button>' : '';

    var modal = '<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModal" aria-hidden="true">'
        + '<div class="modal-dialog" role="document">'
        + '<div class="modal-content">'
        + '<div class="modal-header">'
        + '<h5 class="modal-title" id="exampleModalLabel">' + titre + '</h5>'
        + '<button type="button" class="close" data-dismiss="modal" aria-label="Close">'
        + '<span aria-hidden="true">&times;</span>'
        + '</button> '
        + '</div>'
        + '<div class="modal-body">'
        + content
        + '</div>'
        + '<div class="modal-footer">'
        + '<button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>'
        + btn_action
        + '</div>'
        + ' </div>'
        + '</div>'
        + '</div>';

    $('body').remove('#myModal').append(modal);

    //Si il y a une action
    if (data.btn_action != undefined) {
        $('#valid-action').click(function () {
            action(param_action);
            $('#myModal').modal('hide');
            return false;
        });
    }

    $('#myModal').modal({show: true});
}

//Serialize en objet
$.fn.serializeObject = function serializeObject() {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function () {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

function clearError() {
    $(':input').focus(function () {
        $(this).removeClass('is-invalid');
        $(this).removeClass('is-valid');
        $(this).siblings('.invalid-tooltip').remove();
    });
}

function showUserMainCompaniesHeader() {
    if ($('#userMainCompaniesHeader').length) {
        var url = $('#userMainCompaniesHeader').data('url');
        $.get(url, function (data) {
            $('#userMainCompaniesHeader').html(data);
        });
    }
}

function changeUserMainCompany() {
    $(document).on('change', '#userMainCompaniesHeader select', function () {
        var url = $(this).data('url');
        //var current_url = window.location.pathname;
        var main_company = $(this).val();
        $.post(url, {user_main_company: main_company, request: 'ajax'}, function (data) {
            if (data) {
                window.location.replace(window.location.href);
            }
        });
    });

    return false;
}

function toastrResponse(data) {
    if (typeof data !== 'undefinded') {
        data = JSON.parse(data);
        if (typeof data.status !== 'undefined') {
            if (typeof data.status === 'undefined') {
                data.msg = '';
            }

            if (data.status === 'success') {
                toastr.success(data.msg);
            } else if (data.status === 'error') {
                toastr.error(data.msg);
            } else if (data.status === 'warning') {
                toastr.warning(data.msg);
            } else if (data.status === 'info') {
                toastr.info(data.msg);
            }

        }
    }
    return false;

        
}