<?php
session_start();
setcookie('status', 'true', time()-10, '/');
session_unset();
header("Location: ../View/LoginView.php?message=Logged out successfully");
exit();
?>