$('#sub_company').on('select2:select', function (e) {
    var data = e.params.data;
    $.ajax({
        type: "POST",
        url: "/invoice/setSubCompany",
        data: { id: data.id },
        success: function(res) {;
            window.location.reload();
        }
    })
});