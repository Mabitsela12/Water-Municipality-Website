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
$sql = "SELECT i.status as status ,  i.issue_id as issue_id, it.name as title, i.content as content, aj.date_assigned as date , aj.date_resolved as date_resolved 
FROM issue i , issue_type it , assign_job aj 
WHERE i.issue_type_id =  it.issue_type_id and (aj.issue_id = i.issue_id and aj.emp_id = ".$_SESSION['current_user'].") and i.status = 'resolved'";

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
                    <li><a href="#">Employee Logout</a></li>
                </ul>
            </div>
        </div>
        <div class="container" >
            <div class="navbar2">
                <div class="menu">
                    <ul>
                        <li><a href="plumber.php">View allocations</a></li>
                        <li><a href="view_repaired.php">View resolved</a></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <main>
            <h2>Resolved issues</h2>
            <ul>
            <?php
            if (!empty($issues)) {
                        foreach ($issues as $issue) {
                            
                            echo '<li class="issue">';
                            echo '<h3>' . $issue['title'] . '</h3>';
                            echo '<p>' . $issue['content'] . '</p>';
                            echo '<p class="date">Date assigned - ' . $issue['date'] . '</p>';
                            echo '<p class="date">Date resolved - ' . $issue['date_resolved'] . '</p>';
                            echo '<p class="date" Status - >' . $issue['status'] . '</p>';
                            echo '<form method="POST" action="plumber.php" id="form"">';
                            echo '<input name="issue_id" value="'.$issue['issue_id'].'" type="hidden" />';
                            echo '<input name="submit" id="delete" value="Delete" type="submit" onclick="return confirm("Is the issue resolved?"); />';
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
</body>
</html>