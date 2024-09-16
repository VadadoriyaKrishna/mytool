
        <div id="" class="tab-pane animated--fade-in active">
            <div class="d-flex">
                <div class="col-md-12" id="main_div_size">
                    <div class="tab-content-inner">
                        <div class="tab-content tab-content-container">
                            <div id="" class="tab-pane animated--fade-in active">
                                <?php include_once("/var/www/html/mobigram/receptionist_new/cokey_book/add_cokey_book.php"); ?>
                                <div class="form-inner pt-4">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="cokey_book_table" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Name</th>
                                                    <th>Description</th>
                                                    <th>Cover img</th>
                                                    <th>Price</th>
                                                    <th>Pages</th>
                                                    <th>Per page story count</th>
                                                    <th>Story days count</th>
                                                    <th>Book languages</th>
                                                    <th>Created on</th>
                                                    <th>Edit</th>
                                                    <th>Delete</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Name</th>
                                                    <th>Description</th>
                                                    <th>Cover img</th>
                                                    <th>Price</th>
                                                    <th>Pages</th>
                                                    <th>Per page story count</th>
                                                    <th>Story days count</th>
                                                    <th>Book languages</th>
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
                var table = $("#cokey_book_table").DataTable({
                    "serverSide": true,
                    "processing": true,
                    "ajax": {
                        url: "fluvina_index.php?broughtBy=CokeyBook",
                        type: "post",
                        data: {"action": "get_cokey_book_list"}
                    },
                    columns: [                    { data: 'id', orderable: true, name: 'id' },
                    { data: 'name', orderable: true, name: 'name' },
                    { data: 'description', orderable: true, name: 'description' },
                    {
                        data: 'cover_img_url',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row) {
                            var imageUrl = data;
                            return '<a href="' + imageUrl + '" target="_blank"><img src="' + imageUrl + '" style="max-height: 100px; min-height: 100px; max-width: 100px; min-width: 100px;"/></a>';
                        }
                    },
                    { data: 'price', orderable: true, name: 'price' },
                    { data: 'pages', orderable: true, name: 'pages' },
                    { data: 'per_page_story_count', orderable: true, name: 'per_page_story_count' },
                    { data: 'story_days_count', orderable: true, name: 'story_days_count' },
                    { data: 'book_languages', orderable: true, name: 'book_languages' },
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
                    DeleteConfirmation(id, "cokey_book", "delete_cokey_book");
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
                    var id = $(this).attr("data-id");
                    $.ajax({
                        type: "POST",
                        url: "fluvina_index.php?broughtBy=CokeyBook",
                        data: {action: "edit_cokey_book", id: id},
                        success: function (response) {
                            if (response.status == true) {
                                var data = response.data;
                                if (!$(".add_new_data").hasClass("open_form")) {
                            $(".add_new_data").addClass("open_form");
                            $("#CokeyBook_form_div").css("display", "block");
                            $(".add_new_data").text("Hide CokeyBook");
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