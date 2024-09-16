
        <div id="" class="tab-pane animated--fade-in active">
            <div class="d-flex">
                <div class="col-md-12" id="main_div_size">
                    <div class="tab-content-inner">
                        <div class="tab-content tab-content-container">
                            <div id="" class="tab-pane animated--fade-in active">
                                <?php include_once("/var/www/html/mvctool/views/{$viewDirName}/add_" . strtolower($viewDirName) . ".php"); ?>
                                <div class="form-inner pt-4">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="en_camera_table" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>Nvr_id</th>
                                                    <th>Channel_id</th>
                                                    <th>Name</th>
                                                    <th>Location</th>
                                                    <th>Latitude</th>
                                                    <th>Longitude</th>
                                                    <th>Address</th>
                                                    <th>On_off_status</th>
                                                    <th>Deleted_by</th>
                                                    <th>Deleted_on</th>
                                                    <th>Edit</th>
                                                    <th>Delete</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Nvr_id</th>
                                                    <th>Channel_id</th>
                                                    <th>Name</th>
                                                    <th>Location</th>
                                                    <th>Latitude</th>
                                                    <th>Longitude</th>
                                                    <th>Address</th>
                                                    <th>On_off_status</th>
                                                    <th>Deleted_by</th>
                                                    <th>Deleted_on</th>
                                                    <th>Edit</th>
                                                    <th>Delete</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(function () {
                table = TableDetailReload();
            });
        
            function TableDetailReload() {
                var table = $("#en_camera_table").DataTable({
                    "serverSide": true,
                    "processing": true,
                    "ajax": {
                        url: "Camera/get_en_camera_list",
                        type: "post",
                        data: {"action": "get_en_camera_list"}
                    },
                    columns: [
                        {data: "nvr_id", orderable: true, name: "nvr_id"},
                        {data: "channel_id", orderable: true, name: "channel_id"},
                        {data: "name", orderable: true, name: "name"},
                        {data: "location", orderable: true, name: "location"},
                        {data: "latitude", orderable: true, name: "latitude"},
                        {data: "longitude", orderable: true, name: "longitude"},
                        {data: "address", orderable: true, name: "address"},
                        {data: "on_off_status", orderable: true, name: "on_off_status"},
                        {data: "deleted_by", orderable: true, name: "deleted_by"},
                        {data: "deleted_on", orderable: true, name: "deleted_on"},
                        {
                            data: null,
                            orderable: false,
                            searchable: false,
                            render: function (data, type, row) {
                                return '<a href="javascript:void(0);" class="edit_data" data-id="' + row.id + '"><i class="fa fa-pencil-alt" aria-hidden="true"></i></a>';
                            }
                        },
                        {
                            data: null,
                            orderable: false,
                            searchable: false,
                            className: "text-center",
                            render: function (data, type, row) {
                                return '<a href="javascript:void(0);" class="delete_data" data-id="' + row.id + '"><i class="fa fa-trash" style="color:#ff6252;" aria-hidden="true"></i></a>';
                            }
                        }
                    ],
                    "order": [],
                    fnServerParams: function (data) {
                        data["order"].forEach(function (items, index) {
                            data["order"][index]["column"] = data["columns"][items.column]["name"];
                        });
                    },
                    "bFilter": true,
                });
        
                // Handle delete action
                $(document).off("click", ".delete_data").on("click", ".delete_data", function (e) {
                    e.preventDefault();
                    var id = $(this).data("id");
                    DeleteConfirmation(id, "en_camera", "delete_en_camera");
                });
        
                // Handle edit action
                $(document).off("click", ".edit_data").on("click", ".edit_data", function (e) {
                    var id = $(this).data("id");
                    $.ajax({
                        type: "POST",
                        url: "Camera/edit_en_camera",
                        data: {action: "edit_en_camera", id: id},
                        success: function (response) {
                            if (response.status == true) {
                                form_data_load(response.data);
                            } else {
                                notify("Error", response.msg, "error");
                            }
                        }
                    });
                });
        
                return table;
            }
        </script>