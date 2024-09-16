
        <div id="" class="tab-pane animated--fade-in active">
            <div class="d-flex">
                <div class="col-md-12" id="main_div_size">
                    <div class="tab-content-inner">
                        <div class="tab-content tab-content-container">
                            <div id="" class="tab-pane animated--fade-in active">
                                <?php include_once("/var/www/html/mobigram/receptionist_new/cokey_users/add_cokey_users.php"); ?>
                                <div class="form-inner pt-4">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="cokey_users_table" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>First name</th>
                                                    <th>Address</th>
                                                    <th>Last name</th>
                                                    <th>Age</th>
                                                    <th>Date of birth</th>
                                                    <th>Password</th>
                                                    <th>Mobile number</th>
                                                    <th>Gender</th>
                                                    <th>Created on</th>
                                                    <th>Edit</th>
                                                    <th>Delete</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>First name</th>
                                                    <th>Address</th>
                                                    <th>Last name</th>
                                                    <th>Age</th>
                                                    <th>Date of birth</th>
                                                    <th>Password</th>
                                                    <th>Mobile number</th>
                                                    <th>Gender</th>
                                                    <th>Created on</th>
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
                var table = $("#cokey_users_table").DataTable({
                    "serverSide": true,
                    "processing": true,
                    "ajax": {
                        url: "fluvina_index.php?broughtBy=CokeyUsers",
                        type: "post",
                        data: {"action": "get_cokey_users_list"}
                    },
                    columns: [                    { data: 'id', orderable: true, name: 'id' },
                    { data: 'first_name', orderable: true, name: 'first_name' },
                    { data: 'address', orderable: true, name: 'address' },
                    { data: 'last_name', orderable: true, name: 'last_name' },
                    { data: 'age', orderable: true, name: 'age' },
                    { data: 'date_of_birth', orderable: true, name: 'date_of_birth' },
                    { data: 'password', orderable: true, name: 'password' },
                    { data: 'mobile_number', orderable: true, name: 'mobile_number' },
                    { data: 'gender', orderable: true, name: 'gender' },
                    { data: 'created_on', orderable: true, name: 'created_on' },

                        {
                            data: null,
                            orderable: false,
                            searchable: false,
                            render: function (data, type, row) {
                                var editHtml= '<a href="javascript:void(0);" class="edit_data" data-id="' + row.id + '"><i class="fa fa-pencil-alt" aria-hidden="true"></i></a>';
                                return editHtml;
                            }
                        },
                        {
                            data: null,
                            orderable: false,
                            searchable: false,
                            className: "text-center",
                            render: function (data, type, row) {
                                 $id = row["id"];

                                if (row["splash_for"] != "0") {
                                var deleteHtml= '<a href="javascript:void(0);" class="delete_data" data-id="' + row.id + '"><i class="fa fa-trash" style="color:#ff6252;" aria-hidden="true"></i></a>';
                                }
                                else
                                { var deleteHtml="";
                                }   
                                 return deleteHtml;
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
                    DeleteConfirmation(id, "CokeyUsers", "delete_cokey_users");
                });

                 // This function is use for the after delete load data
                $(document).off("click", "table").on("click", "table", function (e) {
                    if ($(this).hasClass("reloadData")) {
                        reloadData(table);
                    }
                });
        
                // Handle edit action
                $(document).off("click", ".edit_data").on("click", ".edit_data", function (e) {
                    var id = $(this).data("id");
                    $.ajax({
                        type: "POST",
                        url: "fluvina_index.php?broughtBy=CokeyUsers",
                        data: {action: "edit_cokey_users", id: id},
                        success: function (response) {
                            if (response.status == true) {
                                var data = response.data;
                                if (!$(".add_new_data").hasClass("open_form")) {
                            $(".add_new_data").addClass("open_form");
                            $("#CokeyUsers_form_div").css("display", "block");
                            $(".add_new_data").text("Hide CokeyUsers");
                        }

                        $(".rec-panel").animate({scrollTop: 0}, "slow"); // page scroll

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
<?php require_once "/var/www/html/mobigram/deleteConfirmationPopupHtml.php"  ?>