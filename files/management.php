<?php
    require('shell.php');
	
	$parse = false;
	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$parse = true;
	}
	
?>

<div class="content">
            <h2>Management</h2>
            <p>
			
			<form method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
				 <h3>Create New Employee:</h3>
				
				<label for="employeefName"> First Name: </label>
				<input name="employeefName" type="text" id = "employeefName"><br>
				<label for="employeelName"> Last Name: </label>
				<input name="employeelName" type="text" id = "employeelName"><br>
				<label for="role"> Role: </label>
				<select class="employee-select" name = "role">
				<?php
				$db = new PDO('sqlite:./../data.db');
				$db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$res = $db -> query('select * from employeeRole');
	
				foreach ($res as $row) 
				{
					$id = $row['id'];
					$role = $row['role'];
					echo '<option value="'.$id.'">'.$role.'</option>';	
				}
			
				
				?>
				</select><br>	
				<label for="employeePin"> PIN: </label>
				<input name="employeePin" type="text" id = "employeePin" maxlength="4" pattern="[0-9]{4}"><br><br>
				<input type="submit" name="submit" value ="Create Employee"> <input type="reset">
			</form>
			
			<?php
			if ($parse)
			{
				$db = new PDO('sqlite:./../data.db');
		
				$fName = $_POST['employeefName'];
				$lName = $_POST['employeelName'];
				$role = $_POST['role'];
				$pin = $_POST['employeePin'];
				
				$qry = $db->prepare(
					'INSERT INTO employees (fName, lName, role, pin) VALUES (?, ?, ?, ?)');
				$qry->execute(array($fName, $lName, $role, $pin));
			}
			?>
            </p>
        </div>
    </div>
</body>

</html>
