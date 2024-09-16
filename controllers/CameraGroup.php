<?php

require_once '/var/www/html/mobigram/models/CameraGroupModel.php';

class CameraGroup extends CameraGroupModel {

    function __construct() {
        parent::__construct();
        header('Content-Type: application/json');

        if (isset($_POST['action']) && $_POST['action'] != '') {
            $action = $_POST['action'];

            if($action == "get_en_camera_group_list") {
                self::getEn_camera_groupList();
            } elseif ($action == "add_en_camera_group") {
                self::addEn_camera_group();
            } elseif ($action == "edit_en_camera_group") {
                self::getEn_camera_groupById();
            } elseif ($action == "delete_en_camera_group") {
                $this->deleteEn_camera_group();
            }
        }
    }

    private function getEn_camera_groupList() {
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

        $totalRecords = $this->CameraGroupModel->count($search_keyword);

        if ($totalRecords > 0) {
            $list = $this->CameraGroupModel->list($search_keyword, $start, $length, $sortBy, $sortOrder);
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

    private function addEn_camera_group() {
     $required_params = ["nvr_camera_json", "name", "deleted_by", "deleted_on"];

        if (CommonFunctions::CheckRequiredParams($required_params, 'post')) {

            $id = $_POST['id'];
            $nvr_camera_json = $_POST['nvr_camera_json'];
            $name = $_POST['name'];
            $status = $_POST['status'];
            $created_by = $_POST['created_by'];
            $created_on = $_POST['created_on'];
            $updated_by = $_POST['updated_by'];
            $updated_on = $_POST['updated_on'];
            $deleted_by = $_POST['deleted_by'];
            $deleted_on = $_POST['deleted_on'];
            $is_deleted = $_POST['is_deleted'];

            $msg = "";

            if ($msg == "") {
                // Collect data for insertion or update
                $data = [
                    "nvr_camera_json" => $nvr_camera_json,
                    "name" => $name,
                    "status" => $status,
                    "created_by" => $created_by,
                    "created_on" => $created_on,
                    "updated_by" => $updated_by,
                    "updated_on" => $updated_on,
                    "deleted_by" => $deleted_by,
                    "deleted_on" => $deleted_on,
                    "is_deleted" => $is_deleted,
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
    private function getEn_camera_groupById() {
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

    private function deleteEn_camera_group() {
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
