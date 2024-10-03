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
$sql = "SELECT i.status as status ,  i.issue_id as issue_id, it.name as title, i.content as content, aj.date_assigned as date
FROM issue i , issue_type it , assign_job aj 
WHERE i.issue_type_id =  it.issue_type_id and (aj.issue_id = i.issue_id and aj.emp_id = ".$_SESSION['current_user'].") and i.status <> 'resolved'";

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

//assign employee work

if (isset($_POST['submit'])) {
    
    // Get the form data
    $issue_id = $_POST['issue_id'];
    $status = $_POST['status'];
    
    if(strcmp($issue_id,'resolved') == 0){
        // Prepare the SQL query
        $sql = "UPDATE assign_job SET date_resolved = SYSDATE() WHERE issue_id = ? and emp_id = ?";
        // Bind the parameters
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss",$issue_id, $_SESSION['current_user']);
        $stmt->execute();
    }

    $sql1 = "UPDATE issue SET status = ? WHERE issue_id = ?";
    // Bind the parameters
    $stmt1 = $conn->prepare($sql1);
    $stmt1->bind_param("ss",$status,$issue_id);
    // Execute the query
    if ($stmt1->execute()) {
        echo "<div class='notification'>Assigned successfully</div>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
//end
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
                        <li><a href="view_resolved.php">View resolved</a></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <main>
            <h2>Allocations</h2>
            <ul>
            <?php
            if (!empty($issues)) {
                        foreach ($issues as $issue) {
                            
                            echo '<li class="issue">';
                            echo '<h3>' . $issue['title'] . '</h3>';
                            echo '<p>' . $issue['content'] . '</p>';
                            echo '<p class="date">' . $issue['date'] . '</p>';
                            echo '<form method="POST" action="plumber.php" id="form"">';
                            echo '<select name="status" class="type" required>';
                            echo '<option disabled selected value>'.$issue['status'].'</option>';
                            if(strcmp($issue['status'],'submitted') == 0){
                                echo '<option value="in progress">In progress</option>';
                                echo '<option value="resolved">Resolved</option>';}
                                else{
                                    echo '<option value="resolved">Resolved</option>';
                                }
                            echo '</select>';
                            echo '<input name="issue_id" value="'.$issue['issue_id'].'" type="hidden" />';
                            echo '<input name="submit" value="Submit status" type="submit" onclick="return confirm("Is the issue resolved?"); />';
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