<?php
    session_start();

    if ( isset($_POST['submit']))
    {
        $db = new PDO('sqlite:data.db');
        $stmt = $db->prepare('SELECT id, pin, fName, lName FROM employees WHERE id = :id AND pin = :pin');
        $stmt->bindParam(':id', $_POST['employeeID'], PDO::PARAM_STR);
        $stmt->bindParam(':pin', $_POST['employeePIN'], PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($row)
        {
            $_SESSION['id'] = $row['id'];
            $_SESSION['pin'] = $row['pin'];
            $_SESSION['fName'] = $row['fName'];
            $_SESSION['lName'] = $row['lName'];
            header("Location: http://localhost/project/files/main.php");
        }
        else
        {
            echo 'Invalid user/pin!';
        }
         
        // process login, check DB for credentials and create session ID for control pages
        
        
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
        <link rel="stylesheet" type="text/css" href="styles/style.css">
    </head>
    <body>
        <h1>Punch System</h1>
        <h3>Please login below:</h3>
        <form method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
            <label for="empoyeeID">Employee #:</label>
            <input name="employeeID" type="text" maxlength="4" pattern="[0-9]{4}">
            <br>
            <label for="employeePIN">Employee PIN: </label>
            <input name="employeePIN" type="text" maxlength="4" pattern="[0-9]{4}">
            <br>
            <input type="submit" name="submit"> <input type="reset">
        </form>
    </body>
</html>
