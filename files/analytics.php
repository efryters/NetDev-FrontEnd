<?php
    require('shell.php');
	
	$parse = false;
	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$parse = true;
	}
	
	
?>

	<div class="content">
		<h2>Analytics</h2>
		<p>
		<form method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
		<?php
		if ($user['role'] == "Supervisor")
		{
			$db = new PDO('sqlite:./../data.db');
			$db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$res = $db -> query('SELECT * FROM employees');

			echo "<label> Employee:  </label>";
			echo '<select class="employee-select" name = "employeeName">';

			foreach ($res as $row) 
			{
				$id = $row['id'];
				$name = $row['fName'];
				echo '<option value="'.$id.'">'.$name.'</option>';
				
			}
			echo '</select><input type="submit" name="submit"></form>';
			
		}
		else
		{
			$employeeID = $user['id'];
			$parse = true;
		}
		
		if ($parse)
		{
			if ($user['role'] == "Supervisor")
			{
				$employeeID = $_POST['employeeName'];
			}
			$query_str = "
			SELECT * FROM punchData WHERE employee = ?";
		

			$db = new PDO('sqlite:./../data.db');
			$stmt = $db->prepare($query_str);
			$stmt->execute(array($employeeID));
			$punchID = $stmt->fetchAll();
			$index = 0;
			foreach ($punchID as $rec)
			{
				if (isset($rec['timeOut'])) 
				{
					$data[$index] = $rec['id'];
					$timeOut[$index] = $rec['timeOut'];
					$timeIn[$index] = $rec['timeIn'];
					$min = 60;
					$shift[$index] =($timeOut[$index] - $timeIn[$index])/$min;
				}
				
				$index++;
			}
			
			$db = NULL;
		
			echo "<h3> Record for Employee #{$employeeID} </h3>";
			
			echo '<div class="chart-container">
			<canvas id="employeeChart"></canvas>';
		}
		?>
		</p>
			<script>
				var pID = <?php echo '["' . implode('", "', $data) . '"]' ?>;
				var shiftTime = <?php echo '["' . implode('", "', $shift) . '"]' ?>;
				
				// Example code from chart.js docs
				var canvas = document.getElementById('employeeChart');
				var ctx = canvas.getContext('2d');
				
				var myChart = new Chart(ctx, {
					type: 'bar',
					data: {
						labels: pID,		// Employee punch entry date? Or entry #?
						datasets: [{
							label: 'Employee Punch Info',
							data: shiftTime,										// Actual time data per punch [(timeout - timein) / 60 seconds] (epoch time is in seconds)
							backgroundColor: [
								'rgba(255, 99, 132, 0.2)',
								'rgba(54, 162, 235, 0.2)',
								'rgba(255, 206, 86, 0.2)',
								'rgba(75, 192, 192, 0.2)',
								'rgba(153, 102, 255, 0.2)',
								'rgba(255, 159, 64, 0.2)'
							],
							borderColor: [
								'rgba(255, 99, 132, 1)',
								'rgba(54, 162, 235, 1)',
								'rgba(255, 206, 86, 1)',
								'rgba(75, 192, 192, 1)',
								'rgba(153, 102, 255, 1)',
								'rgba(255, 159, 64, 1)'
							],
							borderWidth: 1
						}]
					},
					options: {
						title: {
							display: true,
							text: 'Employee Shift Info'
						},
						scales: {	
							yAxes: [{
								scaleLabel: {
									display: true,
									labelString: 'Minutes Worked'
								  },
								
								ticks: {
									beginAtZero: true
								}
							}],
							xAxes: [{
								scaleLabel: {
									display: true,
									labelString: 'Record #'
								  }
								
								
								
							}]
						}
					}
				});
			
			</script>
		</div>
		
	</div>
		
    </div>
</body>

</html>
