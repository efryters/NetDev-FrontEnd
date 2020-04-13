<?php
    session_start();
    $bad_login = false;

    if ( isset($_SESSION['id']))
    {
        header("Location: ./files/main.php");
    }

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
            header("Location: ./files/main.php");
        }
        else
        {
            $bad_login = true;
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
		
		echo "<label> Employee ID:   </label>";
		echo '<select class="employee-select" name = "employeeID" >';
		
		foreach ($res as $row) 
		{
			$id = $row['id'];
			$name = $row['fName'];
			echo '<option value="'.$id.'">'.$name.'</option>';
			
		}
		echo "</select>";
	?>
                <br>
                <label for="employeePIN">Employee PIN: </label>
                <input name="employeePIN" type="text" maxlength="4" pattern="[0-9]{4}">
                <br>
                <br>
                <input type="submit" name="submit"> <input type="reset">
                <?php
                    if ($bad_login)
                    {
                        echo "<p>Incorrect credentials.</p>";
                    }
                    ?>
            </form>
        </div>
    </div>
</body>

</html>