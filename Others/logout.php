<?php
session_start();
session_unset();
session_destroy();
header("Location: LoginView.php?message=Logged out successfully");
exit();
?>