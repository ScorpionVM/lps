<?php
    session_start();
    session_unset();

    include "functions.php";
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        
        $newObj = get_json_contents('config.json', true);
        
        foreach ($newObj["users"] as $key => $value) {
            if(md5($_POST["username"]) == $value["username"] and md5($_POST["passwd"]) == $value["password"]){
                $_SESSION["admin_access"] = $value["level"];
                header("Location: /constructor.php");
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
    <title>admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container" style="width: 300px">
        <h1 style="text-align: center">Admin panel</h1>
        <form action="" method="post" style="text-align: center;">
            <input type="text" class="inp_admin" name="username" placeholder="Username" required>
            <input type="password" class="inp_admin" name="passwd" placeholder="Password" required>
            <button type="submit" id="conn_btn">Connect</button>
        </form>
        <div class='author'>Developed by Magnet Veniamin</div>
    </div>
</body>
</html>