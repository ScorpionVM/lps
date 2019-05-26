<?php
   session_start();

   //echo date_timestamp_get();

   if(isset($_POST["date_val"]) and $_POST["date_val"] != ''){
      if($_POST["date_val"] == date("Y-m-d")){
         echo "<form action='' method='POST'>
            <label>Time start: </label><input type='time' name='st_time_val'><br>
            <label>Time end: </label><input type='time' name='ed_time_val'>
            <input type='submit' name='time_val'>
         </form>";
      }
   }

   if(isset($_POST["time_val"]) and $_POST["time_val"] != ''){
      $cr = date("h:i A");
      if(strtotime($_POST["st_time_val"]) <= strtotime($cr) and strtotime($cr) <= strtotime($_POST["ed_time_val"])){
         echo "go";
      } else {
         echo "Access denied";
      }
   
   }
?>

<head>
<style>
 .max_value {
    background-color: green;
    color: white;
 }
 .medium_value {
    background-color: yellow;
 }
 .min_value {
    background-color: red;
    color: white;

}
</style>
<link rel="stylesheet" href="style.css">

</head>

<body>
   <!-- 
   <div class="container">
      <p class='max_value'>Max</p>
      <p class='medium_value'>Medium</p>
      <p class='min_value'>Min</p>
   </div>

   <div id='datatime'>
      <form action="" method="POST">
         <input type="date" name="date_val">
         <input type="submit">
      </form>      
   </div>

   -->

   <?php 
      include "functions.php";
      
      if(!isset($_SESSION["count"])){
         $_SESSION["count"] = 0;
      }

      if(!isset($_SESSION["time"])){
         $_SESSION["time"] = date("h:i A");
      }

      if (isset($_POST["update"]) and $_POST["update"] != ''){
         $_SESSION["count"] = $_POST["count"];
         $_SESSION["time"] = strtotime($_POST["time"]);
         echo date("H:i", $_SESSION["time"]);
      }

      $form = "<form action='' method='POST'>"; $form_end = "</form>";
      $table = "<table border>"; $table_end = "</table>";
      $edit = "<br><button type='sumbit' name='edit' value='#'>Edit</button> ";
 
      $stat = "disabled";
      $line = "<tr><td>Items</td><td>".$_SESSION["count"]."</td><td>".$_SESSION["time"]."</td></tr>"; 

      if(isset($_POST["edit"]) and $_POST["edit"] != ''){
         $line = "<tr><td>Items</td>
            <td><input type='number' min=0 name='count' placeholder='".$_SESSION["count"]."'></td>
            <td><input type='time' name='time' value='".date("H:i", $_SESSION["time"])."'></tr>"; 
         
            $stat = "enabled";
      }

      $update = "<button type='sumbit' name='update' value='#' $stat>Update</button>";

      concate($element, [$form, $table, $line, $table_end, $edit, $update, $form_end]);
      
      echo $element;
   ?>

</body>