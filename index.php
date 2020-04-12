<?php
    session_start();

    if ( isset($_POST['submit']))
    {
        $db = new PDO('sqlite:./data.db');
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
	<link rel="shortcut icon" href="./styles/signin.png">
</head>

<body>
    <header>
        <div class="header-wrapper">
            <div class="header-title">
                <h1>Punch Management System</h1>
            </div>
            <div class="header-personalize">
                
            </div>
        </div>
    </header>
    <div class="wrapper">
        <div class="login-content">
            <h3>Please login below:</h3>
            <form method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
        <?php
	
		$db = new PDO('sqlite:./data.db');
		$db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$res = $db -> query('select * from employees');
		
		echo '<div class="form-group">';
		echo "<label> Employee ID:   </label>";
		echo '<select class="employee-select" name = "employee" >';
		
		foreach ($res as $row) 
		{
			$id = $row['id'];
			$name = $row['fName'];
			echo '<option value="'.$id.'">'.$name.'</option>';
			
		}
		echo "</select></div>";
	?>
                <br>
                <label for="employeePIN">Employee PIN: </label>
                <input name="employeePIN" type="text" maxlength="4" pattern="[0-9]{4}">
                <br>
                <input type="submit" name="submit"> <input type="reset">
            </form>
        </div>
    </div>
</body>

</html>