<?php
    session_start();
    if ( isset( $_SESSION['id'] ))
    {
        echo 'Logged in<br>';
        echo 'Welcome ' . $_SESSION['fName'];
    }
    else
    {
        echo 'Please login <a href="../index.php"> here. </a>';
        exit();
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Dashboard</title>
        <link rel="stylesheet" type="text/css" href="../styles/style.css">
    </head>
    <body>
        <h1>Dashboard</h1>
    </body>
</html>
