<?php
    session_start();

    if ( isset( $_SESSION['id'] ))
    {
        // Determine user data.
        $query_str = "
        SELECT employees.id, employees.fName, employees.lName, employees.role, employeeRole.role
        FROM employees
        INNER JOIN employeeRole
        ON employees.role = employeeRole.id
        WHERE employees.id = :id
        ";
        $db = new PDO('sqlite:./../data.db');
        $stmt = $db->prepare($query_str);
        $stmt->bindParam(':id', $_SESSION['id'], PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($row)
        {
            $user['id'] = $row['id'];
            $user['fName'] = $row['fName'];
            $user['lName'] = $row['lName'];
            $user['role'] = $row['role'];
        }
        
        // Get all roles
        $query_str = "
        SELECT id, role
        FROM employeeRole
        ";
        $stmt = $db->prepare($query_str);
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($rows as $row)
        {
            $roles[$row['id']] = $row['role'];
        }

        $db = NULL;
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
                        <h2>Welcome {$user['role']}: {$user['fName']} {$user['lName']} </h2>
                        
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
