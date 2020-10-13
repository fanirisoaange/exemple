/* 
 * Users
 */


$(document).ready(function () {


    $('#userModal').on('show.bs.modal', function (e) {
        var url = $(e.relatedTarget);
        var modal = $(this);
        modal.find('.modal-body').load(url.data("remote"));

    });


    if (jQuery().dataTable) {
        $('#userList').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true
        });
    }

    $(document).on('click', '#addUserGroupCompanies', function (e) {
        e.preventDefault;
        group = $('#newGroup').val();
        companies = $('#newCompanies').val();
        if (group && group != '' && companies && companies != '') {

            rows = $('#userGroupsComapnies tbody tr').length;
            row = rows + 1;

            //create the groups dropdown. UserGroups is defined in view
            selectGroup = '<div class="form-group"><select name="group_companies[' + row + '][group]" class="form-control select2bs4" required>';
            $(userGroups).each(function (iG, eG) {
                selectGroup += '<option value="' + eG.id + '" ' + (eG.id == group ? 'selected' : '') + '>' + eG.name + '</option>';
            });
            selectGroup += '</select><div>';

            //create the companies dropdown. userCompanies is defined in view
            company_selected = '';
            selectCompanies = '<div class="form-group"><div class="select2-success"><select name="group_companies[' + row + '][companies][]" class="form-control select2bs4" multiple="multiple" required>';
            $(userCompanies).each(function (iUC, eUC) {
                $(companies).each(function (iC, eC) {
                    if (eC == eUC.id) {
                        company_selected = 'selected';
                    }
                });
                selectCompanies += '<option value="' + eUC.id + '" ' + company_selected + ' >' + eUC.commercial_name + '</option>';
                company_selected = '';
            });
            selectCompanies += '</select></div></div>';

            //HTML
            html = '<tr>';
            html += '<td></td>';
            html += '<td>' + selectGroup + '</td>';
            html += '<td>' + selectCompanies + '</td>';
            html += '</tr>';

            $('#userGroupsComapnies tbody').append(html);
            $('.select2').select2();
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });
        }

        return false;
    });
    $('.select2').select2();
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    });
});

function ajaxUserDelete(event, id, msgConfirm, msgSuccess, msgError) {
    event.preventDefault();
    if (confirm(msgConfirm)) {
        $.ajax({
            type : 'GET',
            url : '/user/delete/' + id,
            success : function(res, statut) {
                toastr.success(res.message, msgSuccess, toastr_options);
                window.location.href = '/user/list';
            },
            error : function (request, status, error) {
                toastr.error(error.message, msgError);
            },
            dataType : 'json'
        });
    } else
        return;
}