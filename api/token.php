<?php
/**
 * This is for creating, validating, and extending tokens. Each Method has its own requirements.
 * Most require the session TOKEN. Will always return a TOKEN value, may be INVALID or the token,
 * if it is VALID, and the ID associated with the token.
 */

//Required Config File
require_once(dirname(dirname(__FILE__))."/library/config.php");

//MySQL Connection
$mysqli = mysqli_connect($config["db"]["host"], $config["db"]["username"], $config["db"]["password"], $config["db"]["dbname"], $config["db"]["port"]);

//Default values if nothing is passed
$response['TOKEN'] = "INVALID";
$response['VALID'] = false;
$response['USER_ID'] = null;
$response['PERMISSION'] = 4;
$response['REASON'] = 0;
http_response_code(400);

/**
 * GET method will check if the session TOKEN is still valid.
 */
if($_SERVER['REQUEST_METHOD'] == "GET"){
    if(isset($_GET['TOKEN'])){
        $_GET['TOKEN'] = mysqli_real_escape_string($mysqli, $_GET['TOKEN']);
        $result = mysqli_query($mysqli, "SELECT user_id FROM tokens WHERE token = '".$_GET['TOKEN']."' AND expiry > NOW()");

        while ($row = mysqli_fetch_assoc($result)) {
            $result1 = mysqli_query($mysqli, "SELECT permission FROM users WHERE id = ".$row["user_id"].";");

            while($row1 = mysqli_fetch_assoc($result1)) {

                $response['TOKEN'] = $_GET['TOKEN'];
                $response['VALID'] = true;
                $response['USER_ID'] = $row["user_id"];
                $response["PERMISSION"] = $row1["permission"];
                $response['REASON'] = -1;
                break;
            }
            break;
        }

        http_response_code(200);
    }
/**
 * POST method that will create a new session token using the users credentials.
 */
}else if($_SERVER['REQUEST_METHOD'] == "POST"){
    if(isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
        $_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));
    }

    if(isset($_POST['student_id']) && isset($_POST['password'])) {
        http_response_code(200);

        $url  = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
        $url .= $_SERVER['SERVER_NAME'];
        $url .= $_SERVER['REQUEST_URI'];
        $url =  dirname($url);

        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($_POST),
            ),
        );
        $context  = stream_context_create($options);
        $result = json_decode(file_get_contents($url."/authentication.php", false, $context), true);

        $response["REASON"] = $result["REASON"];

        if($result['VALID']) {
            $charset = array_flip(array_merge(range('a', 'z'), range('A', 'Z'), range(0, 9)));
            $token = implode('', array_rand($charset, 20));

            $user_id = $result["USER_ID"];

            $expiry = date("Y-m-d H:i:s", strtotime("+30 minutes"));

            mysqli_query($mysqli, "INSERT INTO tokens (user_id, token, expiry)  VALUES (" . $user_id . ", '" . $token . "', '" . $expiry . "');");

            $query = mysqli_query($mysqli, "SELECT permission FROM users WHERE id = ".$result["USER_ID"].";");

            while($row = mysqli_fetch_assoc($query)) {
                $response['TOKEN'] = $token;
                $response['VALID'] = true;
                $response['USER_ID'] = $result["USER_ID"];
                $response["PERMISSION"] = $row["permission"];
            }
        }
    }
/**
 * PUT method that will update the tokens expiry and extend by 30 mins.
 */
}else if($_SERVER['REQUEST_METHOD'] == "PUT"){
        http_response_code(200);
        if(isset($_GET['TOKEN'])){

            $_GET['TOKEN'] = mysqli_real_escape_string($mysqli, $_GET['TOKEN']);

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
            $result = json_decode(file_get_contents($url."/token.php?TOKEN=".$_GET['TOKEN'], false, $context), true);

            $response["REASON"] = $result["REASON"];

            if($result['VALID']) {
                $expiry = date("Y-m-d H:i:s", strtotime("+30 minutes"));

                $response["debug"] = $expiry;

                mysqli_query($mysqli, "UPDATE tokens SET expiry = '".$expiry."' WHERE token = '" . $_GET['TOKEN'] . "';");

                $response["TOKEN"] = $_GET['TOKEN'];
                $response["VALID"] = true;
                $response["USER_ID"] = $result["USER_ID"];
                $response["PERMISSION"] = $result["PERMISSION"];
                http_response_code(200);
            }
        }
/**
 * DELETE method will delete the TOKEN, ending that TOKENs session.
 */
}else if($_SERVER['REQUEST_METHOD'] == "DELETE"){
    http_response_code(200);
    $response['DEBUG'] = $_GET;
    if(isset($_GET['TOKEN'])){
        $_GET['TOKEN'] = mysqli_real_escape_string($mysqli, $_GET['TOKEN']);

        mysqli_query($mysqli, "DELETE FROM tokens WHERE token = '".$_GET['TOKEN']."'");
        $response["TOKEN"] = $_GET['TOKEN'];

        mysqli_query($mysqli, "DELETE FROM tokens WHERE expiry < NOW()");
    }
}
mysqli_close($mysqli);
echo json_encode($response);