<?php 

class ModelGenerator {
    private $columns;
    private $tableName;
    private $controllerName;
    private $modelName;
    private $viewDirName;

    public function __construct($columns, $tableName, $viewDirName, $modelName, $controllerName) {
        $this->columns = $columns;
        $this->tableName = $tableName;
        $this->viewDirName = $viewDirName;
        $this->controllerName = $controllerName;
        $this->modelName = $modelName;
    }

    public function generateModel() {
        $fieldNames = [];
        $imageColumns = []; // To hold column names related to images

        foreach ($this->columns as $column) {
            if (!in_array($column['Field'], ['created_by', 'updated_by', 'updated_on', 'ip', 'status', 'is_deleted'])) {
                $fieldNames[] = $column['Field'];
                if (preg_match('/img|image|photo|upload/', $column['Field'])) {
                    $imageColumns[] = $column['Field'];
                }
            }
        }
        print_r('<pre>');
        print_r($this->columns);
        print_r('<pre>');
        $columnArrayList = json_encode($fieldNames);
        $columnArrayListTableColumn = implode(", ", $fieldNames);

        $modelContent = "<?php\n\n";
        $modelContent .= "require_once '/var/www/html/mobigram/Functions.php';\n\n";
        $modelContent .= "class {$this->modelName} extends Functions {\n\n";
        $modelContent .= "    function __construct() {\n";
        $modelContent .= "        parent::__construct();\n";
        $modelContent .= "        \$this->load_Model_by_path('CommonSqlFunctionsModel', '/var/www/html/mobigram/models');\n";
        $modelContent .= "    }\n\n";

        // save function
        $modelContent .= "    public function save{$this->controllerName}Data(\$id, \$data) {\n";
        $modelContent .= "        if (\$id > 0) {\n";
        $modelContent .= "            \$result = \$this->CommonSqlFunctionsModel->where('id', \$id)->update('{$this->tableName}', \$data);\n";
        $modelContent .= "        } else {\n";
        $modelContent .= "            \$result = \$this->CommonSqlFunctionsModel->insert('{$this->tableName}', \$data);\n";
        $modelContent .= "        }\n\n";
        //$modelContent .= "        return \$result ? true : false;\n";
        $modelContent .= "      if (\$result) {\n";
        $modelContent .= "          return true;\n";
        $modelContent .= "      } else {\n";
        $modelContent .= "          return false;\n";
        $modelContent .= "      }\n";
        $modelContent .= "    }\n\n";

        // count function
        $modelContent .= "    public function {$this->controllerName}Count(\$search_keyword = '') {\n";
        $modelContent .= "        \$and_condition = '';\n\n";
        $modelContent .= "        if (\$search_keyword != '') {\n";
        $modelContent .= "            \$column_array = {$columnArrayList};\n";
        $modelContent .= "            \$and_condition = \" and (\" . CommonFunctions::createTableLikeColumnCondition(\$column_array, \$search_keyword) . \") \";\n";
        $modelContent .= "        }\n\n";
        $modelContent .= "        \$query = \"SELECT count(id) as totalCount FROM {$this->tableName} WHERE is_deleted = '0' \$and_condition\";\n";
        $modelContent .= "        \$row = \$this->CommonSqlFunctionsModel->query(\$query)->result_row();\n\n";
        $modelContent .= "        return \$row['totalCount'];\n";
        $modelContent .= "    }\n\n";

        // list function
        $modelContent .= "    public function {$this->controllerName}List(\$search_keyword = '', \$start = '', \$page_limit = '', \$sortBy = '', \$sortOrder = '') {\n";
        $modelContent .= "        \$and_condition = '';\n\n";
        $modelContent .= "        if (\$search_keyword != '') {\n";
        $modelContent .= "            \$column_array = {$columnArrayList};\n";
        $modelContent .= "            \$and_condition = \" and (\" . CommonFunctions::createTableLikeColumnCondition(\$column_array, \$search_keyword) . \") \";\n";
        $modelContent .= "        }\n\n";
        $modelContent .= "        \$sortBy = (\$sortBy == '') ? 'id' : \$sortBy;\n";
        $modelContent .= "        \$sortOrder = (\$sortOrder == '') ? 'DESC' : \$sortOrder;\n\n";
        $modelContent .= "        \$limit = '';\n";
        $modelContent .= "        if (\$start != '' && \$page_limit != '') {\n";
        $modelContent .= "            \$limit = \" LIMIT \$start, \$page_limit\";\n";
        $modelContent .= "        }\n\n";
        $modelContent .= "        \$table_column = '{$columnArrayListTableColumn}';\n\n";
        $modelContent .= "        \$sql = \"SELECT \$table_column FROM {$this->tableName} WHERE is_deleted = '0' \$and_condition ORDER BY \$sortBy \$sortOrder \$limit\";\n";
        $modelContent .= "        \$list = \$this->CommonSqlFunctionsModel->query(\$sql)->result_array();\n\n";
       
        if (!empty($imageColumns)) {
            $modelContent .= "        if (!empty(\$list)) {\n";
            $modelContent .= "            foreach (\$list as \$index => \$value) {\n";
            foreach ($imageColumns as $imageColumn) {
                $modelContent .= "                if (isset(\$value['$imageColumn'])) {\n";
                $modelContent .= "                    \$image_url = CommonFunctions::getDigitalOceanFileUrl(DO_UPLOAD_SPACE, \$value['$imageColumn'], '{$this->controllerName}/{$imageColumn}/');\n";
                $modelContent .= "                    \$list[\$index]['{$imageColumn}_url'] = \$image_url;\n";
                $modelContent .= "                }\n";
            }
            $modelContent .= "            }\n";
            $modelContent .= "        }\n";
        }
        $modelContent .= "        return \$list;\n";
        $modelContent .= "    }\n\n";

        // get data by ID function
        $modelContent .= "    public function get{$this->controllerName}DataById(\$id) {\n";
        $modelContent .= "        \$sql = \"SELECT * FROM {$this->tableName} WHERE is_deleted = '0' and id = '\$id'\";\n";
        $modelContent .= "        \$row = \$this->CommonSqlFunctionsModel->query(\$sql)->result_row();\n\n";
       
       
        if (!empty($imageColumns)) {
            foreach ($imageColumns as $imageColumn) {
                $modelContent .= "            if (!empty(\$row)) {\n";
                $modelContent .= "                \$row['{$imageColumn}_url'] = CommonFunctions::getDigitalOceanFileUrl(DO_UPLOAD_SPACE, \$row['$imageColumn'], '{$this->controllerName}/{$imageColumn}/');\n";
                //$modelContent .= "                \$row['{$imageColumn}_url'] = \$image_url;\n";
                $modelContent .= "            }\n";
            }
        }

        $modelContent .= "        return \$row;\n";
        $modelContent .= "    }\n";
        $modelContent .= "}\n";
        $modelContent .= "?>";

        // Write the content to a file
        $modelFilePath = __DIR__ . "/models/{$this->modelName}.php";
        file_put_contents($modelFilePath, $modelContent);
        echo "Model file '{$this->modelName}.php' has been created successfully!\n";
    }
}
?>
