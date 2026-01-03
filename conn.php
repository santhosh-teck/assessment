<?php
if(isset($_GET["logout"])){
    session_start();
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit;
}
$conn = mysqli_connect("localhost", "root", "", "test");
if(mysqli_connect_errno()){
    echo "Connection Failed".mysqli_connect_error();
    exit;
}
?>