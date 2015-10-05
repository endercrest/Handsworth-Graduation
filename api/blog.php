<?php
/**
 * This is for retrieving, updating, creating, and deleting blog posts
 */

//Required Config File
require_once(dirname(dirname(__FILE__))."/library/config.php");

//MySQL Connection
$mysqli = mysqli_connect($config["db"]["host"], $config["db"]["username"], $config["db"]["password"], $config["db"]["dbname"], $config["db"]["port"]);

$response = array();
http_response_code(400);

if($_SERVER["REQUEST_METHOD"] == "GET"){
    http_response_code(200);
    //Check if looking for a specific post
    if(isset($_GET['ID'])){
        $id = $_GET['ID'];

        if(empty($_GET['DATA'])){
            $_GET['DATA'] = array("id", "user_id", "title", "body", "timestamp", "author", "date", "categories");
        }

        foreach($_GET['DATA'] as $data){
            if($data == "author"){
                $result = mysqli_query($mysqli, "SELECT first_name, last_name FROM `users.primary_info` WHERE user_id = ".$id.";");

                while($row1 = mysqli_fetch_assoc($result)){
                    $response["post"]["author"] = $row1["first_name"]." ".$row1["last_name"];
                    http_response_code(200);
                    break;
                }
            }else if($data == "date"){
                $result = mysqli_query($mysqli, "SELECT `timestamp` FROM `blog.posts` WHERE id = ".$id.";");

                while($row1 = mysqli_fetch_assoc($result)){
                    $response['post']["date"] = date("M j", $row1["timestamp"]);
                    http_response_code(200);
                    break;
                }
            }else if($data == "categories"){
                $response["post"]["categories"] = array();
                $result = mysqli_query($mysqli, "SELECT category_type_id FROM `blog.category` WHERE post_id = ".$id.";");

                while($row1 = mysqli_fetch_assoc($result)){
                    $result1 = mysqli_query($mysqli, "SELECT `name` FROM `blog.category_types` WHERE id = ".$row1['category_type_id'].";");

                    while($row2 = mysqli_fetch_assoc($result1)){
                        $response["post"]["categories"][] = $row2["name"];
                        http_response_code(200);
                        break;
                    }
                }
            }else{
                $result = mysqli_query($mysqli, "SELECT " . $data . " FROM `blog.posts` WHERE id = " . $id . ";");

                while ($row1 = mysqli_fetch_assoc($result)) {
                    $response['post'][$data] = $row1[$data];
                    http_response_code(200);
                    break;
                }
            }
        }
    }else{
        $response["posts"] = array();

        if(empty($_GET['DATA'])){
            $_GET['DATA'] = array("id", "user_id", "title", "body", "timestamp", "author", "date", "categories");
        }

        $sql = "SELECT id FROM `blog.posts`";

        if(!empty($_GET["CATEGORY"])){
            $result = mysqli_query($mysqli, "SELECT post_id AS id FROM `blog.category` WHERE category_type_id = ".$_GET["CATEGORY"].";");

            $first = true;

            while($row = mysqli_fetch_assoc($result)){
                if($first){
                    $sql .= " WHERE id =".$row["id"];
                    $first = false;
                }else {
                    $sql .= " OR id =".$row["id"];
                }
            }

            if($first){
                $sql .= "WHERE id = 0";
            }
            //$sql = "SELECT post_id AS id FROM `blog.category` WHERE category_type_id = ".$_GET["CATEGORY"];
        }

        $sql .= " ORDER BY `timestamp` DESC";

        if(isset($_GET['LIMIT'])){
            $sql .= " LIMIT ";

            if(isset($_GET['PAGE'])){
                $sql .= $_GET['PAGE'].",";
            }
            $sql .= $_GET['LIMIT'];
        }

        $query = mysqli_query($mysqli, $sql);

        while ($row = mysqli_fetch_assoc($query)){
            $id = $row['id'];

            $array = array();
            $response["test"] = $_GET;

            foreach($_GET['DATA'] as $data){
                if($data == "author"){
                    $result = mysqli_query($mysqli, "SELECT first_name, last_name FROM `users.primary_info` WHERE user_id = ".$id.";");

                    while($row1 = mysqli_fetch_assoc($result)){
                        $array["author"] = $row1["first_name"]." ".$row1["last_name"];
                    }
                }else if($data == "date"){
                    $result = mysqli_query($mysqli, "SELECT `timestamp` FROM `blog.posts` WHERE id = ".$id.";");

                    while($row1 = mysqli_fetch_assoc($result)){
                        $array["date"] = date("M j", strtotime($row1["timestamp"]));
                        http_response_code(200);
                        break;
                    }
                }else if($data == "categories"){
                    $array["categories"] = array();
                    $result = mysqli_query($mysqli, "SELECT category_type_id FROM `blog.category` WHERE post_id = ".$id.";");

                    while($row1 = mysqli_fetch_assoc($result)){
                        $result1 = mysqli_query($mysqli, "SELECT `name` FROM `blog.category_types` WHERE id = ".$row1['category_type_id'].";");

                        while($row2 = mysqli_fetch_assoc($result1)){
                            $array["categories"][] = $row2["name"];
                            http_response_code(200);
                            break;
                        }
                    }
                }else {
                    $result = mysqli_query($mysqli, "SELECT " . $data . " FROM `blog.posts` WHERE id = " . $id . ";");

                    while ($row1 = mysqli_fetch_assoc($result)) {
                        $array[$data] = $row1[$data];
                        break;
                    }
                }
            }

            $response["posts"][] = $array;
            http_response_code(200);
        }
    }
/**
 * POST method will create a new blog post with the provided data. This requires a valid token.
 */
}else if($_SERVER['REQUEST_METHOD'] == "POST"){
    if(isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
        $_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));
    }

    //TODO Add Support for categories being added to a post.

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
        $token_response = json_decode(file_get_contents($url."/token.php?TOKEN=".$_GET["TOKEN"], false, $context), true);

        if($token_response['VALID']){
            $_POST['user_id'] = mysqli_real_escape_string($mysqli, $_POST['user_id']);
            $_POST['title'] = mysqli_real_escape_string($mysqli, $_POST['title']);
            $_POST['body'] = mysqli_real_escape_string($mysqli, $_POST['body']);

            if(isset($_POST['timestamp'])){
                $_POST['timestamp'] = mysqli_real_escape_string($mysqli, $_POST['timestamp']);
            }else{
                $_POST['timestamp'] = date("Y-m-d H:i:s", time());
            }

            mysqli_query($mysqli, "INSERT INTO `blog.posts` (user_id, title, body, `timestamp`) VALUES (".$_POST['user_id'].", '".$_POST['title']."', '".$_POST['body']."', '".$_POST['timestamp']."');");

            $query = mysqli_query($mysqli, "SELECT LAST_INSERT_ID()");

            while($row1 = mysqli_fetch_row($query)){
                $response["post"]["id"] = $row1[0];
                break;
            }

            $response["post"]["user_id"] = $_POST['user_id'];
            $response["post"]["title"] = $_POST['title'];
            $response["post"]["body"] = $_POST['body'];
            $response["post"]["timestamp"] = $_POST['timestamp'];

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
            $info = json_decode(file_get_contents($url."/user.php?TOKEN=".$_GET["TOKEN"]."&DATA[]=first_name&DATA[]=last_name", false, $context), true);

            $response['post']["author"] = $info["first_name"]." ".$info["last_name"];
            http_response_code(200);
        }else{
            $response["ERROR"][] = "Invalid 'TOKEN'";
        }
    }else{
        $response["ERROR"][] = "Missing 'TOKEN'";
    }
/**
 * PUT method updates a blog post data
 */
}else if($_SERVER['REQUEST_METHOD'] == "PUT"){
    if(isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
        $_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));
    }
    //TODO Comeback to get PUT method to work. Data is not sent via PUT or POST for body. Will have to investigate.

    if(isset($_GET['TOKEN'])){
        if(isset($_GET['ID'])) {
            $id = $_GET['ID'];

            $url = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
            $url .= $_SERVER['SERVER_NAME'];
            $url .= $_SERVER['REQUEST_URI'];
            $url = dirname($url);

            $options = array(
                'http' => array(
                    'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method' => 'GET',
                ),
            );
            $context = stream_context_create($options);
            $token_response = json_decode(file_get_contents($url . "/token.php?TOKEN=".$_GET["TOKEN"], false, $context), true);

            if ($token_response['VALID']) {
                foreach($_POST['DATA'] as $key=>$value){
                    mysqli_query($mysqli, "UPDATE `blog.posts` SET ".$key." = ".$value." WHERE id = ".$id.";");
                }

                $url = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
                $url .= $_SERVER['SERVER_NAME'];
                $url .= $_SERVER['REQUEST_URI'];
                $url = dirname($url);

                $options = array(
                    'http' => array(
                        'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                        'method' => 'GET',
                    ),
                );
                $context = stream_context_create($options);
                $info = json_decode(file_get_contents($url."/blog.php?ID=".$id, false, $context), true);

                $response["post"] = $info;
                http_response_code(200);
            }else{
                $response["ERROR"][] = "Invalid 'TOKEN'";
            }
        }else{
            $response['ERROR'][] = "Missing 'ID'";
        }
    }else{
        $response["ERROR"][] = "Missing 'TOKEN'";
    }

}else if($_SERVER['REQUEST_METHOD'] == "DELETE"){
    if(isset($_GET['ID'])){
        $id = $_GET['ID'];

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
        $token_response = json_decode(file_get_contents($url."/token.php?TOKEN=".$_GET["TOKEN"], false, $context), true);

        if($token_response['VALID']){
            mysqli_query($mysqli, "DELETE FROM `blog.posts` WHERE id = ".$id.";");
            http_response_code(200);
        }else{
            $response['ERROR'][] = "Invalid 'TOKEN'";
        }
    }else{
        $response["ERROR"][] = "Missing 'ID'";
    }
}

echo json_encode($response);