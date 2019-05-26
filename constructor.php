<?php
    session_start();

    if(!isset($_SESSION["admin_access"])){
        session_destroy();
        header("Location: /admin.php");
    }

    include 'functions.php';

    sort_config();
    create_access_list();

    // get groups list

    $grupe = new FilesystemIterator("./grupe/", FilesystemIterator::SKIP_DOTS);
    $lista_ani = array();

    foreach ($grupe as $key => $value) {
        $an_universitar = explode('.', explode('/',$value)[2])[0];
        $full_path = $value;
        $temp = array('an' => $an_universitar, 'path_file' => $full_path);
        array_push($lista_ani, $temp); sort($lista_ani);
    };

    $selector_an_universitar = "<form action='' method='POST'>
        <table><tr><td><select class='selector_an' name='an_univ' required>
        <option value=''>Select years</option>";
    
    $sau = '';

    foreach ($lista_ani as $key => $value) {
        $sau = $sau . "<option value='".$value["path_file"]."'>".$value["an"]."</option>";
    }

    $selector_an_universitar = $selector_an_universitar . $sau . "</select></td>
        <td><button type='submit' name='stats'>View stats</button></td>
        <td><button type='submit' name='recom'>View recom</button></td>
    </tr></table></form>";

    if(isset($_POST["stats"])){
        $_SESSION["year_data"] = array($_POST["an_univ"] => explode('.', explode('/',$_POST["an_univ"])[2])[0]);
        header("Location: /main.php");
    } else if(isset($_POST["recom"])){
        $_SESSION["year_data"] = array($_POST["an_univ"] => explode('.', explode('/',$_POST["an_univ"])[2])[0]);
        header("Location: /custom_answer.php");    
    }

    // add grups to db

    $time_yr = get_time();

    if(!isset($_SESSION["per_year"])){
        $_SESSION["per_year"] = $time_yr;
    }

    if(isset($_POST["select_year"])){
        $_SESSION["per_year"] = $_POST["year"];
    }

    $selector_year_for_show = "<form action='' method='POST'>
        <table><tr><td><select class='selector_an' name='year' required>
        <option value='' disabled>Select years</option>";
    
    $sau = '';

    foreach ($lista_ani as $key => $value) {
        if($value["an"] == $_SESSION["per_year"]){
            $selected = "selected";
        } else {
            $selected = '';
        }

        $sau = $sau . "<option value='".$value["an"]."' $selected>".$value["an"]."</option>";
    }

    concate($selector_year_for_show, [$sau,"</select></td>
        <td><button type='submit' name='select_year'>Select</button></td>
    </tr></table></form>"]);

    $group_list = get_json_contents("./grupe/".$_SESSION["per_year"].".json");
    $config_list = get_json_contents("./config.json");

    $_SESSION["edit_status"] = FALSE;
    $btn_stat = "style='display: none;'";
    $disabled_add = '';

    if(isset($_POST["edit_table"]) and $_POST["edit_table"] != ''){
        $_SESSION["edit_status"] = TRUE;
        $btn_stat = "style='display: initial;'";
        $disabled_add = 'disabled';
    }

    if(isset($_POST["cancel"]) and $_POST["cancel"] != ''){
        $_SESSION["edit_status"] = FALSE;
        $btn_stat = "style='display: none;'";
        $disabled_add = '';
        $remove_checker = FALSE;
        $remove_column = '';
    }

    
    if(isset($_POST["remove"]) and $_POST["remove"] != ''){
        $remove_column = "<td>Remove</td>";
        $remove_checker = TRUE;
        $btn_stat = "style='display: initial;'";
    } else {
        $remove_column = '';
        $remove_checker = FALSE;
    }

    if(isset($_POST["execute"]) and $_POST["execute"] != ''){
        $copy = $config_list["grupe"][$_SESSION["per_year"]];
        foreach ($copy as $key => $value) {
            if(isset($_POST[$value["code"].'_remove'])){
                unset($config_list["grupe"][$_SESSION["per_year"]][$key]);
            }
            put_json_contents("./config.json", $config_list);
        }
    }

    if(isset($_POST["update_table"]) and $_POST["update_table"] != ''){
        $copy = $config_list["grupe"][$_SESSION["per_year"]];
        $obj = array();

        foreach ($copy as $key => $value) {
            if(isset($_POST[$value["code"].'_text'])){
                change_value($obj["code"], $value["code"], $_POST[$value["code"].'_text']);
                change_value($obj["nrst"], $value["nrst"], $_POST[$value["code"].'_number']);
                change_value($obj["access"]["date"], $value["access"]["date"], $_POST[$value["code"].'_date']);
                change_value($obj["access"]["time"]["start"], $value["access"]["time"]["start"], strtotime($_POST[$value["code"].'_time_start']));
                change_value($obj["access"]["time"]["end"], $value["access"]["time"]["end"], strtotime($_POST[$value["code"].'_time_end']));
                unset($config_list["grupe"][$_SESSION["per_year"]][$key]);
                
                $config_list["grupe"][$_SESSION["per_year"]][$key] = $obj;
                put_json_contents("./config.json", $config_list);
            }
        }
    }

    function upload_data_to_table(){
        global $config_list;
        
        $obj = array();
        $obj["code"] = $_POST["cod_grupa"];
        $obj["nrst"] = $_POST["nr_stud"];
        $obj["access"]["date"] = $_POST["date"];
        $obj["access"]["time"]["start"] = strtotime($_POST["time_start"]);
        $obj["access"]["time"]["end"] = strtotime($_POST["time_end"]);

        //print_r($obj);
        if(!in_array($obj, $config_list["grupe"][$_SESSION["per_year"]])){
            $config_list["grupe"][$_SESSION["per_year"]][count($config_list["grupe"][$_SESSION["per_year"]])] = $obj;
            put_json_contents("./config.json", $config_list);
        }
    } 

    if(isset($_POST["upload"]) and $_POST["upload"] != ''){
        if(count($config_list["grupe"][$_SESSION["per_year"]]) > 0){
            foreach($config_list["grupe"][$_SESSION["per_year"]] as $key => $value){
                if($value["code"] != $_POST["cod_grupa"]){
                    upload_data_to_table();
                    break;
                }
            }
        } else {
            upload_data_to_table();
        }
    }

    $add_value = "<div id='myModal' class='modal'>
        <div class='modal-content'>
            <span class='close'>&times;</span>
            <h4>Add new line of data</h4><hr>
            <form action='' method='POST'>
                <p id='data_exists' style='display: none; margin-left: 10px;' class='tcenter'>Error: Data exist!</p>
                <label>Cod grupa</label><input type='text' name='cod_grupa' onkeyup='data_exists(this)' required><br>
                <label>Nr de studenti</label><input type='number' min=0 name='nr_stud' required><br>
                <label>Data examen</label><input type='date' name='date' required><br>
                <p>Timpul de access:</p>
                <label>Inceput</label><input type='time' name='time_start' required><br>
                <label>Sfarsit</label><input type='time' name='time_end' required><hr>
                <div class='btn_poz'>
                    <button type='submit' name='upload' id='upload_modal' value='#'>Upload</button>
                </div>                
            </form>
            <form action='' method='POST'>
                <button type='submit' name='cancel'>Cancel</button>
            </form>
        </div>
    </div>";

    $form = "<form action='' method='POST'>"; $form_end = "</form>";
    $add_btn = " <button type='submit' class='btn_submit' name='add_table' id='myBtn' value='#' $disabled_add>Add line</button> ";
    $edit_btn = "<button type='submit' class='btn_submit' name='edit_table' value='#'>Edit</button> ";
    $update_btn = " <button type='submit' class='btn_submit' name='update_table' value='#' $btn_stat>Update</button> ";
    $remove_btn = " <button type='sumbit' class='btn_submit' name='remove' value='#' $btn_stat>Remove</button>";
    $execute_btn = " <button type='sumbit' class='btn_submit' name='execute' value='#'>Execute</button>";
    $cancel_btn = " <button type='submit' class='btn_submit' name='cancel' value='#' $btn_stat>Cancel</button> ";

    if(!$remove_checker){
        concate($btns_ex, [$edit_btn, $remove_btn, $update_btn, $cancel_btn]);
    } else {
        concate($btns_ex, [$execute_btn, $cancel_btn]);
    }

    if(count($config_list["grupe"][$_SESSION["per_year"]]) != 0) {
        $table_group = "<div class='tcenter'>
            <caption style='margin-bottom: 5px'>Lista grupe, anul de studii ".$_SESSION["per_year"]."</caption>
            <table border class='tab_list_grupe'>
            <tr>
                <td>Groups</td>
                <td>Count of students</td>
                <td>Exam date</td>
                <td colspan=2>Access time</td>
                $remove_column
            </tr>";

        $line = '';
        
        foreach ($config_list["grupe"][$_SESSION["per_year"]] as $key => $value) {
            $_val = "<tr>
                <td class='groups_code'>". transform_tag($value["code"], $value["code"], 'text', '',  $_SESSION["edit_status"]) ."</td>
                <td>". transform_tag($value["nrst"], $value["code"], 'number', '', $_SESSION["edit_status"]) ."</td>
                <td>". transform_tag($value["access"]["date"], $value["code"], 'date', '',  $_SESSION["edit_status"]) ."</td>
                <td>". transform_tag(date("h:i A", $value["access"]["time"]["start"]), $value["code"], 'time', 'start',  $_SESSION["edit_status"]) ."</td>
                <td>". transform_tag(date("h:i A", $value["access"]["time"]["end"]), $value["code"], 'time', 'end',  $_SESSION["edit_status"]) ."</td>
                ". return_box($remove_checker, $value["code"]) ."
            </tr>";
            
            concate($line, $_val);
        }

        concate($selector_edit_group, [$selector_year_for_show, $form, $table_group, $line, "</table></div>", $btns_ex, $form_end, $add_btn, $add_value]);
    
    } else {
        $div = "<div class='not_found'>For years ".$_SESSION["per_year"].", data not found!</div>";    
        concate($selector_edit_group, [$selector_year_for_show, $div, $add_btn, $add_value]);
    }

    //delete files

    if(isset($_POST["delete_files"]) and $_POST["delete_files"] != ''){
        $filename = $_POST["select_del_year"].".json";
        
        $path = "./grupe/"; $backup = "./backup/";

        copy($path.$filename, $backup.$filename.".bak");        
        unlink($path.$filename);
    }

    $del_form = "<form action='' method='POST'>";
    $del_form_end = "</form>";
    $del_submit_btn = "<button type='submit' name='delete_files' value='#'>Delete</button>";
    $del_select_year = "<select class='selector_an' name='select_del_year' required>
        <option value=''>Select year</option>";

    foreach ($lista_ani as $key => $value) {
        concate($del_select_year, "<option value='".$value["an"]."'>".$value["an"]."</option>");
    }

    concate($del_select_year, ["</select>", $del_submit_btn]);
    concate($delete_files, [$del_form, $del_select_year, $del_form_end]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Constructor</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="modal.css">
</head>
<body>
    <div class="container" style="width: 1000px">
        <h2 id="header">Admin panel :: <?= $_SESSION["admin_access"]; ?></h2><hr size=5px>
        <?php 
            hr_text("Adaugati si modificati lista de grupe per an de studiu");
            echo $selector_edit_group; 

            hr_text("Statistica si recomandarile per grupe");
            echo $selector_an_universitar;

            hr_text("Sterge raspunsurile per an de studiu si/sau grupa");
            echo $delete_files;
        ?>
    </div>
</body>
<script src="modal.js"></script>
<script src="script.js"></script>
</html>