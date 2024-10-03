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

// SQL query to issues data
$sql = "SELECT i.issue_id as issue_id, it.name as title, i.content as content, i.report_date as date
FROM issue i , issue_type it 
WHERE i.issue_type_id =  it.issue_type_id and i.user_id in (SELECT user_id FROM user , role WHERE user.role_id = role.role_id and role.name = 'citizen')
ORDER BY i.issue_id DESC";

// Execute the query
$result = $conn->query($sql);

// Check if there are results
if ($result->num_rows > 0) {
    $issues = array(); // Initialize an array to store the retrieved data
    
    // Fetch data from the result set
    while ($row = $result->fetch_assoc()) {
        $issues[] = $row;
    }
}


// retrieve  all employees to display on the select tag
$sql = "SELECT user_id as emp_id,username FROM user WHERE role_id = (SELECT role_id FROM role  WHERE name = 'plumber' )";

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

//assign employee work

if (isset($_POST['submit'])) {
    
    // Get the form data
    $emp_id = $_POST['assigned'];
    $issue_id = $_POST['issue_id'];
   
    
      // Prepare the SQL query
  $sql = "INSERT INTO assign_job (emp_id , issue_id) VALUES (?, ?)";
  
  // Bind the parameters
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss",$emp_id, $issue_id);
  
  // Execute the query
  if ($stmt->execute()) {
    echo "<script>";
    echo "alert('Assigned successfully')";
    echo "</script>";
    header('Refresh: 0');
  } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
  }
}
//end
if (isset($_POST['delete'])) {
    
    // Get the form data
    $issue_id = $_POST['id'];
   
    
      // Prepare the SQL query
    $sql = "DELETE FROM issue WHERE issue_id = ?";
    
    // Bind the parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $issue_id);
    
    // Execute the query
    if ($stmt->execute()) {
        echo "<script>";
        echo "alert('Deleted successfully')";
        echo "</script>";
        header('Refresh: 0');
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
        
        <main>
            <h2>Citizen Reports</h2>
            <ul>
            <?php
            if (!empty($issues)) {
                        foreach ($issues as $issue) {
                            
                            echo '<li class="issue">';
                            echo '<h3>' . $issue['title'] . '</h3>';
                            echo '<p>' . $issue['content'] . '</p>';
                            echo '<p class="date">' . $issue['date'] . '</p>';
                            echo '<form method="POST" action="admin.php" id="form" onsubmit="return confirmSubmit()">';
                            echo '<select name="assigned" class="type" required>';
                            echo '<option disabled selected value>Select employee</option>';
                            foreach ($employees as $employee) {
                                echo '<option value="'.$employee["emp_id"].'">'.$employee["username"].'</option>';
                            } 
                            echo '</select>';
                            echo '<input name="issue_id" value="'.$issue['issue_id'].'" type="hidden" />';
                            echo '<input name="submit" value="Assign" type="submit" />';
                            echo '</form>';
                            echo '<form method="POST" action="admin.php" id="form" onsubmit="return confirmDelete()">';
                            echo '<input name="id" value="'.$issue['issue_id'].'" type="hidden" />';
                            echo '<input name="delete" value="Delete" type="submit" id="delete"/>';
                            echo '</form>';
                            echo '</li>';
                           
                        }
                    } else {
                        echo '<li>No issue yet.</li>';
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
            return confirm("Confirm allocation?")
        }
        function confirmDelete(){
            return confirm("Are you sure you want to delete?")
        }
    </script>
</body>
</html>