<?php
    require('shell.php');
	
	$query_str = "
        SELECT punchData.id, punchData.employee, employees.fName, employees.lName, punchData.timeIn, punchData.timeOut, employees.present
        FROM punchData
        INNER JOIN employees
        ON punchData.employee = employees.id

        ";
    if ($user['role'] == "Worker")
    {
        $query_str = $query_str . "WHERE employees.id = " . $user['id'];
    }

    $db = new PDO('sqlite:./../data.db');
    $stmt = $db->prepare($query_str);
    $stmt->execute();
    $punch_data_rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    $db = NULL;
?>

<div class="content">
            <h2>Analytics</h2>
            <p>
			<?php
			if ($user['role'] == "Supervisor")
			{
				$db = new PDO('sqlite:./../data.db');
	
				echo "<label> Employee ID:  </label>";
				echo '<select class="employee-select" name = "employeeID">';
	
				foreach ($res as $row) 
				{
					$id = $row['id'];
					$name = $row['fName'];
					echo '<option value="'.$id.'">'.$name.'</option>';
					
				}
				echo '<option value = "all"> All Employees </option></select>';
			}
			?>
            </p>
        </div>
    </div>
</body>

</html>
