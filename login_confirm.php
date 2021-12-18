<?php
session_start();
require_once "connection.php";
$conn = new DBController();
if(empty($_POST["number"]) && empty($_POST["password"]))  
{  
     echo '<script>alert("Both Fields are required")</script>';  
}  
else  
{  
     $number = $_POST["number"];  
     $password = $_POST["password"];  
     $password = md5($password);  


     
     $query = "SELECT * FROM users WHERE number = '$number' AND password = '$password'";  

     $result = $conn->runQuery($query);
     
     if($result)  
     {  
        $_SESSION['number'] = $number;
        $_SESSION['user_id'] = $result[0]['id'];
        $_SESSION['success'] = "You are now logged in"; 
        header("location:index.php");  
     }  
     else  
     {  
          echo '<script>alert("Wrong User Details")</script>';
           
     }  
}  
