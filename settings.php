<?php
    require_once('Git.php');

    if($_SERVER["REQUEST_METHOD"] == 'POST'){
        
        if(isset($_POST["git_pull"]) and $_POST["git_pull"] != ''){
            $repo = Git::open('');
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Settings</title>
</head>
<body>
    <h1>Web settings</h1>
    <form action='' method='POST'>
        <button type='submit' name='git_pull' value='#'>PULL</button>
    </form>
</body>
</html>