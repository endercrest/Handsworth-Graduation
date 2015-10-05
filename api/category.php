<?php
/**
 *
 */

//Required Config File
require_once(dirname(dirname(__FILE__))."/library/config.php");

//MySQL Connection
$mysqli = mysqli_connect($config["db"]["host"], $config["db"]["username"], $config["db"]["password"], $config["db"]["dbname"], $config["db"]["port"]);

$response = array();
http_response_code(400);

/**
 * GET method will return information about either a single category or all categories
 */
if($_SERVER["REQUEST_METHOD"] == "GET"){
    if(isset($_GET['ID'])){
        $id = $_GET["ID"];

        if(empty($_GET['DATA'])){
            $_GET['DATA'] = array("id", "name", "size");
        }

        foreach($_GET['DATA'] as $data){
            if($data == "id"){
                $response["category"]["id"] = $id;
                http_response_code(200);
            }else if($data == "name"){
                $result = mysqli_query($mysqli, "SELECT `name` FROM `blog.category_types` WHERE id = ".$id.";");

                while($row = mysqli_fetch_assoc($result)){
                    $response["category"]["name"] = $row["name"];
                    http_response_code(200);
                    break;
                }
            }else if($data == "size"){
                $result = mysqli_query($mysqli, "SELECT COUNT(id) FROM `blog.category` WHERE category_type_id =  ".$id.";");

                while($row = mysqli_fetch_row($result)){
                    $response["category"]["size"] = $row[0];
                    http_response_code(200);
                }
            }
        }

    }else if(isset($_GET['NAME'])){
        $result = mysqli_query($mysqli, "SELECT id FROM `blog.category_types` WHERE `name` = '".$_GET['name']."';");

        $id = 0;
        while($row = mysqli_fetch_assoc($result)){
            $id = $row["id"];
            break;
        }

        if(empty($_GET['DATA'])){
            $_GET['DATA'] = array("id", "name", "size");
        }

        foreach($_GET['DATA'] as $data){
            if($data == "id"){
                $response["category"]["id"] = $id;
                http_response_code(200);
            }else if($data == "name"){
                $response["category"]["name"] = $_GET['name'];
                http_response_code(200);
            }else if($data == "size"){
                $result = mysqli_query($mysqli, "SELECT COUNT(id) FROM `blog.category` WHERE category_type_id = ".$id.";");

                while($row = mysqli_fetch_row($result)){
                    $response["category"]["size"] = $row[0];
                    http_response_code(200);
                    break;
                }
            }
        }
    }else{
        $response["categories"] = array();

        if(empty($_GET['DATA'])){
            $_GET['DATA'] = array("id", "name", "size");
        }

        $query = mysqli_query($mysqli, "SELECT id FROM `blog.category_types`");

        while($row = mysqli_fetch_assoc($query)){
            $id = $row['id'];

            $array = array();

            foreach($_GET['DATA'] as $data){
                if($data == "id"){
                    $array["id"] = $id;
                }else if($data == "name"){
                    $result = mysqli_query($mysqli, "SELECT `name` FROM `blog.category_types` WHERE id = ".$id.";");

                    while($row1 = mysqli_fetch_assoc($result)){
                        $array["name"] = $row1["name"];
                        break;
                    }
                }else if($data == "size"){
                    $result = mysqli_query($mysqli, "SELECT COUNT(id) FROM `blog.category` WHERE category_type_id = ".$id.";");

                    while($row1 = mysqli_fetch_row($result)){
                        $array["size"] = $row1[0];
                        break;
                    }
                }
            }

            $response["categories"][] = $array;
            http_response_code(200);
        }
    }
    //TODO Add POST support
}
echo json_encode($response);