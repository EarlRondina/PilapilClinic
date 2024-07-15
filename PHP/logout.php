<?php
session_start();
unset($_SESSION['Username']);
session_destroy();
echo "<script type='text/javascript'>alert('You are Logged Out');</script>";
header("Location: ../login.html");
exit;
?>