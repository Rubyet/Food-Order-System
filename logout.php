<?php
session_start();
unset($_SESSION['username']);
unset($_SESSION['number']);
unset($_SESSION['user_id']);
unset($_SESSION['success']);
unset($_SESSION['cart_item']);
unset($_SESSION['total_price']);
header('location: index.php');
?>
