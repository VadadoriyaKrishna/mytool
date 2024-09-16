<?php 

class controllerGenerator{
    private $columns;
    private $tableName;
    private $controllerName;
    private $modelName;
    private $viewDirName;

    function generateController($column,$controllerName, $modelName, $viewDirName, $tableName)
    
{
    $this->columns = $column;
    $getcontrollerName = "new $controllerName();";
    
    
//     print_r('<pre>');
//   print_r($getcontrollerName);
//   print_r('<pre>');

    
    $controllerContent = "<?php\n\n";
    $controllerContent .= "require_once '/var/www/html/mobigram/models/{$modelName}.php';\n\n";
    $controllerContent .= "class {$controllerName} extends {$modelName} {\n\n";
    $controllerContent .= "    function __construct() {\n";
    $controllerContent .= "        parent::__construct();\n";
    $controllerContent .= "        header('Content-Type: application/json');\n\n";
    $controllerContent .= "        if (isset(\$_POST['action']) && \$_POST['action'] != '') {\n";
    $controllerContent .= "            \$action = \$_POST['action'];\n\n";
    $controllerContent .= "            if(\$action == \"get_{$tableName}_list\") {\n";
    $controllerContent .= "                self::get" . ucfirst($tableName) . "List();\n";
    $controllerContent .= "            } elseif (\$action == \"add_{$tableName}\") {\n";
    $controllerContent .= "                self::add" . ucfirst($tableName) . "();\n";
    $controllerContent .= "            } elseif (\$action == \"edit_{$tableName}\") {\n";
    $controllerContent .= "                self::get" . ucfirst($tableName) . "ById();\n";
    $controllerContent .= "            } elseif (\$action == \"delete_{$tableName}\") {\n";
    $controllerContent .= "                \$this->delete" . ucfirst($tableName) . "();\n";
    $controllerContent .= "            }\n";
    $controllerContent .= "        }\n";
    $controllerContent .= "    }\n\n";

    // Generate CRUD methods
    $controllerContent .= "    private function get" . ucfirst($tableName) . "List() {\n";
    $controllerContent .= "        \$list = array();\n\n";
    $controllerContent .= "        if (isset(\$_POST['search']['value']) && trim(\$_POST['search']['value']) != '') {\n";
    $controllerContent .= "            \$search_keyword = \$_POST['search']['value'];\n";
    $controllerContent .= "        }\n\n";
    $controllerContent .= "        \$sortBy = '';\n";
    $controllerContent .= "        if (\$_POST[\"order\"][0][\"column\"] != \"\") {\n";
    $controllerContent .= "            \$sortBy = \$_POST[\"order\"][0][\"column\"];\n";
    $controllerContent .= "        }\n\n";
    $controllerContent .= "        \$sortOrder = '';\n";
    $controllerContent .= "        if (\$_POST[\"order\"][0][\"dir\"] != \"\") {\n";
    $controllerContent .= "            \$sortOrder = \$_POST[\"order\"][0][\"dir\"];\n";
    $controllerContent .= "        }\n\n";
    $controllerContent .= "        \$start = \$_POST['start'];\n";
    $controllerContent .= "        \$length = \$_POST['length'];\n\n";
    $controllerContent .= "        \$totalRecords = \$this->{$controllerName}Count(\$search_keyword);\n\n";
    $controllerContent .= "        if (\$totalRecords > 0) {\n";
    $controllerContent .= "            \$list = \$this->{$controllerName}List(\$search_keyword, \$start, \$length, \$sortBy, \$sortOrder);\n";
    $controllerContent .= "        }\n\n";
    $controllerContent .= "        \$message = (count(\$list) > 0) ? 'Data loaded successfully' : 'No Data Found';\n";
    $controllerContent .= "        \$response = array('status' => true,\n";
    $controllerContent .= "            'draw' => (isset(\$_POST['draw'])) ? \$_POST['draw'] : 0,\n";
    $controllerContent .= "            'recordsTotal' => (int) \$totalRecords,\n";
    $controllerContent .= "            'recordsFiltered' => (int) \$totalRecords,\n";
    $controllerContent .= "            'data' => (array) \$list,\n";
    $controllerContent .= "            'message' => (string) \$message);\n\n";
    $controllerContent .= "        echo json_encode(\$response);\n";
    $controllerContent .= "        exit;\n";
    $controllerContent .= "    }\n\n";

    $controllerContent .= "    private function add" . ucfirst($tableName) . "() {\n";
    $commonFields = ['id', 'created_on', 'created_by', 'updated_by', 'updated_on', 'is_deleted','ip'];
    
    $required_params = [];
    
    // Loop through columns and exclude common fields
    foreach ($this->columns as $column) {
        if ($column['Null'] == 'NO' && !in_array($column['Field'], $commonFields) && strpos($column['Field'], 'img') === false) {
            $required_params[] = $column['Field'];
        }
    }
    
    // Convert the array to a directly assignable format 
    $controllerContent .= "     \$required_params = [\"" . implode('", "', $required_params) . "\"];\n";
    
    $controllerContent .= "\n        if (CommonFunctions::CheckRequiredParams(\$required_params, 'post')) {\n\n";
    
    // Loop to create variables from POST data, excluding common and image fields
    foreach ($this->columns as $column) {
        if (!in_array($column['Field'], $commonFields) && strpos($column['Field'], 'img') === false) {
            $controllerContent .= "            \${$column['Field']} = \$_POST['{$column['Field']}'];\n";
        }
    }
    // Handle 'id' and 'old_image' separately
    $controllerContent .= "            \$id = \$_POST['id'];\n";

    foreach ($this->columns as $column) {
    if (in_array($column['Field'], ['cover_img', 'profile', 'photo', 'image'])){
    $controllerContent .= "            \$old_image = \$_POST['old_image'];\n";
    }
    }
    //$controllerContent .= "\n            \$msg = \"\";\n\n";
    
    // Handle image fields separately
    foreach ($this->columns as $column) {
        if (strpos($column['Field'], 'img') !== false) {
            $controllerContent .= "            // Check if a new image is uploaded\n";
            $controllerContent .= "            if (isset(\$_FILES['{$column['Field']}']) && !empty(\$_FILES['{$column['Field']}']['name']) && !empty(\$_FILES['{$column['Field']}'])) {\n";
            $controllerContent .= "                \$file_name = \$_FILES['{$column['Field']}']['name'];\n";
            $controllerContent .= "                \$extension = strtolower(end(explode('.', \$file_name)));\n";
            $controllerContent .= "                \$allowed_types = ['jpg', 'jpeg', 'png', 'webp'];\n";
            $controllerContent .= "                if (!in_array(\$extension, \$allowed_types)) {\n";
            $controllerContent .= "                    \$msg = \"Please upload a valid image. Only JPG, JPEG, PNG, and WEBP images are allowed.\";\n";
            $controllerContent .= "                } else {\n";
            $controllerContent .= "                    \$maxSize = 600 * 1024; // 600KB in bytes\n";
            $controllerContent .= "                    if (\$_FILES['{$column['Field']}']['size'] > \$maxSize) {\n";
            $controllerContent .= "                        \$msg = \"Image file size exceeds the maximum limit of 600KB.\";\n";
            $controllerContent .= "                    } else {\n";
            $controllerContent .= "                        // Upload the file\n";
            $controllerContent .= "                        \$file_name = CommonFunctions::FileUploadOnDigitalOcean(DO_UPLOAD_SPACE, '{$column['Field']}', 'cokey/book_cover_img/', \$allowed_types);\n";
            $controllerContent .= "                    }\n";
            $controllerContent .= "                }\n";
            $controllerContent .= "            } else {\n";
            $controllerContent .= "                // Use existing image if no new image is uploaded\n";
            $controllerContent .= "                if (\$id > 0 && !empty(\$_POST['old_image'])) {\n";
            $controllerContent .= "                    \$file_name = \$old_image;\n";
            $controllerContent .= "                } else {\n";
            $controllerContent .= "                    \$msg = \"Cover image field is required.\";\n";
            $controllerContent .= "                }\n";
            $controllerContent .= "            }\n";
            $controllerContent .= "                \$user_id = \$_SESSION['userId'];\n";
            $controllerContent .= "                \$ip = CommonFunctions::get_ip();\n";
            $controllerContent .= "                \$error_msg = \"\";\n";
            $controllerContent .= "                \$file_name = \"\";\n";

            $controllerContent .= "            if (\$msg == \"\") {\n";
            $controllerContent .= "                if (\$id == \"0\" || (\$old_image == '' && \$id > 0)) {\n";
            $controllerContent .= "            if (isset(\$_FILES['{$column['Field']}']) && !empty(\$_FILES['{$column['Field']}']['name'])) {\n";
            $controllerContent .= "                        \$allowed_types = ['jpg', 'jpeg', 'png', 'webp'];\n";
            $controllerContent .= "                        \$directory_path = 'cokey/cover_img/';\n";
            $controllerContent .= "                        \$space_name = DO_UPLOAD_SPACE;\n";
            $controllerContent .= "                        \$file_name = CommonFunctions::FileUploadOnDigitalOcean(\$space_name, '{$column['Field']}', \$directory_path, \$allowed_types);\n";
            $controllerContent .= "                    } else {\n";
            $controllerContent .= "                        \$error_msg = 'image file field is required.';\n";
            $controllerContent .= "                    }\n";
            $controllerContent .= "                } else {\n";
            $controllerContent .= "                    if (\$old_image == '') {\n";
            $controllerContent .= "                        \$error_msg = 'image file field is required.';\n";
            $controllerContent .= "                    } else {\n";
            $controllerContent .= "                        \$file_name = \$old_image;\n";
            $controllerContent .= "                    }\n";
            $controllerContent .= "                }\n";

            $controllerContent .= "                if (\$error_msg != \"\") {\n";
            $controllerContent .= "                    CommonFunctions::FailMessage(\$error_msg);\n";
            $controllerContent .= "                }\n";
            
        }
    }
    

    // if (strpos($column['Field'], 'img') !== true) {
    //     $controllerContent .= "            if (\$msg == \"\") {\n";
    // }

    $controllerContent .= "                // Collect data for insertion or update\n";
    $controllerContent .= "                \$data = [\n";

foreach ($this->columns as $column) {
    if (!in_array($column['Field'], $commonFields)) {
        // Check if the column name contains 'img', 'image', 'photo', etc.
        if (strpos($column['Field'], 'img') !== false || 
            strpos($column['Field'], 'image') !== false || 
            strpos($column['Field'], 'photo') !== false) {
            
            // Assign $file_name to image-related fields
            $controllerContent .= "                    \"{$column['Field']}\" => \$file_name,\n";
        } else {
            // Assign the regular variable for other fields
            $controllerContent .= "                    \"{$column['Field']}\" => \${$column['Field']},\n";
        }
    }
}

$controllerContent .= "                ];\n\n";

    $controllerContent .= "                \$user_id = \$_SESSION['userId'];\n";
    $controllerContent .= "                \$ip = CommonFunctions::get_ip();\n";
    $controllerContent .= "                \$cur_date = CommonFunctions::cur_date();\n";
    
    $controllerContent .= "                if (\$id == \"0\") {\n";
    $controllerContent .= "                    \$data[\"created_by\"] = \$user_id;\n";
    $controllerContent .= "                    \$data[\"created_on\"] = \$cur_date;\n";
    $controllerContent .= "                } else {\n";
    $controllerContent .= "                    \$data[\"updated_by\"] = \$user_id;\n";
    $controllerContent .= "                    \$data[\"updated_on\"] = \$cur_date;\n";
    $controllerContent .= "                }\n\n";
    
    $controllerContent .= "                \$result = \$this->Save{$controllerName}Data(\$id, \$data);\n\n";
    $controllerContent .= "                if (\$result) {\n";
    $controllerContent .= "                    CommonFunctions::SuccessMessage(\"{$this->controllerName} Data Saved Successfully.\");\n";
    $controllerContent .= "                } else {\n";
    $controllerContent .= "                    CommonFunctions::ProcessingError();\n";
    $controllerContent .= "                }\n";
    // $controllerContent .= "            } else {\n";
    // $controllerContent .= "                CommonFunctions::FailMessage(\$msg);\n";
    // $controllerContent .= "            }\n";
    $controllerContent .= "        } else {\n";
    $controllerContent .= "            CommonFunctions::ParamsError();\n";
    $controllerContent .= "        }\n";
    $controllerContent .= "    }\n";
    $controllerContent .= " }\n";
    

    $controllerContent .= "    private function get". ucfirst($tableName) ."ById() {\n";
    $tab = "\t"; // Tab character

    $controllerContent .= $tab . "\$required_params = [\"id\"];\n"; // Tab before this line

    $controllerContent .= $tab . "if (CommonFunctions::CheckRequiredParams(\$required_params, 'post')) {\n"; // Tab and new line

    $controllerContent .= $tab . $tab . "\$id = \$_POST[\"id\"];\n"; // Double tab for indentation

    $controllerContent .= $tab . $tab . "\$row = \$this->get{$controllerName}DataById(\$id);\n"; // Double tab for indentation

    $controllerContent .= $tab . $tab . "\$message = (count(\$row) > 0) ? 'Data loaded successfully' : 'No Data Found';\n"; // Double tab for indentation

    $controllerContent .= $tab . $tab . "\$response = array('status' => true, 'message' => (string) \$message, 'data' => (array) \$row);\n"; // Double tab for indentation

    $controllerContent .= $tab . $tab . "echo json_encode(\$response);\n"; // Double tab for indentation

    $controllerContent .= $tab . $tab . "exit;\n"; // Double tab for indentation

    $controllerContent .= $tab . "} else {\n"; // Single tab

    $controllerContent .= $tab . $tab . "CommonFunctions::ParamsError();\n"; // Double tab for indentation

    $controllerContent .= $tab . "}\n"; // Single tab for the closing bracket


    $controllerContent .= "    }\n\n";

    $controllerContent .= "    private function delete" . ucfirst($tableName) . "() {\n";
    $controllerContent .= <<<PHP
    \$required_params = ['id'];

    if (CommonFunctions::CheckRequiredParams(\$required_params, 'post')) {

        \$id = \$_POST["id"];
        \$userId = \$_SESSION["userId"];

        \$row = \$this->get{$controllerName}DataById(\$id);

        if (!empty(\$row)) {

            \$data = [
                "is_deleted" => 1,
                "updated_by" => \$userId,
                "updated_on" => CommonFunctions::cur_date()
            ];

            \$result = \$this->Save{$controllerName}Data(\$id, \$data);

            if (\$result) {
                CommonFunctions::SuccessMessage('{$this->controllerName} deleted successfully.');
            } else {
                CommonFunctions::ProcessingError();
            }
        } else {
            CommonFunctions::ProcessingError();
        }
    } else {
        CommonFunctions::ParamsError();
    }
PHP;

    $controllerContent .= "\n" . $tab . "}\n";

    $controllerContent .= "}\n";

    $controllerContent .= "{$getcontrollerName}";


    $controllerFilePath = __DIR__ . "/controllers/{$controllerName}.php";
    file_put_contents($controllerFilePath, $controllerContent);
    echo "Controller file '{$controllerName}.php' has been created successfully!\n";
}

// public function getControllerName($column,$controllerName, $modelName, $viewDirName, $tableName) {

//     $getcontrollerName = "new $controllerName()";

//     print_r('<pre>');
//     print_r($getcontrollerName);
//     $controllerContent .= "}\n";
//     return $getcontrollerName;
// }


}





//echo $controllerGenerator->getControllerName();

?>