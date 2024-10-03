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
$sql = "SELECT * FROM user WHERE role_id = (SELECT role_id FROM role  WHERE name = 'plumber' )";

// Execute the query
$result = $conn->query($sql);

// Check if there are results
if ($result->num_rows > 0) {
    $employees = array(); // Initialize an array to store the retrieved data
    
    // Fetch data from the result set
    while ($row = $result->fetch_assoc()) {
        $employees[] = $row;
    }
}

//end

//end
if (isset($_POST['delete'])) {
    
    // Get the form data
    $emp_id = $_POST['id'];
   
    
      // Prepare the SQL query
    $sql = "DELETE FROM user WHERE user_id = ?";
    
    // Bind the parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $emp_id);
    
    // Execute the query
    if ($stmt->execute()) {
        echo "<div class='notification'>Deleted successfully</div>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
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
        <link rel="stylesheet" href="adminCss.css">
    </head>
    <body>
        <div class="container">
        <div class="navbar">
            <div class="logo">
                <h2>Mathipa Municipality</h2>
            </div>
            <div class="menu">
                <ul>
                    <li><a href="/">Home</a></li>
                    <li><a href="/viewAll">Water Issues</a></li>
                    <li><a href="/report">Report a Problem</a></li>
                    <li><a href="/employeeLogin">Employee Login</a></li>
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
                    </ul>
                </div>
            </div>
        </div>
        
        <main>
        <h2>All employees</h2>
            <ul>
            <?php
            if (!empty($employees)) {
                        foreach ($employees as $employee) {
                            
                            echo '<li class="issue">';
                            echo '<h3>' . $employee['username'] . '</h3>';
                            echo '<p>' . $employee['user_id'] . '</p>';
                            echo '<p class="date">' . $employee['password'] . '</p>';
                            echo '<form method="POST" action="update_employees.php" id="form">';
                            echo '<input name="emp_id" value="'.$employee['user_id'].'" type="hidden" />';
                            echo '<input name="username" value="'.$employee['username'].'" type="hidden" />';
                            echo '<input name="password" value="'.$employee['password'].'" type="hidden" />';
                            echo '<input name="submit" value="Update" type="submit" />';
                            echo '</form>';
                            echo '<form method="POST" action="view_employees.php" id="form" onsubmit="return confirmSubmit()">';
                            echo '<input name="id" value="'.$employee['user_id'].'" type="hidden" />';
                            echo '<input name="delete" value="Delete" type="submit" id="delete"/>';
                            echo '</form>';
                            echo '</li>';
                           
                        }
                    } else {
                        echo '<li>No employees yet.</li>';
                    }
            ?>
            </ul>
        </main>
        
        <div class="footer">
            <p>© 2023 Municipality Water Website. All rights reserved.</p>
        </div>
    </div>
    <script>
        function confirmSubmit(){
            return confirm("Are you sure you want to delete?")
        }

    </script>
</body>
</html>