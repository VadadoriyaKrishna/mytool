<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);



require_once 'add_form.php';
require_once 'ControllerGenerator.php';
require_once 'modelGenerator.php';
require_once 'data_listing.php';



class FileGenerator
{
    private $hostname;
    private $username;
    private $password;
    private $database;
    private $tableName;
    private $controllerName;
    private $modelName;
    private $viewDirName;
    private $conn;

    public function __construct($hostname, $username, $password, $database, $tableName, $controllerName, $modelName, $viewDirName)
    {
        $this->hostname = $hostname;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
        $this->tableName = $tableName;
        $this->controllerName = $controllerName;
        $this->modelName = $modelName;
        $this->viewDirName = $viewDirName;
    }

    public function sidebar()
    {
        $viewDirPath = "views/{$this->viewDirName}";
        if (!is_dir($viewDirPath)) {
            mkdir($viewDirPath, 0777, true);
        }
        $sidebarTemplate = "";

        // Sidebar Navigation HTML
        $sidebarTemplate .= '<hr class="sidebar-divider" />' . "\n";
        $sidebarTemplate .= '<li class="nav-item">' . "\n";
        $sidebarTemplate .= '    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse' . $this->controllerName . '" aria-expanded="false" aria-controls="collapse' . $this->controllerName . '">' . "\n";
        $sidebarTemplate .= '        <i class="fas fa-envelope"></i>' . "\n";
        $sidebarTemplate .= '        <span>' . $this->controllerName . '</span>' . "\n";
        $sidebarTemplate .= '    </a>' . "\n";
        $sidebarTemplate .= '    <div id="collapse' . $this->controllerName . '" class="collapse" aria-labelledby="collapse' . $this->controllerName . '" data-parent="#accordionSidebar">' . "\n";
        $sidebarTemplate .= '        <div class="bg-white py-2 collapse-inner rounded">' . "\n";
        $sidebarTemplate .= '            <a class="collapse-item" onclick="loadData(\'<?php echo $routeFilePath; ?>?currentPage=20.26\', \'page-wrapper\', 0), header_filters_show_hide(\'\');">' . "\n";
        $sidebarTemplate .= '                <i class="fas fa-list"></i> List' . "\n";
        $sidebarTemplate .= '            </a>' . "\n";
        $sidebarTemplate .= '        </div>' . "\n";
        $sidebarTemplate .= '    </div>' . "\n";
        $sidebarTemplate .= '</li>' . "\n";

        $this->writeFile("{$viewDirPath}/{$this->viewDirName}_sidebar.php", $sidebarTemplate);
    }
    public function navigation()
{
    $viewDirPath = "views/{$this->viewDirName}";
    if (!is_dir($viewDirPath)) {
        mkdir($viewDirPath, 0777, true);
    }

    $subTab = isset($subTab) ? $subTab : 0;
    $defaultSubTab = "20.26.1"; // Default sub-tab value
    $btn_text = "Add {$this->controllerName}"; // Default button text or get dynamically
    $navigationTemplate = '';

    $navigationTemplate .= '
    <div class="container-fluid content-body-part pb-0"> 
        <!-- inner-wrapper -->
        <div id="inner-wrapper">
            <div class="rec-panel card-tab-right d-flex flex-column"> 
                <div class="header-tab-option">  <!--header-tab-option-->
                    <ul class="nav nav-tabs header-tab-menu">
                        <li>
                            <a href="javascript:void(0);" data-add_btn_text="' . $btn_text . '" class="add_btn_li <?php echo ($subTab == 0 || $subTab == "20.26.1") ? \'active\' : \'\'; ?>" onclick="loadData(\'<?php echo $routeFilePath; ?>?currentPage=' . $defaultSubTab . '\', \'ajax-cokey-tab\', 0);">

                                <i class="fas fa-list"></i>' . htmlspecialchars($this->controllerName, ENT_QUOTES, 'UTF-8') . ' List
                            </a>
                        </li>
                    </ul>
                    <span class="tab-text-small rx-rigth-btn "> 
                        <a href="javascript:void(0);" class="add_new_data">' . $btn_text . '</a> 
                    </span>
                </div>';

    // Add the tab content
    $navigationTemplate .= '<div class="tab-content right-inner-container" id="ajax-cokey-tab"> 
                <?php
                if ($subTab != 0) {
                    require_once($page[$subTab]);
                } else {
                    require_once($page["' . $defaultSubTab . '"]);
                }
                ?> 
            </div>';

    $navigationTemplate .= '
            </div>
        </div>
    </div>

    <script>
        $(document).on("click", ".add_btn_li", function () {
            $(".add_btn_li").removeClass("active");
            var btn_text = $(this).data("add_btn_text");
            $(".add_new_data").text(btn_text);
            $(this).addClass("active");
        });
    </script>';

    $this->writeFile("{$viewDirPath}/{$this->viewDirName}_navigation.php", $navigationTemplate);
}


    public function generateFiles()
    {
        $this->generateController();
        $this->generateModel();
        $this->generateViewDir();
        $this->generateForm(); // Call method to generate the form
        $this->dataListing();
        $this->navigation();
        $this->sidebar();
        echo "Files generated successfully!";
    }

    private function generateController()
    {
        $mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
        if ($mysqli->connect_error) {
            throw new Exception("Connection failed: " . $mysqli->connect_error);
        }

        $query = "SHOW COLUMNS FROM {$this->tableName}";
        $result = $mysqli->query($query);

        if (!$result) {
            throw new Exception("Query failed: " . $mysqli->error);
        }

        $columns = [];
        while ($row = $result->fetch_assoc()) {
            $columns[] = $row;
        }
        $mysqli->close();

        $controllerGenerator = new ControllerGenerator($columns, $this->tableName, $this->viewDirName, $this->modelName, $this->controllerName);
        $controllerGenerator->generateController($columns, $this->controllerName, $this->modelName, $this->viewDirName, $this->tableName);
       // $controllerGenerator->getControllerName($columns, $this->controllerName, $this->modelName, $this->viewDirName, $this->tableName);
    }


    private function generateModel()
    {
        $mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
        if ($mysqli->connect_error) {
            throw new Exception("Connection failed: " . $mysqli->connect_error);
        }

        $query = "SHOW COLUMNS FROM {$this->tableName}";
        $result = $mysqli->query($query);

        if (!$result) {
            throw new Exception("Query failed: " . $mysqli->error);
        }

        $columns = [];
        while ($row = $result->fetch_assoc()) {
            $columns[] = $row;
        }
        $mysqli->close();
        $modelGenerator = new modelGenerator($columns, $this->tableName, $this->viewDirName, $this->modelName, $this->controllerName);
        $modelGenerator->generateModel($columns, $this->controllerName, $this->modelName, $this->viewDirName, $this->tableName);
    }

    private function generateViewDir()
    {
        $viewDirPath = "views/{$this->viewDirName}";
        if (!is_dir($viewDirPath)) {
            mkdir($viewDirPath, 0777, true);
        }
        /*$listViewTemplate = "<?php include 'viewGenerator.php'; ?>\n";
        $addViewTemplate = "<?php include 'add_form.php'; ?>\n";

        // Create default view files
        $listViewTemplate = "<h1>List of {$this->controllerName}</h1>\n";
        $addViewTemplate = "<h1>Add New {$this->controllerName}</h1>\n";
        
        $this->writeFile("{$viewDirPath}/{$this->viewDirName}_list.php", $listViewTemplate);
        $this->writeFile("{$viewDirPath}/{$this->viewDirName}_add.php", $addViewTemplate);*/
    }

    private function generateForm()
    {

        $mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
        if ($mysqli->connect_error) {
            throw new Exception("Connection failed: " . $mysqli->connect_error);
        }

        $query = "SHOW COLUMNS FROM {$this->tableName}";

        $result = $mysqli->query($query);


        if (!$result) {
            throw new Exception("Query failed: " . $mysqli->error);
        }

        $columns = [];
        while ($row = $result->fetch_assoc()) {
            $columns[] = $row;
        }
        $mysqli->close();

        // print_r('<pre>');
        // print_r($columns);

        $formGenerator = new FormGenerator($columns, $this->viewDirName, $this->controllerName, $this->tableName);
        $formGenerator->generateView();
    }
    private function dataListing()
    {
        $mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
        if ($mysqli->connect_error) {
            throw new Exception("Connection failed: " . $mysqli->connect_error);
        }

        $query = "SHOW COLUMNS FROM {$this->tableName}";
        $result = $mysqli->query($query);

        if (!$result) {
            throw new Exception("Query failed: " . $mysqli->error);
        }

        $columns = [];
        while ($row = $result->fetch_assoc()) {
            $columns[] = $row;
        }
        $mysqli->close();

        $dataGenerator = new DynamicTableGenerator($columns, $this->tableName, $this->viewDirName, $this->modelName, $this->controllerName);
        $dataGenerator->generateDataTable($columns, $this->controllerName, $this->modelName, $this->viewDirName, $this->tableName);
    }




    private function writeFile($path, $content)
    {
        if (file_put_contents($path, $content) !== false) {
            echo "File created: $path\n";
        } else {
            echo "Failed to create file: $path\n";
        }
    }
}



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hostname = $_POST['hostname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $database = $_POST['database'];
    $tableName = $_POST['tableName'];
    $controllerName = $_POST['controllerName'];
    $modelName = $_POST['modelName'];
    $viewDirName = $_POST['viewDirName'];

    $generator = new FileGenerator($hostname, $username, $password, $database, $tableName, $controllerName, $modelName, $viewDirName);
    $generator->generateFiles();
}
