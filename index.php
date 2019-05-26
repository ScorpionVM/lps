<?php
    session_start();

    include 'functions.php';

    checktime_access();

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $_SESSION["grupa"] = $_POST["grupa"];
        header("Location: /sondaj.php");
    }

    $_SESSION["timestr"] = get_time();

    $obj = get_json_contents("./config.json")["grupe"][$_SESSION["timestr"]];

    $options = '';
    foreach ($obj as $key => $value) {
        $options = $options . "<option value='".$value["code"]."'>".$value["code"]."</option>";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>LPS sondaj</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>
    <div class='container' style='width: 500px'>
        <h4 class="tcenter">CHESTIONAR PRIVIND EVALUAREA STUDENȚILOR ASUPRA ACTIVITĂȚII DIDACTICE</h4><hr size=2px>
        <pre>
    <b>Cursul:</b> Limbaje de programare structurată (LPS)
    <b>Profesor:</b> prof. dr. ing. Dănuț ZAHARIEA
    <b>Anul universitar:</b> <?= $_SESSION["timestr"]; ?>
        </pre>

        <form action="" method='POST' class='tcenter'>
            <select  name="grupa" id="grupa" required>
                <option value="">Selectati grupa</option>
                <?php echo $options; ?>
            </select>
            <button id="go">Start</button>
        </form>
        <div class='author'>Developed by Magnet Veniamin</div>
    </div>
</body>
</html>