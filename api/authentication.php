<?php
/**
 * This is for user authentication. This file must always be passed the users email, and password.
 * It will the return if valid, and that users id. The users id can also be retrieved using the user api.
 */

//Required Config File
require_once(dirname(dirname(__FILE__))."/library/config.php");

//MySQL Connection
$mysqli = mysqli_connect($config["db"]["host"], $config["db"]["username"], $config["db"]["password"], $config["db"]["dbname"], $config["db"]["port"]);

//Default values if nothing is passed
$response['VALID'] = false;
$response['USER_ID'] = null;
$response["REASON"] = 0;

/*
 * Reason Codes:
 *
 * -1 - All is normal
 * 0 - Incorrect Credentials
 * 1 - Account not active
 */
http_response_code(400);

/**
 * GET method is to check if the authentication is valid.
 */
if($_SERVER['REQUEST_METHOD'] == "POST"){
    if(isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
        $_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));
    }

    if(isset($_POST['student_id']) && isset($_POST['password'])){
        http_response_code(200);
        $_POST['student_id'] = mysqli_real_escape_string($mysqli, $_POST['student_id']);
        $_POST['password'] = mysqli_real_escape_string($mysqli, $_POST['password']);

        //TODO Come back as sha1 might not be necessary;
        $_POST['password'] = sha1($_POST['password']);

        $result = mysqli_query($mysqli, "SELECT id FROM users WHERE student_id = '".$_POST['student_id']."' AND password = '".$_POST['password']."'");

        while($row = mysqli_fetch_assoc($result)){
            $response['USER_ID'] = $row['id'];
            $response['VALID'] = true;
            $response["REASON"] = -1;
            $query = mysqli_query($mysqli, "SELECT id FROM `users.claim` WHERE user_id = ".$row['id'].";");
            while($row1 = mysqli_fetch_assoc($query)){
                $response['VALID'] = false;
                $response["REASON"] = 1;
                break;
            }
            break;
        }
    }
}
mysqli_close($mysqli);
echo json_encode($response);