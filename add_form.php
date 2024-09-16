<?php

class FormGenerator
{
    private $columns;
    private $viewDirName;
    private $controllerName;
    private $tableName;

    public function __construct($columns, $viewDirName, $controllerName,$tableName)
    {
        $this->columns = $columns;
        $this->viewDirName = $viewDirName;
        $this->controllerName = $controllerName;
        $this->tableName = $tableName;
    }
    

    private function mapColumnTypeToField($columnType,$columnName)
    {
        $columnType = strtolower($columnType);
        $columnName = strtolower($columnName);
        print_r($columnName);

        // Handle specific field names
        if (in_array($columnName, ['profile', 'photo', 'image','cover_img'])) {
            return 'file';
        } elseif (in_array($columnName, ['password', 'confirm_password'])) {
            return 'password';
        }

        // Handle generic column types
        if (strpos($columnType, 'int') !== false) {
            return 'text';
        } elseif (strpos($columnType, 'varchar') !== false || strpos($columnType, 'char') !== false) {
            return 'text';
        } elseif (strpos($columnType, 'text') !== false) {
            return 'textarea';
        } elseif (strpos($columnType, 'tinyint') !== false) {
            return 'text';
        } elseif (strpos($columnType, 'datetime') !== false || strpos($columnType, 'timestamp') !== false) {
            return 'datetime-local';
        } elseif (strpos($columnType, 'enum') !== false) {
            return 'radio';
        } elseif (strpos($columnType, 'double') !== false || strpos($columnType, 'float') !== false || strpos($columnType, 'decimal') !== false) {
            return 'text';
        } else {
            return 'text';
        }
    }

    private function generateFormElements()
    {
        $formElements = "";
        $rowOpen = false; // To track if a row div is open
        $columnCount = 0; // To count the number of columns added to the current row

      
        $commonFields = ['id','status', 'created_on', 'created_by', 'updated_by', 'updated_on', 'is_deleted','ip'];

         foreach ($this->columns as $column) {
             $fieldType = $this->mapColumnTypeToField($column['Type'],$column['Field']);
             $fieldName = htmlspecialchars($column['Field'], ENT_QUOTES, 'UTF-8');
             $label = ucfirst(str_replace('_', ' ', $fieldName));
                if (!$rowOpen) {
                    $formElements .= "    <div class='form-inner-group row'>\n";
                    $rowOpen = true;
                }
    

             if (in_array($fieldName, $commonFields)) {
                 continue;
             }
     
             if ($fieldType == 'textarea') {
                 $formElements .= "    <div class='col-sm-3'>\n";
                 $formElements .= "        <label class='field-title' for='{$fieldName}'>{$label}<sub>*</sub>:</label>\n";
                 $formElements .= "        <textarea id='{$fieldName}' name='{$fieldName}' class='form-control'></textarea>\n";
                 $formElements .= "    </div>\n";
             } elseif ($fieldType == 'radio') {
                 $enumOptions = str_replace(["enum(", ")", "'"], "", $column['Type']);
                 $enumValues = explode(",", $enumOptions);
                 $formElements .= "    <div class='col-sm-3'>\n";
                 $formElements .= "        <label class='field-title'>{$label}<sub>*</sub>:</label>\n";
                 foreach ($enumValues as $value) {
                     $formElements .= "        <div class='form-check'>\n";
                     $formElements .= "            <input type='radio' id='{$fieldName}_{$value}' name='{$fieldName}' value='" . ucfirst($value) . "' class='form-check-input'>\n";
                     $formElements .= "            <label for='{$fieldName}_{$value}' class='form-check-label'>{$value}</label>\n";
                     $formElements .= "        </div>\n";
                 }
                 $formElements .= "    </div>\n";
             } else {
                 $formElements .= "    <div class='col-sm-3'>\n";
                 $formElements .= "        <label class='field-title' for='{$fieldName}'>{$label}<sub>*</sub>:</label>\n";
                 $formElements .= "        <input type='{$fieldType}' id='{$fieldName}' name='{$fieldName}' class='form-control'>\n";
                 $formElements .= "    </div>\n";
             }
             if (in_array($fieldName, ['cover_img', 'profile', 'photo', 'image']))
             {

                $formElements .= "        <input type='hidden' id='old_image' name='old_image' value='' />\n";


                $formElements .= "        <img  class='image_preview' src='' width='200' height='200' style=' display: none;'/>\n";

             }
            $columnCount++; // Increment the column count

            // Check if we need to close the row after 4 columns
            if ($columnCount % 4 == 0) { 
                $formElements .= "    </div>\n"; // Close the row
                $rowOpen = false;
                $columnCount = 0; // Reset column count for the next row
            }
         }
   
        // Close any open row at the end
            if ($rowOpen) {
                $formElements .= "    </div>\n";
            }

        return $formElements;
    }

    public function generateForm()
    {
        $formElements = $this->generateFormElements();
        return $formElements;
    }

    public function generateView()
    {
        $viewDirPath = __DIR__ . "/views/{$this->viewDirName}";
        if (!file_exists($viewDirPath)) {
            mkdir($viewDirPath, 0777, true);
        }



        $formElements = $this->generateFormElements();
        $columnNames = json_encode(array_column($this->columns, 'Field'));

        $formResetFunction = "";
        $checkRequiredValuesFunction = "";

        

        foreach ($this->columns as $column) {
            $commonFields = [  'created_on', 'created_by', 'updated_by', 'updated_on', 'is_deleted','ip'];
            $fieldName = htmlspecialchars($column['Field'], ENT_QUOTES, 'UTF-8');
            

            if (in_array($fieldName, $commonFields)) {
                continue;
            }
            if (in_array($fieldName, ['img', 'profile', 'photo', 'image']))
            {
            $checkRequiredValuesFunction .= "    if ($('#{$fieldName}').val() != '') {\n";
            $checkRequiredValuesFunction .= "        var fileName = $('#{$fieldName}').val();\n";
            $checkRequiredValuesFunction .= "        var extension = fileName.substring(fileName.lastIndexOf('.') + 1).toLowerCase();\n";
            $checkRequiredValuesFunction .= "        var allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];\n";
            $checkRequiredValuesFunction .= "        var isValidExtension = allowedExtensions.includes(extension);\n";
            $checkRequiredValuesFunction .= "        if (!isValidExtension) {\n";
            $checkRequiredValuesFunction .= "            $('#{$fieldName}').notify('Only allowed file types are JPG, JPEG, PNG, and WEBP.', {position: 'bottom'});\n";
            $checkRequiredValuesFunction .= "            is_valid = false;\n";
            $checkRequiredValuesFunction .= "            countOptionError++;\n";
            $checkRequiredValuesFunction .= "        } else {\n";
            $checkRequiredValuesFunction .= "            var fileSize = $('#{$fieldName}').get(0).files[0].size;\n";
            $checkRequiredValuesFunction .= "            var maxSize = 600 * 1024; // 600KB\n";
            $checkRequiredValuesFunction .= "            if (fileSize > maxSize) {\n";
            $checkRequiredValuesFunction .= "                $('#{$fieldName}').notify('File size exceeds the maximum limit of 600KB.', {position: 'bottom'});\n";
            $checkRequiredValuesFunction .= "                is_valid = false;\n";
            $checkRequiredValuesFunction .= "                countOptionError++;\n";
            $checkRequiredValuesFunction .= "            }\n";
            $checkRequiredValuesFunction .= "        }\n";
            $checkRequiredValuesFunction .= "    }\n";
        } else {
            // General validation for other inputs
            $label = ucfirst(str_replace('_', ' ', $fieldName));
            $checkRequiredValuesFunction .= "    if ($('#{$fieldName}').val() == '') {\n";
            $checkRequiredValuesFunction .= "        is_valid = false;\n";
            $checkRequiredValuesFunction .= "        countOptionError++;\n";
            $checkRequiredValuesFunction .= "        $('#{$fieldName}').notify('Please enter {$label}', {position: 'bottom'});\n";
            $checkRequiredValuesFunction .= "    }\n";
        }
        // print_r("<pre>");
        // print_r("$fieldName");
           
        if ($fieldName == 'id') {
            $formResetFunction .= "    $('#{$fieldName}').val('0');\n";
        } else {
            $formResetFunction .= "    $('#{$fieldName}').val('');\n";


        }
            // $label = ucfirst(str_replace('_', ' ', $fieldName));
            // $formResetFunction .= "    $('#{$fieldName}').val('');\n";
            // $checkRequiredValuesFunction .= "    if ($('#{$fieldName}').val() == '') {\n";
            // $checkRequiredValuesFunction .= "        is_valid = false;\n";
            // $checkRequiredValuesFunction .= "        countOptionError++;\n";
            // $checkRequiredValuesFunction .= "        $('#{$fieldName}').notify('Please enter {$label}', {position: 'bottom'});\n";
            // $checkRequiredValuesFunction .= "    }\n";

        $formDataLoadFunction = "";
        foreach ($this->columns as $column) {
            $commonFields = [ 'status', 'created_on', 'created_by', 'updated_by', 'updated_on', 'is_deleted', 'ip'];
            $fieldName = htmlspecialchars($column['Field'], ENT_QUOTES, 'UTF-8');
        
            if (in_array($fieldName, $commonFields)) {
                continue;
            }
        
            $label = ucfirst(str_replace('_', ' ', $fieldName));
        
            // Handle special fields like images
           
        }
        
        }
        foreach ($this->columns as $column) {
        $fieldName = htmlspecialchars($column['Field'], ENT_QUOTES, 'UTF-8');
        $commonFields = [ 'status', 'created_on', 'created_by', 'updated_by', 'updated_on', 'is_deleted', 'ip'];
        
        if (in_array($fieldName, $commonFields)) {
            continue;
        }
       
         if (strpos($fieldName, 'img') !== false || 
            strpos($fieldName, 'image') !== false || 
            strpos($fieldName, 'photo') !== false) {
                $formResetFunction .= "    $('.image_preview').attr('src', '');\n";
                $formResetFunction .= "    $('#old_image').val('');\n";

                $formResetFunction .= "    $('.image_preview').hide();\n";
            
                $formDataLoadFunction .= "    $('#{$fieldName}').val('');\n";
                $formDataLoadFunction .= "    $('.image_preview').attr('src', data.{$fieldName}_url);\n";
                $formDataLoadFunction .= "    $('#old_image').val(data.{$fieldName});\n";
                $formDataLoadFunction .= "    $('.image_preview').show();\n";
 
            } else {
                // General fields
                $formDataLoadFunction .= "    $('#{$fieldName}').val(data.{$fieldName});\n";
                
                
                
            }}

        $formResetFunction .= "    $('#status').val('0').trigger('change');\n";
        $formResetFunction .= "    $('.status_div').hide();\n";

        $formDataLoadFunction .= "    $('#status').val('0').trigger('change');\n";
        $formDataLoadFunction .= "    $('.status_div').show();\n";



        $imagechagefunction = "";
        foreach ($this->columns as $column) {
            $commonFields = [ 'status', 'created_on', 'created_by', 'updated_by', 'updated_on', 'is_deleted', 'ip'];
            $fieldName = htmlspecialchars($column['Field'], ENT_QUOTES, 'UTF-8');
        
            if (in_array($fieldName, $commonFields)) {
                continue;
            }
        
            // Handle special fields like images
            if (in_array($fieldName, ['cover_img', 'profile', 'photo', 'image'])) {
                

                $imagechagefunction   .= "  $('#{$fieldName}').change(function() { \n";
                $imagechagefunction   .= "      readURL(this); })\n";
            } 
        }


        $viewTemplate = <<<HTML



    <div class="form-inner pt-4" id="{$this->controllerName}_form_div" style="display: none;">
        <form method="post" action="" id="{$this->controllerName}_form" enctype="multipart/form-data">
            <input type="hidden" id="id" name="id" value="0" />
            
           
            {$formElements}
            <div class="form-inner-group row">
            <div class="col-md-3 status_div"  style=" display: none;">
                <label class="field-title">Status<sub>*</sub></label>
                <select name="status" id="status" class="selectpicker form-control">
                    <option value="0">Inactive</option>
                    <option value="1">Active</option>
                </select> 
            </div>
            <!--div class="col-sm-3" >
                <img  class="image_preview" src="" width='200' height="200" style=" display: none;"/>
            </div-->
            </div>

            <div class="form-inner-group row ">			
                <div class="col-md-5" ></div>
                <div class="col-md-4" >
                    <button type="button" class="btn btn-light red" onclick="form_reset();" >Clear</button>
                    <button type="submit" class="btn btn-light ladda-button blue"  id="submit-btn" data-style="expand-right" data-size="l">
                        <i class="fa fa-save"></i> Save 
                    </button>
                </div>
            </div>
        </form>
    </div>
<script>
     table = '';


    $('.selectpicker').selectpicker();

   function form_reset() {
        {$formResetFunction}
    }
    function form_data_load(data)
    {
        {$formDataLoadFunction}
    }
    $(document).off('click', '.add_new_data').on('click', '.add_new_data', function (e) {
        e.preventDefault();
        if ($(this).hasClass('open_form')) {
            $('#{$this->controllerName}_form_div').css('display', 'none');
            $(this).removeClass('open_form');
            $(this).text('Add {$this->controllerName}');
            form_reset();
        } else {
            $('#{$this->controllerName}_form_div').css('display', 'block');
            $(this).addClass('open_form');
            $(this).text('Hide {$this->controllerName}');
            form_reset();
        }
    });
    {$imagechagefunction}

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('.image_preview').attr('src', e.target.result).css('display', 'block');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function CheckRequiredValues() {
        var is_valid = true;
        var countOptionError = 0;

        {$checkRequiredValuesFunction}

        return is_valid;
    }


  
        $('#{$this->controllerName}_form').submit(function(e) {

        var l = Ladda.create($('#submit-btn')[0]);
        l.start();

        //SetContentPublishApp();
            if (!CheckRequiredValues()) {
                //event.preventDefault();
                l.stop();



                return false;
            }
            e.preventDefault();
        var formData = new FormData(this);

        formData.append("action", "add_{$this->viewDirName}");

        $.ajax({
            type: 'POST',
            url: 'fluvina_index.php?broughtBy=$this->controllerName',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                l.stop();
                if (response.status == false) {
                    if (response.msg != null && response.msg != '') {
                        notify('Error', response.msg, 'error');
                    } else {
                        notify('Error', 'Please enter valid data.', 'error');
                    }
                } else {
                    notify('Successfully', '$this->controllerName data updated successfully.', 'success');

                    $(".add_new_data").trigger('click');
                    table.ajax.reload();
                }
            }
        });
        return false;
            
        });

        
      

</script>

HTML;

        $viewFilePath = "{$viewDirPath}/add_{$this->viewDirName}.php";
        file_put_contents($viewFilePath, $viewTemplate);
        echo "<br>Add file 'add_{$this->viewDirName}.php' has been created in '{$this->viewDirName}' directory successfully!\n";
    }
}
