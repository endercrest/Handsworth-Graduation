<?php
//FILE NAMING CONVENTION
//SAVED IN STUDENTID FOLDER
//'STUDENTID_YEAR_TASKID_FILENUM.EXT

//THREE DIGIT FILE NUM

/**
 *
 */

//Required Config File
require_once(dirname(dirname(__FILE__))."/library/config.php");

//MySQL Connection
$mysqli = mysqli_connect($config["db"]["host"], $config["db"]["username"], $config["db"]["password"], $config["db"]["dbname"], $config["db"]["port"]);

//Default Response.
$response = array();
http_response_code(401);

function human_filesize($bytes, $decimals = 2) {
    $size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
}
function startsWith($haystack, $needle) {
    // search backwards starting from haystack length characters from the end
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
}
function endsWith($haystack, $needle) {
    // search forward starting from end minus needle length characters
    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
}

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

    if($token_check["VALID"]){
        if($_SERVER["REQUEST_METHOD"] == "GET"){
            if(isset($_GET["REQUEST"])) {
                //Request Types: task_info, task_files, user_files, user_info
                if ($_GET['REQUEST'] == "task_info") {
                    if (empty($_GET["DATA"])) {
                        $_GET['DATA'] = array("id", "name", "description", "due", "extensions", "global");
                    }

                    if (isset($_GET['TASK_ID'])) {
                        $id = $_GET["TASK_ID"];

                        foreach ($_GET['DATA'] as $data) {
                            $result = mysqli_query($mysqli, "SELECT " . $data . " FROM `tasks` WHERE id = " . $id . ";");

                            while ($row1 = mysqli_fetch_assoc($result)) {
                                $response["task"][$data] = $row1[$data];
                                break;
                            }
                        }
                    } else {
                        if (isset($_GET["USER_ID"])) {
                            $user_id = $_GET["USER_ID"];

                            $sql = "SELECT task_id FROM `tasks.assigned` WHERE user_id = ".$user_id." AND enabled = true";

                            if (isset($_GET["LIMIT"])) {
                                $sql .= " LIMIT ";

                                if (isset($_GET["PAGE"])) {
                                    $sql .= $_GET["PAGE"] . ",";
                                }

                                $sql .= $_GET["LIMIT"];
                            }

                            $query = mysqli_query($mysqli, $sql);

                            while($row = mysqli_fetch_assoc($query)){
                                $task_id = $row['task_id'];

                                $array = array();

                                foreach ($_GET['DATA'] as $data) {
                                    if($data == "status"){
                                        $result = mysqli_query($mysqli, "SELECT id FROM `tasks.assigned` WHERE user_id = ".$user_id." AND task_id = ".$task_id." AND enabled = true;");

                                        while($row1 = mysqli_fetch_assoc($result)){
                                            $id = $row1["id"];

                                            $result1 = mysqli_query($mysqli, "SELECT `status` FROM `tasks.status` WHERE assignment_id = ".$id." ORDER BY `timestamp` DESC;");

                                            while($row2 = mysqli_fetch_assoc($result1)){
                                                $array[$data] = $row2["status"];
                                                break;
                                            }
                                        }
                                    }else {
                                        $result = mysqli_query($mysqli, "SELECT " . $data . " FROM `tasks` WHERE id = " . $task_id . ";");

                                        while ($row1 = mysqli_fetch_assoc($result)) {
                                            $array[$data] = $row1[$data];
                                            break;
                                        }
                                    }
                                }

                                $response["tasks"][] = $array;
                            }
                        } else {
                            $sql = "SELECT id FROM `tasks` ORDER BY `due` DESC";

                            if (isset($_GET["LIMIT"])) {
                                $sql .= " LIMIT ";

                                if (isset($_GET["PAGE"])) {
                                    $sql .= $_GET["PAGE"] . ",";
                                }

                                $sql .= $_GET["LIMIT"];
                            }


                            $query = mysqli_query($mysqli, $sql);

                            while ($row = mysqli_fetch_assoc($query)) {
                                $id = $row['id'];

                                $array = array();

                                foreach ($_GET['DATA'] as $data) {
                                    $result = mysqli_query($mysqli, "SELECT " . $data . " FROM `tasks` WHERE id = " . $id . ";");

                                    while ($row1 = mysqli_fetch_assoc($result)) {
                                        $array[$data] = $row1[$data];
                                        break;
                                    }
                                }

                                $response["tasks"][] = $array;
                            }
                        }
                    }
                    http_response_code(200);
                } else if ($_GET['REQUEST'] == "task_files") {
                    if (empty($_GET['DATA'])) {
                        $_GET["DATA"] = array("id", "task_id", "path", "name", "description");
                    }

                    if(isset($_GET["FILE_ID"])){
                        $file_id = $_GET["FILE_ID"];

                        foreach($_GET["DATA"] as $data){
                            if($data == "id"){
                                $response["file"][$data] = $file_id;
                            }else{
                                $result = mysqli_query($mysqli, "SELECT ".$data." FROM `tasks.files` WHERE id = ".$file_id.";");

                                while($row1 = mysqli_fetch_assoc($result)){
                                    $response["file"][$data] = $row1[$data];
                                    break;
                                }
                            }
                        }
                    }else{
                        if(isset($_GET["TASK_ID"])){
                            $task_id = $_GET["TASK_ID"];

                            $query = mysqli_query($mysqli, "SELECT id FROM `tasks.files` WHERE task_id = ".$task_id.";");

                            while($row = mysqli_fetch_assoc($query)){
                                $file_id = $row["id"];

                                $array = array();

                                foreach($_GET["DATA"] as $data){
                                    if($data == "id"){
                                        $array[$data] = $file_id;
                                    }else if($data == "task_id"){
                                        $array[$data] = $task_id;
                                    }else{
                                        $result = mysqli_query($mysqli, "SELECT ".$data." FROM `tasks.files` WHERE id = ".$file_id.";");

                                        while($row1 = mysqli_fetch_assoc($result)){
                                            $array[$data] = $row1[$data];
                                            break;
                                        }
                                    }
                                }

                                $response["files"][] = $array;
                            }
                        }else{
                            $query = mysqli_query($mysqli, "SELECT id FROM `tasks`");

                            while($row = mysqli_fetch_assoc($query)){
                                $task_id = $row["id"];

                                $query1 = mysqli_query($mysqli, "SELECT id FROM `tasks.files` WHERE task_id = ".$task_id.";");

                                while($row1 = mysqli_fetch_assoc($query1)){
                                    $file_id = $row1["id"];

                                    $array = array();

                                    foreach($_GET["DATA"] as $data){
                                        if($data == "id"){
                                            $array[$data] = $file_id;
                                        }else if($data == "task_id"){
                                            $array[$data] = $task_id;
                                        }else{
                                            $result = mysqli_query($mysqli, "SELECT ".$data." FROM `tasks.files` WHERE id = ".$file_id.";");

                                            while($row2 = mysqli_fetch_assoc($result)){
                                                $array[$data] = $row2[$data];
                                                break;
                                            }
                                        }
                                    }

                                    $response["files"][$task_id] = $array;
                                }
                            }
                        }
                    }
                    http_response_code(200);
                } else if ($_GET['REQUEST'] == "user_files") {
                    if (empty($_GET['DATA'])) {
                        $_GET['DATA'] = array("id", "user_id", "task_id", "path", "original_name", "size");
                    }

                    if (isset($_GET['FILE_ID'])) {
                        $file_id = $_GET['FILE_ID'];

                        foreach ($_GET['DATA'] as $data) {
                            if ($data == "id") {
                                $response["file"][$data] = $file_id;
                            }else if($data == "size"){
                                $result = mysqli_query($mysqli, "SELECT path FROM `tasks.user_files` WHERE id = " . $file_id . ";");

                                while($row = mysqli_fetch_assoc(($result))){
                                    $file_path = $row["path"];

                                    $response["file"][$data] = human_filesize(filesize("../".$file_path));
                                }
                            } else {
                                $result = mysqli_query($mysqli, "SELECT " . $data . " FROM `tasks.user_files` WHERE id = " . $file_id . ";");

                                while ($row = mysqli_fetch_assoc($result)) {
                                    $response["file"][$data] = $row[$data];
                                    break;
                                }
                            }
                        }
                    } else {
                        $response["files"] = array();
                        if (isset($_GET["USER_ID"])) {
                            if (isset($_GET["TASK_ID"])) {
                                $user_id = $_GET['USER_ID'];
                                $task_id = $_GET['TASK_ID'];

                                $query = mysqli_query($mysqli, "SELECT id FROM `tasks.user_files` WHERE user_id = " . $user_id . " AND task_id = " . $task_id . ";");

                                while ($row = mysqli_fetch_assoc($query)) {
                                    $file_id = $row['id'];

                                    $array = array();

                                    foreach ($_GET["DATA"] as $data) {
                                        if ($data == "id") {
                                            $array[$data] = $file_id;
                                        }else if($data == "size"){
                                            $result = mysqli_query($mysqli, "SELECT path FROM `tasks.user_files` WHERE id = " . $file_id . ";");

                                            while($row = mysqli_fetch_assoc(($result))){
                                                $file_path = $row["path"];

                                                $array[$data] = human_filesize(filesize("../".$file_path));
                                            }
                                        } else {
                                            $result = mysqli_query($mysqli, "SELECT " . $data . " FROM `tasks.user_files` WHERE id = " . $file_id . ";");

                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $array[$data] = $row[$data];
                                                break;
                                            }
                                        }
                                    }

                                    $response["files"][] = $array;
                                }
                            } else {
                                $user_id = $_GET["USER_ID"];

                                $query = mysqli_query($mysqli, "SELECT task_id FROM `tasks.assigned` WHERE user_id = " . $user_id . " AND enabled = true;");

                                while ($row = mysqli_fetch_assoc($query)) {
                                    $task_id = $row["task_id"];

                                    $query1 = mysqli_query($mysqli, "SELECT id FROM `tasks.user_files` WHERE user_id = " . $user_id . " AND task_id = " . $task_id . ";");

                                    while ($row1 = mysqli_fetch_assoc($query1)) {
                                        $file_id = $row["id"];

                                        $array = array();

                                        foreach ($_GET["DATA"] as $data) {
                                            if ($data == "id") {
                                                $array[$data] = $file_id;
                                            }else if($data == "size"){
                                                $result = mysqli_query($mysqli, "SELECT path FROM `tasks.user_files` WHERE id = " . $file_id . ";");

                                                while($row = mysqli_fetch_assoc(($result))){
                                                    $file_path = $row["path"];

                                                    $array[$data] = human_filesize(filesize("../".$file_path));
                                                }
                                            } else {
                                                $result = mysqli_query($mysqli, "SELECT " . $data . " FROM `tasks.user_files` WHERE id = " . $file_id . ";");

                                                while ($row2 = mysqli_fetch_assoc($result)) {
                                                    $array[$data] = $row2[$data];
                                                    break;
                                                }
                                            }
                                        }

                                        $response["files"][$task_id][] = $array;
                                    }
                                }
                            }
                        } else {
                            if (isset($_GET["TASK_ID"])) {
                                $task_id = $_GET["TASK_ID"];

                                $query = mysqli_query($mysqli, "SELECT user_id FROM `tasks.user_files` WHERE task_id = " . $task_id . ";");

                                while ($row = mysqli_fetch_assoc($query)) {
                                    $user_id = $row['user_id'];

                                    $query1 = mysqli_query($mysqli, "SELECT id FROM `tasks.user_files` WHERE user_id = " . $user_id . " AND task_id = " . $task_id . ";");

                                    while ($row1 = mysqli_fetch_assoc($query1)) {
                                        $file_id = $row["id"];

                                        $array = array();

                                        foreach ($_GET["DATA"] as $data) {
                                            if ($data == "id") {
                                                $array[$data] = $file_id;
                                            }else if($data == "size"){
                                                $result = mysqli_query($mysqli, "SELECT path FROM `tasks.user_files` WHERE id = " . $file_id . ";");

                                                while($row = mysqli_fetch_assoc(($result))){
                                                    $file_path = $row["path"];

                                                    $array[$data] = human_filesize(filesize("../".$file_path));
                                                }
                                            } else {
                                                $result = mysqli_query($mysqli, "SELECT " . $data . " FROM `tasks.user_files` WHERE id = " . $file_id . ";");

                                                while ($row2 = mysqli_fetch_assoc($result)) {
                                                    $array[$data] = $row2[$data];
                                                    break;
                                                }
                                            }
                                        }

                                        $response["files"][$user_id][] = $array;
                                    }
                                }
                            } else {
                                $query = mysqli_query($mysqli, "SELECT user_id,task_id FROM `tasks.assigned` WHERE enabled = true;");

                                while ($row = mysqli_fetch_assoc($query)) {
                                    $user_id = $row['user_id'];
                                    $task_id = $row['task_id'];

                                    $query1 = mysqli_query($mysqli, "SELECT id FROM `tasks.user_files` WHERE user_id = " . $user_id . " AND task_id = " . $task_id . ";");

                                    while ($row1 = mysqli_fetch_assoc($query1)) {
                                        $file_id = $row["id"];

                                        $array = array();

                                        foreach ($_GET["DATA"] as $data) {
                                            if ($data == "id") {
                                                $array[$data] = $file_id;
                                            }else if($data == "id"){
                                                $result = mysqli_query($mysqli, "SELECT path FROM `tasks.user_files` WHERE id = " . $file_id . ";");

                                                while($row = mysqli_fetch_assoc(($result))){
                                                    $file_path = $row["path"];

                                                    $array[$data] = human_filesize(filesize("../".$file_path));
                                                }
                                            } else {
                                                $result = mysqli_query($mysqli, "SELECT " . $data . " FROM `tasks.user_files` WHERE id = " . $file_id . ";");

                                                while ($row2 = mysqli_fetch_assoc($result)) {
                                                    $array[$data] = $row2[$data];
                                                    break;
                                                }
                                            }
                                        }

                                        $response["files"][$task_id][$user_id][] = $array;
                                    }
                                }
                            }
                        }

                    }
                    http_response_code(200);
                } else if ($_GET['REQUEST'] == "user_info") {
                    if (empty($_GET["DATA"])) {
                        $_GET['DATA'] = array("id", "task_id", "user_id", "status", "comment");
                    }

                    if (isset($_GET['USER_ID'])) {
                        if (isset($_GET['TASK_ID'])) {
                            $user_id = $_GET["USER_ID"];
                            $task_id = $_GET['TASK_ID'];

                            foreach ($_GET["DATA"] as $data) {
                                if ($data == "task_id") {
                                    $response["info"][$data] = $task_id;
                                } else if ($data == "user_id") {
                                    $response["info"][$data] = $user_id;
                                } else if ($data == "id") {
                                    $result = mysqli_query($mysqli, "SELECT id FROM `tasks.assigned` WHERE user_id = " . $user_id . " AND task_id = " . $task_id . " AND enabled = true;");

                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $response["info"][$data] = $row["id"];
                                        break;
                                    }
                                } else if ($data == "status") {
                                    $result = mysqli_query($mysqli, "SELECT id FROM `tasks.assigned` WHERE user_id = " . $user_id . " AND task_id = " . $task_id . " AND enabled = true;");

                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $id = $row["id"];

                                        $result1 = mysqli_query($mysqli, "SELECT `status` FROM `tasks.status` WHERE assignment_id = " . $id . " ORDER BY `timestamp` DESC;");

                                        while ($row1 = mysqli_fetch_assoc($result1)) {
                                            $response["info"][$data] = $row1['status'];
                                            break;
                                        }
                                        break;
                                    }
                                } else if ($data == "comment") {
                                    $result = mysqli_query($mysqli, "SELECT id FROM `tasks.assigned` WHERE user_id = " . $user_id . " AND task_id = " . $task_id . " AND enabled = true;");

                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $id = $row["id"];

                                        $result1 = mysqli_query($mysqli, "SELECT `comment` FROM `tasks.status` WHERE assignment_id = " . $id . " ORDER BY `timestamp` DESC;");

                                        while ($row1 = mysqli_fetch_assoc($result1)) {
                                            $response["info"][$data] = $row1['comment'];
                                            break;
                                        }
                                        break;
                                    }
                                }
                            }
                        } else {
                            $user_id = $_GET["USER_ID"];

                            $query = mysqli_query($mysqli, "SELECT task_id,id FROM `tasks.assigned` WHERE user_id = ".$user_id." AND enabled = true;");

                            while($row = mysqli_fetch_assoc($query)){
                                $task_id = $row["task_id"];
                                $id = $row["id"];

                                $array = array();

                                foreach($_GET["DATA"] as $data){
                                    if ($data == "task_id") {
                                        $array[$data] = $task_id;
                                    } else if ($data == "user_id") {
                                        $array[$data] = $user_id;
                                    } else if ($data == "id") {
                                        $array[$data] = $id;
                                    } else if ($data == "status") {
                                        $result = mysqli_query($mysqli, "SELECT `status` FROM `tasks.status` WHERE assignment_id = " . $id . " ORDER BY `timestamp` DESC;");

                                        while ($row1 = mysqli_fetch_assoc($result)) {
                                            $array[$data] = $row1['status'];
                                            break;
                                        }
                                    } else if ($data == "comment") {
                                        $result = mysqli_query($mysqli, "SELECT `comment` FROM `tasks.status` WHERE assignment_id = " . $id . " ORDER BY `timestamp` DESC;");

                                        while ($row1 = mysqli_fetch_assoc($result)) {
                                            $array[$data] = $row1['status'];
                                            break;
                                        }
                                    }

                                    $response["info"][] = $array;
                                }
                            }
                        }
                    } else {
                        if (isset($_GET['TASK_ID'])) {
                            //TODO
                        } else {
                            //TODO
                        }
                    }
                    http_response_code(200);
                }else if($_GET["REQUEST"] == "assigned"){
                    if(isset($_GET["USER_ID"])){
                        if(isset($_GET["TASK_ID"])){
                            $user_id = $_GET["USER_ID"];
                            $task_id = $_GET["TASK_ID"];

                            $query = mysqli_query($mysqli, "SELECT id FROM `tasks.assigned` WHERE task_id = ".$task_id." AND user_id = ".$user_id." AND enabled = true;");

                            $response["assigned"] = false;
                            $response["user_id"] = $user_id;
                            $response["task_id"] = $task_id;
                            while($row = mysqli_fetch_assoc($query)){
                                $response["assigned"] = true;
                                break;
                            }
                            http_response_code(200);
                        }else{
                            $response["ERROR"][] = "TASK_ID must be provided";
                        }
                    }else{
                        $response["ERROR"][] = "USER_ID must be provided";
                    }
                }else{
                    $response["ERROR"][] = "Invalid Request";
                    http_response_code(400);
                }
            }else{
                $response["ERROR"][] = "Missing 'REQUEST'";
                http_response_code(400);
            }
        }else if($_SERVER["REQUEST_METHOD"] == "POST"){
            if(isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
                $_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));
            }

            if(isset($_GET["REQUEST"])){
                if($_GET["REQUEST"] == "task"){
                    if(isset($_POST["name"])){
                        if(isset($_POST["description"])){
                            if(isset($_POST["due"])){
                                if(isset($_POST["global"])){
                                    $name = $_POST["name"];
                                    $description = $_POST["description"];
                                    $due = date("Y-m-d H:i:s", strtotime($_POST["due"]));
                                    $global = $_POST["global"];

                                    mysqli_query($mysqli, "INSERT INTO tasks (name, description, due, extensions, global)  VALUES ('".$name."', '".$description."', '.$due.', '', ".$global.")");
                                    $query = mysqli_query($mysqli, "SELECT LAST_INSERT_ID();");
                                    while($row = mysqli_fetch_row($query)){
                                        $task_id = $row[0];
                                        $query1 = mysqli_query($mysqli, "SELECT id FROM users WHERE permission = 2");
                                        while($row1 = mysqli_fetch_assoc($query1)){
                                            $user_id = $row1["id"];
                                            mysqli_query($mysqli, "INSERT INTO `tasks.assigned` (user_id, task_id, enabled) VALUES (".$user_id.", ".$task_id.", ".$global.");");
                                            $query2 = mysqli_query($mysqli, "SELECT LAST_INSERT_ID();");
                                            while($row2 = mysqli_fetch_row($query2)){
                                                $assignment_id = $row2[0];
                                                mysqli_query($mysqli, "INSERT INTO `tasks.status` (assignment_id, `status`, `comment`) VALUES (".$assignment_id.", 'Assigned', '');");
                                            }
                                        }
                                        $response["task"]["id"] = $task_id;
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }else if($_GET["REQUEST"] == "assign"){
                    if(isset($_GET["USER_ID"])){
                        if(isset($_GET["TASK_ID"])){
                            $user_id = $_GET["USER_ID"];
                            $task_id = $_GET["TASK_ID"];

                            $global = $_POST['enabled'];

                            $query = mysqli_query($mysqli, "SELECT id FROM `tasks.assigned` WHERE task_id = ".$task_id." AND user_id = ".$user_id.";");

                            $response['DEBUG'][] = mysqli_num_rows($query);
                            $response['DEBUG'][] = mysqli_num_rows($query) > 0;

                            if(mysqli_num_rows($query) > 0) {
                                mysqli_query($mysqli, "UPDATE `tasks.assigned` SET enabled = " . $global . " WHERE task_id = " . $task_id . " AND user_id = " . $user_id . ";");
                                $response["update"] = true;
                            }else{
                                mysqli_query($mysqli, "INSERT INTO `tasks.assigned` (user_id, task_id, enabled) VALUES (".$user_id.", ".$task_id.", ".$global.");");
                                $query1 = mysqli_query($mysqli, "SELECT LAST_INSERT_ID();");
                                while($row1 = mysqli_fetch_row($query1)){
                                    $assignment_id = $row1[0];
                                    mysqli_query($mysqli, "INSERT INTO `tasks.status` (assignment_id, `status`, `comment`) VALUES (".$assignment_id.", 'Assigned', '');");
                                    break;
                                }

                                $response["update"] = true;
                            }
                        }
                    }
                }else if($_GET["REQUEST"] == "status"){
                    if(isset($_GET["USER_ID"])){
                        if(isset($_GET["TASK_ID"])){
                            $user_id = $_GET["USER_ID"];
                            $task_id = $_GET["TASK_ID"];

                            $query = mysqli_query($mysqli, "SELECT id FROM `tasks.assigned` WHERE task_id = ".$task_id." AND user_id = ".$user_id.";");

                            while($row = mysqli_fetch_assoc($query)){
                                $assignment_id = $row["id"];

                                mysqli_query($mysqli, "INSERT INTO `tasks.status` (assignment_id, `status`, `comment`) VALUES (".$assignment_id.", '".$_POST["status"]."', '".$_POST["comment"]."');");
                                $query1 = mysqli_query($mysqli, "SELECT LAST_INSERT_ID();");
                                while($row1 = mysqli_fetch_row($query1)){
                                    $response["status"]["status"] = $_POST["status"];
                                    $response["status"]["comment"] = $_POST["comment"];
                                    $response["status"]["assignment_id"] = $_POST["assignment_id"];
                                    $response["status"]["id"] = $row1[0];
                                }
                                break;
                            }
                        }
                    }
                }else if($_GET["REQUEST"] == "file"){
                    if(isset($_FILES["file"])){
                        if(isset($_GET["TASK_ID"])) {
                            $task_id = $_GET["TASK_ID"];

                            $target_dir = "uploads/";

                            $temp_path = $_FILES["file"]["tmp_name"];
                            $original_name = $_FILES["file"]["name"];
                            $size = $_FILES["file"]["size"];

                            if ($size <= $config["max_file_size"]) {
                                //TODO Check extension endings
                                /*$query = mysqli_query($mysqli, "SELECT extensions FROM `tasks` WHERE id = ".$task_id.";");

                                $extensions = array();
                                while($row = mysqli_fetch_assoc($query)){
                                    $extensions = explode(",", $row["extensions"]);
                                    break;
                                }

                                $format = false;
                                foreach($extensions as $ext){
                                    //$format = endsWith($);
                                }*/
                                if(isset($_GET["USER_ID"])) {
                                    $user_id = $_GET["USER_ID"];

                                    $query1 = mysqli_query($mysqli, "SELECT student_id FROM users WHERE id = ".$user_id.";");

                                    $student_id = "";
                                    while($row1 = mysqli_fetch_assoc($query1)){
                                        $student_id = $row1["student_id"];
                                        break;
                                    }

                                    if($student_id != ""){
                                        $target_dir .= $student_id."/";
                                        mkdir($target_dir);

                                        $query2 = mysqli_query($mysqli, "SELECT path FROM `tasks.user_files` WHERE user_id = ".$user_id." AND task_id = ".$task_id.";");

                                        //Example Name: 024633s_15-16_1_001.txt
                                        $files = array();
                                        while($row2 = mysqli_fetch_assoc($query2)){
                                            $files[] = $row2["path"];
                                        }


                                        $nameEnd = "";
                                        for($i = 1; $i < 1000; $i++){
                                            $set = false;
                                            foreach($files as $file){
                                                $name = explode(".", $file);
                                                if(endsWith($name[0], $i."")){
                                                    $set = true;
                                                    break;
                                                }
                                            }
                                            if(!$set){
                                                if($i < 10){
                                                    $nameEnd = "00".$i;
                                                    break;
                                                }else if($i < 100){
                                                    $nameEnd = "0".$i;
                                                    break;
                                                }else{
                                                    $nameEnd = $i;
                                                    break;
                                                }
                                            }
                                        }

                                        $file_type = pathinfo($original_name, PATHINFO_EXTENSION);

                                        $target_file = $target_dir . $student_id."_".$config["current_year"]."_".$task_id."_".$nameEnd.".".$file_type;

                                        if(move_uploaded_file($temp_path, "../".$target_file)){
                                            mysqli_query($mysqli, "INSERT INTO `tasks.user_files` (user_id, task_id, path, original_name) VALUES (".$user_id.", ".$task_id.", '".$target_file."', '".$original_name."');");
                                            $query3 = mysqli_query($mysqli, "SELECT LAST_INSERT_ID();");
                                            while($row3 = mysqli_fetch_row($query3)){
                                                $id = $row3[0];
                                                $response["file"]["id"] = $id;
                                                $response["file"]["user_file"] = $user_id;
                                                $response["file"]["task_file"] = $task_id;
                                                $response["file"]["path"] = $target_file;
                                                $response["file"]["original_name"] = $original_name;
                                                $response["file"]["size"] = human_filesize($size);
                                                break;
                                            }
                                        }else{
                                            $response["ERROR"][] = "Something went wrong while uploading";
                                            http_response_code(400);
                                        }
                                    }else{
                                        $response["ERROR"][] = "Invalid USER_ID";
                                        http_response_code(400);
                                    }
                                }else{
                                    //TODO Add support for uploading task_files
                                    //File saving location uploads/task_<id>/
                                    //File naming
                                    if(isset($_FILES["file"])) {
                                        if (isset($_GET["TASK_ID"])) {
                                            $task_id = $_GET["TASK_ID"];

                                            $target_dir = "uploads/task_".$task_id."/";

                                            $temp_path = $_FILES["file"]["tmp_name"];
                                            $original_name = $_FILES["file"]["name"];
                                            $size = $_FILES["file"]["size"];

                                            $file_type = pathinfo($original_name, PATHINFO_EXTENSION);

                                            $target_file = $target_dir.$original_name;

                                            if(!file_exists("../".$target_dir)){
                                                mkdir("../".$target_dir, 0777, true);
                                            }

                                            if(move_uploaded_file($temp_path, "../".$target_file)){
                                                mysqli_query($mysqli, "INSERT INTO  `tasks.files` (task_id, path, `name`, description) VALUES (".$task_id.", '".$target_file."', '".$_POST["name"]."', '".$_POST["description"]."');");
                                                $query = mysqli_query($mysqli, "SELECT LAST_INSERT_ID();");
                                                while($row = mysqli_fetch_row($query)){
                                                    $response["file"]["id"] = $row[0];
                                                    $response["file"]["task_id"] = $task_id;
                                                    $response["file"]["path"] = $target_file;
                                                    $response["file"]["name"] = $_POST["name"];
                                                    $response["file"]["description"] = $_POST["description"];
                                                    break;
                                                }
                                            }else{
                                                $response["DEBUG"][] = file_exists("../".$target_dir);
                                                $response["DEBUG"][] = "../".$target_dir;
                                                $response["ERROR"][] = "Something went wrong while uploading";
                                                http_response_code(400);
                                            }

                                        }else{
                                            $response["ERROR"][] = "TASK_ID required";
                                            http_response_code(400);
                                        }
                                    }else{
                                        $response["ERROR"][] = "File must be uploaded";
                                        http_response_code(400);
                                    }
                                }


                            } else {
                                $response["ERROR"][] = "File must be less then " . human_filesize($config["max_file_size"]);
                                http_response_code(400);
                            }
                        }else{
                            $response["ERROR"][] = "TASK_ID is required";
                            http_response_code(400);
                        }
                    }else{
                        $response["ERROR"][] = "File must be uploaded";
                        http_response_code(400);
                    }
                }

            }
            http_response_code(200);
        }else if($_SERVER["REQUEST_METHOD"] == "PUT"){
            if(isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
                $_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));
            }

            if(isset($_GET["REQUEST"])){
                if($_GET["REQUEST"] == "task_info"){
                    if(isset($_GET["TASK_ID"])){
                        $response["task"][]= array();
                        $task_id = $_GET['TASK_ID'];

                        foreach($_POST as $key => $value){
                            if($key == "due"){
                                $value = date("Y-m-d H:i:s", strtotime($value));

                                mysqli_query($mysqli, "UPDATE `tasks` SET `due` = '".$value."' WHERE id = ".$task_id.";");

                                $response["task"]["due"] = $value;
                            }else {
                                mysqli_query($mysqli, "UPDATE `tasks` SET `" . $key . "` = '".$value."' WHERE id = ".$task_id.";");

                                $response["task"][$key] = $value;
                            }
                        }
                        http_response_code(200);
                    }else {
                        $response["ERROR"][] = "TASK_ID is required";
                        http_response_code(400);
                    }
                }else if($_GET["REQUEST"] == "assign"){
                    if(isset($_GET["TASK_ID"])){
                        if(isset($_GET["USER_ID"])){
                            if(isset($_POST["enabled"])) {
                                $user_id = $_GET["USER_ID"];
                                $task_id = $_GET["TASK_ID"];

                                $global = $_POST['enabled'];

                                $query = mysqli_query($mysqli, "SELECT id FROM `tasks.assigned` WHERE task_id = " . $task_id . " AND user_id = " . $user_id . ";");

                                if (mysqli_num_rows($query) > 0) {
                                    mysqli_query($mysqli, "UPDATE `tasks.assigned` SET enabled = " . $global . " WHERE task_id = " . $task_id . " AND user_id = " . $user_id . ";");
                                    $response["update"] = true;
                                    http_response_code(200);
                                } else {
                                    mysqli_query($mysqli, "INSERT INTO `tasks.assigned` (user_id, task_id, enabled) VALUES (" . $user_id . ", " . $task_id . ", " . $global . ");");
                                    $query1 = mysqli_query($mysqli, "SELECT LAST_INSERT_ID();");
                                    while ($row1 = mysqli_fetch_row($query1)) {
                                        $assignment_id = $row1[0];
                                        mysqli_query($mysqli, "INSERT INTO `tasks.status` (assignment_id, `status`, `comment`) VALUES (" . $assignment_id . ", 'Assigned', '');");
                                        break;
                                    }

                                    $response["update"] = true;
                                    http_response_code(200);
                                }
                            }else{
                                $response["ERROR"][] = "enabled required";
                                http_response_code(400);
                            }
                        }else{
                            $response["ERROR"][] = "USER_ID required";
                            http_response_code(400);
                        }
                    }else{
                        $response["ERROR"][] = "TASK_ID required";
                        http_response_code(400);
                    }
                }else{
                    $response["ERROR"][] = "Invalid REQUEST";
                    http_response_code(400);
                }
            }else{
                $response["ERROR"][] = "REQUEST is required";
                http_response_code(400);
            }
        }else if($_SERVER["REQUEST_METHOD"] == "DELETE"){
            if(isset($_GET["FILE_ID"])){
                if(isset($_GET['FILE_TYPE'])){
                    if($_GET["FILE_TYPE"] == "user"){
                        $file_id = $_GET["FILE_ID"];

                        $query = mysqli_query($mysqli, "SELECT path FROM `tasks.user_files` WHERE id = ".$file_id.";");

                        while($row = mysqli_fetch_assoc($query)){
                            unlink("../".$row["path"]);
                            break;
                        }

                        mysqli_query($mysqli, "DELETE FROM `tasks.user_files` WHERE id = ".$file_id.";");
                    }else if($_GET["FILE_TYPE"] == "task"){
                        $file_id = $_GET["FILE_ID"];

                        $query = mysqli_query($mysqli, "SELECT path FROM `tasks.files` WHERE id = ".$file_id.";");

                        while($row = mysqli_fetch_assoc($query)){
                            unlink("../".$row["path"]);
                            break;
                        }

                        mysqli_query($mysqli, "DELETE FROM `tasks.files` WHERE id = ".$file_id.";");
                    }else{
                        $response['ERROR'][] = "Invalid FILE_TYPE";
                        http_response_code(400);
                    }
                }
                //Will Compleletly delete anything associated with the task_id.
            }else if(isset($_GET["TASK_ID"])){
                if(isset($_GET["USER_ID"])){
                    $task_id = $_GET["TASK_ID"];
                    $user_id = $_GET["USER_ID"];

                    $query = mysqli_query($mysqli, "SELECT id FROM `tasks.assigned` WHERE task_id = ".$task_id." AND user_id = ".$user_id.";");

                    while($row1 = mysqli_fetch_assoc($query)){
                        mysqli_query($mysqli, "DELETE FROM `tasks.status` WHERE assignment_id = ".$row1["id"].";");
                    }

                    $query = mysqli_query($mysqli, "SELECT path FROM `tasks.user_files` WHERE task_id = ".$task_id.";");

                    while($row1 = mysqli_fetch_assoc($query)){
                        unlink("../".$row1["path"]);
                    }

                    mysqli_query($mysqli, "DELETE FROM `tasks.assigned` WHERE task_id = ".$task_id." AND user_id = ".$user_id.";");
                    mysqli_query($mysqli, "DELETE FROM `tasks.user_files` WHERE task_id = ".$task_id." AND user_id = ".$user_id.";");
                }else{
                    $task_id = $_GET["TASK_ID"];

                    $query = mysqli_query($mysqli, "SELECT id FROM `tasks.assigned` WHERE task_id = ".$task_id.";");

                    while($row1 = mysqli_fetch_assoc($query)){
                        mysqli_query($mysqli, "DELETE FROM `tasks.status` WHERE assignment_id = ".$row1["id"].";");
                    }

                    $query = mysqli_query($mysqli, "SELECT path FROM `tasks.user_files` WHERE task_id = ".$task_id.";");

                    while($row1 = mysqli_fetch_assoc($query)){
                        unlink("../".$row1["path"]);
                    }

                    $query = mysqli_query($mysqli, "SELECT path FROM `tasks.files` WHERE task_id = ".$task_id.";");

                    while($row1 = mysqli_fetch_assoc($query)){
                        unlink("../".$row1["path"]);
                    }

                    mysqli_query($mysqli, "DELETE FROM `tasks.assigned` WHERE task_id = ".$task_id.";");
                    mysqli_query($mysqli, "DELETE FROM `tasks.user_files` WHERE task_id = ".$task_id.";");
                    mysqli_query($mysqli, "DELETE FROM `takss.files` WHERE task_id = ".$task_id.";");
                    mysqli_query($mysqli, "DELETE FROM `tasks` WHERE id = ".$task_id.";");
                }
            }
            http_response_code(200);
        }
    }else{
        $response["ERROR"][] = "Missing 'TOKEN'";
        http_response_code(400);
    }
}
mysqli_close($mysqli);
echo json_encode($response);