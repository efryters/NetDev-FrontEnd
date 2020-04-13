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
            <h2 style="text-align: center;"><u>Punch Data</u></h2>
            <h3>
                Most recent punch data:
            </h3>
            <table>
                <thead>
                    <th>Record</th>
                    <th>Employee #</th>
                    <th>Name</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                </thead>
                <?php

                    foreach ($punch_data_rows as $data_row)
                    {
                        $dt_format = 'Y-m-d H:i:s';
                        $dt_in = date($dt_format, "{$data_row['timeIn']}");
                        if ($data_row['timeOut'] != NULL)
                            $dt_out =  date($dt_format, "{$data_row['timeOut']}");
                        else
                            $dt_out = "Pending";
                        echo "<tr>";
                            echo "<td>{$data_row['id']}</td>";
                            echo "<td>{$data_row['employee']}</td>";
                            echo "<td>{$data_row['fName']} {$data_row['lName']}</td>";
                            echo "<td>{$dt_in}</td>";
                            echo "<td>{$dt_out}</td>";
                        echo "</tr>";
                    }
                ?>
            </table>
            <br>
        </div>
    </div>
</body>

</html>