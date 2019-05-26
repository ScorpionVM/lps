<?php
    //get utc time + 3h
    function utc_time($format, $td=NULL){
        switch($td){
            case 't':
                return date($format, strtotime(gmdate($format))+10800);
            break;
            
            case 'd':
                return gmdate($format);
            break;

            default:
                return gmdate($format);
            break;
        }
    }
    
    //function to determin current interval year for current date
    function get_time(){
        $y = intval(utc_time('Y','d'));
        $m = intval(utc_time('m','d'));
        if($m >= 1){
            $t = $y-1;
            $time = "$t-$y";
        } else {
            $t = $y+1;
            $time = "$y-$t";
        }
        return $time;
    }

    //function to get content from json file and decode to an php array
    function get_json_contents($filename){
        if(file_exists($filename)){
            $Json = file_get_contents($filename, true);
            return json_decode($Json, true);
        } else {
            $oldObj = json_encode(array(), true);
            file_put_contents($filename,$oldObj);
            return get_json_contents($filename);
        }
    }

    function put_json_contents($filename, $obj){
        $js = json_encode($obj, true);
        file_put_contents($filename, $js);
    }

    function get_data_per_grupa($quest, $option, $Array_grupe){
        $totals = 0; $lists = array();
        foreach ($Array_grupe as $key => $value) {
            $lists[$key] = 0;
            foreach ($value as $_key => $_value) {
                foreach ($_value as $_1_key => $_1_value) {
                    if($quest == $_1_key){
                        if(gettype($_1_value) == 'string'){
                            if($option == $_1_value){
                                $lists[$key]++;
                            }
                        } else {
                            if(is_array($_1_value)){
                                foreach ($_1_value as $_2_value) {
                                    if($option == $_2_value){
                                        $lists[$key]++;
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        foreach ($lists as $key => $value) {
            $totals = $totals + $value;
        }

        return array(
            "grupe" => $lists,
            "total" => $totals
        );
    }

    function get_max_value($array){
        $max = 0;
        foreach ($array as $key => $value) {
            if($value["totals"] > $max){
                $max = $value["totals"];
            }
        }
        return $max;
    } 

    function get_class_per_value($value, $verif){
        if($value == 0){
            $_class = "class='zero_value_tt'";
        } else if($value == $verif){
            $_class = "class='max_value_tt'";
        } else {
            $_class = '';
        }
        
        return $_class;
    }

    function sort_config(){
        $list_s = array(); 
        $config_list = get_json_contents("./config.json");
        
        foreach ($config_list["grupe"] as $key => $value) {
            $list_e = array();

            foreach ($value as $_key => $_value) {
                $list_e[$_value["code"]] = $_value;
            }
            ksort($list_e);

            $list_s[$key] = array();

            foreach ($list_e as $_key => $_value) {
                array_push($list_s[$key], $_value);
            }
        }
        
        $config_list["grupe"] = $list_s;
        put_json_contents("./config.json", $config_list);
    }

    function create_access_list(){
        $time = get_time();
        $config_list = get_json_contents("./config.json");
        $access = array();
        foreach ($config_list["grupe"][$time] as $key => $value) {
            $sub = $value["access"];
            if(!isset($access[$sub["date"]])){
                $access[$sub["date"]] = array();
            }
            array_push($access[$sub["date"]], $sub["time"]);
        }

        $config_list["access"] = $access;
        put_json_contents("./config.json", $config_list);
    }

    function get_question($array, $Array_grupe){
        $name = $array["question"];
        $quest = $array["name"];
        $option = array();
        foreach ($array as $key => $value) {
            if(!in_array($key, array("question","name","nr","multiselect","type","tag"))){  
                
                $lista = get_data_per_grupa($quest, $key, $Array_grupe);
                
                $option[$key] = array(
                    "name" => $value,
                    "grupe_list" => $lista["grupe"],
                    "totals" =>  $lista["total"]
                );
            }
        }

        $data = array("name" => $name, "options" => $option);
        return $data;
    }

    function concate(&$elem, $string){
        if(is_array($string)){
            foreach ($string as $key => $value) {
                $elem = $elem . $value;
            }
        } else {
            $elem = $elem . $string;
        }
    }

    function hr_text($element){
        echo "<table style='width: 100%; table-layout: fixed;' class='tcenter'><tr>
                <td><hr class='hrred'></td>
                <td colspan=2><hr class='hrred'><code>".$element."</code><hr class='hrred'></td>
                <td><hr class='hrred'></td>
            </tr></table>";
    }

    function time_value($start, $current_time, $end){
        if($start <= $current_time and $current_time <= $end){
            return TRUE; 
        } else {
            return FALSE;
        }
    }

    function checktime_access(){
        create_access_list();

        $current_date = utc_time("Y-m-d",'d');
        $list = get_json_contents("./config.json")["access"];
        $current_time = strtotime(utc_time("H:i",'t'));

        if(!isset($list[$current_date])){
            //echo "Wrong date ".$current_date;
            if($_SERVER['REQUEST_URI'] != '/server_error.php'){
                $_SESSION["msg_error"] = "Time is over!"; //"Wrong connection!";
                header("Location: /server_error.php");
            }
        } else {
            $tf = FALSE;

            $list = $list[$current_date];

            if(count($list) == 1){
                //echo "equal 1 <hr>"; print_r($list);
                $tf = time_value($list[0]["start"], $current_time, $list[0]["end"]);
            
            } else if (count($list) > 1) {
                //echo "greater then 1 <hr>"; print_r($list);
                foreach ($list as $key => $value) {
                    $tf = time_value($value["start"], $current_time, $value["end"]);
                    if($tf == TRUE){
                        break;
                    }
                }
            } else {
                //echo "else <hr>"; print_r($list);
                if($_SERVER['REQUEST_URI'] != '/server_error.php'){
                    $_SESSION["msg_error"] = "Time is over!"; //"Wrong connection!";
                    header("Location: /server_error.php");
                }
            }

            //echo "<br>". $tf;
            if($tf == TRUE){
                if($_SERVER['REQUEST_URI'] != '/'){
                    header("Location: /");
                }
            } else {
                if($_SERVER['REQUEST_URI'] != '/server_error.php'){
                    $_SESSION["msg_error"] = "Time is over!"; //"Wrong connection!";
                    header("Location: /server_error.php");
                }
            }
        }
    }

    function transform_tag($value, $uid, $type, $se, $status){
        if($status){
            switch ($type) {
                case 'number':
                    $elem = "<input type='number' min=0 name='".$uid."_number' value='$value'>"; 
                    break;
                
                case 'date':
                    $elem = "<input type='date' name='".$uid."_date' value='$value'>";                     
                    break;

                case 'time':
                    $elem = "<input type='time' name='".$uid."_time_$se' value='". date("H:i", strtotime($value)) ."'>";                     
                    break;

                case 'text':
                    $elem = "<input type='text' name='".$uid."_text' value='$value'>"; 
                break;
            }
            return $elem;
        } else {
            return $value;
        }
    }

    function change_value(&$value, $default, $update){
        if($update != ''){
            $value = $update;
        } else {
            $value = $default;
        }
    }

    function return_box($status, $uid){
        if($status){
            return "<td><input type='checkbox' name='".$uid."_remove'></td>";
        } else {
            return '';
        }
    }
?>