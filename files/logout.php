<?php
    session_start();

    if (isset($_SESSION['id']))
    {
        session_destroy();
        session_abort();
        header("Location: ../index.php");
    }
    else
    {
        echo 'Please login <a href="../index.php"> here. </a>';
        exit();
    }
?>