<?php

require_once '/var/www/html/mobigram/models/CokeyBook.php';

class Array extends CokeyBook {

    function __construct() {
        parent::__construct();
        header('Content-Type: application/json');

        if (isset($_POST['action']) && $_POST['action'] != '') {
            $action = $_POST['action'];

            if($action == "get_cokey_book_list") {
                self::getCokey_bookList();
            } elseif ($action == "add_cokey_book") {
                self::addCokey_book();
            } elseif ($action == "edit_cokey_book") {
                self::getCokey_bookById();
            } elseif ($action == "delete_cokey_book") {
                $this->deleteCokey_book();
            }
        }
    }

    private function getCokey_bookList() {
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

        $totalRecords = $this->CokeyBook->count($search_keyword);

        if ($totalRecords > 0) {
            $list = $this->CokeyBook->list($search_keyword, $start, $length, $sortBy, $sortOrder);
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

    private function addCokey_book() {
        $required_params = [];

        if (CommonFunctions::CheckRequiredParams($required_params, 'post')) {


            $msg = "";

            if ($msg == "") {
                // Collect data for insertion or update
                $data = [
                ];

                $user_id = $_SESSION['userId'];
                $ip = CommonFunctions::get_ip();
                $cur_date = CommonFunctions::cur_date();
                if ($cokey_book_id == "0") {
                    $data["created_by"] = $user_id;
                    $data["created_on"] = $cur_date;
                } else {
                    $data["updated_by"] = $user_id;
                    $data["updated_on"] = $cur_date;
                }

                $result = $this->SaveCokeyBookData($cokey_book_id, $data);

                if ($result) {
                    CommonFunctions::SuccessMessage("Cokey Book Data Saved Successfully.");
                } else {
                    CommonFunctions::ProcessingError();
                }
            } else {
                CommonFunctions::FailMessage($msg);
            }
        } else {
            CommonFunctions::ParamsError();
        }
    }
    private function getCokey_bookById() {
	$required_params = ["id"];
	if (CommonFunctions::CheckRequiredParams($required_params, 'post')) {
		$id = $_POST["id"];
		$row = $this->getCokeyBookDataById($id);
		$message = (count($row) > 0) ? 'Data loaded successfully' : 'No Data Found';
		$response = array('status' => true, 'message' => (string) $message, 'data' => (array) $row);
		echo json_encode($response);
		exit;
	} else {
		CommonFunctions::ParamsError();
	}
    }

    private function deleteCokey_book() {
    $required_params = ['id'];

    if (CommonFunctions::CheckRequiredParams($required_params, 'post')) {

        $id = $_POST["id"];
        $userId = $_SESSION["userId"];

        $row = $this->getCokeyBookDataById($id);

        if (!empty($row)) {

            $data = [
                "is_deleted" => 1,
                "updated_by" => $userId,
                "updated_on" => CommonFunctions::cur_date()
            ];

            $result = $this->SaveCokeyBookData($id, $data);

            if ($result) {
                CommonFunctions::SuccessMessage('Cokey book deleted successfully.');
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
