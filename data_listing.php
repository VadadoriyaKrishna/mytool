<?php
class DynamicTableGenerator {
    private $viewDirPath;
    private $commonFields = [ 'status', 'created_by', 'updated_by', 'updated_on', 'is_deleted','ip'];

    private function filterColumns($columns) {
        // Filter out common fields
        return array_filter($columns, function ($column) {
            return !in_array($column['Field'], $this->commonFields);
        });
    }
    public function generateDataTable($columns, $controllerName, $modelName, $viewDirName, $tableName) {

        $columns = $this->filterColumns($columns);

        // Define the path where the view file will be saved
        //$viewDirPath = "{$this->viewDirPath}/{$viewDirName}";
        $viewDirPath = __DIR__ . "/views/{$viewDirName}";
       
        $viewFilePath = "{$viewDirPath}/list_" . strtolower($tableName) . ".php";

        // Create the directory if it doesn't exist
        if (!is_dir($viewDirPath)) {
            mkdir($viewDirPath, 0777, true);
        }

        $viewTemplate = '
        <div id="" class="tab-pane animated--fade-in active">
            <div class="d-flex">
                <div class="col-md-12" id="main_div_size">
                    <div class="tab-content-inner">
                        <div class="tab-content tab-content-container">
                            <div id="" class="tab-pane animated--fade-in active">
                                <?php include_once("/var/www/html/mobigram/receptionist_new/'.$viewDirName.'/add_'. strtolower($viewDirName) . '.php"); ?>
                                <div class="form-inner pt-4">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="' . strtolower($tableName) . '_table" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>';
        
                // Dynamically create table headers
                foreach ($columns as $column) {
                    $headerName = str_replace('_', ' ', $column['Field']);  // Replace underscores with spaces

                    $viewTemplate .= '
                                                    <th>' . ucfirst($headerName) . '</th>';
                }
                $viewTemplate .= '
                                                    <th>Edit</th>
                                                    <th>Delete</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr>';
        
                // Dynamically create table footers
                foreach ($columns as $column) {
                    $headerName = str_replace('_', ' ', $column['Field']);  // Replace underscores with spaces
                    $viewTemplate .= '
                                                    <th>' . ucfirst($headerName) . '</th>';
                }
                $viewTemplate .= '
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
        </div>';
        
                // Generate DataTable initialization script with proper indentation
                $viewTemplate .= '
        <script>
            $(function () {
                table = TableDetailReload();
            });
        
            function TableDetailReload() {
                var table = $("#' . strtolower($tableName) . '_table").DataTable({
                    "serverSide": true,
                    "processing": true,
                    "ajax": {
                        url: "fluvina_index.php?broughtBy='.$controllerName.'",
                        type: "post",
                        data: {"action": "get_' . strtolower($tableName) . '_list"}
                    },
                    columns: [';
        
                // Dynamically create DataTable column definitions

                
                
                foreach ($columns as $column) {
                    $fieldName = htmlspecialchars($column['Field'], ENT_QUOTES, 'UTF-8');
                    
                    if (strpos($fieldName, 'img') !== false || 
                    strpos($fieldName, 'image') !== false || 
                    strpos($fieldName, 'photo') !== false) {
                        // Handle image columns
                        $viewTemplate .= "                    {\n";
                        
                        $viewTemplate .= "                        data: null,\n";
                        $viewTemplate .= "                        orderable: false,\n";
                        $viewTemplate .= "                        searchable: false,\n";
                        $viewTemplate .= "                        render: function (data, type, row) {\n";
                        $viewTemplate .= "                            var {$fieldName}_url = data.{$fieldName}_url;\n";
                        $viewTemplate .= "                            return '<a href=\"' + {$fieldName}_url + '\" target=\"_blank\"><img src=\"' + {$fieldName}_url + '\" style=\"max-height: 100px; min-height: 100px; max-width: 100px; min-width: 100px;\"/></a>';\n";
                        $viewTemplate .= "                        }\n";
                        $viewTemplate .= "                    },\n";
                    } else {
                        // Handle regular columns
                        $viewTemplate .= "                    { data: '{$fieldName}', orderable: true, name: '{$fieldName}' },\n";
                    }
                }
        
                $viewTemplate .= '
                        {
                            data: null,
                            orderable: false,
                            searchable: false,
                            render: function (data, type, row) {
                                var editHtml= \'<a href="javascript:void(0);" class="edit_data" data-id="\' + row.id + \'"><i class="fa fa-pencil-alt" aria-hidden="true"></i></a>\';
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
                                var deleteHtml= \'<a href="javascript:void(0);" class="delete_data" data-id="\' + row.id + \'"><i class="fa fa-trash" style="color:#ff6252;" aria-hidden="true"></i></a>\';
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
                    DeleteConfirmation(id, "' . $controllerName . '", "delete_' . strtolower($tableName) . '");
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
                        url: "fluvina_index.php?broughtBy='.$controllerName.'",
                        data: {action: "edit_' . strtolower($tableName) . '", id: id},
                        success: function (response) {
                            if (response.status == true) {
                                var data = response.data;
                                if (!$(".add_new_data").hasClass("open_form")) {
                            $(".add_new_data").addClass("open_form");
                            $("#'.$controllerName.'_form_div").css("display", "block");
                            $(".add_new_data").text("Hide '.$controllerName.'");
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
        </script>';
        $viewTemplate .= "\n";

        $viewTemplate .= '<?php require_once "/var/www/html/mobigram/deleteConfirmationPopupHtml.php"  ?>';
        
                // Define the path where the view file will be saved
                //$viewFilePath = "{$viewDirPath}/list_" . strtolower($tableName) . ".php";
                $viewFilePath = "{$viewDirPath}/{$viewDirName}_list.php";
        
                // Save the generated content to the view file
                file_put_contents($viewFilePath, $viewTemplate);
                echo "<br><br>View file '{$viewDirName}_list.php' has been created in '{$viewDirName}' directory successfully!\n";
            }
        }