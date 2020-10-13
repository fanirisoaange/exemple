$(document).ready(function () {
    
    if (jQuery().dataTable) {
        $('#permissionsList').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true
        });
    }
    
    $("#formPermissionAdd textarea").attr('rows','2');
    
    $(document).on('click', '.updateGroupPermission', function (e) {
        e.preventDefault;

        var checked = $(this).is(":checked");
        var url = $(this).data('url');
        var group_id = $(this).data('group_id');
        var permission_id = $(this).data('permission_id');

        $.post(url, {checked: checked, group_id: group_id, permission_id: permission_id}, function (data) {
            if (data) {
                toastrResponse(data);
            }
        });
    });
});