<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "water_issues_db";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// retrieve  all employees to display on the select tag
$sql = "SELECT report_date, status, COUNT(*) as count FROM issue WHERE user_id = 3 GROUP BY report_date, status ORDER BY report_date DESC";
// Execute the query
$rst = $conn->query($sql);

// Check if there are results
if ($rst->num_rows > 0) {
    $table1 = array(); // Initialize an array to store the retrieved data
    
    // Fetch data from the result set
    while ($row = $rst->fetch_assoc()) {
        $table2[] = $row;
    }
}

//end

// Query to retrieve issue data
$query = "SELECT status FROM issue";

$result = $conn->query($query);

// Check if there are results
if ($result->num_rows > 0) {
    $data = array(); // Initialize an array to store the retrieved data

    // Fetch data from the result set
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    // Count the number of issues for each status
    $resolvedCount = 0;
    $inProgressCount = 0;
    $submittedCount = 0;

    foreach ($data as $row) {
        switch ($row['status']) {
            case 'resolved':
                $resolvedCount++;
                break;
            case 'in progress':
                $inProgressCount++;
                break;
            case 'submitted':
                $submittedCount++;
                break;
        }
    }

    // Create an associative array to hold the counts
    $issueStatusCounts = [
        "Resolved" => $resolvedCount,
        "In Progress" => $inProgressCount,
        "Submitted" => $submittedCount
    ];

    // Convert the issue status counts to dataPoints
    $dataPoints = [];
    foreach ($issueStatusCounts as $status => $count) {
        $dataPoints[] = ["label" => $status, "y" => $count];
    }
} else {
    echo "No data found";
}

// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Municipality Water Website</title>
        <link rel="stylesheet" href="statisticsCss.css">
        <script>
            window.onload = function () {
                var chart = new CanvasJS.Chart("chartContainer", {
                    animationEnabled: true,
                    exportEnabled: true,
                    theme: "light1",
                    title: {
                        text: "Issue Status Chart"
                    },
                    data: [
                        {
                            type: "column",
                            dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                        }
                    ]
                });
                chart.render();
            }
        </script>

    </head>
    <body>
        <div class="container">
        <div class="navbar">
            <div class="logo">
                <h2>Mathipa Municipality</h2>
            </div>
            <div class="menu">
                <ul>
                    <li><a href="index.php">Home</a></li>
                </ul>
            </div>
        </div>
        <div class="container" >
            <div class="navbar2">
                <div class="menu">
                    <ul>
                        <li><a href="admin_report.php">Report Issues</a></li>
                        <li><a href="view_reports.php">View MyReports </a></li>
                        <li><a href="admin.php">Allocate</a></li>
                        <li><a href="view_employees.php">View employees</a></li>
                        <li><a href="create_emp.php">Create employee</a></li>
                        <li><a href="statistics.php">View statistics</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <h2>Statistics</h2>
        <main>
        <div id="data1">
        <?php
        
        if (!empty($table1)) {
            echo '<table border="1">';
            echo '<tr><th>Date</th><th>Status</th><th>Count</th></tr>';
            foreach ($table1 as $trow) {
                echo '<tr>';
                echo '<td>' . $trow["report_date"] . '</td>';
                echo '<td>' . $trow["status"] . '</td>';
                echo '<td>' . $trow["count"] . '</td>';
                echo '</tr>';
            }
        } else {
            echo '<li>No issue yet.</li>';
        }?>
        </div>
        <div id="chartContainer"></div>
    </main>
    
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>
</html>