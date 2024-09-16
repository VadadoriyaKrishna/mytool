<?php

require_once '/var/www/html/mobigram/models/CameraModel.php';

class Camera extends CameraModel {

    function __construct() {
        parent::__construct();
        header('Content-Type: application/json');

        if (isset($_POST['action']) && $_POST['action'] != '') {
            $action = $_POST['action'];

            if($action == "get_en_camera_list") {
                self::getEn_cameraList();
            } elseif ($action == "add_en_camera") {
                self::addEn_camera();
            } elseif ($action == "edit_en_camera") {
                self::getEn_cameraById();
            } elseif ($action == "delete_en_camera") {
                $this->deleteEn_camera();
            }
        }
    }

    private function getEn_cameraList() {
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

        $totalRecords = $this->CameraCount($search_keyword);

        if ($totalRecords > 0) {
            $list = $this->CameraList($search_keyword, $start, $length, $sortBy, $sortOrder);
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

    private function addEn_camera() {
     $required_params = ["nvr_id", "channel_id", "name", "location", "latitude", "longitude", "address", "on_off_status", "deleted_by", "deleted_on"];

        if (CommonFunctions::CheckRequiredParams($required_params, 'post')) {

            $nvr_id = $_POST['nvr_id'];
            $channel_id = $_POST['channel_id'];
            $name = $_POST['name'];
            $location = $_POST['location'];
            $latitude = $_POST['latitude'];
            $longitude = $_POST['longitude'];
            $address = $_POST['address'];
            $on_off_status = $_POST['on_off_status'];
            $deleted_by = $_POST['deleted_by'];
            $deleted_on = $_POST['deleted_on'];

            $msg = "";

            if ($msg == "") {
                // Collect data for insertion or update
                $data = [
                    "nvr_id" => $nvr_id,
                    "channel_id" => $channel_id,
                    "name" => $name,
                    "location" => $location,
                    "latitude" => $latitude,
                    "longitude" => $longitude,
                    "address" => $address,
                    "on_off_status" => $on_off_status,
                    "deleted_by" => $deleted_by,
                    "deleted_on" => $deleted_on,
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

                $result = $this->SaveCameraData($cokey_book_id, $data);

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
    private function getEn_cameraById() {
	$required_params = ["id"];
	if (CommonFunctions::CheckRequiredParams($required_params, 'post')) {
		$id = $_POST["id"];
		$row = $this->getCameraDataById($id);
		$message = (count($row) > 0) ? 'Data loaded successfully' : 'No Data Found';
		$response = array('status' => true, 'message' => (string) $message, 'data' => (array) $row);
		echo json_encode($response);
		exit;
	} else {
		CommonFunctions::ParamsError();
	}
    }

    private function deleteEn_camera() {
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
