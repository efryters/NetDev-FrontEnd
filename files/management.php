<?php
    require('shell.php');
	
	$parse = false;
	$delete = false;

	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		if (isset($_POST['submitNewEmployee']))
			$parse = true;
		elseif (isset($_POST['sumbitDeleteEmployee'])) {
			if(isset($_POST['rdEmployeeID']))
			{
				$deleteID = $_POST['rdEmployeeID'];
				$delete = true;
			}
		}
	}

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
		$db = NULL;
		header("Refresh:0");
	}

	if ($delete)
	{
		$db = new PDO('sqlite:./../data.db');
		$stmt = $db->prepare('DELETE FROM employees WHERE id = :id');
		$stmt->bindParam(':id', $deleteID, PDO::PARAM_STR);
		$stmt->execute();
		$db = NULL;
		
	}

	$db = new PDO('sqlite:./../data.db');
	$stmt = $db->prepare('SELECT id, pin, fName, lName FROM employees');
	$stmt->execute();
	$rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

	$index = 0;
	foreach ($rows as $r)
	{
		$employees[$index] = [
			$e[0] = $r['id'],
			$e[1] = $r['fName'],
			$e[2] = $r['lName'],
			$e[3] = $r['pin'],
		];

		$index++;
	}
	$db = NULL;
?>
<div class="content">
	<h2>Management</h2>
	<p></p>

	<form method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
		<h3>Create New Employee:</h3>
		<fieldset>

			<label for="employeefName"> First Name: </label>
			<input name="employeefName" type="text" id="employeefName" required><br>
			<label for="employeelName"> Last Name: </label>
			<input name="employeelName" type="text" id="employeelName" required><br>
			<label for="role"> Role: </label>
			<select class="employee-select" name="role">
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
			<input name="employeePin" type="text" id="employeePin" maxlength="4" pattern="[0-9]{4}" required><br><br>
			<input type="submit" name="submitNewEmployee" value="Create Employee"> <input type="reset">
		</fieldset>
	</form>
	<br>
	<h2>Current Employees</h2>
	<h3>Select an employee to delete:</h3>
	<form method="POST" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
		<fieldset>
			<table>
				<thead>
					<th>Select</th>
					<th>ID</th>
					<th>Name</th>
					<th>PIN</th>
				</thead>
				<?php
					foreach ($employees as $e)
					{
						echo "<tr>";
						echo "<td align=\"center\">"; echo "<input type=\"radio\" name=\"rdEmployeeID\" value=\"{$e[0]}\" required></input> "; echo "</td>";
						echo "<td>"; echo "{$e[0]}"; echo "</td>";
						echo "<td>"; echo "{$e[1]} {$e[2]}"; echo "</td>";
						echo "<td>"; echo "{$e[3]}"; echo "</td>";
						echo "</tr>";
					}
				?>
			</table>
			<input type="submit" name="sumbitDeleteEmployee" value="Delete Employee">
			<input type="reset">
		</fieldset>
	</form>

</div>
</div>
</body>

</html>