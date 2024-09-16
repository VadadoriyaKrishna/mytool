$(document).ready(function () {
    $.ajax({
        url: 'fluvina_index.php?broughtBy=cokey',
        type: 'POST',
        data: { action: 'get_table_columns' },
        success: function (response) {
            var columns = response.columns;
            var columnDefs = [];

            columns.forEach(function (column) {
                if (column.type === 'image') {
                    columnDefs.push({
                        data: column.name,
                        render: function (data) {
                            return '<a href="' + data + '" target="_blank"><img src="' + data + '" style="max-height: 100px; min-height: 100px; max-width: 100px; min-width: 100px;"/></a>';
                        }
                    });
                } else {
                    columnDefs.push({ data: column.name });
                }
            });

            $('#dynamic_table').DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: 'fluvina_index.php?broughtBy=cokey',
                    type: 'POST',
                    data: { action: 'get_table_data' }
                },
                columns: columnDefs,
                order: []
            });
        }
    });

    // Event listeners for edit and delete actions
    $(document).on('click', '.edit_data', function () {
        var id = $(this).data('id');
        $.ajax({
            type: 'POST',
            url: 'fluvina_index.php?broughtBy=cokey',
            data: { action: 'edit_cokey_book', id: id },
            success: function (response) {
                if (response.status) {
                    var data = response.data;
                    $('#dynamic_form').find('[name]').each(function () {
                        $(this).val(data[$(this).attr('name')]);
                    });
                    $('#dynamic_form').show();
                } else {
                    alert(response.msg);
                }
            }
        });
    });

    $(document).on('click', '.delete_data', function () {
        var id = $(this).data('id');
        if (confirm('Are you sure you want to delete this item?')) {
            $.ajax({
                type: 'POST',
                url: 'fluvina_index.php?broughtBy=cokey',
                data: { action: 'delete_cokey_book', id: id },
                success: function (response) {
                    if (response.status) {
                        $('#dynamic_table').DataTable().ajax.reload();
                    } else {
                        alert(response.msg);
                    }
                }
            });
        }
    });
});
