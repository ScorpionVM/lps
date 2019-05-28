<?php
    session_start();

    if(!isset($_SESSION["year_data"])){
        header("Location: /admin.php");
    }

    foreach ($_SESSION["year_data"] as $key => $value) {
        $filename = $key;
        $years = $value;
    }

    include 'functions.php';

    $Array_quest = get_json_contents("./question.json")["a"];
    $Array_grupe = get_json_contents($filename); 

    $table = ''; $table_data = array();

    if(count($Array_grupe) > 0){

        foreach ($Array_quest as $key => $subArray) {
            if(!in_array($subArray["name"], array("quest18","quest19", "quest20"))){
                //echo "<hr size=10px color='red'>"; print_r($subArray); echo "<hr size=10px color='red'>";
                $question = get_question($subArray, $Array_grupe);
                //print_r($question); echo "<hr>";
                $table_data[$key] = $question;
            }
        }

        $table = "<table border class='mini stats_table'>";

        foreach ($table_data as $key => $value) {
            $caption = "<tr>
                    <td id='capt' colspan=".(count($value["options"]["a"]["grupe_list"])+2).">".($key+1).". ".$value["name"]."</td></tr>";
            
                    $grupe_line = "<tr><td><br></td>";
            foreach ($value["options"]["a"]["grupe_list"] as $_key => $_value) {
                $grupe_line = $grupe_line . "<td class='tcenter'>".$_key."</td>";
            }
            $grupe_line = $grupe_line . "<td class='tcenter'>Totals</td></tr>";
            
            $answer = ""; $max_value = get_max_value($value["options"]);
            foreach ($value["options"] as $_key => $_value) {
                $_class = get_class_per_value($_value["totals"], $max_value);
                
                $line = "<tr $_class><td>".$_value["name"]."</td>";
                foreach ($_value["grupe_list"] as $grupa => $count){
                    $line = $line . "<td>".$count."</td>";
                }
                $answer = $answer . $line . "<td>".$_value["totals"]."</td><tr>";
            }
            $table = $table . $caption . $grupe_line . $answer;
        }
        $table = $table . "</table>";
    } else {
        $table = 'Not found any data!';
    }

    if(isset($_POST["back_to_const"])){
        header("Location: /constructor.php");
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>main</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container" style="width: 80%">
        <h2 id="header">Admin panel :: <?= $_SESSION["admin_access"]; ?></h2>
        <h4 id='header' class='tcenter'>Anul universitar :: <?= $years; ?></h4><hr>
        <form action='' method="POST">
            <button type='submit' name='back_to_const'>Back</button>
        </form>
        <?php echo $table; ?>
        <form action='' method="POST">
            <button type='submit' name='back_to_const'>Back</button>
        </form>
    </div>
</body>
</html>