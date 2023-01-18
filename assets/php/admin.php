<?php

// functions go here

session_start();
$userName = $_SESSION['userName'];
$userId = $_SESSION['userId'];
$data['username'] = $userName;
$data['userid'] = $userId;
echo json_encode($data);

?>
