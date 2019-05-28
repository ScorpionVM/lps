<?php
  session_start();
  
  include "functions.php";

  if(!isset($_SESSION["grupa"])){
    header("Location: /");
  }

  if($_SERVER["REQUEST_METHOD"] == "POST"){
    $arrayName = $_SESSION["block_array"];
    $saveList = array();
    foreach ($arrayName as $key => $value) {
      if($value['tag'] == 'input'){
        $post_value = $_POST[$value["name"]];
      } else {
        $post_value = htmlspecialchars($_POST[$value['name']]);
      }
      $saveList[$value["name"]] = $post_value;
    }

    $filename_t = './grupe/'.$_SESSION['timestr'].'.json';

    if(file_exists($filename_t)){
        $oldJson = file_get_contents($filename_t, true);
        $oldObj = json_decode($oldJson, true);
        $oldArr = $oldObj; 
    } else {
        $oldArr = array();
    }

    $n = count($oldArr[$_SESSION["grupa"]]);
    $oldArr[$_SESSION["grupa"]][$n] = $saveList;
    
    ksort($oldArr);

    put_json_contents($filename_t, $oldArr);

    header("Location: /ty.php");
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LPS - Sondaj</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container" style="width: 75%">
        <h1 id="header">Chestionar</h1><hr size=5px;>
        <p class='tcenter'><?= $_SESSION["grupa"] ?></p> 
        <form action="" method="post">
          <?php
              $newJson = file_get_contents("./question.json", true);
              $newObj = json_decode($newJson, true);
              $arrayName = $newObj["a"];
              $_SESSION["block_array"] = $arrayName;
              $ct = 0;

              foreach ($arrayName as $key => $value) {
                $quest = $value["question"];
                $name = $value["name"];
                $nr = $value["nr"];

                $tag = $value["tag"];
                
                if($tag == 'input'){
                  $multi = $value["multiselect"];
                  $type = $value["type"];
                  
                  if($multi == 1){
                      $name = $name.'[]';
                  }
                }
                
                echo "<div class='cont_q'>
                  <h3 class='question'>$nr. $quest</h3><hr>";

                  $pred_keys = array('question','name','multiselect','type', 'nr');
                  foreach ($value as $_key => $_value) {
                    if(!in_array($_key, $pred_keys)){
                      if($tag == 'input'){
                        if($_key != 'tag'){
                          echo "<span class='space_var'>
                            <input type='$type' name='$name' value='$_key' onclick='multi_check(this, $multi, $nr)'>
                            <label>$_value</label>
                          </span><br>";
                        }
                      } else {
                        $ct++;
                        echo "<span class='spce_var'>
                          <textarea maxlength='355' name='$name' onkeyup='contor(this,$ct)' class='textarea_d' cols='70' rows='5' style='resize: none'></textarea>
                        </span><br><code class='contor' id='contor_".$ct."'>355/355</code>";
                      } 
                    }
                  }
                echo "</div>";
              }
          ?><hr>
          <button type="submit" name='send_answer'>Submit</button>
        </form>
    </div>
    
</body>
<script type="text/javascript" src="script.js"></script>
</html>