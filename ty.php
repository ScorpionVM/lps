<?php
  session_start();

  if(!isset($_POST["send_answer"])){
    session_destroy();
    header("Location: /");
  }
  
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Multumesc</title>
  <link rel="stylesheet" href="style.css">
</head>
<body onload='redirect_to_mainpage(45)'>
  <div class="container" style="width: 600px">
    <h1 id="header">Vă mulțumesc pentru răspunsuri!</h1>
    <div class="tcenter">
      <code>Page auto redirect after <code id='timeleft'>45</code> sec</code></div>
  </div>
</body>
<script src="script.js"></script>
</html>