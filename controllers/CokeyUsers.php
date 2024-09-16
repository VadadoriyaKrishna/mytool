<?php

require_once '/var/www/html/mobigram/models/CokeyUsersModel.php';

class CokeyUsers extends CokeyUsersModel {

    function __construct() {
        parent::__construct();
        header('Content-Type: application/json');

        if (isset($_POST['action']) && $_POST['action'] != '') {
            $action = $_POST['action'];

            if($action == "get_cokey_users_list") {
                self::getCokey_usersList();
            } elseif ($action == "add_cokey_users") {
                self::addCokey_users();
            } elseif ($action == "edit_cokey_users") {
                self::getCokey_usersById();
            } elseif ($action == "delete_cokey_users") {
                $this->deleteCokey_users();
            }
        }
    }

    private function getCokey_usersList() {
        $list = array();

        if (isset($_POST['search']['value']) && trim($_POST['search']['value']) != '') {
            $search_keyword = $_POST['search']['value'];
        }

        $sortBy = '';
        if ($_POST["order"][0]["column"] != "") {
            $sortBy = $_POST["order"][0]["column"];
        }

        $sortOrder = '';
        if ($_POST["order"][0]["dir"] != "") {
            $sortOrder = $_POST["order"][0]["dir"];
        }

        $start = $_POST['start'];
        $length = $_POST['length'];

        $totalRecords = $this->CokeyUsersCount($search_keyword);

        if ($totalRecords > 0) {
            $list = $this->CokeyUsersList($search_keyword, $start, $length, $sortBy, $sortOrder);
        }

        $message = (count($list) > 0) ? 'Data loaded successfully' : 'No Data Found';
        $response = array('status' => true,
            'draw' => (isset($_POST['draw'])) ? $_POST['draw'] : 0,
            'recordsTotal' => (int) $totalRecords,
            'recordsFiltered' => (int) $totalRecords,
            'data' => (array) $list,
            'message' => (string) $message);

        echo json_encode($response);
        exit;
    }

    private function addCokey_users() {
     $required_params = ["first_name", "address", "last_name", "age", "date_of_birth", "password", "mobile_number", "gender"];

        if (CommonFunctions::CheckRequiredParams($required_params, 'post')) {

            $first_name = $_POST['first_name'];
            $address = $_POST['address'];
            $last_name = $_POST['last_name'];
            $age = $_POST['age'];
            $date_of_birth = $_POST['date_of_birth'];
            $password = $_POST['password'];
            $mobile_number = $_POST['mobile_number'];
            $gender = $_POST['gender'];
            $id = $_POST['id'];
                // Collect data for insertion or update
                $data = [
                    "first_name" => $first_name,
                    "address" => $address,
                    "last_name" => $last_name,
                    "age" => $age,
                    "date_of_birth" => $date_of_birth,
                    "password" => $password,
                    "mobile_number" => $mobile_number,
                    "gender" => $gender,
                ];

                $user_id = $_SESSION['userId'];
                $ip = CommonFunctions::get_ip();
                $cur_date = CommonFunctions::cur_date();
                if ($id == "0") {
                    $data["created_by"] = $user_id;
                    $data["created_on"] = $cur_date;
                } else {
                    $data["updated_by"] = $user_id;
                    $data["updated_on"] = $cur_date;
                }

                $result = $this->SaveCokeyUsersData($id, $data);

                if ($result) {
                    CommonFunctions::SuccessMessage(" Data Saved Successfully.");
                } else {
                    CommonFunctions::ProcessingError();
                }
        } else {
            CommonFunctions::ParamsError();
        }
    }
    private function getCokey_usersById() {
	$required_params = ["id"];
	if (CommonFunctions::CheckRequiredParams($required_params, 'post')) {
		$id = $_POST["id"];
		$row = $this->getCokeyUsersDataById($id);
		$message = (count($row) > 0) ? 'Data loaded successfully' : 'No Data Found';
		$response = array('status' => true, 'message' => (string) $message, 'data' => (array) $row);
		echo json_encode($response);
		exit;
	} else {
		CommonFunctions::ParamsError();
	}
    }

    private function deleteCokey_users() {
    $required_params = ['id'];

    if (CommonFunctions::CheckRequiredParams($required_params, 'post')) {

        $id = $_POST["id"];
        $userId = $_SESSION["userId"];

        $row = $this->getCokeyUsersDataById($id);

        if (!empty($row)) {

            $data = [
                "is_deleted" => 1,
                "updated_by" => $userId,
                "updated_on" => CommonFunctions::cur_date()
            ];

            $result = $this->SaveCokeyUsersData($id, $data);

            if ($result) {
                CommonFunctions::SuccessMessage(' deleted successfully.');
            } else {
                CommonFunctions::ProcessingError();
            }
        } else {
            CommonFunctions::ProcessingError();
        }
    } else {
        CommonFunctions::ParamsError();
    }
	}
}
new CokeyUsers();