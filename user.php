<?php
session_start();

require_once "connection.php";
$conn = new DBController();
// REGISTER USER
  // receive all input values from the form
  $username = $_POST['username'];
  $number = $_POST['number'];
  $password_1 = $_POST['password_1'];
  $password_2 = $_POST['password_2'];



  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($username)) { array_push($errors, "Username is required"); }
  if (empty($number)) { array_push($errors, "number is required"); }
  if (empty($password_1)) { array_push($errors, "Password is required"); }
  if ($password_1 != $password_2) {
	array_push($errors, "The two passwords do not match");
  }

  // first check the database to make sure 
  // a user does not already exist with the same username and/or number
  $user_check_query = "SELECT * FROM users WHERE username='$username' OR number='$number' LIMIT 1";
  //$result = mysqli_query($conn, $user_check_query);
  $user = $conn->runQuery($user_check_query);

  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
  	$password = md5($password_1);//encrypt the password before saving in the database

  	$query = "INSERT INTO users (username, number, password) 
  			  VALUES('$username', '$number', '$password')";
    $conn->runQuery($query);
  	header('location: login.php');
  }


// ... 
