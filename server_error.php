<?php   
    session_start();

    include "functions.php";
    
    checktime_access();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Server</title>
    <style>
        #elem {
            font-size: 220px;
            margin: auto;
            margin-top: 50px;
            width: max-content;
        }
     
        h1 {
            margin: auto;
            margin-top: 20px;
            width: max-content;
        }

        p {
            margin: auto;
            width: max-content;
        }
    </style>
</head>
<body>
    <div id='elem'>500</div>
    <h1><?= $_SESSION["msg_error"] ?></h1>
    <p>Please try again later...</p>
</body>
</html>