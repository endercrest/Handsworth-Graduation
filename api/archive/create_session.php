<?php
/**
 * Creates a new session using the users credentials. Will return the session TOKEN or "INVALID".
 *
 * required: User email, and user password
 * returns: Sessions token, status code, and message with details
 */

require_once(dirname(dirname(__FILE__)) . "/library/config.php");

/*$response = array();
$response["session"] = "INVALID";
if(isset($_POST['email']) && isset($_POST['password'])){
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
    $result = json_decode(file_get_contents($url."/validate_credentials.php", false, $context), true);
    if($result["valid"]){
        $response['status'] = "200 OK";
        $response['message'] = "Session Key Returned";

        //TODO CREATE SESSION STUFF
        $response['session'] = "SESSION HERE";
    }else{
        $response['status'] = "401 Unauthorized";
        $response['message'] = "Credentials are not valid";
    }
}else{
    $response['status'] = "401 Unauthorized";
    $response['message'] = "Missing Required Parameters";
}
echo json_encode($response);*/

$response = array();
$response["session"] = "INVALID";
if(isset($_POST['email']) && isset($_POST['password'])){
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
    $result = json_decode(file_get_contents($url."/validate_credentials.php", false, $context), true);
    if($result["valid"]){
        $response['status'] = "200 OK";
        $response['message'] = "Session Key Returned";

        //TODO CREATE SESSION STUFF
        $response['session'] = "SESSION HERE";
    }else{
        http_response_code(401);
    }
}else{
    http_response_code(401);
}
echo json_encode($response);