<?php
    require('shell.php');
	
	$query_str = "
        SELECT punchData.id, punchData.employee, employees.fName, employees.lName, punchData.timeIn, punchData.timeOut, employees.present
        FROM punchData
        INNER JOIN employees
        ON punchData.employee = employees.id WHERE employees.id = " . $user['id'];
	
	
	
    $db = new PDO('sqlite:./../data.db');
    $stmt = $db->prepare($query_str);
    $stmt->execute();
    $punch_data_rows = $stmt->fetch(\PDO::FETCH_ASSOC);
	
	$dt_format = 'Y-m-d H:i:s';
	
	$parse = false;
	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$parse = true;
	}
?>

<div class="content">
	<h2>Main</h2>
	<p>		
		<form method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
			
		<?php
		
			$pres_str = "SELECT present from employees WHERE id = ?";
			$pres_qry = $db->prepare($pres_str);
			$pres_qry->execute(array($user['id']));
			$present = $pres_qry->fetchColumn();
			
			if ($present == 0)
			{
				echo '<button name="Punch In" type="submit">Punch In </button>';
				if ($parse)
				{
				$pres_update = 1;	
				$punch_in = time();		
				
				$pi_str = "INSERT INTO punchData (employee, timeIn) VALUES (?, ?)";
				$pi_qry = $db->prepare($pi_str);
				$pi_qry->execute(array($user['id'], $punch_in));	
				
				$present_str = "UPDATE employees SET present = ? WHERE id = ?";
				$present_qry = $db->prepare($present_str);
				$present_qry->execute(array($pres_update, $user['id']));
				}
			}
			elseif ($present == 1)
			{
				echo '<button name="Punch Out" type="submit"> Punch Out</button>';
				if ($parse)
				{
				$pres_update = 0;
				$punch_out = time();		
				
				$id_str = "SELECT id from punchData WHERE timeOut is NULL AND employee = ?";
				$id_qry = $db->prepare($id_str);
				$id_qry->execute(array($user['id']));
				$id_num = $id_qry->fetchColumn();
				$po_str = "UPDATE punchData SET timeOut = ? WHERE id = ?";
				$po_qry = $db->prepare($po_str);
				$po_qry->execute(array($punch_out, $id_num));	
				
				$present_str = "UPDATE employees SET present = ? WHERE id = ?";
				$present_qry = $db->prepare($present_str);
				$present_qry->execute(array($pres_update, $user['id']));
				}
			}
				
		?>
			
			
		</form>		
    </p>       
</div>
</body>

</html>
