/* 
 * blacklistFiles
 */

$(document).ready(function () {
    if (jQuery().dataTable) {
        var arrayColumnDefs = [];
        arrayColumnDefs[0] = { type: 'date-eu', targets: 1 }
        $('#blacklistFiles').DataTable({
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

    $('#divCompany').find('select').change(function (e) {
        var companyId = $('#divCompany').find('select').val();
        if (companyId == '')
            return;
        $.ajax({
            type : 'GET',
            url : '/order/getCompanyByAjax/' + companyId,
            success : function(res, statut) {
                window.location.reload();
            },
            error : function (request, status, error) {
                toastr.error(error.message, 'Error');
            },
            dataType : 'json'
        });
    });
});

function ajaxBlacklistFileSend(event, id, msgConfirm, msgSuccess, msgImportedLines, msgError) {
    event.preventDefault();  
    if (confirm(msgConfirm)) {
        $.ajax({
            type: 'GET',
            url: '/blacklistfiles/send/' + id,
            beforeSend: function() {
                $('html').css('cursor', 'wait');
            },
            success: function (res, statut) {
                if (res.resultat == 1) {
                    toastr.success(res.statistique[0].email + msgImportedLines, msgSuccess, toastr_options);
                    window.location.reload();
                } else {
                    toastr.error(res.erreur, msgError);
                }
            },
            error: function (request, status, error) {
                toastr.error(error.message, msgError);
            },
            complete: function() {
                $('html').css('cursor', 'default');
            },
            dataType: 'json'
        });
    } else {
        return;
    }
}

function ajaxBlacklistFileDelete(event, msgConfirm, msgSuccess, msgLineDeleted, msgError) {
    event.preventDefault();

    if (confirm(msgConfirm)) {
        $.ajax({
            type: 'GET',
            url: '/blacklistfiles/delete',
            beforeSend: function() {
                $('html').css('cursor', 'wait');
            },
            success: function (res, statut) {
                if (res.resultat == 1) {
                    toastr.success(res.total_suppression + msgLineDeleted, msgSuccess, toastr_options);
                    window.location.reload();
                } else {
                    toastr.error(res.erreur, msgError);
                }
            },
            error: function (request, status, error) {
                toastr.error(error.message, msgError);
            },
            complete: function() {
                $('html').css('cursor', 'default');
            },
            dataType: 'json'
        });
    } else {
        return;
    }
} 

$('form').on('submit', function (e) {
    var idFieldsFiles = $(this).attr('idFieldsFiles');
    var maxSize = $(this).attr('upload_max_filesize');
    var msgFilesEmpty = $(this).attr('msgFilesEmpty');
    var msgInvalidExtension = $(this).attr('msgInvalidExtension');
    var msgInvalidSize = $(this).attr('msgInvalidSize');
    var msgEmptyName = $(this).attr('msgEmptyName');
    return validFieldCustom(this, idFieldsFiles, maxSize, msgFilesEmpty, msgInvalidExtension, msgInvalidSize, msgEmptyName);
});

function validFieldCustom(form, idFieldsFiles, maxSize, msgFilesEmpty, msgInvalidExtension, msgInvalidSize, msgEmptyName) {    
    if (validFile(form, idFieldsFiles, maxSize.replace('M', ''), msgFilesEmpty, msgInvalidExtension, msgInvalidSize)) {
        if (controlFieldInForm(form, msgEmptyName) == true) {
            form.submit();
        }  
    }
    return false;
}

function controlFieldInForm(object, msgEmptyName)
{
    var nextStep = true;

    $(object).find('.form-error').each(function() {
        $(this).hide();
    });

    $(object).find('input[type="text"], input[type="password"], input[type="number"], input[type="email"], textarea').each(function() {
        if ($(this).val() == '') {
            var html = "<span class=\"form-error\">" + msgEmptyName + "</span>";
            $(html).insertAfter(this);
            nextStep = false;
        }
    });

    return nextStep;
}

function validFile(form, files, maxSize, msgFilesEmpty, msgInvalidExtension, msgInvalidSize) {
    $(form).find('.form-error').each(function() {
        $(this).hide();
    });

    var listExtensionValid = ['application/vnd.ms-excel'];

    var fileIn = $("#" + files)[0];

    if (fileIn.files[0] == undefined || fileIn.files[0] == 'undefined') {
        if ($('#id').length == 0) {
            var html = "<span class=\"form-error\">" + msgFilesEmpty + ".</span>";
            $(html).insertAfter($("#" + files));

            return false;
        }
    } else {
        var size = fileIn.files[0].size;
        var type = fileIn.files[0].type;

        if (listExtensionValid.indexOf(type) == -1) {
            var html = "<span class=\"form-error\">" + msgInvalidExtension + ".</span>";
            $(html).insertAfter($("#" + files));

            return false;
        }

        if (size > 1024 * 1024 * maxSize) {
            var html = "<span class=\"form-error\">" + msgInvalidSize + ".</span>";
            $(html).insertAfter($("#" + files));

            return false;
        }
    }

    return true;
}
