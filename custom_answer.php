<?php
    session_start();

    if(!isset($_SESSION["year_data"])){
        header("Location: /admin.php");
    }

    if(isset($_POST["back"])){
        header("Location: /constructor.php");
    }

    foreach ($_SESSION["year_data"] as $key => $value) {
        $filename = $key;
        $years = $value;
    }

    include 'functions.php';

    $Array_quest = get_json_contents("./question.json")["a"];
    $Array_grupe = get_json_contents($filename); 

    //print_r($Array_grupe);

    $line_table = '';
    foreach ($Array_grupe as $key => $value) {
        $line_table = $line_table . "<td><button onclick='show_recom($key)'>$key</button></td>";
    }

    $tables = '';
    foreach ($Array_quest as $key => $value) {
        if($value["tag"] == 'text'){
            $question = $value["question"];
            $quest_nr = $value["name"];

            foreach ($Array_grupe as $_key => $_value) {
                $tables = $tables . "<table border class='mini per_grupa hidden $_key'>";
                $part = "<tr id='_key'><td class='tcenter'>$_key</td><td>$question</td></tr>";
                foreach ($_value as $x_key => $x_value) {
                    if($x_value[$quest_nr] != ''){
                        $part = $part . "<tr><td class='brpd' colspan=2>".$x_value[$quest_nr]."</td></tr>";
                    }
                }
                $tables = $tables . $part . "</table>";
            }
            
            
        }
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
    <div class="container" style='width: 80%'>
        <h2 id="header">Admin panel :: <?= $_SESSION["admin_access"]; ?></h2>
        <h4 id='header' class='tcenter'>Anul universitar :: <?= $years; ?></h4><hr>
        <p class='tcenter'>Select for show answers:</p>
        <table border class="tab_recom">
            <tr>
                <?= $line_table; ?>
            </tr>
        </table>
        <?= $tables; ?>
        <form action='' method='POST'>
            <button type='submit' name='back'>Back</button>
        </form>

    </div>
    
</body>
<script src="script.js"></script>
</html>