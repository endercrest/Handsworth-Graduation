<?php
/**
 * This is for retrieving, updating, or creating user information or accounts. This file must always be passed the session TOKEN
 * for verification.
 */

//Required Config File
require_once(dirname(dirname(__FILE__))."/library/config.php");

//MySQL Connection
$mysqli = mysqli_connect($config["db"]["host"], $config["db"]["username"], $config["db"]["password"], $config["db"]["dbname"], $config["db"]["port"]);

//Default Response.
$response = array();
http_response_code(401);

$primary = array("first_name", "last_name", "phone_number", "email", "address");

if(isset($_GET['TOKEN'])){
    $url  = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
    $url .= $_SERVER['SERVER_NAME'];
    $url .= $_SERVER['REQUEST_URI'];
    $url =  dirname($url);

    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'GET',
        ),
    );
    $context  = stream_context_create($options);
    $token_check = json_decode(file_get_contents($url."/token.php?TOKEN=".$_GET["TOKEN"], false, $context), true);

    if($token_check["VALID"]) {
        http_response_code(400);

        /**
         * GET method that retrieves the desired information.
         *
         * Arguments: TOKEN, USER_ID(Optional), and DATA[]
         */
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            if(isset($_GET['USER_ID'])) {
                $id = $_GET['USER_ID'];

                if (empty($_GET['DATA'])) {
                    $_GET['DATA'] = array("id", "student_id", "first_name", "last_name", "phone_number", "email", "address", "profile_picture", "permission", "status");
                }

                foreach ($_GET['DATA'] as $data) {
                    if ($data == "id") {
                        $response['id'] = $id;
                        http_response_code(200);
                    } else if ($data == "student_id") {
                        $result = mysqli_query($mysqli, "SELECT student_id FROM users WHERE id = " . $id . ";");

                        while ($row = mysqli_fetch_assoc($result)) {
                            $response["student_id"] = $row["student_id"];
                            http_response_code(200);
                            break;
                        }
                    }else if($data == "permission"){
                        $result = mysqli_query($mysqli, "SELECT permission FROM users WHERE id = " . $id . ";");

                        while ($row = mysqli_fetch_assoc($result)) {
                            $response["permission"] = $row["permission"];
                            http_response_code(200);
                            break;
                        }
                    }else if($data == "status"){
                        $status = "Registered";

                        $result = mysqli_query($mysqli, "SELECT id FROM `users.claim` WHERE user_id =".$id.";");

                        while($row = mysqli_fetch_assoc($result)){
                            $status = "Unregistered";
                            break;
                        }

                        $response["status"] = $status;

                    }  else if (in_array($data, $primary)) {
                        $result = mysqli_query($mysqli, "SELECT " . $data . " FROM `users.primary_info` WHERE user_id = " . $id . ";");

                        while ($row = mysqli_fetch_assoc($result)) {
                            $response[$data] = $row[$data];
                            http_response_code(200);
                            break;
                        }
                    } else if($data == "profile_picture"){
                        $result = mysqli_query($mysqli, "SELECT id FROM `users.secondary_field_types` WHERE `name` = '" . $data . "';");

                        $field_id = null;

                        while ($row = mysqli_fetch_assoc($result)) {
                            $field_id = $row['id'];
                            break;
                        }

                        if ($field_id != null) {
                            $result = mysqli_query($mysqli, "SELECT `value` FROM `users.secondary_info` WHERE user_id = " . $id . " AND field_type_id = " . $field_id . ";");

                            while ($row = mysqli_fetch_assoc($result)) {
                                $response[$data] = $row["value"];
                                http_response_code(200);
                                break;
                            }

                            if(empty($response[$data])){
                                $response[$data] = "style/Handsworth-Graduation/img/avatar.png";
                                http_response_code(200);
                            }
                        }
                    } else {
                        //REMINDER Make sure to include install script to add any secondary fields. (SETUP STUFF)
                        $result = mysqli_query($mysqli, "SELECT id FROM `users.secondary_field_types` WHERE `name` = '" . $data . "';");

                        $field_id = null;

                        while ($row = mysqli_fetch_assoc($result)) {
                            $field_id = $row['id'];
                            break;
                        }

                        if ($field_id != null) {
                            $result = mysqli_query($mysqli, "SELECT `value` FROM `users.secondary_info` WHERE user_id = " . $id . " AND field_type_id = " . $field_id . ";");

                            while ($row = mysqli_fetch_assoc($result)) {
                                $response[$data] = $row["value"];
                                http_response_code(200);
                                break;
                            }
                        }
                    }
                }
            /**
             * The section will return a list of all users and any selected details. You can limit number sent by setting LIMIT value.
             * To state the "page" set PAGE(Requires LIMIT).
             */
            }else{
                if (empty($_GET['DATA'])) {
                    $_GET['DATA'] = array("id", "student_id", "permission", "first_name", "last_name", "phone_number", "email", "address", "profile_picture", "status");
                }

                if(empty($_GET["SEARCH"])){
                    $_GET["SEARCH"] = "";
                }

                $sql = "SELECT user.id FROM users user, `users.primary_info` info WHERE user.id = info.user_id";

                if(isset($_GET["PERMISSION"])){
                    if(is_numeric($_GET["PERMISSION"])) {
                        $sql .= " AND permission = " . $_GET["PERMISSION"];
                    }else{
                        $response["ERROR"][] = "PERMISSION must be an integer";
                    }
                }

                $sql.= " AND (first_name LIKE '%".$_GET["SEARCH"]."%' OR last_name LIKE '%".$_GET["SEARCH"]."%' OR student_id LIKE '%".$_GET["SEARCH"]."%')";

                if(isset($_GET['LIMIT'])){
                    $sql .= " LIMIT ";

                    if(isset($_GET['PAGE'])){
                        $sql .= $_GET['PAGE'].",";
                    }
                    $sql .= $_GET['LIMIT'];
                }

                $query = mysqli_query($mysqli, $sql);
                http_response_code(200);

                while($row = mysqli_fetch_assoc($query)){
                    $id = $row["id"];

                    $array = array();

                    foreach($_GET['DATA'] as $data){
                        if ($data == "id") {
                            //$response[]['id'] = $token_check['USER_ID'];
                            $array["id"] = $id;
                        } else if ($data == "student_id") {
                            $result = mysqli_query($mysqli, "SELECT student_id FROM users WHERE id = " . $id . ";");

                            while ($row = mysqli_fetch_assoc($result)) {
                                //$response[]["student_id"] = $row["student_id"];
                                $array["student_id"] = $row['student_id'];
                                break;
                            }
                        }else if($data == "permission"){
                            $result = mysqli_query($mysqli, "SELECT permission FROM users WHERE id = " . $id . ";");

                            while ($row = mysqli_fetch_assoc($result)) {
                                $array["permission"] = $row['permission'];
                                break;
                            }
                        }else if($data == "status"){
                            $status = "Registered";

                            $result = mysqli_query($mysqli, "SELECT id FROM `users.claim` WHERE user_id =".$id.";");

                            while($row = mysqli_fetch_assoc($result)){
                                $status = "Unregistered";
                                break;
                            }

                            $array["status"] = $status;

                        } else if (in_array($data, $primary)) {
                            $result = mysqli_query($mysqli, "SELECT " . $data . " FROM `users.primary_info` WHERE user_id = " . $id . ";");

                            while ($row = mysqli_fetch_assoc($result)) {
                                //$response[][$data] = $row[$data];
                                $array[$data] = $row[$data];
                                break;
                            }
                        } else {
                            //REMINDER Make sure to include install script to add any secondary fields. (SETUP STUFF)
                            $result = mysqli_query($mysqli, "SELECT id FROM `users.secondary_field_types` WHERE `name` = '" . $data . "';");

                            $field_id = null;

                            while ($row = mysqli_fetch_assoc($result)) {
                                $field_id = $row['id'];
                                break;
                            }

                            if ($field_id != null) {
                                $result = mysqli_query($mysqli, "SELECT `value` FROM `users.secondary_info` WHERE id = " . $id . " AND field_type_id = " . $field_id . ";");

                                while ($row = mysqli_fetch_assoc($result)) {
                                    //$response[][$data] = $row["value"];
                                    $array[$data] = $row['value'];
                                    http_response_code(200);
                                    break;
                                }
                            }
                        }
                    }

                    $response["users"][] = $array;
                }
            }
        /**
         * POST method creates a new account. There are two methods of adding accounts. Either to upload a spreadsheet, or to individually add a new user.
         *
         * Arguments: TOKEN,
         */
        } else if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if(isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
                $_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));
            }

            //If set then adding a mass number of accounts.
            if(isset($_POST['ROLE']) && isset($_FILES['FILE'])){
                require_once("../library/PHPExcel/PHPExcel/IOFactory.php");

                if ($_POST['ROLE'] == "STUDENT") {
                    $obj = PHPExcel_IOFactory::load($_FILES['FILE']["tmp_name"]);
                    $dataArray = $obj->getActiveSheet()->toArray(null, true, true, true);

                    $first = true;
                    foreach($dataArray as $value){
                        if($first){
                            $first = false;
                            continue;
                        }
                        $value["B"] = sha1($value["B"]);

                        if(empty($value['B']) || empty($value['A'])){
                            continue;
                        }

                        //Add The User
                        mysqli_query($mysqli, "INSERT INTO users (student_id, `password`) VALUES ('".$value['A']."', '".$value["B"]."');");

                        $result = mysqli_query($mysqli, "SELECT id FROM users WHERE student_id = '".$value['A']."';");
                        $id = 0;
                        while($row = mysqli_fetch_assoc($result)){
                            $id = $row['id'];
                            break;
                        }

                        mysqli_query($mysqli, "INSERT INTO `users.primary_info` (user_id, first_name, last_name, phone_number, email, address) VALUES (".$id.", '".$value['C']."', '".$value['D']."', '".$value['E']."', '".$value['F']."', '".$value['G']."');");

                        //Add user to tasks
                        $query = mysqli_query($mysqli, "SELECT id,`global` FROM tasks");

                        while($row = mysqli_fetch_assoc($query)){
                            $task_id = $row["id"];
                            $global = $row["global"];

                            mysqli_query($mysqli, "INSERT INTO `tasks.assigned` (user_id, task_id, enabled) VALUES (".$id.", ".$task_id.", ".$global.");");
                            $query1 = mysqli_query($mysqli, "SELECT LAST_INSERT_ID();");
                            while($row1 = mysqli_fetch_row($query1)){
                                $assignment_id = $row1[0];
                                mysqli_query($mysqli, "INSERT INTO `tasks.status` (assignment_id, `status`, `comment`) VALUES (".$assignment_id.", 'Assigned', '');");
                                break;
                            }
                        }

                        mysqli_query($mysqli, "INSERT INTO `users.claim` (user_id) VALUES (".$id.");");

                        //TODO Comeback if there is any secondary data needed to be added.

                        $response["students"][] = $value["A"];
                        http_response_code(200);
                    }
                }
            }else{
                if(empty($_POST["STUDENT_ID"])){
                    $valid = false;
                    $new_id = "000000s";
                    while(!$valid){
                        $num1 = rand(0, 9);
                        $num2 = rand(0, 9);
                        $num3 = rand(0, 9);
                        $num4 = rand(0, 9);
                        $num5 = rand(0, 9);
                        $num6 = rand(0, 9);
                        $new_id = $num1.$num2.$num3.$num4.$num5.$num6."s";

                        $query = mysqli_query($mysqli, "SELECT id FROM users WHERE student_id = '".$new_id."';");

                        $valid = true;
                        while($row = mysqli_fetch_assoc($query)){
                            $valid = false;
                            break;
                        }
                    }

                    $_POST["STUDENT_ID"] = $new_id;
                }

                if(empty($_POST["PASSWORD"])){
                    $_POST["PASSWORD"] = "student";
                }

                $_POST['PASSWORD'] = sha1($_POST['PASSWORD']);

                mysqli_query($mysqli, "INSERT INTO users (student_id, `password`, `permission`) VALUES ('".$_POST['STUDENT_ID']."', '".$_POST['PASSWORD']."', '".$_POST["PERMISSION"]."');");

                $result = mysqli_query($mysqli, "SELECT id FROM users WHERE student_id = '".$_POST['STUDENT_ID']."';");
                $id = 0;
                while($row = mysqli_fetch_assoc($result)){
                    $id = $row['id'];
                    break;
                }

                if(!isset($_POST["FIRST_NAME"])){
                    $_POST["FIRST_NAME"] = "";
                }
                if(!isset($_POST["LAST_NAME"])){
                    $_POST["LAST_NAME"] = "";
                }
                if(!isset($_POST["PHONE_NUMBER"])){
                    $_POST["PHONE_NUMBER"] = "";
                }
                if(!isset($_POST["ADDRESS"])){
                    $_POST["ADDRESS"] = "";
                }
                if(!isset($_POST["EMAIL"])){
                    $_POST["EMAIL"] = "";
                }

                mysqli_query($mysqli, "INSERT INTO `users.primary_info` (user_id, first_name, last_name, phone_number, email, address) VALUES (".$id.", '".$_POST['FIRST_NAME']."', '".$_POST['LAST_NAME']."', '".$_POST['PHONE_NUMBER']."', '".$_POST['EMAIL']."', '".$_POST['ADDRESS']."');");


                if(isset($_POST["PERMISSION"])){
                    if($_POST["PERMISSION"] == 2){
                        $query = mysqli_query($mysqli, "SELECT id,`global` FROM tasks");

                        while($row = mysqli_fetch_assoc($query)){
                            $task_id = $row["id"];
                            $global = $row["global"];

                            mysqli_query($mysqli, "INSERT INTO `tasks.assigned` (user_id, task_id, enabled) VALUES (".$id.", ".$task_id.", ".$global.");");
                            $query1 = mysqli_query($mysqli, "SELECT LAST_INSERT_ID();");
                            while($row1 = mysqli_fetch_row($query1)){
                                $assignment_id = $row1[0];
                                mysqli_query($mysqli, "INSERT INTO `tasks.status` (assignment_id, `status`, `comment`) VALUES (".$assignment_id.", 'Assigned', '');");
                                break;
                            }
                        }
                    }
                }

                mysqli_query($mysqli, "INSERT INTO `users.claim` (user_id) VALUES (".$id.");");

                //TODO Comeback if there is any secondary data needed to be added.

                $response["student"]["student_id"] = $_POST['STUDENT_ID'];
                $response["student"]["user_id"] = $id;
                http_response_code(200);
            }

        /**
         * PUT method updates any information that is stored for the user.
         *
         * Arguments: TOKEN, USER_ID(Optional), DATA[]
         */
        } else if ($_SERVER["REQUEST_METHOD"] == "PUT") {
            if(isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
                $_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));
            }

            $id = $token_check['USER_ID'];
            if(isset($_GET['USER_ID'])){
                $id = $_GET['USER_ID'];
            }

            foreach($_POST["DATA"] as $field => $value){
                if(empty($field)){
                    continue;
                }

                if($field == "student_id"){
                    mysqli_query($mysqli, "UPDATE users SET student_id = '".$value."' WHERE id = ".$id.";");

                    $response[$field] = $value;
                    http_response_code(200);
                }else if($field == "password"){
                    $value = sha1($value);

                    mysqli_query($mysqli, "UPDATE users SET `password` = '".$value."' WHERE id = ".$id.";");

                    $response[$field] = "";
                    http_response_code(200);
                }else if($field == "permission"){
                    mysqli_query($mysqli, "UPDATE users SET `permission` = ".$value." WHERE id = ".$id.";");

                    $response[$field] = $value;
                    http_response_code(200);
                }else if(in_array($field, $primary)){
                    mysqli_query($mysqli, "UPDATE `users.primary_info` SET `".$field."` = '".$value."' WHERE user_id = ".$id.";");

                    $response[$field] = $value;
                    http_response_code(200);
                }else{
                    $result = mysqli_query($mysqli, "SELECT id FROM `users.secondary_field_types` WHERE `name` = '".$field."';");

                    $field_id = null;

                    while($row = mysqli_fetch_assoc($result)){
                        $field_id = $row['id'];
                        break;
                    }

                    if($field_id != null){
                        mysqli_query($mysqli, "UPDATE `users.secondary_info` SET `value` = ".$value." WHERE field_type_id = ".$field_id." AND user_id = ".$id.";");

                        $response[$field] = $value;
                        http_response_code(200);
                    }
                }
            }
        /**
         * DELETE method will completely delete all data related to a user. This method is meant only to remove a user from the system.
         */
        } else if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
            if(isset($_GET['USER_ID'])){
                $_GET['USER_ID'] = intval($_GET['USER_ID']);

                mysqli_query($mysqli, "DELETE FROM users WHERE id = ".$_GET["USER_ID"].";");
                mysqli_query($mysqli, "DELETE FROM `users.primary_info` WHERE user_id = ".$_GET['USER_ID'].";");
                mysqli_query($mysqli, "DELETE FROM tokens WHERE user_id = ".$_GET['USER_ID'].";");
                mysqli_query($mysqli, "DELETE FROM `users.secondary_info` WHERE user_id = ".$_GET['USER_ID'].";");

                http_response_code(200);
            }else{
                $response['ERROR'][] = "MISSING 'USER_ID'";
            }
            //TODO Return when more data for a user is stored
        }
    }else{
        $response['ERROR'][] = "INVALID TOKEN";
    }
}else{
    $response["ERROR"][] = "A valid token is required to access this api";
}
mysqli_close($mysqli);
echo json_encode($response);