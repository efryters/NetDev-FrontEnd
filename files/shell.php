<?php
    session_start();

    if ( isset( $_SESSION['id'] ))
    {
        // Load page
        echo "
        
        <!DOCTYPE html>
        <html>

        <head>
            <title>Dashboard</title>
            <link rel=\"stylesheet\" type=\"text/css\" href=\"../styles/style.css\">
        </head>

        <body>
            <header>
                <div class=\"header-wrapper\">
                    <div class=\"header-title\">
                        <h1>Punch Management System</h1>
                    </div>
                    <div class=\"header-personalize\">
                        <h2>Welcome {$_SESSION['fName']} {$_SESSION['lName']} </h2>
                        
                    </div>
                </div>
            </header>
            <div class=\"wrapper\">
                <div class=\"navbar\">
                    <nav>
                        <a class=\"navbar-text\" href=\"./main.php\">Home</a><br>
                        <a class=\"navbar-text\" href=\"./data.php\">Punch Data</a><br>
                        <a class=\"navbar-text\" href=\"./analytics.php\">Analytics</a><br>
                        <a class=\"navbar-text\" href=\"./management.php\">Management</a><br>
                        <a class=\"navbar-text\" href=\"./logout.php\">Logout</a><br>
                    </nav>
                </div>
        
        ";
    }
    else
    {
        echo 'Please login <a href="../index.php"> here. </a>';
        exit();
    }
?>
