<?php

session_start();
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
$sql = "SELECT i.issue_id as issue_id, it.name as title, i.content as content, i.report_date as date ,i.status as status
FROM issue i , issue_type it 
WHERE i.issue_type_id = it.issue_type_id and i.user_id = ".$_SESSION['current_user'];

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

//end

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
                    <li><a href="index.php">Home</a></li>
                    <li><a href="Logout">Logout</a></li>
                </ul>
            </div>
        </div>
        <div class="navbar2">
            <div class="menu">
                <ul>
                    <li><a href="report.php">Report Issues</a></li>
                    <li><a href="citizen.php">View MyReports </a></li>
                </ul>
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
                            echo '<p class="date">Date reported ' . $issue['date'] . '</p>';
                            echo '<div class = "status"> Status - '.$issue["status"].'</div>';
                            echo '<form method="POST" action="update_c_issue.php" id="form">';
                            echo '<input name="issue_id" value="'.$issue['issue_id'].'" type="hidden" />';
                            echo '<input name="update" value="Update" type="submit" />';
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
        function confirmDelete(){
            return confirm("Are you sure you want to delete?")
        }
    </script>
</body>
</html>