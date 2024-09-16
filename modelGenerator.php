<?php 

class modelGenerator{
    private $columns;
    private $tableName;
    private $controllerName;
    private $modelName;
    private $viewDirName;

    public function __construct($columns, $tableName, $viewDirName, $modelName, $controllerName){
        $this->columns = $columns;
        $this->tableName = $tableName;
        $this->viewDirName = $viewDirName;
        $this->controllerName = $controllerName;
        $this->modelName = $modelName;

    }
    

    function generateModel($column,$controllerName, $modelName, $viewDirName, $tableName) 
    {
        $columnsArray = $column; // This will be an array of column names like ['name', 'cover_img', 'created_on', ...]
        $fieldNames = [];
foreach ($columnsArray as $column) {
    if (!in_array($column['Field'], ['created_by','updated_by','updated_on','ip','status', 'is_deleted'])) { // Exclude common fields
        $fieldNames[] = $column['Field'];
    }
}

// Now $fieldNames will contain only the field names you need
// print_r('<pre>');
// print_r($fieldNames);
// print_r("hello");
$columnsArray = $fieldNames;
//$columnArrayListTableColumn = '"' . implode('", "', $columnsArray) . '"';
$columnArrayListTableColumn = implode(", ", $columnsArray);


        // Debugging output
        // print_r($columnsArray);
    
        // Use the column array in your model template
        $columnArrayList = json_encode($columnsArray);
    
        // print_r($columnArrayList);
        
    $modelTemplate = <<<EOD
    <?php
    
    require_once '/var/www/html/mobigram/Functions.php';
    
    class {$modelName} extends Functions {
    
        function __construct() {
            parent::__construct();
            \$this->load_Model_by_path('CommonSqlFunctionsModel', '/var/www/html/mobigram/models');
        }
    
        public function Save{$controllerName}Data(\$id, \$data) {
            if (\$id > 0) {
                \$result = \$this->CommonSqlFunctionsModel->where('id', \$id)->update('{$tableName}', \$data);
            } else {
                \$result = \$this->CommonSqlFunctionsModel->insert("{$tableName}", \$data);
            }
    
            return \$result ? true : false;
        }
    
        public function {$controllerName}Count(\$search_keyword = "") {
            \$and_condition = '';
    
            if (\$search_keyword != '') {
                \$column_array = {$columnArrayList};
                \$and_condition = " and (" . CommonFunctions::createTableLikeColumnCondition(\$column_array, \$search_keyword) . ") ";
            }
    
            \$query = "SELECT count(id) as totalCount FROM {$tableName} WHERE is_deleted = '0' \$and_condition";
            \$row = \$this->CommonSqlFunctionsModel->query(\$query)->result_row();
    
            return \$row["totalCount"];
        }
    
        public function {$controllerName}List(\$search_keyword = '', \$start = '', \$page_limit = '', \$sortBy = '', \$sortOrder = '') {
           \$and_condition = '';
            
           if (\$search_keyword != '') {
                \$column_array = {$columnArrayList};
                \$and_condition = " and (" . CommonFunctions::createTableLikeColumnCondition(\$column_array, \$search_keyword) . ") ";
            }

        \$sortBy = (\$sortBy == '') ? " id " : \$sortBy;
        \$sortOrder = (\$sortOrder == '') ? 'DESC' : \$sortOrder;

        \$limit = "";
        if (\$start != '' && \$page_limit != '') {
            \$limit = " LIMIT \$start, \$page_limit";
        }

        \$table_column = "{$columnArrayListTableColumn}";

        \$sql = "SELECT \$table_column FROM {$tableName} WHERE is_deleted = '0' \$and_condition ORDER BY \$sortBy \$sortOrder \$limit";
        \$list = \$this->CommonSqlFunctionsModel->query(\$sql)->result_array();

    // Process the result for additional data manipulation (e.g., generating URLs)
    if (!empty(\$list)) {
    \$column_array = {$columnArrayList};
         foreach (\$list as \$index => \$value) {
                foreach (\$column_array as \$columnName) {
                    // Only generate image-related code if the field name matches img, image, photo, or upload
                    if (in_array(\$columnName, ['cover_img', 'image', 'photo', 'upload'])) {
                        \$coverImageField = \$columnName;
                        if (isset(\$value[\$coverImageField])) {
                            \$cover_img_url = CommonFunctions::getDigitalOceanFileUrl(DO_UPLOAD_SPACE, \$value[\$coverImageField], "{$controllerName}/" . \$coverImageField . "/");
                            \$list[\$index]["{\$coverImageField}_url"] = \$cover_img_url;
                        }
                    }
                }
            }

        
        }
        return \$list;

}
    
        public function get{$controllerName}DataById(\$id) {
            \$sql = "SELECT * FROM {$tableName} WHERE is_deleted = '0' and id = '\$id'";
            \$row = \$this->CommonSqlFunctionsModel->query(\$sql)->result_row();

            
             if (!empty(\$row)) {
             \$column_array = {$columnArrayList};
            foreach (\$column_array as \$columnName) {
                // Only generate image-related code if the field name matches img, image, photo, or upload
                if (in_array(\$columnName, ['cover_img', 'image', 'photo', 'upload'])) {
                    \$coverImageField = \$columnName;
                    if (isset(\$row[\$coverImageField])) {
                        \$row["{\$coverImageField}_url"] = CommonFunctions::getDigitalOceanFileUrl(DO_UPLOAD_SPACE, \$row[\$coverImageField], "{$controllerName}/" . \$coverImageField . "/");
                    }
                }
            }
            
        }
            return \$row;
        }
    }
EOD;

    $modelFilePath = __DIR__ . "/models/{$modelName}.php";
    file_put_contents($modelFilePath, $modelTemplate);
    echo "Model file '{$modelName}.php' has been created successfully!\n";

    }
}
?>