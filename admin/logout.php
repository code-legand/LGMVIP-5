<?php
    session_start();
    session_destroy();
    session_start();
    $_SESSION['success'] = "Logged Out";
    header("Location: index.php");
?>