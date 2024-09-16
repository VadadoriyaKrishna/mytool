<?php

require_once '/var/www/html/mobigram/models/CokeyBookModel.php';

class CokeyBook extends CokeyBookModel {

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

        $totalRecords = $this->CokeyBookCount($search_keyword);

        if ($totalRecords > 0) {
            $list = $this->CokeyBookList($search_keyword, $start, $length, $sortBy, $sortOrder);
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
     $required_params = ["name", "description", "price", "pages", "per_page_story_count", "story_days_count", "book_languages", "status"];

        if (CommonFunctions::CheckRequiredParams($required_params, 'post')) {

            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $pages = $_POST['pages'];
            $per_page_story_count = $_POST['per_page_story_count'];
            $story_days_count = $_POST['story_days_count'];
            $book_languages = $_POST['book_languages'];
            $status = $_POST['status'];
            $id = $_POST['id'];
            $old_image = $_POST['old_image'];
            // Check if a new image is uploaded
            if (isset($_FILES['cover_img']) && !empty($_FILES['cover_img']['name']) && !empty($_FILES['cover_img'])) {
         if($_FILES['cover_img']['size'] <= 0) {
         $msg = "Please upload valid cover image.";
            } else {
                $file_name = $_FILES['cover_img']['name'];
                $extension = strtolower(end(explode('.', $file_name)));
                $allowed_types = ['jpg', 'jpeg', 'png', 'webp'];
                if (!in_array($extension, $allowed_types)) {
                    $msg = "Please upload a valid image. Only JPG, JPEG, PNG, and WEBP images are allowed.";
                } else {
                    $maxSize = 600 * 1024; // 600KB in bytes
                    if ($_FILES['cover_img']['size'] > $maxSize) {
                        $msg = "Image file size exceeds the maximum limit of 600KB.";
                    } 
                }
         }
    } else {
        if ($old_image == '') {
            $msg = "cover_img field is required.";
        }
    }
            if ($msg == "") {
                $user_id = $_SESSION['userId'];
                $ip = CommonFunctions::get_ip();
                 $cur_date = CommonFunctions::cur_date();
                $error_msg = "";
                $file_name = "";
                if ($id == "0" || ($old_image == '' && $id > 0)) {
            if (isset($_FILES['cover_img']) && !empty($_FILES['cover_img'])) {
                        $allowed_types = ['jpg', 'jpeg', 'png', 'webp'];
                        $directory_path = 'cokey_book/cover_img/';
                        $space_name = DO_UPLOAD_SPACE;
                        $file_name = CommonFunctions::FileUploadOnDigitalOcean($space_name, $input_type_file_name='cover_img', $directory_path, $allowed_types);
                    } else {
                        $error_msg = 'cover_img file field is required.';
                    }
                } else {
                    if ($old_image == '') {
                        $error_msg = 'cover_img file field is required.';
                    } else {
                        $file_name = $old_image;
                    }
                }
                if ($error_msg != "") {
                    CommonFunctions::FailMessage($error_msg);
                }
                // Collect data for insertion or update
                $data = [
                    "name" => $name,
                    "description" => $description,
                    "cover_img" => $file_name,
                    "price" => $price,
                    "pages" => $pages,
                    "per_page_story_count" => $per_page_story_count,
                    "story_days_count" => $story_days_count,
                    "book_languages" => $book_languages,
                    "status" => $status,
                    "ip" => $ip
                ];

                if ($id == "0") {
                    $data["created_by"] = $user_id;
                    $data["created_on"] = $cur_date;
                } else {
                    $data["updated_by"] = $user_id;
                    $data["updated_on"] = $cur_date;
                }

                $result = $this->SaveCokeyBookData($id, $data);

                if ($result) {
                    CommonFunctions::SuccessMessage("CokeyBook Data Saved Successfully.");
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
                CommonFunctions::SuccessMessage('CokeyBook data deleted successfully.');
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
new CokeyBook();