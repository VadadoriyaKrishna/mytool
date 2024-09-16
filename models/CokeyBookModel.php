    <?php
    
    require_once '/var/www/html/mobigram/Functions.php';
    
    class CokeyBookModel extends Functions {
    
        function __construct() {
            parent::__construct();
            $this->load_Model_by_path('CommonSqlFunctionsModel', '/var/www/html/mobigram/models');
        }
    
        public function SaveCokeyBookData($id, $data) {
            if ($id > 0) {
                $result = $this->CommonSqlFunctionsModel->where('id', $id)->update('cokey_book', $data);
            } else {
                $result = $this->CommonSqlFunctionsModel->insert("cokey_book", $data);
            }
    
            return $result ? true : false;
        }
    
        public function CokeyBookCount($search_keyword = "") {
            $and_condition = '';
    
            if ($search_keyword != '') {
                $column_array = ["id","name","description","cover_img","price","pages","per_page_story_count","story_days_count","book_languages","created_on"];
                $and_condition = " and (" . CommonFunctions::createTableLikeColumnCondition($column_array, $search_keyword) . ") ";
            }
    
            $query = "SELECT count(id) as totalCount FROM cokey_book WHERE is_deleted = '0' $and_condition";
            $row = $this->CommonSqlFunctionsModel->query($query)->result_row();
    
            return $row["totalCount"];
        }
    
        public function CokeyBookList($search_keyword = '', $start = '', $page_limit = '', $sortBy = '', $sortOrder = '') {
           $and_condition = '';
            
           if ($search_keyword != '') {
                $column_array = ["id","name","description","cover_img","price","pages","per_page_story_count","story_days_count","book_languages","created_on"];
                $and_condition = " and (" . CommonFunctions::createTableLikeColumnCondition($column_array, $search_keyword) . ") ";
            }

        $sortBy = ($sortBy == '') ? " id " : $sortBy;
        $sortOrder = ($sortOrder == '') ? 'DESC' : $sortOrder;

        $limit = "";
        if ($start != '' && $page_limit != '') {
            $limit = " LIMIT $start, $page_limit";
        }

        $table_column = "id, name, description, cover_img, price, pages, per_page_story_count, story_days_count, book_languages, created_on";

        $sql = "SELECT $table_column FROM cokey_book WHERE is_deleted = '0' $and_condition ORDER BY $sortBy $sortOrder $limit";
        $list = $this->CommonSqlFunctionsModel->query($sql)->result_array();

    // Process the result for additional data manipulation (e.g., generating URLs)
    if (!empty($list)) {
    $column_array = ["id","name","description","cover_img","price","pages","per_page_story_count","story_days_count","book_languages","created_on"];
         foreach ($list as $index => $value) {
                foreach ($column_array as $columnName) {
                    // Only generate image-related code if the field name matches img, image, photo, or upload
                    if (in_array($columnName, ['cover_img', 'image', 'photo', 'upload'])) {
                        $coverImageField = $columnName;
                        if (isset($value[$coverImageField])) {
                            $cover_img_url = CommonFunctions::getDigitalOceanFileUrl(DO_UPLOAD_SPACE, $value[$coverImageField], "CokeyBook/" . $coverImageField . "/");
                            $list[$index]["{$coverImageField}_url"] = $cover_img_url;
                        }
                    }
                }
            }

        
        }
        return $list;

}
    
        public function getCokeyBookDataById($id) {
            $sql = "SELECT * FROM cokey_book WHERE is_deleted = '0' and id = '$id'";
            $row = $this->CommonSqlFunctionsModel->query($sql)->result_row();

            
             if (!empty($row)) {
             $column_array = ["id","name","description","cover_img","price","pages","per_page_story_count","story_days_count","book_languages","created_on"];
            foreach ($column_array as $columnName) {
                // Only generate image-related code if the field name matches img, image, photo, or upload
                if (in_array($columnName, ['cover_img', 'image', 'photo', 'upload'])) {
                    $coverImageField = $columnName;
                    if (isset($row[$coverImageField])) {
                        $row["{$coverImageField}_url"] = CommonFunctions::getDigitalOceanFileUrl(DO_UPLOAD_SPACE, $row[$coverImageField], "CokeyBook/" . $coverImageField . "/");
                    }
                }
            }
            
        }
            return $row;
        }
    }