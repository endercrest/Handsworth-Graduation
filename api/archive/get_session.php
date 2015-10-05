<?php
/**
 * This file will get the users current session token. Will either return TOKEN or "INVALID"
 *
 * required: User email
 * returns: Session token, status code, and message with details.
 */

require_once(dirname(dirname(__FILE__)) . "/library/config.php");

$response = array();
$response["session"] = "INVALID";
if(isset($_POST['email'])){


    /*$url  = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
    $url .= $_SERVER['SERVER_NAME'];
    $url .= $_SERVER['REQUEST_URI'];

    $url =  dirname($url);

    // use key 'http' even if you send the request to https://...
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($_POST),
        ),
    );
    $context  = stream_context_create($options);
    $result = json_decode(file_get_contents($url."/validate_credentials.php", false, $context), true);
    if($result["valid"]){
        $response['status'] = "200 OK";
        $response['message'] = "Session Key Returned";

        //$mysqli = mysqli_connect($config["db"]["host"], $config["db"]["username"], $config["db"]["password"], $config["db"]["dbname"], $config["db"]["port"]);
        //mysqli_query($mysqli, "SELECT token ")


        $response['session'] = "SESSION HERE";
    }else{
        $response['status'] = "401 Unauthorized";
        $response['message'] = "Credentials are not valid";
    }*/
}else{
    $response['status'] = "401 Unauthorized";
    $response['message'] = "Missing Required Parameters";
}
echo json_encode($response);