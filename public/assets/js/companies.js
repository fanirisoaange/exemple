$(document).ready(function () {
    if (jQuery().dataTable) {
        var arrayColumnDefs = [];
        arrayColumnDefs[0] = { type: 'date-eu', targets: 1 }
        $('#companyList').DataTable({
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
    
    $(document).on('click', '.display_subcompany', function (e) {
        e.preventDefault;
        $(this).parents("tr").next().toggle("fast");
        return false;
    });

    $(document).on('change', '#dopdownMainID', function () {
        url = $(this).data('url');
        main_id = $(this).val();
        console.log(url, 'url');
        console.log(main_id, 'parent_id');
        $('#dopdownParentID option').remove();
        $.post(url, {main_id: main_id}, function (data) {
            if (data) {
                options = '';
                $.each(data, function (k, v) {
                    options += '<option value="' + k.trim() + '">' + v + '</option>';
                });
                $('#dopdownParentID').append(options);

                $('#dopdownParentID').select2({
                    theme: 'bootstrap4'
                });
            }
        }, 'json');
    });

    formCompanyBilling();

    $(document).on('click', '#companyBilling', function () {
        formCompanyBilling(true);
    });
});

function formCompanyBilling(scroll = false) {
    var checked = $('#companyBilling').is(":checked");
    if (checked) {
        $('#formCompanyBilling').hide();
    } else {
        $('#formCompanyBilling').show();
        if (scroll) {
            $("body,html").animate({
                scrollTop: $("#formCompanyBilling").offset().top - 72
            },
                    'fast'
                    );
        }
}
}