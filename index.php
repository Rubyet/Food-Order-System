<?php
    session_start();
    require_once "connection.php";
    $user_id = isset($_SESSION['user_id']) ? trim($_SESSION['user_id']) : '';
    
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>
        <?
            if($user_id !=''){
                echo "Welcome, " . $user_id ;
            }
        ?>
    </h1>
    <br>
    <a href="login.php">Login</a>
    <br>
    <a href="register.php">Register</a>
    <br>
    <a href="menu.php">Menu te niya ja</a>
    <br>
    <?
            if($user_id !=''){
                echo "<a href='logout.php'>Logout</a>";
            }
        ?>
    

</body>
</html>