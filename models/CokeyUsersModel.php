<?php

require_once '/var/www/html/mobigram/Functions.php';

class CokeyUsersModel extends Functions {

    function __construct() {
        parent::__construct();
        $this->load_Model_by_path('CommonSqlFunctionsModel', '/var/www/html/mobigram/models');
    }

    public function saveCokeyUsersData($id, $data) {
        if ($id > 0) {
            $result = $this->CommonSqlFunctionsModel->where('id', $id)->update('cokey_users', $data);
        } else {
            $result = $this->CommonSqlFunctionsModel->insert('cokey_users', $data);
        }

      if ($result) {
          return true;
      } else {
          return false;
      }
    }

    public function CokeyUsersCount($search_keyword = '') {
        $and_condition = '';

        if ($search_keyword != '') {
            $column_array = ["id","first_name","address","last_name","age","date_of_birth","password","mobile_number","gender","created_on"];
            $and_condition = " and (" . CommonFunctions::createTableLikeColumnCondition($column_array, $search_keyword) . ") ";
        }

        $query = "SELECT count(id) as totalCount FROM cokey_users WHERE is_deleted = '0' $and_condition";
        $row = $this->CommonSqlFunctionsModel->query($query)->result_row();

        return $row['totalCount'];
    }

    public function CokeyUsersList($search_keyword = '', $start = '', $page_limit = '', $sortBy = '', $sortOrder = '') {
        $and_condition = '';

        if ($search_keyword != '') {
            $column_array = ["id","first_name","address","last_name","age","date_of_birth","password","mobile_number","gender","created_on"];
            $and_condition = " and (" . CommonFunctions::createTableLikeColumnCondition($column_array, $search_keyword) . ") ";
        }

        $sortBy = ($sortBy == '') ? 'id' : $sortBy;
        $sortOrder = ($sortOrder == '') ? 'DESC' : $sortOrder;

        $limit = '';
        if ($start != '' && $page_limit != '') {
            $limit = " LIMIT $start, $page_limit";
        }

        $table_column = 'id, first_name, address, last_name, age, date_of_birth, password, mobile_number, gender, created_on';

        $sql = "SELECT $table_column FROM cokey_users WHERE is_deleted = '0' $and_condition ORDER BY $sortBy $sortOrder $limit";
        $list = $this->CommonSqlFunctionsModel->query($sql)->result_array();

        return $list;
    }

    public function getCokeyUsersDataById($id) {
        $sql = "SELECT * FROM cokey_users WHERE is_deleted = '0' and id = '$id'";
        $row = $this->CommonSqlFunctionsModel->query($sql)->result_row();

        return $row;
    }
}
?>